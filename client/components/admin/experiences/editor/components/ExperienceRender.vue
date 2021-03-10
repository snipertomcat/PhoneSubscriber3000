<template>
    <div class="render-container">
        <template v-if="!loading">
            <div class="canvas">
                <div class="header">
                    <template v-if="has_header">
                        <img :src="session_selected.header.source" alt="">
                    </template>
                    <template v-else>
                        <img :src="default_header" alt="">
                    </template>
                </div>
                <div class="elements">
                    <template v-if="has_elements" v-for="(element,index) in elements">
                        <div class="element">
                            <template v-if="element.component === 'Title'">
                                <r-title :component-data="element"></r-title>
                            </template>
                            <template v-if="element.component === 'Image'">
                                <r-image :component-data="element"></r-image>
                            </template>
                            <template v-if="element.component === 'Text'">
                                <r-text :component-data="element"></r-text>
                            </template>
                            <template v-if="element.component === 'Video'">
                                <r-video :component-data="element"></r-video>
                            </template>
                            <template v-if="element.component === 'Downloadable'">
                                <r-downloadable :component-data="element"></r-downloadable>
                            </template>
                            <template v-if="element.component === 'Evaluation'">
                                <r-evaluation :component-data="element"></r-evaluation>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    import Title from './preview/Title';
    import Image from './preview/Image';
    import Text from './preview/Text';
    import Video from './preview/Video';
    import Downloadable from './preview/Downloadable';
    import Evaluation from '../../../../platform/evaluations/Evaluation';

    export default {
        name: "ExperienceRender",
        props: {
            experienceId: {
                default: 0
            },
            sessionId: {
                default: null
            },
            experienceData: {
                required: true,
                type: Object
            },
            sessionData: {
                required: true,
                type: Object
            }
        },
        components: {
            'r-evaluation' : Evaluation,
            'r-title': Title,
            'r-image': Image,
            'r-text': Text,
            'r-video': Video,
            'r-downloadable': Downloadable
        },
        watch: {
            sessionId (value) {
                this.readSession();
            }
        },
        computed: {
            auth_user () { return this.$root.$refs.adminNav.user },
            enrollment_progress () { return this.selected.session.current_enrollment_progress },
            session_selected () { return this.selected.session },
            elements () { return this.selected.session.elements },
            has_elements () { return !_.isEmpty(this.elements) },
            has_header () {
                let has_valid_image = false;
                let header = this.session_selected.header;

                if(!_.isNull(header) && !_.isEmpty(header))
                    if(!_.isNull(header.source) && !_.isEmpty(header.source))
                        has_valid_image = true;

                return has_valid_image;
            },
            default_header () { return this.sessionData.full_path_cover },
        },
        beforeMount() {
            this.request(this.experienceId)
        },
        methods: {
            request (experience_id) {
                //this.experience = json_test;
                this.readSession();
            },
            read () {
                if (_.isNull(this.experience) || _.isEmpty(this.experience.content)) { return 0; }
                let session_id = (!_.isNull(this.sessionId)) ? this.sessionId : this.experience.content[1].session_id;
                this.selectSession(session_id);
            },
            readSession() {
                if(_.isEmpty(this.sessionData)) {
                    console.error('not found session data');
                    return 0;
                }
                if(typeof this.sessionData.json_data === 'string')
                    this.selected.session = JSON.parse(this.sessionData.json_data);
                else
                    this.selected.session = this.sessionData.json_data;
                this.reload();
            },
            selectSession (session_id) {
                let session = _.find(this.experience.content, {session_id: session_id});
                if (_.isEmpty(session) || _.isNull(session)) return 0;
                session.selected = true;
                this.selected.session = session;
            },
            reload() {
                this.loading = true;
                setTimeout(() => { this.loading = false }, 100);
            }
        },
        data () {
            return {
                experience: this.experienceData,
                loading: false,
                selected: {
                    session: null
                }
            }
        }
    }

    const json_test = {
        "experience": {
            "cover": "https://....",
            "title": "Experiencia chingona",
            "etc": "..."
        },
        "content": [
            {
                "title":"Reto 1",
                "image":null,
                "header":{
                    "source":"https://s3.us-west-2.amazonaws.com/dev.cdn.apithy.com/experience_media_content/-1580517046.png",
                    "name":"-1580517046.png"
                },
                "prefix":"challenge",
                "elements":[
                    {
                        "title":"title",
                        "type":"Title",
                        "icon":null,
                        "selected":false,
                        "config":{
                            "content":"",
                            "json":{
                                "type":"doc",
                                "content":[
                                    {
                                        "type":"heading",
                                        "attrs":{
                                            "level":1
                                        },
                                        "content":[
                                            {
                                                "type":"text",
                                                "text":"title 1"
                                            }
                                        ]
                                    }
                                ]
                            },
                            "html":"<h1>title 1</h1>"
                        },
                        "uid":17
                    },
                    {
                        "title":"text",
                        "type":"Text",
                        "icon":null,
                        "selected":false,
                        "config":{
                            "content":"",
                            "json":{
                                "type":"doc",
                                "content":[
                                    {
                                        "type":"paragraph",
                                        "content":[
                                            {
                                                "type":"text",
                                                "text":"Lorem ipsum dolor sit amet consectetur adipiscing elit, dui sed massa tortor ullamcorper fames, laoreet maecenas commodo senectus mollis donec. Quis vestibulum dictumst mus commodo fermentum vitae mauris nostra tempor, dis nec condimentum phasellus montes lacus tincidunt primis, justo orci magna molestie vivamus bibendum enim nisl. Fermentum sapien nec in praesent ligula luctus aenean nam non, aliquam donec consequat cum duis felis neque feugiat placerat maecenas, taciti odio class euismod tortor lacus erat enim."
                                            }
                                        ]
                                    }
                                ]
                            },
                            "html":"<p>Lorem ipsum dolor sit amet consectetur adipiscing elit, dui sed massa tortor ullamcorper fames, laoreet maecenas commodo senectus mollis donec. Quis vestibulum dictumst mus commodo fermentum vitae mauris nostra tempor, dis nec condimentum phasellus montes lacus tincidunt primis, justo orci magna molestie vivamus bibendum enim nisl. Fermentum sapien nec in praesent ligula luctus aenean nam non, aliquam donec consequat cum duis felis neque feugiat placerat maecenas, taciti odio class euismod tortor lacus erat enim.</p>"
                        },
                        "uid":75
                    },
                    {
                        "title":"image",
                        "type":"Image",
                        "icon":null,
                        "selected":true,
                        "config":{
                            "source":"https://s3.us-west-2.amazonaws.com/dev.cdn.apithy.com/experience_media_content/17-1580452491.png",
                            "width":0,
                            "height":0,
                            "name":"17-1580452491.png"
                        },
                        "uid":79
                    },
                    {
                        "title": 'video',
                        "type":'Video',
                        "icon":null,
                        "selected":false,
                        "config":{
                            "source":'',
                            "cover":'https://s3.us-west-2.amazonaws.com/cdn.apithy.com/web/player_cover_template.jpg'
                        },
                        "uid":81
                    },
                ],
                "opened_sub_challenges":false,
                "editing":false,
                "selected":true,
                "session":null,
                "id":"ch-0",
                "session_id":339
            },
            {
                "title":"Reto 2",
                "image":null,
                "header":{
                    "source":"https://s3-us-west-2.amazonaws.com/dev.cdn.apithy.com/experience_media_content/16-1580327488.jpeg",
                    "name":"16-1580327488.jpeg"
                },
                "prefix":"challenge",
                "elements":[
                    {
                        "title":"title",
                        "type":"Title",
                        "icon":null,
                        "selected":false,
                        "config":{
                            "content":"",
                            "json":{
                                "type":"doc",
                                "content":[
                                    {
                                        "type":"heading",
                                        "attrs":{
                                            "level":1
                                        },
                                        "content":[
                                            {
                                                "type":"text",
                                                "text":"Titulo 2"
                                            }
                                        ]
                                    }
                                ]
                            },
                            "html":"<h1>Titulo 2</h1>"
                        },
                        "uid":25
                    },
                    {
                        "title":"image",
                        "type":"Image",
                        "icon":null,
                        "selected":false,
                        "config":{
                            "source":'https://s3-us-west-2.amazonaws.com/dev.cdn.apithy.com/experience_media_content/25-1580514800.png',
                            "width":0,
                            "height":0,
                            "name":'25-1580514800.png'
                        },
                        "uid":27
                    },
                ],
                "opened_sub_challenges":false,
                "editing":false,
                "selected":false,
                "session":null,
                "id":"ch-1",
                "session_id":340
            }
        ]
    };
