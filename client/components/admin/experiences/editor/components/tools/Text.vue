<template>
  <div>
    <editor-menu-bar :editor="editor" v-slot="{ commands, isActive, focused }">
      <div class="menubar hidden" :class="{ 'not-hidden': focused }">
        <button
          class="menubar__button"
          :class="{ 'is-active': isActive.bold() }"
          @click="commands.bold"
        >
          <span class="fas fa-bold"></span>
        </button>

        <button
          class="menubar__button"
          :class="{ 'is-active': isActive.italic() }"
          @click="commands.italic"
        >
          <span class="fas fa-italic"></span>
        </button>

        <button
          class="menubar__button"
          :class="{ 'is-active': isActive.strike() }"
          @click="commands.strike"
        >
          <span class="fas fa-strikethrough"></span>
        </button>

        <button
          class="menubar__button"
          :class="{ 'is-active': isActive.underline() }"
          @click="commands.underline"
        >
          <span class="fas fa-underline"></span>
        </button>

        <button
          class="menubar__button"
          :class="{ 'is-active': isActive.heading({ level: 2 }) }"
          @click="commands.heading({ level: 2 })"
        >
          <span>H2</span>
        </button>

        <button
          class="menubar__button"
          :class="{ 'is-active': isActive.heading({ level: 3 }) }"
          @click="commands.heading({ level: 3 })"
        >
          <span>H3</span>
        </button>

        <button
          class="menubar__button"
          :class="{ 'is-active': isActive.ordered_list() }"
          @click="commands.ordered_list"
        >
          <span class="fas fa-list-ol"></span>
        </button>

        <button
          class="menubar__button"
          :class="{ 'is-active': isActive.bullet_list() }"
          @click="commands.bullet_list"
        >
          <span class="fas fa-list-ul"></span>
        </button>

        <button
          class="menubar__button"
          :class="{ 'is-active': isActive.blockquote() }"
          @click="commands.blockquote"
        >
          <span class="fas fa-quote-right"></span>
        </button>

        <button
          class="menubar__button"
          :class="{ 'is-active': isActive.code_block() }"
          @click="commands.code_block"
        >
          <span class="fas fa-code"></span>
        </button>

        <button class="menubar__button" @click="commands.horizontal_rule">
          <!-- <span class="icon-minus"></span> -->
          HR
        </button>

        <button class="menu__button"
                @click="commands.embed({ src: '' })">
          iFrame
        </button>

        <button class="menu__button"
                @click="commands.video({ src: '', poster: '' })">
          <span class="fas fa-video"></span>
        </button>

        <button class="menubar__button" @click="commands.undo">
          <span class="fas fa-undo"></span>
        </button>

        <button class="menubar__button" @click="commands.redo">
          <span class="fas fa-redo"></span>
        </button>
      </div>
    </editor-menu-bar>

    <editor-content class="editor__content" :editor="editor" />

    <!-- <button class="btn" @click="exportJson">Exportar JSON</button> -->
  </div>
</template>

<script>
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
import Iframe from './iFrame.js';
import Video from './video.js';

export default {
  name: "Text",
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
  mounted () {
    this.initEditor();
  },
  methods: {
    initEditor () {
      this.editor = null;
      this.editor = new Editor({
        editable: false,
        content: this.component.config.html,
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
          new Iframe(),
          new Video()
        ],
        onUpdate: ({ getJSON, getHTML }) => {
          this.component.config.json = getJSON();
          this.component.config.html = getHTML();
        },
        onFocus: () => {
          this.component.selected = true
        }
      })
    }
  },
  data() {
    return {
      component: this.componentOptions,
      editor: null,
      editable: true
    };
  }
};
</script>

<style scoped>
.menubar {
  @apply bg-gray-800 rounded-sm;
  color: white;
}

.menubar__button{
  @apply py-1 px-2;
}

.menubar__button:hover{
  @apply bg-black;
}

.ProseMirror-focused {
  @apply border-2 border-solid border-blue-400;
}

.not-hidden {
  @apply block flex;
}
</style>
