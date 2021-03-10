<template>
    <div class="evaluable-tool" @click="selectEvaComponent(component)">
        <div class="question">
            <editor-menu-bar :editor="editor" v-slot="{commands, isActive, focused}">
                <div class="menubar" :class="{ 'hidden': !focused }">
                    <button class="menu__button"
                            :class="{'is-active': isActive.bold()}"
                            @click="commands.bold">
                        <i class="fas fa-bold"></i>
                    </button>
                    <button class="menu__button"
                            :class="{'is-active': isActive.italic()}"
                            @click="commands.italic">
                        <i class="fas fa-italic"></i>
                    </button>
                    <button class="menu__button"
                            :class="{'is-active': isActive.underline()}"
                            @click="commands.underline">
                        <i class="fas fa-underline"></i>
                    </button>
                    <button class="menu__button"
                            :class="{'is-active': isActive.strike()}"
                            @click="commands.strike">
                        <i class="fas fa-strikethrough"></i>
                    </button>
                    <button class="menu__button"
                            :class="{'is-active': isActive.heading({level:2})}"
                            @click="commands.heading({level:2})">
                        H2
                    </button>
                    <button class="menu__button"
                            :class="{'is-active': isActive.heading({level:3})}"
                            @click="commands.heading({level:3})">
                        H3
                    </button>
                    <button class="menu__button"
                            :class="{'is-active': isActive.ordered_list()}"
                            @click="commands.ordered_list">
                        <i class="fas fa-list-ol"></i>
                    </button>
                    <button class="menu__button"
                            :class="{'is-active': isActive.bullet_list()}"
                            @click="commands.bullet_list">
                        <i class="fas fa-list-ul"></i>
                    </button>
                    <button class="menu__button"
                            :class="{'is-active': isActive.blockquote()}"
                            @click="commands.blockquote">
                        <i class="fas fa-quote-right"></i>
                    </button>
                    <button class="menu__button"
                            :class="{'is-active': isActive.code_block()}"
                            @click="commands.code_block">
                            <i class="fas fa-code"></i>
                    </button>
                    <button class="menu__button"
                            :class="{'is-active': isActive.horizontal_rule()}"
                            @click="commands.undo">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button class="menu__button"
                            @click="commands.undo">
                        <i class="fas fa-undo"></i>
                    </button>
                    <button class="menu__button"
                            @click="commands.redo">
                        <i class="fas fa-redo"></i>
                    </button>
                </div>
            </editor-menu-bar>
            <editor-content class="editor__content border border-gray-300" :editor="editor"></editor-content>
        </div>
        <div class="w-full">
            <div class="flex sort-images">
                <div class="flex answers" v-sortable="sortable_options" v-if="!loading">
                    <template v-for="(answer, index) in answers">
                        <div class="w-auto image-answer" :key="index">
                            <div class="remove-answer">
                                <div class="remove-icon" @click="removeAnswer(index)">
                                    <i class="fa fa-times"></i>
                                </div>
                            </div>
                            <div class="indicator">
                                <div class="num">{{index + 1}}</div>
                            </div>
                            <div class="answer" :class="{'loaded':!lodash.isEmpty(answer.image)}">
                                <div class="" @click="addImageFor(`answer_${index}_${uuid}`)">
                                    <input :id="`answer_${index}_${uuid}`" type="file" class="hidden" v-on:change="updateFile($event, answer)">
                                    <template v-if="lodash.isEmpty(answer.image)">
                                        <i class="fas fa-image"></i>
                                    </template>
                                    <template v-else-if="!answer.configurations.uploadfinished">
                                        <span>{{answer.configurations.uploadPercent}}%</span>
                                    </template>
                                    <template v-else>
                                        <img :src="answer.image" :alt="answer.configurations.imageData.title">
                                    </template>
                                </div>
                                <div class="footer">
                                    <div class="image-text">
                                        <textarea type="text" maxlength="22" v-model="answer.configurations.imageText" placeholder="coloca tu texto"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
                <template v-if="lodash.size(this.answers) < answer_limit">
                    <div class="w-auto">
                        <div class="add-answer" @click="addAnswer">
                            <i class="fas fa-plus"></i>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
    import bus from "../../../../../../helpers/bus";
    import { Editor, EditorContent, EditorMenuBar } from 'tiptap';
    import {
        Blockquote,
        CodeBlock,
        HardBreak,
        Heading,
        HorizontalRule,
        OrderedList,
        BulletList,
        ListItem,
        TodoItem,
        TodoList,
        Bold,
        Code,
        Italic,
        Link,
        Strike,
        Underline,
        History,
        Focus
    } from 'tiptap-extensions';

    export default {
        name: "SortImages",
        inject: ['helper'],
        props: {
            componentOptions: {
                required: true,
                type: Object
            }
        },
        components: {
            EditorContent,
            EditorMenuBar
        },
        computed: {
            input() { return this.component.title },
            answers() { return this.component.answers },
            configurations() { return this.component.configurations },
            default_answer() { return this.component.configurations.default_answer },
            lodash() { return _ },
            upload_route () {
                let url = route('sessions.media.upload', {
                    experience: this.$parent.getExperience('system_id'),
                    session: this.$parent.getSession('system_id')
                });
                return url;
            },
            uuid() { return this._uid; }
        },
        created () {
            bus.$on('disable_editor', evt => {
                this.editor.setOptions({
                    editable: false,
                })
            })
        },
        mounted () {
            this.initEditor();
        },
        methods: {
            selectEvaComponent() {
                if(!_.has(this.component, 'not_evaluable')) {
                    return this.$parent.selectEvaComponent();
                }
            },
            initEditor() {
                this.editor = null;
                this.editor = new Editor({
                    editable: false,
                    content: this.configurations.html,
                    extensions: [
                        new Blockquote(),
                        new BulletList(),
                        new CodeBlock(),
                        new HardBreak(),
                        new Heading({ levels: [2, 3] }),
                        new HorizontalRule(),
                        new ListItem(),
                        new OrderedList(),
                        new TodoItem(),
                        new TodoList(),
                        new Link(),
                        new Bold(),
                        new Code(),
                        new Italic(),
                        new Strike(),
                        new Underline(),
                        new History(),
                        new Focus({
                            className: "editor-focused",
                            nested: false
                        })
                    ],
                    onFocus: (e) => {
                        this.editor.setOptions({
                            editable: true,
                        })
                        if(!this.component.selected)
                            this.selectEvaComponent(this.component);
                        this.editor.focus()
                    },
                    onUpdate: ({ getJSON, getHTML, state }) => {
                        this.component.title = state.doc.textBetween(1, state.doc.content.size, ' ');
                        this.configurations.json = getJSON();
                        this.configurations.html = getHTML();
                    },
                });
            },
            removeAnswer(pos) {
                this.answers.splice(pos, 1);

                this.updateWeight();
                this.reload();
            },
            addAnswer() {
                if (_.size(this.answers) < this.answer_limit) {
                    let answer = this.helper.clone(this.default_answer);
                    this.answers.push(answer);
                    this.updateWeight();
                    if(this.component.not_evaluable) {
                        this.component.configurations.correct_answer = this.answers;
                        this.setFakeIds()
                    }
                }
            },
            markAsCorrect(event) {
                let el = event.target;
                let _arr = this.answers;
                _.each(_arr, item => {
                    item.is_correct = false;
                });
                el.checked = true;
            },
            reload() {
                this.loading = true;
                setTimeout(() => {
                    this.loading = false;
                }, 10);
            },
            isLast(index) {
                return index === _.size(this.answers) - 1
            },
            updateWeight() {
                _.each(this.answers, (answer, index) => {
                    answer.configurations.order.weight = index;
                })
            },
            setFakeIds() {
                _.each(this.answers, (answer, index) => {
                    answer.answer_id = index;
                })
            },
            async updateFile(event, answer) {
                let file = _.first(event.target.files);
                answer.image = null;
                let uploaded_image = await this.uploadImage(file, answer)
                answer.configurations.imageData = uploaded_image.data
                answer.image = uploaded_image.data.full_path_url
            },
            addImageFor(element_id) {
                let input = document.getElementById(element_id);
                input.click();
            },
            uploadImage(file, answer) {
                let formData = new FormData();
                formData.append('uuid', this._uid);
                formData.append('title', `image-${file.name}`);
                formData.append('description', '');
                formData.append('media-type', 'image');
                formData.append('file', file);
                let options = {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                    onUploadProgress: (progressEvent) => {
                        if (progressEvent.lengthComputable) {
                            console.log(progressEvent)
                            let loaded = (progressEvent.loaded / progressEvent.total) * 100;
                            answer.configurations.uploadPercent = parseInt(loaded);
                            answer.configurations.uploadfinished = parseInt(loaded) === 100;
                        }
                    }
                };
                return axios.post(this.upload_route, formData, options)
            }
        },
        data () {
            return {
                component: this.componentOptions,
                answer_limit: 12,
                editor: null,
                answer_editor: null,
                editable: true,
                loading: false,
                sortable_options: {
                    handle: '.image-answer',
                    onStart: (evt) => {
                        this.editable = false;
                    },
                    onEnd: (evt) => {
                        let from = evt.oldIndex,
                            to = evt.newIndex,
                            array = this.component.answers,
                            item = array[from];
                        if (from < to) {
                            array.splice(to + 1, 0, item);
                            array.splice(from, 1);
                        } else {
                            array.splice(to, 0, item);
                            array.splice(from + 1, 1);
                        }
                        this.editable = true;
                        this.updateWeight();
                        if(this.component.not_evaluable) {
                            this.component.configurations.correct_answer = array;
                            this.setFakeIds()
                        }
                        this.reload();
                    }
                }
            };
        }
    }
