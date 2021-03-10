<template>
    <div>
        <div class="canvas-wrapper" :class="{'shadow-canvas':is_content_section_selected}">
            <template v-if="is_experience_cover_selected">
                <div class="in-store">
                    <div class="experience-card-wrapper">
                        <div class="step">
                            <div class="step-number">Paso 1</div>
                        </div>
                        <div class="experience-options">
                            <div class="options" @click="showOptions">
                                <i class="fas fa-cog"></i>
                            </div>
                        </div>
                        <div class="experience-card">
                            <div class="e-cover cursor-pointer" @click="showModal('cover_modal')">
                                <input id="eCover" type="file" class="hidden" @change="submitCover(true)">
                                <template v-if="has_cover">
                                    <div class="image-wrapper">
                                        <img id="cover" :src="cover" alt="">
                                    </div>
                                    <div class="hidden-layout">
                                        <div class="layout-content">
                                            <div class="block w-1/6 mx-auto"><i class="fas fa-file-image text-6xl"></i></div>
                                            <div class="block w-6/12 mx-auto"><span>Elige una imagen</span></div>
                                        </div>
                                    </div>
                                </template>
                                <template v-else>
                                    <div class="h-full py-8">
                                        <div class="block w-1/6 mx-auto"><i class="fas fa-file-image text-6xl"></i></div>
                                        <div class="block w-6/12 mx-auto"><span>Elige una imagen</span></div>
                                    </div>
                                </template>
                            </div>
                            <div class="e-author border-0">
                                <div class="image-wrapper">
                                    <img :src="experience.company_author.full_path_logo" alt="">
                                </div>
                            </div>
                            <div class="e-title">
                                <div class="w-full h-full py-4 px-4">
                                    <textarea class="w-full h-full" placeholder="Añade el nombre de tu experiencia" v-model="experience.title" @input="setTitle"/>
                                </div>
                            </div>
                            <div class="e-teacher">
                                <template v-if="has_teacher"></template>
                                <template v-else>
                                    <div class="h-full py-3 px-4">
                                        <span>Añade un instructor</span>
                                    </div>
                                </template>
                            </div>
                            <div class="e-tags">
                                <template v-if="has_tags"></template>
                                <template v-else>
                                    <div class="h-full py-3 px-4">
                                        <span>Añade etiquetas</span>
                                    </div>
                                </template>
                            </div>
                            <div class="e-price border-0">
                                <div class="w-full py-4 px-4">
                                    <input class="w-full" type="number" min="0" v-model="experience.price_default" placeholder="Añade un precio">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="experience-card-wrapper">
                        <div class="step">
                            <div class="step-number">Paso 2</div>
                        </div>
                        <div class="experience-card">
                            <div class="e-summary">
                                <div class="w-full h-full py-4 px-4">
                                    <textarea class="w-full h-full"
                                              placeholder="Añade un resumen de la experiencia"
                                              v-model="experience.summary">
                                    </textarea>
                                </div>
                            </div>
                            <div class="e-description">
                                <div class="w-full h-full py-4 px-4">
                                    <textarea class="w-full h-full"
                                              placeholder="Añade una descripción del contenido"
                                              v-model="experience.description">
                                    </textarea>
                                </div>
                            </div>
                            <div class="e-features">
                                <div class="w-full py-3 px-3 text-center text-xs">
                                    <span>Añade características de tu contenido</span>
                                </div>
                                <div class="flex">
                                    <div class="w-full py-2 px-4">
                                        <input class="w-full h-full"
                                               type="number"
                                               min="0"
                                               v-model="experience.features.hours_content.time_value"
                                               placeholder_="Tiempo de contenido en minutos"
                                               placeholder="Tiempo en minutos">
                                    </div>
                                    <div class="w-full py-2 px-4">
                                        <input class="w-full h-full"
                                               type="number"
                                               min="0"
                                               v-model="experience.features.num_videos"
                                               placeholder_="Cuantos videos contiene"
                                               placeholder="Núm. de videos">
                                    </div>
                                </div>
                                <div class="flex">
                                    <div class="w-full py-2 px-4">
                                        <input class="w-full h-full"
                                               type="number"
                                               min="0"
                                               v-model="experience.features.num_content_downloads"
                                               placeholder_="Cuantos archivos descargables"
                                               placeholder="Num. de descargables">
                                    </div>
                                    <div class="w-full py-2 px-4">
                                        <input class="w-full h-full"
                                               type="number"
                                               min="0"
                                               v-model="experience.features.num_activities"
                                               placeholder="Núm. de actividades">
                                    </div>
                                </div>
                            </div>
                            <div class="e-buttons border-0">
                                <div class="action-button add-cart">
                                    <span>Añadir al carrito</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <template v-if="is_content_section_selected">
                <div class="content">
                    <div class="canvas" id="canvas">
                        <div class="canvas-overlay" @click="unselect"></div>
                        <Header class="" :header-data.sync="canvas.content.header" v-if="false"></Header>
                        <div class="canvas-elements" v-sortable="sortable_options">
                            <template v-if="!canvas.loading">
                                <template v-for="(item,index) in canvas.content.elements">
                                    <template v-if="!item.prefix">
                                        <component-wrapper :component-data="item" :key="index" :index="index"></component-wrapper>
                                    </template>
                                </template>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
        </div>
        <ToolsBar class="tools z-10" :tools-bar.sync="tools_bar"></ToolsBar>
    </div>
