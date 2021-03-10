<template>
  <div class="experience-container font-sarabun">
    <div class="main" oncontextmenu="return false;">
      <template v-if="!experienceEvaluation.visible">
        <div class="experience-structure relative w-full h-full lg:w-3/12 lg:inline-block overflow-hiddeng"
             :class="[sidebar.hide ? 'hidden' : 'block']"
        >
          <div class="back">
            <div
                class="flex flex-row mr-0 ml-0 px-3 w-full h-12 flex items-center justify-between px-4 border-b border-gray-lighter text-blue-medium text-xl font-bold">
              <div class="w-auto align-self-center pointer" @click="goToExperiences"><i class="material-icons">keyboard_arrow_left</i>
              </div>
              <div class="w-9/12 py-2 align-self-center pointer" @click="goToExperiences">
                <div class="back-text">Home</div>
              </div>
              <button class="block text-xl text-blue-medium lg:invisible" @click="toggleSidebar">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <div class="ch-list">
            <e-structure :structure-data.sync="sessions" :current-session="currentSession"
                         ref="structure"></e-structure>
          </div>
          <div class="company-info w-full h-20 bg-gray-lighter absolute bottom-0 hidden lg:block">
            <img :src="company_logo" :alt="company_name">
            <div class=""><span>{{company_name}}</span></div>
          </div>
        </div>
        <div class="experience-render relative w-full h-full lg:w-9/12 lg:inline-block"
             :class="[sidebar.hide ? 'block' : 'hidden']"
        >
          <div class="title-bar">
            <!-- apithy logo (probably redirect to Home) -->
            <a href="#" class="apithy-logo-mobile w-1/12 block text-3xl text-blue-medium lg:invisible mb-0">
              <img src="/favico.icon" alt="apithy logo">
            </a>

            <!-- Challenge title -->
            <span class="w-8/12 mx-4 text-gray-darker text-xl font-bold font-title text-no-overflow text-center">
            {{experience.title}}
          </span>

            <!-- Toogle sidebar button (mobile only) -->
            <div class="text-3xl text-blue-medium lg:hidden mb-0" @click="toggleSidebar">
              <i class="fas fa-bars"></i>
            </div>

            <a href="#" class="w-1/12 hidden lg:inline-block">
              <img src="/images/apithy-logo.svg" alt="apithy logo">
            </a>
          </div>
          <div class="render lg:px-8">
            <e-render ref="render"></e-render>
          </div>
        </div>
      </template>
      <template v-else>
        <div class="card-body p-0">
          <div class="experience-structure relative w-full h-full overflow-hiddeng">
            <e-rating :final_score="getFinalScore()" :experience="getExperience()" :auth_user="getAuthUser()"
                      origin="experience" :enrollment_id="enrollmentId"></e-rating>
          </div>
        </div>
      </template>
    </div>
    <!-- Notifications -->
    <div class="notifications" :class="{'visible':has_notifications, 'hide':!has_notifications}">
      <template v-for="(n, i) in notifications">
        <div :class="n.class" v-if="n.visible" :style="`transform:translateY(-${(i+1 )*100}%)`">
          <div class="close-button" v-if="n.closeVisible">
            <i class="fa fa-times" @click="removeNotification(i)"></i>
          </div>
          <div class="content">
            <div class="message">{{ n.message }}</div>
            <template v-if="typeof n.detail === 'object'">
              <div class="details" v-html="n.detail.html">
                {{ n.detail.html }}
              </div>
            </template>
            <template v-else>
              <div class="details">
                {{ n.detail }}
              </div>
            </template>
          </div>
        </div>
      </template>
    </div>
  </div>
</template>