</script>

<style lang="scss" scoped>
.sort-images {
    @apply overflow-x-auto py-4;
    .answers {
        .image-answer {
            @apply mr-4;
            width: 8rem;
            &:hover {
                .remove-answer {
                    .remove-icon {
                        opacity: 1;
                    }
                }
            }
            .remove-answer {
                @apply relative w-full h-0;
                .remove-icon {
                    @apply text-white bg-red-600 cursor-pointer;
                    position: absolute !important;
                    opacity: 0;
                    padding: 2px 7px;
                    width: 25px;
                    height: 25px;
                    top: -6px;
                    right: -6px;
                    border-radius: 50%;
                    z-index: 1;
                    transition: all 0.3s ease-in-out;
                }
            }
            .indicator {
                @apply w-full h-6 mb-2 text-center;
                background-color: #F9FAFB;
                .num {
                    @apply w-auto;
                }
            }
            .answer {
                @apply w-full border rounded shadow-md cursor-pointer;
                &.loaded {
                    @apply p-0;
                    img {
                        @apply w-full h-full;
                    }
                }
                > div:not(.footer) {
                    @apply flex w-full h-32 justify-center items-center;
                    font-size: 4rem;
                }
                .footer {
                    @apply py-4;
                    .image-text {
                        @apply flex w-full px-2 justify-center items-center;
                        textarea {
                            @apply w-full text-center;
                            resize: none;
                            border: unset !important;
                        }
                    }
                }
            }
        }
    }
    .add-answer {
        @apply flex min-w-24 min-h-24 justify-center items-center p-2 border rounded cursor-pointer;
    }
}
</style>
