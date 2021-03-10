import {Node} from 'tiptap'

export default class Video extends Node {

  get name() {
    return 'video'
  }

  get schema() {
    return {
      group: 'block',
      selectable: false,
      attrs: {
        src: { default: null },
        poster: { default: null },
        autoplay: { default:false },
        controls: { default:true },
        muted: { default:false },
        preload: { default:false },
      },
      parseDOM: [{
        tag: 'video',
        getAttrs: dom => ({
          src: dom.getAttribute('src'),
          autoplay: dom.getAttribute('autoplay'),
          controls: dom.getAttribute('controls'),
          muted: dom.getAttribute('muted'),
          poster: dom.getAttribute('poster'),
          preload: dom.getAttribute('preload')
        }),
      }],
      toDOM: node => ['iframe', {
        preload: node.attrs.preload,
        autoplay: node.attrs.autoplay,
        controls: node.attrs.controls,
        muted: node.attrs.muted,
        src: node.attrs.src,
        poster: node.attrs.poster,
      }],
    }
  }

  get view() {
    return {
      props: ['node', 'updateAttrs', 'view'],
      computed: {
        src: {
          get() { return this.node.attrs.src },
          set(src) { this.updateAttrs({ src })},
        },
        poster: {
          get() { return this.node.attrs.poster },
          set(poster) { this.updateAttrs({ poster })}
        },
        muted: {
          get() { return this.node.attrs.muted },
          set(muted) { this.updateAttrs({ muted })}
        },
        controls: {
          get() { return this.node.attrs.controls },
          set(controls) { this.updateAttrs({ controls })}
        },
        autoplay: {
          get() { return this.node.attrs.autoplay },
          set(autoplay) { this.updateAttrs({ autoplay })}
        },
        preload: {
          get() { return this.node.attrs.preload },
          set(preload) { this.updateAttrs({ preload })}
        }
      },
      template: `
        <div class="video">
          <div class="video-player" oncontextmenu="return false;">
            <video 
                :src="src" 
                :poster="poster" 
                :autoplay="autoplay" 
                :preload="preload" 
                :controls="controls" 
                :muted="muted" 
                disablePictureInPicture 
                controlsList="nodownload nofullscreen"
                onresize="return false;"
            >
            </video>
          </div>
          <template v-if="view.editable">
            <div class="video-inputs">
              <span>Video URL: </span><input type="text" v-model="src" @paste.stop>
              <!--<span>Poster URL: </span><input type="text" v-model="poster" @paste.stop>-->
            </div>
          </template>
        </div>
      `,
    }
  }

  commands({type}) {
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
