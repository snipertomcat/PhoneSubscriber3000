import {Node} from 'tiptap'

export default class Iframe extends Node {

    get name() {
        return 'embed'
    }

    get schema() {
        return {
            attrs: {
                src: {
                    default: null,
                },
            },
            group: 'block',
            selectable: false,
            parseDOM: [{
                tag: 'iframe',
                getAttrs: dom => ({
                    src: dom.getAttribute('src'),
                }),
            }],
            toDOM: node => ['iframe', {
                src: node.attrs.src,
                frameborder: 0,
                allowfullscreen: 'true',
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
                source() {
                    let url = this.node.attrs.src
                    let isYoutube = new RegExp('(?:.+?)?(?:\\/v\\/|watch\\/|\\?v=|\\&v=|youtu\\.be\\/|\\/v=|^youtu\\.be\\/|watch\\%3Fv\\%3D)','gm');
                    let result = isYoutube.test(url);
                    if(result) {
                        let aux = _.split(url, new RegExp('(?:.+?)?(?:\\/v\\/|watch\\/|\\?v=|\\&v=|youtu\\.be\\/|\\/v=|^youtu\\.be\\/|watch\\%3Fv\\%3D)','gm'))
                        let id_video = _.last(aux).substring(0, 11)
                        url = `https://www.youtube.com/embed/${id_video}`;
                    }
                    if (!_.isEmpty(url))
                        this.node.attrs.src = url;
                    return url;
                }
            },
            template: `
        <div class="iframe">
          <iframe class="iframe__embed" :src="source"></iframe>
          <span>Embed link: </span><input class="iframe__input" @paste.stop type="text" v-model="src" v-if="view.editable" />
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