</template>

<script>
    import {evaluableToolsArray} from "../../../../../static_json/content_editor/tools";
    import {index} from "../../../../../helpers";
    import bus from "../../../../../helpers/bus";
    import ComponentWrapper from "./ComponentWrapper";
    import ToolsBar from "./ToolsBar";
    import Header from './Header';

    let _this;
    export default {
        name: "Canvas",
        props: {
            componentOptions: {required: true},
            toolsBar: {required: true}
        },
        inject: ['getExperience', 'setExperience', 'getSession', 'getSubSession', 'showModal', 'hideModal','changeTitle'],
        components: {ComponentWrapper, ToolsBar, Header},
        mounted() {
          _this = this;
            bus.$on('addTool', tool => this.addComponent(tool));
            bus.$on('removeTool', (confirm, options) => this.removeComponent(confirm, options));
            bus.$on('updateCover', event => this.submitCover());
            bus.$on('loadContent', content => this.loadContent(content));
            bus.$on('loadHeader', header => this.loadHeader(header));
            bus.$on('selectComponent', uid => this.selectComponent(uid));
            bus.$on('sectionChanged', section => { this.section_selected = section });
            bus.$on('reload', () => this.reload());
        },
        watch: {
            'this.canvas.selected.element.editable': value => {
                console.log(value)
            }
        },
        computed: {
            component_selected() {
                return this.canvas.selected.element
            },
            is_experience_cover_selected() {
                return this.section_selected === 'in_store'
            },
            is_content_section_selected() {
                return this.section_selected === 'content'
            },
            tools_bar() {
                return this.toolsBar;
            },
            experience() {
                return this.getExperience();
            },
            has_cover() {
                return !_.isNull(this.getExperience('full_path_cover'));
                // retornar experiencia;
            },
            has_title() {
                return !_.isNull(this.getExperience('title'));
                // retornar experiencia;
            },
            has_teacher() {
                return !_.isNull(this.getExperience('teacher'));
                // retornar experiencia;
            },
            has_author() {
                return !_.isNull(this.getExperience(['company_author','name']));
                // retornar experiencia;
            },
            has_tags() {
                return !_.isNull(this.getExperience('tags'));
                // retornar experiencia;
            },
            has_price() {
                let price = !_.isNull(this.getExperience('price_default'));
                return (!_.isNull(price) && price > 0);
                // retornar experiencia;
            },
            cover() {
                return this.experience.full_path_cover;
            },
            evaluableToolsArray () { return evaluableToolsArray },
            evaluation_template () {
                return this.tools_bar.tools.evaluable.evaluation.template;
            },
        },
        methods: {
            is_evaluable (tool) {
                let componentName = tool.component;
                if(_.has(tool, 'not_evaluable') && tool.not_evaluable) {
                    return false;
                }
                return this.evaluableToolsArray.find(cName => cName === componentName);
            },
            setTitle(event) {
                this.changeTitle(event.target.value)
            },
            submitCover(submit = false) {
                let input = document.getElementById('eCover');
                let url = route('cover.update', [this.experience]);

                if (!submit) {
                    input.click();
                }
                else {
                    if (_.isEmpty(input.files)) return 0;
                    let file = input.files[0];
                    let ext = file.name.substr(file.name.lastIndexOf('.') + 1);
                    let fd = new FormData();

                    fd.append('name', file.name);
                    fd.append('cover', file);
                    axios.post(url, fd)
                        .then(response => {
                            this.setExperience('cover', file)
                            this.updateCover(response.data)
                        })
                        .catch(errors => {
                            console.log(errors)
                        })
                }
            },
            updateCover(data) {
                let src = _.get(data, ['src'], '');
                let image = document.querySelector('.e-cover img#cover');
                this.setExperience('full_path_cover', src);
                image.src = src;
                bus.$emit('hideModal', 'cover_modal');
            },
            unselect() {
                _.each(this.canvas.content.elements, item => {
                    item.selected = false;
                    this.canvas.selected.element = null;
                    if (this.is_evaluable(item.component)) {
                        _.each(item.questions, el => {
                            el.selected = false;
                        })
                    }
                })
            },
            selectComponent(uid) {
                _.each(this.canvas.content.elements, item => {
                    if (item.uid === uid) {
                        item.selected = true;
                        this.canvas.selected.element = item;
                    } else {
                        item.selected = false;
                    }
                })
            },
            addComponent(tool = null) {
                if (_.isEmpty(tool) || _.isNull(tool)) return 0;
                if (_.isEmpty(tool.component) || _.isNull(tool.component)) return 0;

                let array = this.canvas.content.elements;
                let is_new = true;
                if (!_.isArray(array))
                    array = [];
                // evaluable component verification
                if (this.is_evaluable(tool)) {
                    let evaluation = null;
                    if (_.isEmpty(array)) {
                        if (_.isEmpty(this.component_selected) || _.isNull(this.component_selected)) {
                            evaluation = index.clone(this.evaluation_template);
                        } else if (this.component_selected.component !== 'Evaluation') {
                            evaluation = index.clone(this.evaluation_template);
                        } else {
                            is_new = false;
                            evaluation = this.component_selected;
                        }
                    } else {
                        is_new = false;
                        this.component_selected = evaluation = _.find(array, { component_name: 'evaluation' });
                    }
                    if (!_.isNull(evaluation) && !_.isEmpty(evaluation) && !!evaluation) {
                        evaluation.selected = true;
                        evaluation.questions.push(tool);
                        tool = evaluation;
                    }
                }
                else {
                    this.unselect();
                }
                let has_evaluation = _.find(array, { component_name: 'evaluation' });
                if (!has_evaluation)
                    if (is_new) array.push(tool);
                this.canvas.content.elements = array;

                this.setContent();
            },
            removeComponent(confirm = false, options = {}) {
              if (_.isEmpty(this.component_selected)) return console.error('component selected is empty')
                if (confirm && options.target === 'component') {
                    let uid = this.component_selected.uid;
                    _.remove(this.canvas.content.elements, element => {
                        return element.uid === uid
                    });
                    if (this.component_selected.component_name === 'evaluation')
                        if (_.has(this.component_selected, 'id')) {
                            let session_id = this.getSession('session_id');
                            if (this.getSession('type') === 'theme')
                                session_id = this.getSubSession('session_id');
                            this.getExperience(['evaluations_deleted']).push({
                                evaluation_id: this.component_selected.id,
                                session_id: session_id,
                                experience_id: this.getExperience('id')
                            });
                        }
                    this.canvas.selected.element = null;
                    this.hideModal('delete_component_modal');

                    this.setContent();
                    this.reload();
                } else {
                    this.showModal('delete_component_modal', options);
                }
            },
            loadHeader(header) {
                this.canvas.content.header = header;
                bus.$on('updateHeader');
            },
            loadContent(content) {
                try {
                    this.canvas.content.elements = content;
                    this.canvas.selected.element = null;
                    this.reload();
                } catch (e) {

                }
            },
            setContent(content = null) {
                if (_.isNull(content))
                    content = this.canvas.content.elements;
                this.canvas.content.elements = content;
                bus.$emit('setContent', content);
            },
            showOptions() {
                this.showModal('config_modal');
            },
            reload() {
                this.canvas.loading = true;
                setTimeout(() => {
                    this.canvas.loading = false;
                }, 10);
            }
        },
        data() {
            return {
                canvas: this.componentOptions,
                section_selected: null,
                sortable_options: {
                    handle: '.title-component>.handle',
                    onStart: (evt) => {
                        _this.canvas.selected.element.editable = false;
                        bus.$emit('disable_editor');
                        let uid = parseInt(_.split(evt.item.id, '-', 2)[1]);
                        let item = _.find(_this.canvas.content.elements, {uid: uid});
                        if (_.isEmpty(item) || _.isNull(item) || item === 'undefined') return 0;
                    },
                    onEnd: (evt) => {
                        let from = evt.oldIndex,
                            to = evt.newIndex,
                            array = _this.canvas.content.elements,
                            item = array[from];
                        if (from < to) {
                            array.splice(to + 1, 0, item);
                            array.splice(from, 1);
                        } else {
                            array.splice(to, 0, item);
                            array.splice(from + 1, 1);
                        }
                        _this.setContent(array);
                        _this.canvas.selected.element.editable = true;
                        bus.$emit('enable_editor');
                        //this.setContent(index.clone(array));
                        _this.reload();
                    }
                }
            };
        }
    }
</script>

<style>
    /* Content elements titles */
    .canvas-elements>.wrapper .title-component, .evaluation-content>.wrapper .evaluation-title-component {
        @apply h-0 overflow-hidden;
        transition: all 0.5s;
    }

    .canvas-elements>.wrapper.selected, .evaluation-content>.wrapper.selected {
        border: 3px solid #444444;
    }

    .canvas-elements>.wrapper.selected .title-component, .evaluation-content>.wrapper.selected .evaluation-title-component {
        @apply flex items-center h-6 select-none text-white;
        background-color: #444444;
    }
</style>
