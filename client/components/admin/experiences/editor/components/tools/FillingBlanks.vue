<template>
    <div class="" @click="selectEvaComponent(component)">
        <div class="">
            <div class="w-full">
                <editor-menu-bar :editor="editor.main" v-slot="{commands,isActive,focused}">
                    <div class="menubar" :class="{'hidden':!focused}">
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
                            <i class="fas fa-quotes-right"></i>
                        </button>
                        <button class="menu__button"
                                :class="{'is-active': isActive.code_block()}"
                                @click="commands.code_block">
                            < >
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
                        <button class="menu__button"
                                @click="commands.filling">
                            <!--<i class="fas fa-redo"></i>-->
                            filling
                        </button>
                    </div>
                </editor-menu-bar>
                <editor-content :editor="editor.main" class="editor__content border border-gray-300"></editor-content>
            </div>
        </div>
        <div class="">
            <div class="flex mt-3 mb-3">
                <div class="w-10/12">
                    <input class="w-full" type="text" v-model="configurations.input_sentence_value" :placeholder="$t(`messages.editor.${configurations.placeholder}`)">
                </div>
                <div class="w-2/12 text-center">
                    <span class="border rounded px-2 py-1 border-gray-300" @click="addWrongAnswer"><i class="fas fa-plus"></i></span>
                </div>
            </div>
        </div>
        <div class="px-3 mt-3 mb-3">
            <div class="flex">
                <template v-for="(answer, index) in answers">
                    <div class="flex">
                        <div class="rounded-l py-1 px-2"
                             :class="{
                             'bg-yellow-400':answer.is_correct,
                             'bg-red-400 text-white':!answer.is_correct
                             }">
                            <span v-html="answer.configurations.html">{{answer.configurations.html}}</span>
                        </div>
                        <div class="rounded-r py-1 px-2 cursor-pointer"
                             :class="{
                             'bg-yellow-500':answer.is_correct,
                             'bg-red-500 text-white':!answer.is_correct
                             }"
                             @click="removeAnswer(index)">
                            <span><i class="fas fa-times"></i></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
    import {Editor, EditorContent, EditorMenuBar} from 'tiptap';
    import {
        Blockquote,
        Bold,
        BulletList,
        Code,
        CodeBlock,
        Focus,
        HardBreak,
        Heading,
        History,
        HorizontalRule,
        Italic,
        Link,
        ListItem,
        OrderedList,
        Strike,
        TodoItem,
        TodoList,
        Underline
    } from 'tiptap-extensions';
    import Filling from "./fillingBlanks";

    export default {
        name: "FillingBlanks",
        inject: ['helper','selectEvaComponent'],
        props: {
            componentOptions: {
                required: true,
                type: Object
            }
        },
        components: { EditorContent, EditorMenuBar },
        computed: {
            answers() { return this.component.answers },
            configurations() { return this.component.configurations },
            default_answer() { return this.component.configurations.default_answers },
        },
        mounted() {
            this.initEditor();
        },
        methods: {
            initEditor() {
                this.editor.main = null;
                this.editor.main = new Editor({
                    editable: false,
                    content: this.configurations.html,
                    extensions: [
                        new Blockquote(),
                        new BulletList(),
                        new CodeBlock(),
                        new HardBreak(),
                        new Heading({ levels: [1, 2, 3] }),
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
                        }),
                        new Filling(),
                    ],
                    onFocus: (e) => {
                        if(!this.component.selected) {
                            this.selectEvaComponent(this.component);
                            this.editor.main.setOptions({
                                editable: true,
                            })
                        }
                    },
                    onUpdate: ({ getJSON,getHTML, state }) => {
                        this.component.title = state.doc.textBetween(1, state.doc.content.size, ' ');
                        this.component.configurations.json = getJSON();
                        this.component.configurations.html = getHTML();
                    }
                });
                /* Disabled filling the blanks
                if (!_.isNull(this.editor.main)) this.initSelectListener();
                else setTimeout(() => { this.initSelectListener() }, 50)
                 */
            },
            initSelectListener() {
                let el = this.$el.querySelector('.editor__content');
                el.addEventListener('mouseup', e => {
                    let selection = this.getSelectedText();
                    this.editor.has_text_selected = !_.isEmpty(selection);
                    this.markText(selection);
                    this.addAnswer(selection.text)
                })
            },
            getSelectedText() {
                let { selection, state } = this.editor.main;
                let { from, to } = selection;
                let text = state.doc.textBetween(from, to, ' ');
                let slice = state.doc.slice(from, to);
                slice.content.firstChild.set = '_#'+slice.content.firstChild.textContent
                console.log(slice.content.firstChild)
                if(_.isEmpty(text)) return {};
                return { from: from, to: to, text: text};
            },
            markText(s_object) {
                if (this.editor.has_text_selected) {
                    let { from, to, text } = s_object;
                    let new_text = `_#${text}#_`;

                    console.log(this.configurations.json)

                } else {

                }
            },
            addAnswer(text) {
                let answer = this.helper.clone(this.default_answer);
                answer.configurations.filling.text = text;
                this.answers.push(answer);
            },
            addWrongAnswer() {
                let answer = this.helper.clone(this.default_answer);
                answer.configurations.filling.text = this.configurations.input_sentence_value;
                answer.is_correct = false;
                answer.configurations.html = `<p>${this.configurations.input_sentence_value}</p>`;
                this.answers.push(answer);
                this.configurations.input_sentence_value = null;
            },
            removeAnswer(index) {
                this.answers.splice(index, 1);
            }
        },
        data() {
            return {
                component: this.componentOptions,
                loading: false,
                editor: {
                    has_text_selected: false,
                    main: null,
                }
            };
        }
    }
</script>

<style scoped>

</style>
