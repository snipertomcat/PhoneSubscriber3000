<template>
  <div class="">
    <template v-for="session in sessions" v-if="loaded">
      <div :class="{
                 'challenge': true,
                 'legacy': isLegacy(session.json_data.type),
                  'with-sch': !isLegacy(session.json_data.type),
                  'challenge-active' : session.json_data.selected,
                  'blocked' : session.json_data.blocked
                 }"
      >
        <template v-if="isContainer(session)">
          <div class="item" @click="toggleChallenge(session.json_data)">
            <span class="lock fas fa-lock" :class="session.json_data.blocked ? '' : 'invisible'"></span>
            <span class="title text-xl lg:text-lg mb-0">{{session.json_data.title}}</span>
            <span class="arrow text-gray-dark"
                  :class="{
                    'fas': true,
                    'fa-angle-down':session.json_data.opened_sub_challenges,
                    'fa-angle-right':!session.json_data.opened_sub_challenges,
                  }"
            ></span>
            <!--div class="text-center">{{session.json_data.title}}</div-->
          </div>
        </template>
        <template v-else>
          <div class="item" @click="selectItem(session)">
            <span class="lock fas fa-lock" :class="session.json_data.blocked ? '' : 'invisible'"></span>
            <span class="title text-xl lg:text-lg mb-0">{{session.json_data.title}}</span>
            <!--div class="text-center">{{session.json_data.title}}</div-->
          </div>
        </template>
        <template v-if="!isLegacy(session.json_data.type)">
          <div class="item-append">
            <template v-for="sch in session.childs">
              <div class="subchallenge w-full"
                   :class="{
                      'subchallenge-active text-white': sch.json_data.selected,
                      'blocked': sch.json_data.blocked,
                      'hidden': !session.json_data.opened_sub_challenges,
                      'inline-flex': session.json_data.opened_sub_challenges
                   }"
                   @click="selectItem(sch)">
                <div class="item">
                  <div class="space"></div>
                  <span class="lock fas fa-lock" :class="sch.json_data.blocked ? '' : 'invisible'"></span>
                  <div class="">
                    <span class="block lg:text-sm mb-0">{{sch.title}}</span>
                  </div>
                </div>
              </div>
            </template>
          </div>
        </template>
      </div>
    </template>
  </div>
</template>

