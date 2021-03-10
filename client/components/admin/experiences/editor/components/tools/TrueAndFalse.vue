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
            <editor-content class="editor__content border border-gray-300" :editor="editor"></editor-content>
        </div>
        <div class="">
            <div class="answers">
                <template v-for="answer in answers">
                    <div class="flex w-full">
                        <div class="w-2/12">
                            <input class="correct-validator" :type="answer.type" v-model="answer.is_correct" @input="markAsCorrect">
                        </div>
                        <div class="w-8/12">
                            <input type="text" v-model="answer.title">
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
    import { Editor, EditorContent, EditorMenuBar } from 'tiptap';
    import { Bold, Italic, Strike, Underline, History, Focus } from 'tiptap-extensions';
    import Iframe from './iFrame.js';
    import bus from "../../../../../../helpers/bus";

    export default {
        name: "TrueAndFalse",
        inject: ['selectEvaComponent'],
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
            configurations() { return this.component.configurations }
        },
        created () {
            bus.$on('disable_editor', evt => {
                this.editor.setOptions({
                    editable: false,
                })
                /*
                _.each(this.answers, answer => {
                    answer.configurations.editor.setOptions({
                        editable: false
                    })
                })
                */
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
                        new Bold(),
                        new Italic(),
                        new Strike(),
                        new Underline(),
                        new History,
                        new Focus({ className: 'editor-focused', nested: false }),
                        new Iframe(),
                    ],
                    onFocus: (e) => {
                        this.editor.setOptions({
                            editable: true,
                        })
                        if(!this.component.selected)
                            this.selectEvaComponent(this.component);
                        this.editor.focus();
                    },
                    onUpdate: ({ getJSON, getHTML, state }) => {
                        this.component.title = state.doc.textBetween(1, state.doc.content.size, ' ');

                        this.configurations.json = getJSON();
                        this.configurations.html = getHTML();
                    }
                });
            },
            markAsCorrect(event) {
                let el = event.target;
                let _arr = this.answers;
                _.each(_arr, item => {
                    item.is_correct = false;
                });
                el.checked = true;
            }
        },
        data () {
            return {
                component: this.componentOptions,
                editor: null,
                editable: true
            };
        }
    }
</script>

<style scoped lang="scss">
    $color-black: #000000;
    $color-white: #ffffff;
    $color-grey: #dddddd;

    .iframe {
        &__embed {
            width: 100%;
            height: 15rem;
            border: 0;
        }
        &__input {
            display: block;
            width: 100%;
            font: inherit;
            border: 0;
            border-radius: 5px;
            background-color: rgba($color-black, 0.1);
            padding: 0.3rem 0.5rem;
        }
    }
</style>
