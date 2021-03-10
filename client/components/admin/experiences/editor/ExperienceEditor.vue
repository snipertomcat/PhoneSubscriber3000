<template>
  <div class="experience-editor">
    <div class="w-full">
      <div class="flex">
        <div class="w-full tools-item border-r-2">
          <span class="text-2xl">{{ experience_title }}</span>
        </div>
        <div class="w-3/12 flex">
          <button class="tools-item w-1/2 py-2" @click="resync">
            <span class="icon">
              <i class="fas fa-sync-alt"></i>
            </span>
          </button>
          <button class="tools-item w-1/2 py-2" @click="showModal('import_modal')" v-if="true">
            <span class="icon">
              <i class="fas fa-upload"></i>
            </span>
          </button>
          <button class="tools-item w-1/2 py-2" @click="exportStructure" v-if="true">
            <span class="icon">
              <i class="fas fa-download"></i>
            </span>
          </button>
          <button class="tools-item w-1/2 py-2" @click="showMovil">
            <span class="icon">
              <i class="experience-editor-icon-mobile"></i>
            </span>
          </button>
          <button class="tools-item w-1/2 py-2" @click="showDesktop">
            <span class="icon">
              <i class="experience-editor-icon-desktop"></i>
            </span>
          </button>
        </div>
      </div>
    </div>
    <div class="w-full h-full flex flex-grow flex-shrink">
      <StructureBar class="structure" :component-options.sync="structure_bar" ref="structure"></StructureBar>
      <Canvas class="outer-canvas" :component-options.sync="canvas" ref="canvas" :tools-bar.sync="tools_bar"></Canvas>
    </div>
    <div class="modals" v-if="modals.open">
      <div class="background" @click="closeModals"></div>
      <div class="cover-modal" v-if="modals.cover_modal.visible">
        <div class="content" style="padding: 1.125rem">
          <div class="header-buttons">
            <div class="close-button">
              <span @click="hideModal('cover_modal')"><i class="fa fa-times"></i></span>
            </div>
          </div>
          <div class="px-5">
            <div class="title">
              <span v-if="true">Añadir imagen</span>
              <span v-else>{{ $t('messages.editor.cover_modal_title') }}</span>
            </div>
            <div class="dropfile mt-4 select-none cursor-pointer">
              <div class="layout" @click="choseFile">
                <div class="layout-content">
                  <div class="block w-1/12 mx-auto"><i class="fas fa-file-image text-6xl"></i></div>
                  <div class="block w-8/12 mx-auto text-center">
                    <span v-if="true">Utiliza una imagen de 900 x 300 px</span>
                    <span v-else>{{ $t('messages.editor.cover_modal_rule') }}</span>
                  </div>
                  <div class="block w-8/12 mx-auto text-center">
                    <span v-if="true">Selecciona una imagen</span>
                    <span v-else>{{ $t('messages.editor.cover_modal_instruction') }}</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="spacer py-4">
              <div class="line"></div>
            </div>
            <div class="buttons">
              <div class="cancel" @click="hideModal('cover_modal')">
                <span v-if="true">Cancelar</span>
                <span v-else>{{ $t('messages.cancel') }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="config-modal" v-if="modals.config_modal.visible">
        <div class="content">
          <div class="header-buttons">
            <div class="close-button">
              <span @click="hideModal('config_modal')"><i class="fa fa-times"></i></span>
            </div>
          </div>
          <div class="px-5">
            <div class="title">
              <span v-if="true">Configuración</span>
              <span v-else>{{ $t('messages.editor.config_modal_title') }}</span>
            </div>
            <div class="">
              <div class="e-visibility">
                <div class="flex items-center w-full h-full py-2 px-4">
                  <div class="w-1/2">
                    <span>Visible para</span>
                  </div>
                  <div class="w-1/2 text-right">
                    <span class="mr-3">Empresa</span>
                    <span><i class="fas fa-angle-down"></i></span>
                  </div>
                </div>
              </div>
              <div class="e-visibility">
                <div class="flex items-center w-full h-full py-2 px-4">
                  <div class="w-1/2">
                    <span>Visible para</span>
                  </div>
                  <div class="w-1/2 text-right">
                    <span class="mr-3">Empresa</span>
                    <span><i class="fas fa-angle-down"></i></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="spacer mb-4 mt-4">
            </div>
            <div class="buttons">
              <div class="accept" @click="hideModal('config_modal')">
                <span v-if="true">Aceptar</span>
                <span v-else>{{ $t('messages.accept') }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="delete_component_modal" v-if="modals.delete_component_modal.visible">
        <div class="content">
          <div class="header-buttons">
            <div class="close-button">
              <span @click="hideModal('delete_component_modal')"><i class="fa fa-times"></i></span>
            </div>
          </div>
          <div class="px-32">
            <div class="title text-center">
            <span v-if="true">
              ¿Estas seguro de querer borrar este componente?
            </span>
              <span v-else>{{ $t('messages.editor.delete_component_modal_title') }}</span>
            </div>
            <div class="mt-5 mb-5">
              <div class="w-full h-full py-2 px-4 tex-center">
                <span>La información no se podrá recuperar</span>
              </div>
            </div>
            <div class="spacer mb-5 mt-5">
            </div>
            <div class="buttons justify-center">
              <div class="cancel" @click="hideModal('delete_component_modal')">
                <span v-if="true">Cancelar</span>
                <span v-else>{{ $t('messages.cancel') }}</span>
              </div>
              <div class="accept" @click="confirmDelete('tool')">
                <span v-if="true">Aceptar</span>
                <span v-else>{{ $t('messages.accept') }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="delete_component_modal" v-if="modals.delete_session_modal.visible">
        <div class="content">
          <div class="header-buttons">
            <div class="close-button">
              <span @click="hideModal('delete_session_modal')"><i class="fa fa-times"></i></span>
            </div>
          </div>
          <div class="px-32">
            <div class="title text-center">
            <span v-if="true">
              ¿Estas seguro de querer borrar este reto?
            </span>
              <span v-else>{{ $t('messages.editor.delete_component_modal_title') }}</span>
            </div>
            <div class="mt-5 mb-5">
              <div class="w-full h-full py-2 px-4 tex-center">
                <span>La información no se podrá recuperar</span>
              </div>
            </div>
            <div class="spacer mb-5 mt-5">
            </div>
            <div class="buttons justify-center">
              <div class="cancel" @click="hideModal('delete_session_modal')">
                <span v-if="true">Cancelar</span>
                <span v-else>{{ $t('messages.cancel') }}</span>
              </div>
              <div class="accept" @click="confirmDelete('session')">
                <span v-if="true">Aceptar</span>
                <span v-else>{{ $t('messages.accept') }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="config-modal" v-if="modals.import_modal.visible">
        <div class="content">
          <div class="header-buttons">
            <div class="close-button">
              <span @click="hideModal('import_modal')"><i class="fa fa-times"></i></span>
            </div>
          </div>
          <div class="px-5">
            <div class="title">
              <span v-if="true">Importar</span>
            </div>
            <div class="">
              <div class="e-visibility">
                <div class="flex items-center w-full h-full py-2 px-4">
                  <div class="w-1/2">
                    <span>Selecciona el json</span>
                  </div>
                  <div class="w-1/2 text-right">
                    <input type="file" ref="import_file_json">
                  </div>
                </div>
              </div>
            </div>
            <div class="spacer mb-4 mt-4">
            </div>
            <div class="buttons">
              <div class="accept" @click="importStructure">
                <button class="btn button">Importar <i class="fas fa-upload"></i></button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="notifications" :class="{'visible':notifications.open, 'hide':!notifications.open}">
      <div class="close-button" v-if="notifications.closeVisible">
        <i class="fa fa-times" @click="closeNotification"></i>
      </div>
      <div class="save-notification"
           :class="notifications.save_notification.options.class"
           v-if="notifications.save_notification.visible">
        <div class="content">
          <div class="message">{{ notifications.save_notification.options.message }}</div>
          <div class="details" v-if="notifications.save_notification.options.detail !== null">
            {{ notifications.save_notification.options.detail }}
          </div>
        </div>
      </div>
    </div>
    <div class="resolution-warning">
      <div class="message">
        <div class="w-full text-center">
          <h2>Para poder utilizar esta sección, debes contar con una resolución de por lo menos 1366px x 768px.</h2>
        </div>
        <div class="w-full pt-5 pb-5"></div>
        <div class="w-full flex justify-center">
          <div class="w-1/4 text-center">
            <a href="/experiences">
              <button class="w-full px-5 py-3 bg-blue-600 text-white rounded-md shadow-md">Aceptar</button>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  const CONTAINER = 'theme';
  const CONTENT = 'content';

  import {editor} from '../../../../static_json/content_editor/editor';
  import StructureBar from './components/StructureBar';
  import ToolsBar from './components/ToolsBar';
  import bus from '../../../../helpers/bus';
  import Canvas from './components/Canvas';
  import {EditorService} from "../../../../services/Experience/ExperienceEditorService";

  export default {
    name: "ExperienceEditor",
    props: {
      experienceData: {required: true}
    },
    components: {StructureBar, Canvas, ToolsBar},
    computed: {
      structure_bar () {
        return editor.structure_bar;
      },
      experience () {
        return editor.experience;
      },
      tools_bar () {
        return editor.tools_bar;
      },
      //preview() { return editor.preview; },
      canvas () {
        return editor.canvas;
      },
      editor () {
        return editor;
      },
    },
    provide () {
      return {
        setExperience: this.setExperience,
        getExperience: this.getExperience,
        setSession: this.setSession,
        getSession: this.getSession,
        getSubSession: this.getSubSession,
        setView: this.setView,
        getView: this.getView,
        getSelected: this.getSelected,
        showModal: this.showModal,
        hideModal: this.hideModal,
        changeTitle: this.changeTitle,
        hasEnrollments: this.hasEnrollments,
      }
    },
    beforeMount () {
      editor.experience = this.experienceData;
      editor.experience.features = {
        "hours_content": {
          "time_value": null,
          "time_period": "minutes"
        },
        "num_videos": null,
        "num_activities": null,
        "num_content_downloads": null
      };
    },
    mounted () {
      bus.$on('hideModal', modal => this.hideModal(modal));
      bus.$on('showModal', (modal, options) => this.hideModal(modal, options));
      bus.$on('preview', () => this.preview());
      bus.$on('publish', () => this.publish())
      this.loadExperience();
      this.experience.sessions_deleted = [];
      this.experience.evaluations_deleted = [];
      this.experience.evaluations_added = [];

      // Print evaluations of experience
      this.printEvaluationsIds()
    },
    methods: {
      printEvaluationsIds () {
        let experienceEvaluations = []
        let experienceEvaluationsInJson = []
        _.forEach(this.experience.sessions, ch => {
          let chJd = JSON.parse(ch.json_data)
          if (_.size(ch.evaluations) > 0) {
            _.forEach(ch.evaluations, eva => {
              experienceEvaluations.push(eva.id)
            })
          }
          if (_.size(ch.childs) > 0) {
            _.forEach(ch.childs, sch => {
              if (_.size(sch.evaluations) > 0) {
                _.forEach(sch.evaluations, eva => {
                  experienceEvaluations.push(eva.id)
                })
              }
            })
          }
          if (chJd.type === 'content') {
            _.forEach (chJd.elements, chEl => {
              if (chEl.component === 'Evaluation')
                experienceEvaluationsInJson.push(chEl.id)
            })
          }
          else {
            _.forEach(chJd.elements, sch => {
              _.forEach (sch.elements, schEl => {
                if (schEl.component === 'Evaluation')
                  experienceEvaluationsInJson.push(schEl.id)
              })
            })
          }
        })
        console.log(`EIDS: ${experienceEvaluations}`)
        console.log(`JDEIDS: ${experienceEvaluationsInJson}`)
      },
      cleanEvaluation(evaluation) {
        let questions = evaluation.questions;
        for (let a = 0; a < _.size(questions); a++) {
          let question = questions[a];
          question.question_id = question.id
          let has_editor = !_.isNull(question.configurations.editor);
          if (has_editor) {
            question.configurations.editor = null
          }
        for (let b = 0; b < _.size(question.answers); b++) {
          let answer = question.answers[b];
          let has_editor = !_.isNull(answer.configurations.editor);
          if (has_editor) {
            answer.configurations.editor = null
          }
        }
        }
        return evaluation;
      },
      async resync () {
        let structure = this.structure_bar.structure.list;
        // Resync sessions
        for (let i = 0; i < _.size(structure); i++) {
          let ch = structure[i];
          let url = route('sessions.update', {experience: this.experience.system_id, session: ch.session_id});
          if (ch.type === CONTAINER) {
            // Resync children
            for (let j = 0; j < _.size(ch.elements); j++) {
              let sch = ch.elements[j];

              // Update evaluation
              let has_evaluation = false;
              has_evaluation = !_.isEmpty(_.find(sch.elements, {component_name: 'evaluation'}));
              if (has_evaluation) {
                let evaluation = sch.elements[0];
                evaluation.experience_sessions = [sch.session_id];
                evaluation = await this.cleanEvaluation(evaluation);
                let result = axios.patch(`/evaluation/${evaluation.id}`, evaluation);

              }

              let url2 = route('sessions.update', {experience: this.experience.system_id, session: sch.session_id});
              sch.session_parent_id = ch.session_id;
              let form2 = {
                title: sch.title,
                summary: sch.title,
                visibility: 1,
                json_data: JSON.stringify(sch),
                parent_id: sch.session_parent_id
              };
              let session2 = await axios.patch(url2, form2);
            }

            let form = {
              title: ch.title,
              summary: ch.title,
              visibility: 1,
              json_data: JSON.stringify(ch)
            };
            let session = await axios.patch(url, form);
          } else {
            let has_evaluation = false;
            has_evaluation = !_.isEmpty(_.find(ch.elements, {component_name: 'evaluation'}));
            if (has_evaluation) {
              let evaluation = ch.elements[0];
              evaluation.experience_sessions = [ch.session_id];
              evaluation = await this.cleanEvaluation(evaluation);
              let result = axios.patch(`/evaluation/${evaluation.id}`, evaluation);
            }

            let form = {
              title: ch.title,
              summary: ch.title,
              visibility: 1,
              json_data: JSON.stringify(ch)
            };
            let session = await axios.patch(url, form);
          }
        }

        this.$refs.canvas.reload();

      },
      loadExperience () {
        let structureBar = this.$refs.structure;
        let experience = this.experience;
        if (!_.isEmpty(this.experience.title)) {
          this.experience_title = this.experience.title;
        }

        if (_.has(experience, 'drafts') && !_.isEmpty(experience.drafts)) {     // Load last draft if exist
          experience = _.last(experience.drafts)
        }

        if (_.has(experience, 'json_data') && typeof experience.json_data === 'string') {   // Parse json_data if exist
          experience.json_data = JSON.parse(experience.json_data)
        } else console.info('json_data is undefined or empty')

        if (!_.has(experience.json_data, 'sessions_data')) {     // Verify sessions_data
          console.info('the experience has invalid json_data')
        } else {
          let content = experience.json_data.sessions_data;

          let ch_counter = 0;
          let sch_counter = 0;

          _.each(content, ch => {
            ch.id = `ch-${ch_counter}`;
            ch.editing = false;
            if (ch.type === 'theme') {
              _.each(ch.elements, sch => {
                sch.id = `sch-${sch_counter}`
                sch.parent_id = ch.id,
                  sch.editing = false;
                sch_counter += 1;
              })
            }
            ch_counter += 1;
          })

          structureBar.counter = ch_counter;
          structureBar.sch_counter = sch_counter;

          this.structure_bar.structure.list = content;
          this.$refs.structure.cleanSelection();
          this.$refs.structure.select(_.first(content));
        }
      },
      hasEnrollments () {
        return _.get(this.experience, ['has_enrollments'], false);
      },
      changeTitle (title) {
        this.experience_title = title
      },
      getSelected (path = []) {
        return _.get(this.$refs.structure.structure.selected, path, null);
      },
      getSubSession (path = null) {
        let session = this.$refs.structure.$data.structure.selected.sub_challenge;
        if (_.isNull(path) || _.isEmpty(path)) return session;
        return _.get(session, path, null);
      },
      getSession (path = null) {
        let session = this.$refs.structure.$data.structure.selected.challenge;
        if (_.isNull(path) || _.isEmpty(path)) return session;
        return _.get(session, path, null);
      },
      setSession (path = null, value) {
        let session = this.$refs.structure.$data.structure.selected.session;
        if (!_.isNull(path) || !_.isEmpty(path))
          _.set(session, path, value);
        session = value;
      },
      getExperience (path = null) {
        if (_.isNull(path) || _.isEmpty(path)) return this.experience;
        return _.get(this.experience, path, null);
      },
      setExperience (path = null, value) {
        if (!_.isNull(path) || !_.isEmpty(path))
          _.set(editor.experience, path, value);
        editor.experience = value;
      },
      showModal (modal = null, options = {}) {
        if (_.isEmpty(modal) || _.isNull('modal')) return 0;
        this.modals.open = true;
        this.modals[modal].options = options;
        this.modals[modal].visible = true;
      },
      closeModals (e) {
        _.each(this.modals, (modal, name) => {
          if (_.has(this.modals[name], 'visible'))
            this.hideModal(name)
        })
      },
      hideModal (modal = null) {
        if (_.isEmpty(modal) || _.isNull('modal')) return 0;
        this.modals.open = false;
        this.modals[modal].visible = false;
        delete this.modals[modal].options;
      },
      showNotification (notification = null, options = {}) {
        if (_.isEmpty(notification) || _.isNull('notification')) return 0;
        this.closeNotification()
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
      closeNotification (e) {
        _.each(this.notifications, (notification, name) => {
          if (_.has(this.notifications[name], 'visible'))
            this.hideNotification(name)
        })
      },
      hideNotification (notification = null) {
        if (_.isEmpty(notification) || _.isNull('notification')) return 0;
        this.notifications.open = false;
        this.notifications[notification].visible = false;
        delete this.notifications[notification].options;
      },
      choseFile (event) {
        bus.$emit('updateCover', event);
      },
      getView (path = null) {
        if (_.isNull(path) || _.isEmpty(path)) return this.preview;
        return _.get(this.preview, path, null);
      },
      setView (path = null, value) {
        if (!_.isNull(path) || !_.isEmpty(path))
          _.set(this.editor.preview, path, value);
        this.editor.preview = value;
      },
      showDesktop () {
        if (this.structure_bar.cover.selected !== 'in_store') {
          let canvas = document.querySelector('.canvas-wrapper');
          canvas.style.width = '900px';
        }
      },
      showMovil () {
        if (this.structure_bar.cover.selected !== 'in_store') {
          let canvas = document.querySelector('.canvas-wrapper');
          canvas.style.width = '320px';
        }
      },
      confirmDelete (option = null) {
        switch (option) {
          case 'tool':
            if (this.modals.delete_component_modal.options.target === 'evaComponent') {
              bus.$emit('removeQuestion', true, this.modals.delete_component_modal.options);
              break;
            }
            bus.$emit('removeTool', true, this.modals.delete_component_modal.options);
            break;
          case 'session':
            bus.$emit('removeSession', true, this.modals.delete_session_modal.options);
            break;
          default:
            break;
        }
      },
      async prepareContent () {
        let content = await EditorService.destroyTipTapConstructor(this.structure_bar.structure.list, this.hasEnrollments(), this.getExperience('id'));
        this.publish(content)
      },
      preview () {
      },
      async exportStructure () {
        console.log('Generating json...')
        let json = await EditorService.destroyTipTapConstructor(this.structure_bar.structure.list, this.hasEnrollments(), this.experienceData.id);
        let structure = JSON.stringify(json.content);
        console.log('Done.')
        let element = document.createElement('a');
        element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(structure));
        element.setAttribute('download', `experience_${this.experience_title}_id_${this.experienceData.id}.json`);

        element.style.display = 'none';
        document.body.appendChild(element);

        element.click();

        document.body.removeChild(element);
      },
      async importStructure () {
        let input = this.$refs.import_file_json;
        let file = _.head(input.files);

        let reader = new FileReader();
        reader.onload = () => {
          let content = JSON.parse(reader.result);
          this.processJson(content);
          input.value = null;
        };
        reader.readAsText(file);
      },
      async processJson (content = null) {
        if (content === null) return 0;
        let structureBar = this.$refs.structure;
        let ch_counter = 0;
        let sch_counter = 0;

        // Reset internal ch and sch ids
        _.each(content, ch => {
          ch.id = `ch-${ch_counter}`
          if (ch.type === 'theme') {
            _.each(ch.elements, sch => {
              sch.id = `sch-${sch_counter}`
              sch.parent_id = ch.id
              sch_counter += 1;
            })
          }
          ch_counter += 1;
        })

        structureBar.counter = ch_counter;
        structureBar.sch_counter = sch_counter;

        // Create sessions
        for (let i = 0; i < _.size(content); i++) {
          let ch = content[i];
          let url = route('sessions.store', {experience: this.experience.system_id});
          let form = {
            title: ch.title,
            summary: ch.title,
            visibility: 1,
            json_data: JSON.stringify(ch)
          }
          let session = await axios.post(url, form);
          ch.session_id = session.data.id;
          ch.system_id = session.data.system_id;

          // Create evaluation
          if (ch.type === 'content') {
            for (let k = 0; k < _.size(ch.elements); k++) {
              let elem = ch.elements[k];
              if (elem.component_name === 'evaluation') {
                elem.id = null;
                delete elem.id;
                elem.experiences = [this.experience.id];
                elem.experience_sessions = [ch.session_id];
                for (let m = 0; m < elem.questions; m++) {
                  let question = elem.questions[m];
                  question.id = null;
                  for (let n = 0; n < question.answers; n++) {
                    let answer = question.answers[n];
                    answer.id = null;
                  }
                }
                //let eva = await axios.post('/evaluation', elem);
                //elem.id = eva.id;
              }
            }
          }

          // Create sub-sessions
          if (ch.type === 'theme') {
            for (let j = 0; j < _.size(ch.elements); j++) {
              let sch = ch.elements[j];
              let form2 = {
                title: sch.title,
                summary: sch.title,
                visibility: 1,
                parent_id: ch.session_id,
                json_data: JSON.stringify(sch)
              }
              let session2 = await axios.post(url, form2);
              sch.session_id = session2.data.id;
              sch.system_id = session2.data.system_id;
              sch.session_parent_id = ch.session_id;

              // Create evaluation
              for (let k = 0; k < _.size(sch.elements); k++) {
                let elem2 = sch.elements[k];
                if (elem2.component_name === 'evaluation') {
                  for (let l = 0; l < _.size(elem2.questions); l++) {
                    let quest = elem2[l];
                  }
                  //elem2.id = null;
                  delete elem2.id;
                  elem2.experiences = [this.experience.id];
                  elem2.experience_sessions = [sch.session_id];
                  for (let m = 0; m < elem2.questions; m++) {
                    let question = elem2.questions[m];
                    question.id = null;
                    for (let n = 0; n < question.answers; n++) {
                      let answer = question.answers[n];
                      answer.id = null;
                    }
                  }
                  //let eva = await axios.post('/evaluation', elem2);
                  //elem2.id = eva.id;
                }
              }
            }
          }
          console.log('=>', session)
        }

        this.structure_bar.structure.list = content;

        structureBar.cleanSelection()
        let first = _.first(content);
        if (first.type === 'theme')
          first = _.first(first.elements)
        structureBar.select(first)

        this.hideModal('import_modal')
      },
      publish (content = null) {
        if (_.isEmpty(content) && content === null) {
          this.showNotification('save_notification', {
            class: 'info',
            message: 'Guardando experiencia...'
          });
          this.prepareContent();
        } else {
          let url = '/experience/store-experience';
          let experience_data = {
            experience: {},
            content: [],
            sessions_deleted: [],
            evaluations_deleted: [],
            evaluations_added: []
          };
          _.set(experience_data, 'content', content.content);

          _.set(experience_data, 'experience', {
            id: this.experience.id,
            system_id: this.experience.system_id,
            title: this.experience.title,
            summary: this.experience.summary,
            description: this.experience.description,
            price: this.experience.price,
            price_default: this.experience.price_default,
            full_path_cover: this.experience.full_path_cover,
            company_objectives: '<p></p>',
            area_objectives: '<p></p>',
            //visibility: this.experience.visibility = 1,
            tags: this.experience.tags = [],
            instructors: this.experience.instructors = [],
          });

          _.set(experience_data, 'sessions_deleted', this.experience.sessions_deleted);
          _.set(experience_data, 'evaluations_deleted', this.experience.evaluations_deleted);
          _.set(experience_data, 'evaluations_added', content.evaluations);

          //console.log(experience_data)

          axios.post(url, experience_data)
            .then(response => {
              //console.log(response.data)
              setTimeout(() => {
                this.showNotification('save_notification', {
                  class: 'success',
                  message: 'Experiencia guardada',
                  autoClose: true,
                });
              }, 500)
              this.$refs.canvas.reload();
              setTimeout(() => {
              }, 0)
            })
            .catch(error => {
              console.error(error)
              setTimeout(() => {
                this.showNotification('save_notification', {
                  class: 'error',
                  message: 'Error al guardar la experiencia',
                  detail: `${error.response.data.msg}`,
                  closeVisible: true
                });
              }, 500)
            })
        }
      }
    },
    data () {
      return {
        resync_btn: false,
        session_id: null,
        preview_open: false,
        experience_title: 'Añade un nombre a tu experiencia',
        modals: {
          open: false,
          cover_modal: {
            visible: false
          },
          config_modal: {
            visible: false
          },
          delete_component_modal: {
            visible: false
          },
          delete_session_modal: {
            visible: false
          },
          import_modal: {
            visible: false
          }
        },
        notifications: {
          open: false,
          closeVisible: false,
          save_notification: {
            visible: false,
          },
        }
      };
    }
  };
</script>
<style>
  html {
    overflow-y: auto;
  }

  .apithy-footer {
    display: none;
  }
</style>