</script>

<style scoped>
    .render-container {
        min-height: 100vh;
    }
    .header {
        position: relative;
        margin-bottom: 1rem;
        max-height: 30rem;
        min-height: 12rem;
        overflow: hidden;
    }
    .header img {
        width: 100%;
    }
    .elements {
        margin-left: auto;
        margin-right: auto;
        max-width: 720px;
    }
    @media only screen and (max-device-width: 720px) and (max-width: 720px) {
        .elements {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
    }
    .elements .element {
        margin-bottom: 1rem;
    }
    .element .r-image {}
</style>
<style>
    .element blockquote,
    .element figure {
        margin: unset;
    }
    .element blockquote {
        background-color: whitesmoke;
        border-left: 5px solid #dbdbdb;
        padding: 1.25em 1.5em;
    }
    .element ul {
        list-style: disc outside;
        margin-left: 2em;
        margin-top: 1em;
    }
    .element ol:not([type]) {
        list-style-type: decimal;
    }
    .element ol {
        list-style-position: outside;
        margin-left: 2em;
        margin-top: 1em;
    }
    .element strong {
        font-weight: 700;
    }
    .element h1 {
        font-size: 2.5rem !important;
        font-weight: 600;
        color: #aa2721;
    }
    .element h2 {
        font-size: 2rem;
        margin-top: 10px;
        margin-bottom: 30px;
        font-weight: 600;
    }
</style>