<script>
  const CONTAINER = 'theme';
  const CONTENT = 'content';
  export default {
    name: "Structure",
    props: {
      structureData: {
        required: true,
        type: Array
      },
      currentSession: {}
    },
    inject: ['isLegacy', 'select', 'getSelected', 'hideSidebar', 'registerAccess', 'hasEnrollment', 'addNotification'],
    created () {
      //this.current.session = this.currentSession;
      this.sessions = this.structureData;
    },
    mounted () {
      this.initStructure()
    },
    computed: {
      nextElement () {
        let session = this.getCurrent(['session']);
        let element = session.json_data;
        //let element = this.getCurrent(['element']);
        let next = null, flag = null;

        if (element.type === CONTENT) {
          flag = _.findIndex(this.sessions, {id: this.getCurrent(['session', 'id'])})
          next = _.nth(this.sessions, flag + 1)
        }
        if (element.type === CONTAINER) {
          flag = _.findIndex(this.elements, {id: this.getCurrent(['element', 'id'])})
          if (flag < 0) flag = null;
          if (flag < _.size(this.elements))
            next = _.nth(this.elements, flag + 1)
          else {
            flag = _.findIndex(this.sessions, {id: this.getCurrent(['session', 'id'])})
            next = _.nth(this.sessions, flag + 1)
          }
        }
        if (element.prefix === 'sub_challenge') {
          let parent = _.find(this.sessions, {id: session.parent_id})
          flag = _.findIndex(parent.childs, {id:session.id})

          if (flag < 0) flag = null;
          if (flag + 1 < _.size(parent.childs)) {
            next = _.nth(parent.childs, flag + 1)
          }
          if (_.last(parent.childs).id === session.id) {
            flag = _.findIndex(this.sessions, {id: parent.id})
            next = _.nth(this.sessions, flag + 1)
          }
        }
        return next;
      },
      prevElement () {
        if (_.isEmpty(this.elements)) return null;
        let element = null;
        let flag = _.findIndex(this.elements, {id: this.current.element.id}) - 1;
        if (flag > 0)
          element = _.nth(this.elements, flag);
        return element;
      },
      elements () {
        let current_session = this.getCurrent(['session']);
        if (_.has(current_session, 'json_data')) {
          if (typeof current_session.json_data === 'string')
            current_session.json_data = JSON.parse(current_session.json_data)
          return current_session.json_data.elements
        }
      },
    },
    methods: {
      initStructure () {
        // Parse json_data
        _.each(this.sessions, ch => {
          if (typeof ch.json_data === 'string')
            ch.json_data = JSON.parse(ch.json_data)
          ch.json_data.selected = false
          ch.json_data.blocked = true
          if (ch.json_data.type === CONTAINER) {
            ch.json_data.opened_sub_challenges = false;
            _.each(ch.childs, sch => {
              if (typeof sch.json_data === 'string')
                sch.json_data = JSON.parse(sch.json_data)
              sch.json_data.selected = false
              sch.json_data.blocked = true
            })
          }
        })

        let first_ch = _.first(this.sessions);
        let last_ch = _.last(this.sessions);
        let last_unlocked = null;
        this.unlockItem(first_ch, true);
        //this.selectItem(first_ch);
        if (first_ch.json_data.type === CONTAINER) {
          this.toggleChallenge(first_ch.json_data);
          let first_sch = _.first(first_ch.childs);
          this.unlockItem(first_sch, true);
          //this.selectItem(first_sch);
        }

        _.each(this.sessions, (ch, ch_index) => {
          if (_.has(ch, 'current_enrollment_progress')) {
            let ch_status = _.get(ch, ['current_enrollment_progress', 'status']);

            if (ch_status !== this.apithy_constants.SESSION_STATUS_BLOCKED) {
              ch.json_data.blocked = false;
              if (
                ch_status === this.apithy_constants.SESSION_STATUS_IN_PROGRESS ||
                ch_status === this.apithy_constants.SESSION_STATUS_AVAILABLE
              ) {
                last_unlocked = ch;
              }
            }
            if (ch.json_data.type === CONTAINER) {
              _.each(ch.childs, sch => {
                let sch_status = _.get(sch, ['current_enrollment_progress', 'status']);
                if (sch_status !== this.apithy_constants.SESSION_STATUS_BLOCKED) {
                  sch.json_data.blocked = false;
                  if (
                    sch_status === this.apithy_constants.SESSION_STATUS_IN_PROGRESS ||
                    sch_status === this.apithy_constants.SESSION_STATUS_AVAILABLE
                  ) {
                    last_unlocked = sch;
                  }
                }
              })
            }
          }

        });

        // Evaluate if is empty or null and select the first
        if (_.isEmpty(last_unlocked) || _.isNull(last_unlocked)) {
          last_unlocked = _.first(this.sessions);
        }

        // Evaluate if is CH or SCH
        // SCH
        if (last_unlocked.parent_id !== null) {
          let parent = _.find(this.sessions, {id: last_unlocked.parent_id});
          if (_.last(parent.childs, {id: last_unlocked.id})) {
            this.selectItem(last_unlocked);
          }
        }
        // CH
        else {
          if (_.last(this.sessions, {id: last_unlocked.id})) {
            this.selectItem(last_unlocked);
          }
        }

        // Evaluate if is the last CH and SCH
        let last_unlocked_status = last_unlocked.current_enrollment_progress.status;
        if (last_ch.id === last_unlocked.id && last_unlocked_status === this.apithy_constants.SESSION_STATUS_FINISHED) {
          if (first_ch.json_data.type === CONTAINER) {
            let first_sch = _.first(first_ch.childs)
            this.selectItem(first_sch)
          }
          else {
            this.selectItem(first_ch)
          }
        }
        if (_.size(last_ch.childs > 0 )) {
          let last_sch = _.last(ch.childs);
          if (last_sch.id === last_unlocked.id) {
            if (first_ch.json_data.type === CONTAINER) {
              let first_sch = _.first(first_ch.childs)
              this.selectItem(first_sch)
            }
            else {
              this.selectItem(first_ch)
            }
          }
        }

        this.reloadStructure();
      },
      toggleChallenge (item, forceValue = false, value = false) {
        item.opened_sub_challenges = !item.opened_sub_challenges;
        if (forceValue)
          item.opened_sub_challenges = value;
      },
      toggleSelected (item, forceValue = false, value = false) {
        item.selected = !item.selected
        if (forceValue)
          item.selected = value
      },
      selectItem (item) {
        // Item empty
        if (_.isEmpty(item)) return console.error('Item is empty');

        if (_.has(item, 'json_data') && typeof item.json_data === 'string')
          item.json_data = JSON.parse(item.json_data);

        // Item blocked
        if (_.has(item, 'json_data'))
          if (item.json_data.blocked) return console.error('Item blocked');
        if (item.blocked) return console.error('Item blocked');

        // Item already selected
        if (
          item.id === _.get(this.current, ['session', 'id'], null) ||
          item.id === _.get(this.current, ['element', 'id'], null)
        ) return console.error('Already selected this item');

        // Reset selections
        _.each(this.sessions, ch => {
          ch.json_data.selected = false;
          if (_.has(ch.json_data, 'opened_sub_challenges')) {
            ch.json_data.opened_sub_challenges = false;
          }
          _.each(ch.childs, sch => {
            sch.json_data.selected = false;
          })
        })

        // set item as current
        if (_.has(item, 'json_data')) {
          // item is a session object
          this.setCurrent(['session'], item);
          this.setCurrent(['element'], item.json_data);
          item.json_data.selected = true;
          if (item.json_data.prefix === 'sub_challenge') {
            let parent = _.find(this.sessions, {id: item.parent_id});
            parent.json_data.opened_sub_challenges = true;
            parent.json_data.selected = true;
          }
        }
        else {
          // item isn't a section object
          this.setCurrent(['element'], item);
          // item is a challenge
          if (item.prefix === 'sub_challenge') {
            let parent = _.find(this.sessions, {id: item.session_parent_id});
            parent.json_data.opened_sub_challenges = true;
            parent.json_data.selected = true;
            let session = _.find(parent.childs, {id: item.session_id});
            this.setCurrent(['session'], session);
          }
          // item is a sub_challenge
          else {
            let session = _.find(this.sessions, {id: item.session_id});
            this.setCurrent(['session'], session);
            this.setCurrent(['element'], item);
          }
          item.selected = true;
        }

        this.select(item)
        this.hideSidebar()
        window.scrollTo(0, 0)
        this.reloadStructure()
      },
      unlockItem (item, force = false) {
        let jData = null, el = null;

        if (!this.hasEnrollment && !force) {
          return console.log('user without enrollment')
        }

        if (_.has(item, 'json_data')) {
          if (typeof item.json_data === 'string')
            item.json_data = JSON.parse(item.json_data)
          jData = item.json_data;
        } else
          jData = item;

        if (_.isEmpty(jData)) return console.error('jData is null');

        el = jData

        if (jData.prefix === 'challenge') {
          jData.blocked = false;
          if (jData.type === CONTAINER)
            el = _.head(jData.elements)
        }

        el.blocked = false;
        this.reloadStructure();
      },
      setCurrent (prefix = null, value = null) {
        if (_.isNull(prefix)) return console.error('Prefix not defined');
        //this.current[prefix] = value;
        _.set(this.current, prefix, value);
      },
      getCurrent (prefix = null) {
        if (_.isNull(prefix)) return console.error('Prefix not defined');
        return _.get(this.current, prefix, null)
      },
      updateSession (new_session) {
        if (_.has(new_session, 'json_data')) {
          if (typeof new_session.json_data === 'string')
            new_session.json_data = JSON.parse(new_session.json_data)
        }
        let session = _.find(this.sessions, {id: new_session.id});
        if (_.isEmpty(session)) return console.error('Session not found')
        session = new_session
        this.reloadStructure()

      },
      isLastSession (el = null) {
        if (_.isNull(el) || el === 'undefined') {
          el = this.current.session
        }
        let array = this.sessions;
        if (el.json_data.type === CONTAINER) {
          el = this.current.element;
          array = this.current.session.json_data.elements
        }
        return _.last(array).id === el.id
      },
      isContainer (item) {
        if (_.has(item, 'json_data')) {
          return item.json_data.type === CONTAINER
        }
        return item.type === CONTAINER
      },
      reloadStructure () {
        this.loaded = false;
        setTimeout(() => {
          this.loaded = true;
        }, 50);
      },
    },
    data () {
      return {
        loaded: false,
        current: {
          session: null,
          element: null
        },
        sessions: []
      }
    }
  }
</script>

<style scoped>

</style>
