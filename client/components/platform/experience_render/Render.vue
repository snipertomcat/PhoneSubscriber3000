<template>
  <div class="card h-full overflow-hidden">
    <div class="card-header" v-if="false">
      <!-- Progress bar -->
      <div class="h-1 w-full mb-1">
        <div class="h-full bg-yellow-dark" :style="{ width: 'progress' }"></div>
      </div>

      <!-- Progress badge -->
      <div class="h-5 w-full flex justify-center items-center">
        <span class="block bg-yellow-lighter px-3 rounded-full text-xs">1 / 3</span>
      </div>
    </div>
    <div class="card-content lg:h-full lg:min-h-64 p-0 h-rest-16">
      <div class="elements h-full" v-if="loaded">
        <template v-for="(element, e_i) in content">
          <!--div class="element" :class="{'active':isCurrent(element.index)}"-->
          <div :key="e_i"
            class="element active"
            :class="{
              'evaluation':evaluationActive,
              'h-full no-max-w': (element.component === 'ExternalResource')
            }">
            <template v-if="element.component === 'Title'">
              <div class="px-2">
                <r-title :component-data="element"></r-title>
              </div>
            </template>
            <template v-if="element.component === 'Image'">
              <div class="px-2 py-3">
                <r-image :component-data="element"></r-image>
              </div>
            </template>
            <template v-if="element.component === 'Text'">
              <div class="px-2 py-3">
                <r-text :component-data="element"></r-text>
              </div>
            </template>
            <template v-if="element.component === 'Video'">
              <r-video :component-data="element"></r-video>
            </template>
            <template v-if="element.component === 'Downloadable'">
              <div class="px-2">
                <r-downloadable :component-data="element"></r-downloadable>
              </div>
            </template>
            <template v-if="element.component === 'ExternalResource'">
              <r-external-resource :component-data="element"></r-external-resource>
            </template>
            <template v-if="element.component === 'SendText'">
              <r-send-text :component-data="element"></r-send-text>
            </template>
            <template v-if="element.component === 'SortImages'">
              <div class="border border-gray-200 border-solid">
                <r-sort-images :component-data="element"></r-sort-images></div>
            </template>
            <template v-if="element.component === 'ClickAndDrop'">
              <div class="border border-gray-200 border-solid">
                <r-click-and-drop :component-data="element"></r-click-and-drop>
              </div>
            </template>
            <template v-if="element.component === 'Evaluation'">
              <r-evaluation :component-data="element"></r-evaluation>
            </template>
          </div>
        </template>
      </div>
    </div>
    <template v-if="evaluationActive">
      <div class="card-footer border-t border-gray-blueish-light flex items-center justify-center pr-4">
        <button
            type="button"
            class="btn sm:w-full lg:w-64 h-12 text-white bg-blue-medium hover:bg-blue-darker"
            @click="validateEvaluation">
          Verificar respuesta
        </button>
      </div>
    </template>
    <template v-else-if="lastSession">
      <div class="card-footer border-t border-gray-blueish-light flex items-center justify-end pr-4">
        <button
            type="button"
            class="btn sm:w-full lg:w-64 h-12 text-white bg-blue-medium hover:bg-blue-darker" @click="nextElement">
          Terminar
        </button>
      </div>
    </template>
    <template v-else>
      <div class="card-footer border-t border-gray-blueish-light flex items-center justify-end pr-4">
        <button
            type="button"
            class="btn sm:w-full lg:w-64 h-12 text-white bg-blue-medium hover:bg-blue-darker" @click="nextElement">
          Siguiente
        </button>
      </div>
    </template>
  </div>
</template>

