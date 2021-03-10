<template>
    <div id="experience-viewer" style="position: relative">
        <apithy-experience-rating v-if="rateExperience"
                                  :experience="experience"
                                  :auth_user="auth_user">
        </apithy-experience-rating>
        <div v-if="experience.type==apithy_constants.EXPERIENCE_TYPE_ADVENTURE">
            <apithy-experience-sessions-list
                    :experience="experience"
                    :title="experience.title"
                    :experience-type="experience.type"
                    :sessions_list="sessions"
                    :adventures_list="adventures">
            </apithy-experience-sessions-list>
        </div>
        <div v-else-if="experience.type==apithy_constants.EXPERIENCE_TYPE_JOURNEY">
            <apithy-experience-adventure-list
                    :experience="experience"
                    :title="experience.title"
                    :experience-type="experience.type"
                    :sessions_list="sessions"
                    :adventures_list="adventures">
            </apithy-experience-adventure-list>
        </div>
        <iframe class="iframe-experience-item"
                :src="session_data.resource_url"
                width="90%"
                ref="frame"
                scrolling="no"
                @load="loadIframe"
                v-bind:height="iframe_height"
                frameBorder="0">
        </iframe>
    </div>
</template>
<script>
    import ExperienceSessionsList from './ExperiencePublicSessionsList';
    import ExperienceAdventureList from './ExperiencePublicAdventuresList';

    import ExperienceRating from './ExperienceRating';

    export default {
        name: 'apithy-experience-public-viewer',
        components: {
            'apithy-experience-sessions-list': ExperienceSessionsList,
            'apithy-experience-adventure-list': ExperienceAdventureList,
            'apithy-experience-rating': ExperienceRating
        },
        props: {
            experience: "",
            session: ""
        },
        computed: {
            iframeHeight() {
                return this.iframe_height;
            },
            getCurrentTime() {
                return Math.round(+new Date() / 1000)
            },
            iframeWidth(){
                return document.documentElement.clientWidth;
            }
        },
        created() {
        },
        methods: {
            sessionChanged() {
                this.isAdventureFinished();
            },
            loadIframe() {
                this.sessionLoading = false;
                this.iframe_height = this.iframe_height;
                console.log("Loaded");
            },
            apithyIframeSizeHandle(height) {
                $('.iframe-experience-item').attr("style", "");
                this.iframe_height = height;
            },
            apithyHTMLContentHandle() {
                let vm = this;
                window.addEventListener('scroll', function (event) {

                    // Clear our timeout throughout the scroll
                    window.clearTimeout(vm.isScrolling);
                    // Set a timeout to run after scrolling ends
                    vm.isScrolling = setTimeout(() => {
                        vm.handleScroll();
                    }, 66);

                }, false);
            },
            updateSession(session) {
                /*if (session.id != this.session_data.id) {
                    this.sessionLoading = true;
                    this.iframe_height = 700;
                    $('.iframe-experience-item').attr("style", "");
                    //$('.iframe-experience-item').attr("style", "height:700px");
                    this.session_data = session;
                    window.removeEventListener('scroll', this.handleScroll, true);
                    window.location.hash = 'session-' + session.id;
                    this.is_finished = false;

                    if (this.session.status == this.apithy_constants.SESSION_STATUS_FINISHED) {
                        this.is_finished = true;
                    }
                }*/

            },
            handleScroll() {
                if ($(window).height() + $(window).scrollTop() >= $(document).height()) {
                    if (!this.sessionLoading && !this.is_finished) {
                        this.isAdventureFinished();
                    }
                }
            },
            isAdventureFinished() {
                let message = 'Necesitas ingresar o crear una cuenta para continuar.';
                let title = '¡Reto bloqueado!';
                let cookie = {
                    id: this.experience.id,
                    experience: this.experience.system_id,
                    event: (!!this.experience.price_default) ? 'append_to_cart' : 'enroll_user',
                    redirect_to: (!!this.experience.price_default) ? 'experience.preview' : 'experience.viewer',
                };
                this.$cookie.set('public_experience', JSON.stringify(cookie));

                this.$cookie.set('public_experience', JSON.stringify(cookie));
                this.$snotify.confirm(message, title, {
                    showProgressBar: true,
                    closeOnClick: false,
                    buttons: [
                        {
                            text: 'Ingresar',
                            action: (toast) => {
                                this.$snotify.remove(toast.id);
                                window.location.href = route('login');
                            },
                            bold: false
                        },
                        {
                            text: 'Crear cuenta', action: (toast) => {
                                this.$snotify.remove(toast.id);
                                window.location.href = route('signup');
                            }
                        }
                    ]
                });
            },
            sendInteractedData() {},
            sendFinishedData(toast) {
                this.isAdventureFinished();
            },
            setRating(category, value) {
                this.rating[category] = value;
                console.log(this.rating[category]);
            }
        },
        mounted() {

            $('.iframe-experience-item').attr("style", "");
            $('.iframe-experience-item').attr("style", "height:0px");
            $('.iframe-experience-item').attr("height", "0");


            this.$root.$on('apithy-iframe-size', this.apithyIframeSizeHandle);
            this.$root.$on('apithy-html-content', this.apithyHTMLContentHandle);
            this.$root.$on('apithy-video-finished', this.sendFinishedData);



        },
        data() {
            return {
                visibility: ('visibility' in this.experience) ? (this.experience.visibility) : 1,
                companies: [],
                areas: [],
                positions: [],
                enrolled_button: false,
                access_time: Math.round(+new Date() / 1000),
                iframe_height: 900,
                session_data: this.session,
                is_finished: false,
                loader: true,
                sessions: this.experience.sessions,
                adventures: this.experience.adventures,
                rateExperience: false,
                isScrolling: false,
                sessionLoading: true,
            }
        }
    }
