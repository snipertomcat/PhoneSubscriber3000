<template>
  <div class="structure-bar block">
    <div class="section cover">
      <div class="section-title font-bold">
        <span class="section-icon"><i class="fas fa-angle-down"></i></span>
        <span>{{ $t('messages.editor.cover') }}</span>
      </div>
      <div class="section-content cursor-pointer no-select" @click="selectCover">
        <div class="item" :class="{'active':card_selected}">
          <i class="far fa-file mr-3"></i>
          {{ $t(`messages.editor.design_in_store`) }}
        </div>
      </div>
    </div>
    <div class="section content">
      <div class="section-title font-bold">
        <span class="section-icon"><i class="fas fa-angle-down"></i></span>
        <span>{{ $t('messages.editor.content') }}</span>
      </div>

      <div class="section-content" v-sortable="sortable_options.ch" v-if="structure_loaded">
        <template v-for="(item, index) in structure.structure.list">
          <div :id="item.id" :key="item.id" :class="{'item': true, 'selected': item.selected, 'editing':item.editing}">
            <div class="ch handle">
              <i class="fas fa-grip-horizontal"></i>
            </div>
            <div class="cursor-pointer" v-if="item.type === 'content'">
              <i class="far fa-file"></i>
            </div>
            <div class="flex-6 cursor-pointer" @click="select(item, 'challenge')">
              <div class="name">
                <template v-if="item.editing">
                  <input class="w-full border border-gray-300" type="text" v-model="item.title">
                </template>
                <template v-else>{{ item.title }}</template>
              </div>
            </div>
            <template v-if="!item.editing">
              <div class="edit border-l border-gray-400">
                <i class="far fa-edit cursor-pointer" @click="edit(item)"></i>
              </div>
            </template>
            <template v-else>
              <div class="save border-l border-gray-400">
                <i class="far fa-save cursor-pointer" @click="save(item)"></i>
              </div>
            </template>
            <div class="trash">
              <i class="far fa-trash-alt cursor-pointer" @click="remove(item, index)"></i>
            </div>
          </div>
          <div class="" :data-parent-id="item.id" v-sortable="sortable_options.sch">
            <template v-for="(el, i) in item.elements" v-if="isSubchallenge(el)">
              <div :id="el.id" :key="el.id" :class="{'item sch': true, 'selected': el.selected, 'editing':el.editing}">
                <div class="sch handle">
                  <i class="fas fa-grip-horizontal"></i>
                </div>
                <div class="cursor-pointer">
                  <i class="far fa-file"></i>
                </div>
                <div class="flex-6 cursor-pointer" @click="select(el, 'sub_challenge')">
                  <div class="name">
                    <template v-if="el.editing">
                      <input class="w-full border border-gray-300" type="text" v-model="el.title">
                    </template>
                    <template v-else>{{ el.title }}</template>
                  </div>
                </div>
                <template v-if="!el.editing">
                  <div class="edit border-l border-gray-400">
                    <i class="far fa-edit cursor-pointer" @click="edit(el)"></i>
                  </div>
                </template>
                <template v-else>
                  <div class="save border-l border-gray-400">
                    <i class="far fa-save cursor-pointer" @click="save(el)"></i>
                  </div>
                </template>
                <div class="trash">
                  <i class="far fa-trash-alt cursor-pointer" @click="remove(el, i)"></i>
                </div>
              </div>
            </template>
          </div>
        </template>
      </div>
    </div>
    <div class="">
      <div class="add-challenge" @click="add('challenge')">
        <div class="add-button-icon"><i class="far fa-plus-square"></i></div>
        <div class="add-button-text">{{$t('messages.editor.challenge')}}</div>
      </div>
      <div class="add-challenge" @click="add('challenge', false, {type:'theme'})">
        <div class="add-button-icon"><i class="far fa-plus-square"></i></div>
        <div class="add-button-text">{{$t('messages.editor.theme')}}</div>
      </div>
      <div class="add-challenge" @click="add('sub_challenge')">
        <div class="add-button-icon"><i class="far fa-plus-square"></i></div>
        <div class="add-button-text">{{$t('messages.editor.sub_challenge')}}</div>
      </div>
    </div>
  </div>
