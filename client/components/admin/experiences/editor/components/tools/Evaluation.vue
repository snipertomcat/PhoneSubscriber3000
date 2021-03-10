<template>
  <div class="evaluation">
    <!--div class="evaluation-overlay" @click="unselect"></div-->
    <div class="evaluation-content" v-sortable="sortable_options">
      <template v-if="!loading">
        <template v-for="(question, index) in component.questions">
          <div :class="{'wrapper': true, 'selected': question.selected }" :key="index" :id="'uid'" @click="selectComponent(question)">
            <div class="evaluation-title-component">
              <span class="w-2/12">{{ $t(`messages.editor.${question.component_name}`) }}</span>
              <!--span class="w-8/12 text-center"><i class="fas fa-grip-horizontal"></i></span-->
              <span class="handle w-8/12 text-center"><i class="fas fa-grip-horizontal"></i></span>
              <div class="w-1/12"></div>
              <span class="w-1/12 text-center hover:bg-gray-800"
                    @click="removeTool(false, {index: index})">
                <i class="fas fa-trash-alt"></i>
              </span>
            </div>
            <div class="evaluation-component">
              <template v-if="is_true_false(question)">
                <c-true-false :component-options="question" :bus="$parent.bus"></c-true-false>
              </template>
              <template v-if="is_multiple(question)">
                <c-multiple :component-options="question" :bus="$parent.bus"></c-multiple>
              </template>
              <template v-if="is_sort(question)">
                <c-sort-answers :component-options="question" :bus="$parent.bus"></c-sort-answers>
              </template>
              <template v-if="is_sort_images(question)">
                <c-sort-images :component-options="question" :bus="$parent.bus"></c-sort-images>
              </template>
              <template v-if="is_filling_blanks(question)">
                <c-filling-blank :component-options="question" :bus="$parent.bus"></c-filling-blank>
              </template>
              <template v-if="is_click_and_drop(question)">
                <c-click-and-drop :component-options="question" :bus="$parent.bus"></c-click-and-drop>
              </template>
            </div>
          </div>
        </template>
      </template>
    </div>
  </div>
</template>

