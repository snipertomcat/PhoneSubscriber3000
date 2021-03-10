import { tools } from './tools';
import { mobile, desktop } from './footers';

export const editor = {
    starting_guide: false,
    experience: null,
    preview: {
        views: ['desktop','mobile'],
        orientations: ['portrait','landscape'],
        selected: {
            view: 'desktop',
            orientation: 'landscape'
        }
    },
    tools_bar: {
        tabs: ['tools','config'],
        selected: {
            tab: 'tools',
            tool: null,
            section: null
        },
        tools: tools,
        config: null,
        styles: []
    },
    structure_bar: {
        cover: {
            opened: false,
            designs: ['in_store', 'content'],
            selected: 'in_store'
        },
        views: ['tree'],
        selected: {
            view: 'tree',
            challenge: null,
            sub_challenge: null
        },
        structure: {
            templates: {
                challenge: {
                    title: null,
                    image: null,
                    header: {
                        source: null,
                        name: null,
                        min_width: '900px',
                        min_height: '300px'
                    },
                    prefix: 'challenge',
                    elements: [],
                    opened_sub_challenges: false,
                    editing: false,
                    selected: false,
                    session: null,
                },
                sub_challenge: {
                    title: null,
                    image: null,
                    prefix: 'sub_challenge',
                    elements: [],
                    editing: false,
                    selected: false
                }
            },
            list: []
        },
    },
    canvas: {
        loading: false,
        content: {
            header: {
                source: null,
                name: null,
                min_width: '900px',
                min_height: '300px'
            },
            elements: [], // Reemplazable con los elementos que tendrá el reto
        },
        config: {
            alignments: ['top','center','bottom'],
            background: {
                color: null
            },
            selected: {
                alignment: 'top'
            }
        },
        footer: {
            mobile: mobile,
            desktop: desktop
        },
        sources: {
            template: {
                title: null,
                author: null,
                age: null,
                src: null
            },
            list: []
        },
        selected: {
            element: null
        }
    }
};