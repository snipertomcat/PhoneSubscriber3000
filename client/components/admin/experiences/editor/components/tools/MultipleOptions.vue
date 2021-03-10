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
                            @click="commands.undo">
                        <i class="fas fa-undo"></i>
                    </button>
                    <button class="menu__button"
                            @click="commands.redo">
                        <i class="fas fa-redo"></i>
                    </button>
                    <button class="menu__button"
                            @click="commands.embed({ src: '' })">
                        iFrame
                    </button>
                </div>
            </editor-menu-bar>
            <editor-content class="editor__content" :editor="editor"></editor-content>
        </div>
        <div class="">
            <div class="answers" v-sortable="sortable_options" v-if="!loading">
                <template v-for="(answer, index) in answers">
                    <div class="flex w-full mt-3 mb-3">
                        <div class="w-1/12 text-center">
                            <div class="answer-handle">
                                <i class="fas fa-grip-vertical cursor-move"></i>
                            </div>
                        </div>
                        <div class="w-1/12 text-center">
                            <input class="correct-validator" type="checkbox" v-model="answer.is_correct">
                        </div>
                        <div class="w-7/12">
                            <editor-menu-bar :editor="answer.configurations.editor"
                                             v-slot="{commands, isActive, focused}">
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
                                </div>
                            </editor-menu-bar>
                            <editor-content class="editor__content" :editor="answer.configurations.editor"></editor-content>
                        </div>
                        <div class="w-2/12 text-center">
                            <div class="flex">
                                <div class="w-1/2 text-center">
                                    <div class="remove cursor-pointer" @click="removeAnswer(index)">
                                        <i class="fas fa-trash"></i>
                                    </div>
                                </div>
                                <div class="w-1/2 text-center" v-if="isLast(index)">
                                    <div class="add cursor-pointer" @click="addAnswer">
                                        <i class="fas fa-plus"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
    import {Editor, EditorContent, EditorMenuBar} from 'tiptap';
    import {Bold, Italic, Strike, Underline, History, Focus} from 'tiptap-extensions';
    import Iframe from "./iFrame";
    import bus from "../../../../../../helpers/bus";

    let _this;
    export default {
        name: "MultipleOptions",
        inject: ['helper', 'selectEvaComponent'],
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
            answers() {
                return this.component.answers
            },
            configurations() {
                return this.component.configurations
            },
            default_answer() {
                return this.component.configurations.default_answer
            },
            input() {
                return this.title
            },
        },
        created () {
            bus.$on('disable_editor', evt => {
                this.editor.setOptions({
                    editable: false,
                })
            })
        },
        mounted() {
            _this = this;
            if (_.size(this.answers) > 0) {
                this.initAnswers()
            } else {
                this.addAnswer();
            }
            this.initEditor();
        },
        methods: {
            initEditor() {
                this.editor = null;
                if (
                    _.isEmpty(this.editor) ||
                    _.isNull(this.editor) ||
                    this.editor === 'undefined'
                ) {
                    this.editor = new Editor({
                        editable: false,
                        content: this.configurations.html,
                        extensions: [
                            new Bold(),
                            new Italic(),
                            new Strike(),
                            new Underline(),
                            new History,
                            new Focus({className: 'editor-focused', nested: false}),
                            new Iframe(),
                        ],
                        onFocus: (e) => {
                            this.editor.setOptions({
                                editable: true,
                            })
                            if (!this.component.selected)
                                this.selectEvaComponent(this.component);
                            this.editor.focus();
                        },
                        onUpdate: ({getJSON, getHTML, state}) => {
                            this.component.title = state.doc.textBetween(1, state.doc.content.size, ' ');

                            this.configurations.json = getJSON();
                            this.configurations.html = getHTML();
                        }
                    });
                }
            },
            initAnswers() {
                _.each(this.answers, answer => {
                    if (
                        _.isEmpty(answer.configurations.editor) ||
                        _.isNull(answer.configurations.editor) ||
                        answer.configurations.editor === 'undefined'
                    ) {
                        answer.configurations.editor = new Editor({
                            editable: false,
                            content: answer.configurations.html,
                            extensions: [
                                new Bold(),
                                new Italic(),
                                new Strike(),
                                new Underline()
                            ],
                            onFocus: (e) => {
                                answer.configurations.editor.setOptions({
                                    editable: true,
                                })
                            },
                            onBlur: (e) => {
                                answer.configurations.editor.setOptions({
                                    editable: false,
                                })
                            },
                            onUpdate: ({getJSON, getHTML}) => {
                                answer.configurations.json = getJSON();
                                answer.configurations.html = answer.title = getHTML();
                            },
                        })
                    }
                })
            },
            removeAnswer(pos) {
                this.answers.splice(pos, 1);
                if (_.size(this.answers) < 1)
                    this.addAnswer();
                this.reload();
            },
            addAnswer() {
                let answer = this.helper.clone(this.default_answer);
                answer.configurations.editor = new Editor({
                    editable: true,
                    extensions: [
                        new Bold(),
                        new Italic(),
                        new Strike(),
                        new Underline()
                    ],
                    onFocus: (e) => {
                        answer.configurations.editor.setOptions({
                            editable: true,
                        })
                        if (!this.component.selected)
                            this.selectEvaComponent(this.component);
                    },
                    onUpdate: ({getJSON, getHTML}) => {
                        answer.configurations.json = getJSON();
                        answer.configurations.html = answer.title = getHTML();
                    },
                });
                this.answers.push(answer);
                    answer.configurations.editor.focus();
            },
            reload() {
                this.loading = true;
                setTimeout(() => {
                    this.loading = false;
                    //this.unlock();
                }, 10);
            },
            isLast(index) {
                return index === _.size(this.answers) - 1
            },
        },
        data() {
            return {
                component: this.componentOptions,
                editor: null,
                editable: true,
                loading: false,
                sortable_options: {
                    handle: '.answer-handle',
                    onStart: function (evt) {
                        _this.editable = false;
                        //this.lock();
                    },
                    onEnd: function (evt) {
                        let from = evt.oldIndex,
                            to = evt.newIndex,
                            array = _this.component.answers,
                            item = array[from];
                        if (from < to) {
                            array.splice(to + 1, 0, item);
                            array.splice(from, 1);
                        } else {
                            array.splice(to, 0, item);
                            array.splice(from + 1, 1);
                        }
                        _this.editable = true;
                        _this.reload();
                    }
                }
            };
        }
    }
</script>

<style scoped>

</style>