<script>
  const CONTAINER = 'theme';
  const CONTENT = 'content';
  import {bus} from "../../../app_platform";
  import {RenderService} from "../../../services/Experience/ExperienceRenderService";
  import Evaluation from "../evaluations/Evaluation";
  import Title from "../../admin/experiences/editor/components/preview/Title";
  import Image from "../../admin/experiences/editor/components/preview/Image";
  import Text from "../../admin/experiences/editor/components/preview/Text";
  import Video from "../../admin/experiences/editor/components/preview/Video";
  import SendText from "../../admin/experiences/editor/components/preview/SendText";
  import Downloadable from "../../admin/experiences/editor/components/preview/Downloadable";
  import ExternalResource from "../../admin/experiences/editor/components/preview/ExternalResource";
  import SortImages from "../../admin/experiences/editor/components/preview/SortImages";
  import ClickAndDrop from "../../admin/experiences/editor/components/preview/ClickAndDrop";

  import BrowserInteractionTime from 'browser-interaction-time';

  const browserInteractionTime = new BrowserInteractionTime({
    timeIntervalEllapsedCallbacks: [],
    absoluteTimeEllapsedCallbacks: [],
    browserTabInactiveCallbacks: [],
    browserTabActiveCallbacks: [],
    pauseOnMouseMovement: false,
    pauseOnScroll: false,
    idleTimeoutMs: 20000,
    checkCallbacksIntervalMs: 10000
  })

  export default {
    name: "Render",
    inject: [
      'getAuthUser',
      'getExperience',
      'getCurrent',
      'setCurrent',
      'getNextElement',
      'getPrevElement',
      'selectItem',
      'unlock',
      'updateSession',
      'openSubChallenges',
      'closeSubChallenges',
      'endExperience',
      'getStructureSessions',
      'hasEnrollment',
      'addNotification',
    ],
    provide () {
      return {
        'getSession': this.getSession,
         'startTime': this.startTime
      }
    },
    components: {
      'r-evaluation': Evaluation,
      'r-title': Title,
      'r-image': Image,
      'r-text': Text,
      'r-video': Video,
      'r-send-text': SendText,
      'r-downloadable': Downloadable,
      'r-external-resource': ExternalResource,
      'r-sort-images': SortImages,
      'r-click-and-drop': ClickAndDrop,
    },
    created () {
      bus.$on('change_content', content => {
        browserInteractionTime.reset();
        this.render(content)
        browserInteractionTime.startTimer();
        this.lastSession = this.isLastSession();
      })
    },
    mounted () {
      this.registerVisit()

      browserInteractionTime.addBrowserTabActiveCallback(this.isUserActive);
      browserInteractionTime.addBrowserTabInactiveCallback(this.isUserInactive);

      const cb = {
        multiplier: time => time,
        timeInMilliseconds: 10000,
        callback: () => this.getIntervalTime()
      }

      browserInteractionTime.addTimeIntervalEllapsedCallback(cb);

      const callbackData = {
        timeInMilliseconds: 10000,
        callback: () => this.getEfectiveTime(),
        pending: true
      }

      browserInteractionTime.addAbsoluteTimeEllapsedCallback(callbackData)
      browserInteractionTime.reset();
      browserInteractionTime.startTimer();
    },
    computed: {
      evaluationActive () {
        return _.get(this.content, ['0', 'component_name'], null) === 'evaluation';
      },
      auth_user () {
        return this.getAuthUser()
      },
      experience () {
        return this.getExperience()
      },
      session () {
        return this.getCurrent(['session'])
      },
      access_time: function () {
        return Math.round(+new Date() / 1000)
      },
    },
    methods: {
      isUserActive () {
        console.log("Usuario activo: ", browserInteractionTime.getTimeInMilliseconds() / 1000);
      },
      isUserInactive () {
        console.log("Usuario Inactivo: ", browserInteractionTime.getTimeInMilliseconds() / 1000);
      },
      getEfectiveTime () {
        console.log("Tiempo Efectivo: ", browserInteractionTime.getTimeInMilliseconds() / 1000);
      },
      getIntervalTime () {
        console.log("Interval Interacción: ", browserInteractionTime.getTimeInMilliseconds() / 1000);
        this.updateTime();
      },
      startTime () {
        browserInteractionTime.startTimer();
      },
      getFinalEnrollment(){
        return this.final_enrollment;
      },
      updateTime (session = null) {
        if (!this.hasEnrollment) {
          return 0;
        }

        if (_.isNull(session))
          session = this.session

        let activity_interacted = {
          enrollment_id: session.current_enrollment_progress.enrollment_id,
          enrollment_progress: session.current_enrollment_progress.id,
          access_time: this.access_time,
          time: browserInteractionTime.getTimeInMilliseconds() / 1000,
        };

        RenderService.updateUserTime(activity_interacted)
          .then(response => {
            console.log('Time Registered')
          })
          .catch(error => {
            console.error(error.response)
          })
          .then(() => {
          })
      },

      registerAccess (session = null) {
        if (!this.hasEnrollment) {
          return 0;
        }

        if (_.isNull(session))
          session = this.session

        let activity_interacted = {
          user: this.auth_user.id,
          experience: this.experience.id,
          session: session.id,
          enrollment_progress: session.current_enrollment_progress.id,
          access_time: Math.round(+new Date() / 1000),
          data_type: 'content_event',
          verb: 'access',
          score: 0,
          maxScore: 0,
        };

        RenderService.sendAccessData(activity_interacted)
          .then(response => {
            console.log('Access Registered')
          })
          .catch(error => {
            console.error(error.response)
          })
          .then(() => {
          })
      },
      registerVisit (session = null) {
        if (!this.hasEnrollment) {
          return 0;
        }

        if (_.isNull(session))
          session = this.session

        let activity_interacted = {
          user: this.auth_user.id,
          experience: this.experience.id,
          session: session.id,
          enrollment_progress: session.current_enrollment_progress.id,
          access_time: Math.round(+new Date() / 1000),
          data_type: 'content_event',
          verb: 'visit',
          score: 0,
          maxScore: 0,
        };

        RenderService.sendAccessData(activity_interacted)
          .then(response => {
            console.log('Visit Registered')
          })
          .catch(error => {
            console.error(error.response)
          })
          .then(() => {
          })
      },
      registerFinish (session = null) {
        if (!this.hasEnrollment) {
          return 0;
        }
        console.log(session)
        if (_.isNull(session))
          session = this.session
        let activity_finish = {
          user: this.auth_user.id,
          experience: this.experience.id,
          session: session.id,
          enrollment_progress: session.current_enrollment_progress.id,
          access_time: Math.round(+new Date() / 1000),
          data_type: 'content_event',
          verb: 'finished',
          score: 0,
          maxScore: 0,
          finished: RenderService.toUnix(new Date()),
        };

        RenderService.finishSession(activity_finish)
          .then(response => {
            let session = _.get(response, ['data', 'current_session'], null)
            this.$parent.final_enrollment = _.get(response, ['data', 'current_enrollment'], null)
            if (_.isEmpty(session)) {
              return console.error('Session update data is empty.')
            }
            this.updateSession(session)
          })
          .catch(error => {
            console.error(error)
          })
          .then(() => {
          })
      },
      getSession () {
        return this.session;
      },
      render (content) {
        this.content = content
        let firstElement = _.head(this.content);
        let currentSession = this.getCurrent(['session']);

        if (_.has(currentSession, 'parent_id') && currentSession.parent_id !== null) {
          let parent = _.find(this.getStructureSessions(), {id: currentSession.parent_id});
          if (currentSession.id === _.first(parent.childs).id)
            this.registerAccess(parent);
        }
        this.registerAccess()

        if (_.has(currentSession, 'childs') && _.size(currentSession.childs) > 0) {
          this.setCurrent(['session'], currentSession.childs[0])
          currentSession = this.getCurrent(['session']);
          this.registerAccess()
        }
        this.setLocalCurrent(['element'], firstElement)
        this.reload()
      },
      reload () {
        this.loaded = false
        setTimeout(() => {
          this.loaded = true
        }, 10)
      },
      async nextItem () {
        if (!this.hasEnrollment) {
          /*
          this.addNotification({
            name: 'not-enrolled',
            class: 'error',
            message: 'Usuario no enrolado',
            visible: true,
            closeVisible: false,
            detail: {
              html: `
                <div class="flex w-full">
                  <div class="w-1/2">
                    <div class="button">Añadir a carrito</div>
                  </div>
                  <div class="w-1/2">
                    <div class="button">Regresar al store</div>
                  </div>
                </div>`
            },
          });
          */
          return console.log('user without enrollment')
        }
        let next = await this.getNextElement();
        let current_el = this.getCurrent(['session']);
        let has_children = (_.has(current_el, 'childs') && _.size(current_el.childs) > 0);

        if (_.has(current_el, 'json_data')) {
          if (typeof current_el.json_data === 'string')
            current_el.json_data = JSON.parse(current_el.json_data)
        }

        // Send data to server
        let current_enrollment_progress_status = _.get(this.session, ['current_enrollment_progress', 'status'], null);
        this.registerFinish();

        let isLastSCH = this.isLastSCH(current_el);
        if (isLastSCH) {
          let parent = _.find(this.getStructureSessions(), {id: current_el.parent_id});
          this.registerFinish(parent)
          current_el = parent;
        }

        let isLastSession = await this.isLastSession(current_el);

        if (isLastSession) {
          //return window.location.href = '/experiences';
          return this.endExperience();
        }

        this.unlock(next)
        let openSubChallenges = false;
        if (_.get(next, ['json_data', 'type'], 'content') === CONTAINER) {
          openSubChallenges = true;
        }

        if (openSubChallenges) {
          this.openSubChallenges()
          next.json_data.opened_sub_challenges = true;
          let firstElement = _.head(next.childs);
          this.unlock(firstElement)
          this.selectItem(firstElement)
        } else {
          this.selectItem(next)
        }

         this.registerAccess()
      },
      openedEvaluation () {
        return this.getCurrent(['element'])
      },
      validateEvaluation () {
        // @todo validar
        let flag = _.get(this.current, ['element', 'index'], null);
        let current_el = this.getCurrent(['session']);
        if (_.has(current_el, 'json_data')) {
          if (typeof current_el.json_data === 'string')
            current_el.json_data = JSON.parse(current_el.json_data)
        }

        if (_.isNull(flag)) return console.error('flag is null in validateEvaluation.');
        let nextElement = _.get(this.content, [flag + 1], null)

        if (flag + 1 >= _.size(this.content)) {
          bus.$emit('check-reactive')
        }

        if (_.isEmpty(nextElement)) {
          return console.error('next element is empty.')
        }
        ;

        if (this.isLastSession(current_el) && _.isEmpty(nextElement)) {
          this.endExperience();
        }

        this.setLocalCurrent(['element'], nextElement);
        //bus.$emit('next-reactive')
      },
      isCurrent (elementIndex) {
        return _.get(this.current, ['element', 'index'], -1) === elementIndex
      },
      setLocalCurrent (path = [], value = null) {
        if (!_.isEmpty(value))
          _.set(this.current, path, value)
      },
      nextElement () {
        return this.nextItem()
        let element = _.get(this.current, ['element'], null)
        if (_.isNull(element)) return console.error('element is null.');
        let flag = _.get(element, ['index'], null);
        if (_.isNull(flag)) return console.error('flag is null.');
        let nextElement = _.get(this.content, [flag + 1], null)
        if (flag + 1 >= _.size(this.content)) {
          return this.nextItem();
        }
        if (_.isEmpty(nextElement)) {
          return console.error('next element is empty.')
        }
        ;
        this.setLocalCurrent(['element'], nextElement);
      },
      isLastSCH (el = null) {
        let isLast = false;
        if (_.isEmpty(el) || _.isNull(el) || el === 'undefined')
          return false;
        let parentId = _.get(el, ['parent_id'], null);
        if (parentId !== null) {
          let parent = _.find(this.getStructureSessions(), {id: parentId});
          let elIndex = _.findIndex(parent.childs, {id: el.id});
          isLast = elIndex === _.size(parent.childs) - 1;
        }
        return isLast;
      },
      isLastSession (el = null) {
        let isLast = false;
        if (_.isEmpty(el) || _.isNull(el) || el === 'undefined') {
          el = this.getCurrent(['session'])
        }
        let isContainer = _.get(el, ['json_data', 'type'], null) === CONTAINER;
        let array = this.getStructureSessions();

        isLast = _.last(array).id === el.id;

        if (isContainer && isLast) {
          if (_.size(_.last(array).json_data.elements) > 0) {
            array = _.last(array).json_data.elements;
            el = this.getCurrent(['element']);
            isLast = _.last(array).id === el.id;
            return isLast
          }
        }

        return isLast
      },
    },
    data () {
      return {
        lastSession: false,
        loaded: true,
        content: [],
        current: {
          element: null
        },
        final_enrollment:null,
        user_access_time:Math.round(+new Date() / 1000)
      }
    }
  }
</script>

<style scoped lang="scss">
</style>