</script>

<style scoped>
    #experience-viewer {
        position: relative;
        overflow: auto;
        -webkit-overflow-scrolling: touch;
        max-width: 100%;
        width: 100%;
    }

    .iframe-experience-item {
        margin: 0 auto;
        display: block;
        width: 1px;
        min-width: 90%;
        *width: 90%;
    }

    #experience-viewer .fa-check-circle {
        color: green !important;
    }

    #experience-viewer .fa-play-circle {
        color: darkblue;
    }

    #experience-viewer .experience-title a {
        text-decoration: none;
        color: white;
    }

    .experience-rating {
        position: absolute;
        width: 100%;
        height: 100%;
        background-color: darkblue;
        z-index: 1000;
    }

    .experience-rating-image {
        color: white;
        text-align: center;
    }

    .experience-rating-form {
        margin-top: 50px !important;
    }

    .experience-rating .button {
        background-color: dodgerblue;
        color: white;
        display: block;
        margin: 40px auto;
        padding: 5px 20px 0 20px;
    }

    .rating-items span {
        color: white;
        height: 24px;
        display: inline-block;
        vertical-align: top;
    }

    .rating-category {
        width: 220px;
    }

    .rating-triggers {
        display: inline-block;
    }

    .rating-item {
        cursor: pointer;
        margin: 0 20px;
        margin-bottom: 15px;
    }

    .rating-item:hover, .rating-item.selected {
        -webkit-filter: grayscale(0) !important;
        filter: grayscale(0) !important;
    }

    .rating-1 {
        background: url('/images/emojis.png') no-repeat 0 0;
        width: 24px;
        height: 25px;
        display: inline-block;
        -webkit-filter: grayscale(1);
        filter: grayscale(1);
    }

    .rating-2 {
        background: url('/images/emojis.png') no-repeat -55px 0;
        width: 24px;
        height: 25px;
        display: inline-block;
        -webkit-filter: grayscale(1);
        filter: grayscale(1);
    }

    .rating-3 {
        background: url('/images/emojis.png') no-repeat -177px 0;
        width: 24px;
        height: 25px;
        display: inline-block;
        -webkit-filter: grayscale(1);
        filter: grayscale(1);
    }

    .rating-4 {
        background: url('/images/emojis.png') no-repeat -239px 0;
        width: 24px;
        height: 25px;
        display: inline-block;
        -webkit-filter: grayscale(1);
        filter: grayscale(1);
    }

</style>