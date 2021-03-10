import {Node} from 'tiptap'

export default class Filling extends Node {

  get name() {
    return 'filling'
  }

  get schema() {
    return {
      attrs: {
        src: {
          default: null,
        }
      },
      inline: true,
      group: 'inline',
      selectable: false,
      parseDOM: [{
        tag: 'input',
        getAttrs: dom => ({
          //src: dom.getAttribute('src'),
        }),
      }],
      toDOM: node => ['input', {
        //src: node.attrs.src,
      }],
    }
  }

  get view() {
    return {
      props: ['node', 'updateAttrs', 'view'],
      computed: {
        src: {
          get() {
            return this.node.attrs.src
          },
          set(src) {
            this.updateAttrs({
              src,
            })
          },
        },
      },
      template: `<input type="text" @paste.stop>`,
    }
  }

  commands({type}) {
    console.log(type)
    return attrs => (state, dispatch) => {
      const {selection} = state;
      const position = selection.$cursor
        ? selection.$cursor.pos
        : selection.$to.pos;
      const node = type.create(attrs);
      const transaction = state.tr.insert(position, node);
      dispatch(transaction);
    };
  }

}
