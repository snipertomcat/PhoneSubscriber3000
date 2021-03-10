<template>
    <div class="" >
        <editor-content class="editor__content text-center h-full" :editor="editor"></editor-content>
    </div>
</template>

<script>
    import { Editor, EditorContent, EditorMenuBar } from 'tiptap';
    import { Heading } from 'tiptap-extensions';

    export default {
        name: "Title",
        props: {
            componentOptions: {
                required: true,
                type: Object
            }
        },
        components: { EditorContent },
        watch: {
            'component.editable': value => {
                console.log(value)
            }
        },
        mounted () {
            this.initEditor();
        },
        methods: {
            initEditor () {
                this.editor = null;
                this.editor = new Editor({
                    editable: false,
                    content: _.isEmpty(this.component.config.html)
                        ? `<h1></h1>`
                        : this.component.config.html,
                    extensions: [ new Heading({ levels: [1] }) ],
                    onFocus: () => {
                        this.component.selected = true;
                        this.$parent.selectComponent();
                    },
                    onUpdate: ({getJSON, getHTML}) => {
                        this.component.config.json = getJSON();
                        this.component.config.html = getHTML();
                    },
                })
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

<style scoped>

</style>