<script>
  const CONTAINER = 'theme';
  const CONTENT = 'content';
  const CHALLENGE = 'challenge';
  const SUBCHALLENGE = 'sub_challenge';

  import {bus} from "../../../app_platform";
  import Render from './Render';
  import Structure from './Structure';
  import ExperienceRating from "../ExperienceRating";

  export default {
    name: "Container",
    props: {
      experienceData: {},
      currentSession: {},
      authUser: {}
    },
    components: {
      'e-render': Render,
      'e-structure': Structure,
      'e-rating': ExperienceRating
    },
    provide () {
      return {
        getAuthUser: this.getAuthUser,
        getExperience: this.getExperience,
        unlock: this.unlock,
        select: this.select,
        isLegacy: this.isLegacy,
        getCurrent: this.getCurrent,
        setCurrent: this.setCurrent,
        getSelected: this.getSelected,
        getNextElement: this.getNextElement,
        getPrevElement: this.getPrevElement,
        isLastSession: this.isLastSession,
        selectItem: this.selectItem,
        updateSession: this.updateSession,
        openSubChallenges: this.openSubChallenges,
        closeSubChallenges: this.closeSubChallenges,
        endExperience: this.endExperience,
        hideSidebar: this.hideSidebar,
        getStructureSessions: this.getStructureSessions,
        registerAccess: this.registerAccess,
        hasEnrollment: this.has_enrollment,
        addNotification: this.addNotification,
        removeNotification: this.removeNotification,
        removeAllNotification: this.removeAllNotification,
      }
    },
    created () {
      this.$cookie.delete('public_experience');
      this.experience = this.experienceData;
      _.each(this.experience.sessions, session => {
        if (_.has(session, 'json_data')) {
          session.json_data = JSON.parse(session.json_data);
          session.json_data.blocked = true;
          _.each(session.json_data.elements, el => {
            el.blocked = true;
          })
        }
      })
      //this.current.session = this.currentSession;
    },
    computed: {
      sessions () {
        return this.experience.sessions
      },
      company_name () {
        return this.experience.company_author.name
      },
      company_logo () {
        return this.experience.company_author.full_path_logo
      },
      has_enrollment () {
        return _.get(this.currentSession, ['current_enrollment_progress', 'id'], null) !== null
      },
      enrollmentId () {
        return _.get(this.currentSession, ['current_enrollment_progress', 'enrollment_id'], null)
      },
      has_notifications () {
        return !_.isEmpty(this.notifications)
      }
    },
    methods: {
      hideSidebar () {
        this.sidebar.hide = true
      },
      getExperience (path = []) {
        if (_.isEmpty(path)) {
          return this.experienceData;
        }
        return _.get(this.experienceData, path, null);
      },
      getAuthUser () {
        return this.authUser
      },
      goToExperiences () {
        window.location.href = '/home'
      },
      select (item) {
        let content = [];
        // Parse json data to item
        if (_.has(item, 'json_data'))
          item = item.json_data;

        // if the item is blocked do nothing @todo show a warning modal
        if (item.blocked)
          return console.log('blocked');


        if (item.prefix === CHALLENGE) {
          // if challenge type is content, select them
          if (item.type !== CONTAINER) {
            _.set(this.selected, item.prefix, item);
          }
          // if item type is container (challenge with sub-challenges) select the last son available
          else {
            let lastAvailable = this.lastAvailable(item);
            _.set(this.selected, SUBCHALLENGE, lastAvailable)
            if (!_.isEmpty(lastAvailable)) {
              item = lastAvailable;
            }
          }
        }

        // if the item is sub-challenge, select they parent
        if (item.prefix === SUBCHALLENGE) {
          let parent = _.find(this.sessions, {json_data: {id: item.parent_id}}).json_data;
          _.set(this.selected, parent.prefix, parent);
        }

        // add index to elements

        _.each(item.elements, (element, index) => {
          element.index = index
        });

        // emit change content to render
        bus.$emit('change_content', item.elements)
      },
      isLegacy (type) {
        return CONTENT === type
      },
      toggleSidebar () {
        this.sidebar.hide = !this.sidebar.hide
      },
      getCurrent (path = null) {
        return this.$refs.structure.getCurrent(path)
      },
      setCurrent (path = null, value) {
        return this.$refs.structure.setCurrent(path, value)
      },
      getSelected (path = null) {
        return _.get(this.selected, path);
      },
      lastAvailable (item) {
        let elements = [];

        for (let counter = _.size(item.elements) - 1; counter >= 0; counter--) {
          let el = item.elements[counter]
          if (!el.blocked) {
            elements.push(el);
          }
        }
        return _.last(elements);
      },
      unlock (item) {
        this.$refs.structure.unlockItem(item)
      },
      finish (item) {
        item.completed = true;
      },
      getNextElement () {
        return this.$refs.structure.nextElement
        return this.$refs.structure.nextElement
      },
      getPrevElement () {
        return this.$refs.structure.prevElement
      },
      selectItem (item) {
        this.$refs.structure.selectItem(item)
      },
      getStructureSessions () {
        return this.$refs.structure.sessions;
      },
      updateSession (new_session) {
        this.$refs.structure.updateSession(new_session)
      },
      isLastSession (el) {
        return this.$refs.structure.isLastSession(el)
      },
      openSubChallenges () {
        return this.$refs.structure.setCurrent(['session', 'json_data', 'opened_sub_challenges'], true);
      },
      closeSubChallenges () {
        return this.$refs.structure.setCurrent(['session', 'json_data', 'opened_sub_challenges'], false);
      },
      endExperience () {
        let current = this.getCurrent(['session'])
        if (!!this.getExperience().current_session) {
          this.experienceEvaluation.visible = true;
        } else {
          window.location.href = route('experiences.index')
        }
      },
      registerAccess () {
        return this.$refs.render.registerAccess();
      },
      getFinalScore () {
        if (this.final_enrollment) {
          return this.final_enrollment.score;
        } else {
          return false;
        }
      },
      addNotification (notification = {}) {
        if (_.isEmpty(notification) || _.isNull('notification')) return 0;

        if (_.size(this.notifications) >= 5) {
          this.notifications[0].visible = false;
          setTimeout(() => {
            delete this.notifications[0];
          }, 3000);
        }

        this.notifications.push(notification);
        return 0;

        this.notifications.open = true;
        if (_.has(options, 'closeVisible'))
          this.notifications.closeVisible = options.closeVisible;
        this.notifications[notification].options = options;
        this.notifications[notification].visible = true;
        if (_.has(options, 'autoClose') && options.autoClose) {
          let delay = 2000;
          if (_.has(options, 'delay')) {
            delay = options.delay;
          }
          setTimeout(() => this.hideNotification(notification), delay);
        }
      },
      removeNotification (notificationIndex) {
        delete this.notifications[notificationIndex];
      },
      removeAllNotification (e) {
        _.each(this.notifications, (notification, name) => {
          if (_.has(this.notifications[name], 'visible'))
            this.hideNotification(name)
        })
      },
    },
    data () {
      return {
        experience: null,
        current: {
          session: null
        },
        selected: {
          challenge: null,
          sub_challenge: null
        },
        sidebar: {
          hide: true
        },
        experienceEvaluation: {
          visible: false
        },
        notifications: [],
        final_enrollment: false,
      }
    }
  }
</script>

<style lang="scss" scoped>
  .experience-container {
    @apply w-screen h-screen;
  }

  .experience-container > .main {
    @apply flex flex-row flex-wrap;
    height: calc(100% - 2rem);
  }

  .back {
    @apply border-b border-gray-lighter;
  }

  .ch-list {
    height: 80%;
    overflow-y: auto;
  }

  .company-info {
    @apply py-2 text-center;
  }

  .company-info img {
    @apply mx-auto;
    height: calc(100% - 1.25rem);
  }

  .title-bar {
    @apply absolute w-full h-12 bg-white px-4 shadow-header z-10 flex items-center justify-between;
  }
</style>
<style>
  html {
    overflow: unset;
  }

  .page-content {
    min-height: unset;
    overflow: unset;
  }

  @media (min-device-width: 1366px) {
    .page-content {
      overflow: hidden;
    }

    .render {
      height: 98%;
    }
  }
</style>