</template>

<script>
  const CONTAINER = 'theme';
  const CONTENT = 'content';
  var _this = null;

  import {index} from "../../../../../helpers";
  import bus from "../../../../../helpers/bus";

  export default {
    name: "StructureBar",
    props: {
      componentOptions: {
        required: true,
        type: Object
      }
    },
    inject: ['getExperience', 'showModal', 'hideModal'],
    computed: {
      challenge_selected() {
        return this.structure.selected.challenge;
      },
      sub_challenge_selected() {
        return this.structure.selected.sub_challenge;
      },
      card_selected() {
        return this.structure.cover.selected === 'in_store';
      },
    },
    watch: {
      'structure.cover.selected': 'sectionChanged'
    },
    mounted() {
      _this = this;
      bus.$on('setContent', content => this.setContent(content));
      bus.$on('setHeader', header_data => this.setHeader(header_data));
      bus.$on('removeSession', (accepted, options) => this.remove(options.item, options.index, accepted));
      if (_.isEmpty(this.structure.structure.list)) this.add('challenge', true);
      this.structure_loaded = true;
    },
    methods: {
      reload() {
        this.structure_loaded = false;
        setTimeout(() => {
          this.structure_loaded = true;
        }, 500);
      },
      isSubchallenge(item) {
        return ('prefix' in item && item.prefix === 'sub_challenge')
      },
      selectCover() {
        this.structure.cover.selected = 'in_store';
        if (!_.isEmpty(this.challenge_selected) || !_.isNull(this.challenge_selected)) {
          this.structure.selected.challenge.selected = false;
          this.structure.selected.challenge = null;
        }
      },
      sectionChanged() {
        bus.$emit('sectionChanged', this.structure.cover.selected)
      },
      add(target, selected = false, options = {}) {
        //this.counter = this.structure.structure.list.length;
        //this.sch_counter = this.challenge_selected.elements.length;
        let item = null;
        switch (target) {
          case 'challenge':
            item = index.clone(this.structure.structure.templates.challenge);
            item.title = `${this.$t('messages.editor.challenge')} ${this.counter}`;
            item.id = `ch-${this.counter}`;
            item.type = 'content';
            if (options.type || options.type === 'theme')
              item.type = 'theme';
            this.counter++;
            if (_.isEmpty(item) || _.isNull(item)) return 0;
            item = this.createSession(item);
            this.structure.structure.list.push(item);
            break;
          case 'sub_challenge':
            if (!!this.challenge_selected.type && this.challenge_selected.type !== 'theme') return 0;
            item = index.clone(this.structure.structure.templates.sub_challenge);
            item.title = `${this.$t('messages.editor.sub_challenge')} ${this.sch_counter}`;
            item.parent_id = this.challenge_selected.id;
            item.session_parent_id = this.challenge_selected.session_id;
            item.id = `sch-${this.sch_counter}`;
            this.sch_counter++;
            if (_.isEmpty(item) || _.isNull(item)) return 0;
            item = this.createSession(item);
            this.challenge_selected.elements.push(item);
            break;
          default:
            break;
        }
        this.select(item);
      },
      cleanSelection() {
        _.each(this.structure.structure.list, ch => {
          ch.selected = false;
          _.each(ch.elements, sch => {
            sch.selected = false;
          })
        })
      },
      select(item) {
        if (_.isEmpty(item) || _.isNull(item) || item === 'undefined') return 0;
        if (item.editing) return 0;
        let array = null, selected = this.structure.selected;
        this.structure.cover.selected = 'content';
        switch (item.prefix) {
          case 'challenge':
            array = this.structure.structure.list;
            break;
          case 'sub_challenge':
            this.cleanSelection()
            let parent = _.find(this.structure.structure.list, {id: item.parent_id});
            parent.selected = true;
            selected['challenge'] = parent;
            array = this.challenge_selected.elements;
            break;
          default:
            break;
        }
        if (_.isEmpty(array) || _.isNull(array)) return 0;
        _.each(array, el => {
          if (el.id === item.id) {
            el.selected = true;
            selected[item.prefix] = item;
            if(item.type === 'theme') {
              if (_.size(item.elements) < 1)
                this.add('sub_challenge')
              if (_.size(item.elements) > 0)
                this.select(_.head(item.elements))
            }
          } else {
            el.selected = false;
            if (item.prefix === 'challenge') {
              selected.sub_challenge = null;
              _.each(el.elements, sch => {
                if (!!sch.prefix)
                  sch.selected = false;
              })
            }
          }
        })
        if (_.isNull(this.challenge_selected) && _.isEmpty(this.challenge_selected)) return 0;
        this.loadChallengeContent()
      },
      edit(item = null) {
        if (_.isEmpty(item) || _.isNull(item)) return 0;
        let array = this.structure.structure.list;

        if (item.prefix === 'sub_challenge') {
          array = this.challenge_selected.elements;
        }

        if (_.isEmpty(array) || _.isNull(array)) return 0;

        _.each(this.structure.structure.list  , el => {
          el.editing = false
          if (el.type === CONTAINER) {
            _.each(el.elements, sel => {
              sel.editing = false
            })
          }
        });

        item.editing = true
        this.select(item);
      },
      save(item = null) {
        if (_.isEmpty(item) || _.isNull(item)) return 0;
        item.editing = false;
      },
      remove(item = null, index = null, force = false) {
        if (_.isEmpty(item) || _.isNull(item)) return 0;
        this.select(item)
        let array = null, selected = this.structure.selected;
        if (force) {
          switch (item.prefix) {
            case 'challenge':
              array = this.structure.structure.list;
              break;
            case 'sub_challenge':
              array = this.challenge_selected.elements;
              break;
            default:
              break;
          }
          // if the deleted ch/sch is selected as current
          if (item.id === this.challenge_selected.id) {
            let is_parent = this.challenge_selected.id === item.parent_id;
            if (is_parent)
              selected['sub_challenge'] = null;
            selected[item.prefix] = null
          }
          if (!!this.sub_challenge_selected && item.id === this.sub_challenge_selected.id) {
            selected[item.prefix] = null;
          }

          let ch = _.get(array,[index]);

          this.getExperience(['sessions_deleted']).push(ch.session_id);

          _.each(ch.elements, chEl => {
            if (_.has(chEl, 'component_name') && chEl.component_name === 'evaluation') {
              if (_.has(chEl, 'id'))
                this.getExperience(['evaluations_deleted']).push({
                  evaluation_id: chEl.id,
                  session_id: ch.session_id,
                  experience_id: this.getExperience('id')
                });
            }
            if (ch.type === CONTAINER) {
              if(_.has(chEl, 'session_id'))
                this.getExperience(['sessions_deleted']).push(chEl.session_id);
              _.each(chEl.elements, schEl => {
                if (_.has(schEl, 'component_name') && schEl.component_name === 'evaluation') {
                  if (_.has(schEl, 'id'))
                    this.getExperience(['evaluations_deleted']).push({
                      evaluation_id: schEl.id,
                      session_id: chEl.session_id,
                      experience_id: this.getExperience('id')
                    });
                }
              })
            }
          })

          this.$delete(array, index);
          this.hideModal('delete_session_modal');
          if (_.size(array) === 0)
            this.add(item.prefix);
          this.select(_.head(array));
        } else {
          this.showModal('delete_session_modal', {item: item, index: index})
        }
      },
      loadChallengeContent() { // the header loaded is only for the challenges
        //bus.$emit('loadHeader', this.challenge_selected.header);
        if (this.challenge_selected.type !== 'theme') {
          if (_.isEmpty(this.challenge_selected) || !_.has(this.challenge_selected, 'elements'))
            return console.info('challenge_selected is empty')
          bus.$emit('loadContent', this.challenge_selected.elements);
        } else {
          if (_.isEmpty(this.sub_challenge_selected) || !_.has(this.sub_challenge_selected, 'elements'))
            return console.info('sub_challenge_selected is empty')
          //bus.$emit('loadHeader', this.sub_challenge_selected.header);
          bus.$emit('loadContent', this.sub_challenge_selected.elements);
        }
      },
      setContent(content) {
        if (this.challenge_selected.type === 'content')
          this.challenge_selected.elements = content;
        else {
          this.sub_challenge_selected.elements = content;
        }
      },
      setHeader(header) {
        this.challenge_selected.header = header
      },
      createSession(item) {
        let route = `/experiences/${this.getExperience('system_id')}/sessions/create?json=''`;
        axios.get(route)
          .then(response => {
            let session = response.data.session;
            item.session_id = session.id;
            item.system_id = session.system_id;
            item.status = 1;
          })
          .catch(error => {
            console.log(error)
          })
        return item;
      },
      getSession(path = null) {
        if (_.isNull(path) || _.isEmpty(path)) this.challenge_selected;
        return _.get(this.challenge_selected, path, null);
      },
      setSession(path = null, value) {
        if (!_.isNull(path) || !_.isEmpty(path))
          _.set(this.structure.selected.challenge, path, value);
        this.structure.selected.challenge = value;
      },
      setHeader(header_data = null) {
        if (_.isNull(header_data) || _.isEmpty(header_data)) return 0;
        this.structure.selected.challenge.header = header_data;
      }
    },
    data() {
      return {
        structure: this.componentOptions,
        structure_loaded: false,
        counter: 0,
        sch_counter: 0,
        sortable_options: {
          ch: {
            handle: '.ch.handle',
            onEnd: (evt) => {
              return console.log(evt)
              //return console.log(parent_array)
              let from = evt.oldIndex,
                  to = evt.newIndex,
                  array = _this.structure.structure.list,
                  item = array[from];
              if (from < to) {
                  array.splice(to + 1, 0, item);
                  array.splice(from, 1);
              } else {
                  array.splice(to, 0, item);
                  array.splice(from + 1, 1);
              }
              //_this.setContent(array);
              //_this.canvas.selected.element.editable = true;
              //bus.$emit('enable_editor');
              //this.setContent(index.clone(array));
              _this.reload();
            }
          },
          sch: {
            handle: '.sch.handle',
            onEnd: (evt) => {
              let parent_id = _.get(evt, ['item', 'parentElement', 'dataset', 'parentId'], null),
                  parent_array = null;
              if (!_.isNull(parent_id)) {
                parent_array = _.find(_this.structure.structure.list, {id: parent_id});
                //console.log(evt)
                //return console.log(parent_array)
                let from = evt.oldIndex,
                    to = evt.newIndex,
                    array = parent_array.elements,
                    item = array[from];
                if (from < to) {
                    array.splice(to + 1, 0, item);
                    array.splice(from, 1);
                } else {
                    array.splice(to, 0, item);
                    array.splice(from + 1, 1);
                }
                //_this.setContent(array);
                //_this.canvas.selected.element.editable = true;
                //bus.$emit('enable_editor');
                //this.setContent(index.clone(array));
                //_this.reload();
              }
            }
          }
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
  .section {
    @apply p-0;
  }

  .section .section-title {
    @apply flex items-center justify-center h-16 bg-gray-100 no-select;
  }

  .section .section-content {
    @apply flex items-center justify-center h-12 bg-gray-200;
  }

  .content .section-content {
    @apply block justify-start bg-white h-full;
    height: 445px;
    max-height: 515px;
    overflow-y: auto;
    overflow-x: hidden;
    @media (max-height: 800px) {
      height: 300px;
    }
  }

  .content .item.selected {
    @apply text-blue-600 bg-blue-200 cursor-default;
  }

  .content .item {
    @apply flex flex-12 px-4 py-2 w-full;
  }

  .content .item.sch {
    padding-left: 40px !important;
  }

  .content .item .name {
    max-width: 199px;
  }

  .content .item div {
    @apply px-2;
  }

  .content .handle {
    @apply cursor-move;
  }
</style>