<script>
  import bus from "../../../../../../helpers/bus";
  import MultipleOptions from './MultipleOptions';
  import TrueAndFalse from './TrueAndFalse';
  import SortAnswers from './SortAnswers';
  import SortImages from './SortImages';
  import FillingBlanks from './FillingBlanks';
  import ClickAndDrop from './ClickAndDrop';

  let _this;
  export default {
    name: "Evaluation",
    props: {
      componentOptions: {
        required: true,
        type: Object
      }
    },
    inject: ['showModal','hideModal','getExperience','getSession','getSelected','getSubSession'],
    provide() { return {
      selectEvaComponent: this.selectComponent,
      enableEditor: this.enableEditor,
      disableEditor: this.disableEditor
    };},
    components: {
      'c-multiple': MultipleOptions,
      'c-true-false': TrueAndFalse,
      'c-sort-images': SortImages,
      'c-sort-answers': SortAnswers,
      'c-filling-blank': FillingBlanks,
      'c-click-and-drop': ClickAndDrop
    },
    computed: {
      component() {
        return this.componentOptions
      },
      is_loading() {
        return this.loading;
      }
    },
    created() {
      _this = this;
      let timestamp = moment().format("DDMMYYYYhhmmss");

      bus.$on('removeQuestion', (confirm, options) => this.removeTool(confirm, options));
      bus.$on('disable_editor', () => this.disableEditor());
      bus.$on('enable_editor', () => this.enableEditor());

      this.component.component_name = 'evaluation';
      this.component.title = 'evaluation';
      this.component.name = `evaluation_${this.component.uid}_${timestamp}_exp_${this.getExperience('id')}`;
      this.component.description = 'evaluation';
      this.component.type = 'activity';
      this.component.difficulty = 'basic';
      this.component.creation_type = 'manual';
      this.component.experiences = [this.getExperience('id')];
    },
    mounted () {
      let session = this.getSession();
      if (session.type === 'theme') {
        session = this.getSubSession();
      }
      if (!_.isEmpty(session)) {
        this.component.experience_sessions = [session.session_id];
      }
    },
    methods: {
      is_true_false(question) {
        return question.component === 'TrueAndFalse';
      },
      is_multiple(question) {
        return question.component === 'MultipleOptions';
      },
      is_sort(question) {
        return question.component === 'SortAnswers';
      },
      is_sort_images(question) {
        return question.component === 'SortImages';
      },
      is_filling_blanks(question) {
        return question.component === 'FillingBlanks';
      },
      is_click_and_drop(question) {
        return question.component === 'ClickAndDrop';
      },
      selectComponent(component) {
        this.unselect();
        component.selected = true;
      },
      unselect() {
        _.each(this.component.questions, item => {
          item.selected = false;
        })
      },
      removeTool(confirm = false, options = {}) {
        options.target = 'evaComponent';
        if (confirm && options.target === 'evaComponent') {
          let dQ = [];
          if (_.has(this.component, 'deleted_questions')) {
            dQ = this.component.deleted_questions;
          }
          let removed_question = _.remove(this.component.questions, (element, i) => {
            return i === options.index
          });
          if (_.has(removed_question, 'id')) {
            dQ.push(removed_question[0])
          }
          this.component.deleted_questions = dQ;
          this.hideModal('delete_component_modal');

          //this.setContent();
          this.reload();
          if (_.size(this.component.questions) === 0) {
            bus.$emit('removeTool', true, {target: 'component', without_modal: true})
          }
        } else {
          options.target = 'evaComponent';
          if (!options.without_modal) {
            this.showModal('delete_component_modal', options);
          }
        }
      },
      disableEditor () {
        _.each(this.component.questions, component => {
          if (_.has(component, 'editor')) {
            if (_.has(component.editor, 'setOptions'))
              component.editor.setOptions({
                editable: false,
              })
          }
          if(_.has(component, 'answers')) {
            _.each(component.answers, answer => {
              let c = 'config';
              if(_.has(answer, 'configurations')) c = 'configurations';
              if(!_.isNull(answer[c]) && answer[c] !== undefined) {
                if(_.has(answer[c], 'editor'))
                  if (_.has(answer[c].editor, 'setOptions'))
                    answer[c].editor.setOptions({
                      editable: false,
                    })
              }
            })
          }
        })
      },
      enableEditor () {
        _.each(this.component.questions, component => {
          if (_.has(component, 'editor')) {
            if (_.has(component.editor, 'setOptions'))
              component.editor.setOptions({
                editable: true,
              })
          }
          if(_.has(component, 'answers')) {
            _.each(component.answers, answer => {
              let c = 'config';
              if(_.has(answer, 'configurations')) c = 'configurations';
              if (!_.isNull(answer[c]) && answer[c] !== undefined) {
                if (_.has(answer[c], 'editor'))
                  if (_.has(answer[c].editor, 'setOptions'))
                    answer[c].editor.setOptions({
                      editable: true,
                    })
              }
            })
          }
        })
      },
      reload() {
        this.loading = true;
        setTimeout(() => {
          this.loading = false;
        }, 10);
      }
    },
    data() {
      return {
        loading: false,
        sortable_options: {
          group: {
            name: 'evaluations'
          },
          handle: '.evaluation-title-component>.handle',
          onStart: (evt) => {
            //this.canvas.selected.element.editable = false;
            bus.$emit('disable_editor');
            let uid = parseInt(_.split(evt.item.id, '-', 2)[1]);
          },
          onEnd: (evt) => {
            let from = evt.oldIndex,
              to = evt.newIndex,
              array = _this.component.questions,
              item = array[from];
            if (from < to) {
              array.splice(to + 1, 0, item);
              array.splice(from, 1);
            } else {
              array.splice(to, 0, item);
              array.splice(from + 1, 1);
            }
            //_this.setContent(array);
            //this.canvas.selected.element.editable = true;
            bus.$emit('enable_editor');
            //this.setContent(index.clone(array));
            _this.reload();
          }
        }
      };
    }
  }
</script>

<style scoped>
  .evaluation {
    position: relative;
  }
  .evaluation .evaluation-overlay {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 0;
  }
  .evaluation-content {
    z-index: 1;
  }
  .evaluation-content {
    padding: 10px;
  }
</style>
