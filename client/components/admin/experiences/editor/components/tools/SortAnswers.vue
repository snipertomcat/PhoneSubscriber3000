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
            <div class="flex items-end">
                <div class="w-5/6">
                    <editor-menu-bar :editor="answer_editor" v-slot="{commands, isActive, focused}">
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
                    <editor-content :editor="answer_editor"></editor-content>
                </div>
                <div class="w-1/6">
                    <div class="w-1/2 text-center">
                        <div class="add cursor-pointer" @click="addAnswer">
                            <i class="fas fa-plus"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-full">
            <div class="answers" v-sortable="sortable_options" v-if="!loading">
                <template v-for="(answer, index) in answers">
                    <div class="flex items-center w-full mt-3 mb-3" :key="index">
                        <div class="w-1/12 text-center">
                            <div class="answer-handle cursor-move">
                                <i class="fas fa-grip-vertical"></i>
                            </div>
                        </div>
                        <div class="w-1/12 text-center">
                            <span class="text-2xl">{{ answer.configurations.order.weight + 1 }}</span>
                        </div>
                        <div class="w-8/12">
                            <editor-menu-bar :editor="answer.configurations.editor" v-slot="{commands, isActive, focused}">
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
                            <editor-content :editor="answer.configurations.editor"></editor-content>
                            <input class="w-full" type="text" v-model="answer.title" v-if="false">
                        </div>
                        <div class="w-2/12">
                            <div class="flex">
                                <div class="w-1/2 text-center">
                                    <div class="remove cursor-pointer" @click="removeAnswer(index)">
                                        <i class="fas fa-trash"></i>
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
        name: "SortAnswers",
        inject: ['helper','selectEvaComponent'],
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
        },
        created () {
            bus.$on('disable_editor', evt => {
                this.editor.setOptions({
                    editable: false,
                })
                this.answer_editor.setOptions({
                    editable: false
                })
                _.each(this.answers, answer => {
                    answer.configurations.editor.setOptions({
                        editable: false
                    })
                })
            })
        },
        mounted () {
            this.initEditor();
        },
        methods: {
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
                this.answer_editor = new Editor({
                    editable: false,
                    content: '',
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
                        this.answer_editor.setOptions({
                            editable: true,
                        })
                        if(!this.component.selected)
                            this.selectEvaComponent(this.component);
                        this.answer_editor.focus()
                    },
                    onUpdate: ({ getJSON, getHTML, state }) => {
                        this.default_answer.configurations.json = getJSON();
                        this.default_answer.configurations.html = getHTML();
                    },
                });
                _.each(this.component.answers, answer => {
                    if (_.isNull(answer.configurations.editor)) {
                        answer.configurations.editor = new Editor({
                            editable: true,
                            content: answer.configurations.html,
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
                                answer.configurations.editor.setOptions({
                                    editable: true,
                                })
                                if(!this.component.selected)
                                    this.selectEvaComponent(this.component);
                                //answer.configurations.editor.focus()
                            },
                            onUpdate: ({getJSON,getHTML}) => {
                                _.set(answer, ['configurations','json', getJSON()]);
                                answer.configurations.json = getJSON();
                                answer.configurations.html = answer.title = getHTML();
                            }
                        });
                    }
                })
            },
            removeAnswer(pos) {
                this.answers.splice(pos, 1);

                this.updateWeight();
                this.reload();
            },
            addAnswer() {
                let answer = this.helper.clone(this.default_answer);
                answer.configurations.editor = new Editor({
                    editable: true,
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
                        answer.configurations.editor.setOptions({
                            editable: true,
                        })
                        if(!this.component.selected)
                            this.selectEvaComponent(this.component);
                        answer.configurations.editor.focus()
                    },
                    onUpdate: ({getJSON,getHTML}) => {
                        _.set(answer, ['configurations','json', getJSON()]);
                        answer.configurations.json = getJSON();
                        answer.configurations.html = getHTML();
                        answer.title = getHTML();
                    }
                });
                answer.configurations.editor.setContent(this.answer_editor.getJSON(), true);
                this.answers.push(answer);
                this.answer_editor.clearContent();
                this.updateWeight();
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
            }
        },
        data () {
            return {
                component: this.componentOptions,
                editor: null,
                answer_editor: null,
                editable: true,
                loading: false,
                sortable_options: {
                    handle: '.answer-handle',
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
                        this.reload();
                    }
                }
            };
        }
    }
</script>

<style scoped>

</style>
