<template>
    <div id="experience-viewer" style="position: relative">
        <div class="experience-content">
            <template v-if="made_by_editor">
                <experience-render :experience-data.sync="experience"
                                   :session-data.sync="session"
                                   :session-id.sync="session.id">
                </experience-render>
            </template>
            <template v-else>
                <div v-if="session.json_data.header" class="experience-header">
                    <header v-html="session.json_data.header"></header>
                </div>
                <div class="experience-body">
                    <main v-html="session.json_data.main"></main>
                </div>
            </template>
            <div class="experience-controls container padding">

                <div v-if="session.current_enrollment_progress.status !== this.apithy_constants.SESSION_STATUS_FINISHED"
                     class="experience-finish-control" @click="finishSession">
                    <span>Terminar<br>Reto</span>
                </div>

                <div v-else class="experience-finish-control session_finished_button">
                    <span>Reto<br>Finalizado</span>
                </div>

                <div class="experience-sessions-control row">
                    <div class="experience-sessions-prev col-sm-6" v-if="session.prev_session">
                        <div class="control-action" @click="prevSession">
                            <div class="icon"><i class="fas fa-chevron-left"></i></div>
                            <div class="experience-session-text">
                                {{ session.prev_session.title }}
                            </div>
                        </div>
                    </div>
                    <div class="experience-sessions-prev-empty col-sm-6" v-else></div>

                    <div class="experience-sessions-next col-sm-6" v-if="session.next_session">
                        <div class="control-action" @click="nextSession">
                            <div class="icon"><i class="fas fa-chevron-right"></i></div>
                            <div class="experience-session-text">
                                {{ session.next_session.title }}
                            </div>
                        </div>
                    </div>
                    <div class="experience-sessions-next-empty col-sm-6" v-else></div>
                </div>
            </div>
            <div v-if="session.json_data.references" class="experience-info-sources container padding">
                <div v-html="session.json_data.references"></div>
            </div>
        </div>
    </div>
</template>
<script>
    import bus from '../../helpers/bus';
    import ExperienceRender from '../admin/experiences/editor/components/ExperienceRender';
    export default {
        name: 'apithy-experience-canvas',
        props: {
            mode: "",
            experience: "",
            auth_user: "",
            companies_data: "",
            enrollment_progress: "",
            session: ""
        },
        components: { 'experience-render':ExperienceRender },
        computed: {
            getCurrentTime() {
                return Math.round(+new Date() / 1000)
            },
            made_by_editor() {
                return (
                    !_.isNull(this.experience.json_data) &&
                    !_.isEmpty(this.experience.json_data)
                )
            }
        },
        mounted() {
            if (!!this.show_enrolled_notification) {
                this.$snotify.success('Te has enrolado correctamente.', '¡Enrolamiento exitoso!', {
                    showProgressBar: true,
                    closeOnClick: true,
                    pauseOnHover: true,
                    timeout: 2000,
                });
            }

            if(typeof this.session_data.json_data === 'string')
                this.session_data.json_data = JSON.parse(this.session_data.json_data);

            this.$parent.$on('update-session', this.loadExperienceScripts);

            setTimeout(() =>
                this.loadExperienceScripts(),
                2000);

        },
        methods: {
            finishSession() {
                this.$emit("finishSession");
            },
            prevSession() {
                this.$emit("prevSession");
            },
            nextSession() {
                this.$emit("nextSession");
            },
            loadExperienceScripts() {
                let script_tag = '';
                let inline_script = '';

                if('js_scripts' in this.session_data.json_data)
                    this.session_data.json_data.js_scripts.forEach(function (script) {
                        script_tag = document.createElement('script');
                        if (script.type == 'src') {
                            script_tag.setAttribute('src', script.value + '?time=' + Date.now());
                        } else if (script.type == 'inline') {
                            inline_script = document.createTextNode(script.value);
                            script_tag.appendChild(inline_script);
                        }

                        document.body.appendChild(script_tag)
                    });

                this.$nextTick(function () {

                    let apithy_iframe_params = {
                        user: this.auth_user.id,
                        experience: this.experience.id,
                        session: this.session.id,
                        enrollment_progress: this.session.current_enrollment_progress.id,
                        access_time: this.access_time,
                        track_events: true,
                        env: this.apithy_constants.ENV
                    }

                    let activity_data = {
                        user: this.auth_user.id,
                        experience: this.experience.id,
                        session: this.session.id,
                        enrollment_progress: this.session.current_enrollment_progress.id,
                        library_data: {
                            versionedName: "apithy.SendText-1.0",
                            versionedNameNoSpaces: "apithy.SendText",
                            machineName: "apithy.SendText"
                        },
                        activity_info: {
                            apithy_activity_metadata: {}
                        }
                    };

                    let enrollment_id = this.session.current_enrollment_progress.id;

                    $('iframe').each(function () {
                        let current_src = $(this).attr("src");
                        let new_src = current_src + '?' + $.param(apithy_iframe_params);
                        $(this).attr('src', new_src);
                    })

                    $("form.send-text").each(function () {

                        activity_data.activity_info.apithy_activity_metadata = {
                            id: 0,
                            cid: 0,
                            title: $(this).find("#section").val() + "-" + $(this).find("#activity_num").val() + "-" + $(this).find("#game").val(),
                            name: $(this).find("#section").val() + "-" + $(this).find("#activity_num").val() + "-" + $(this).find("#game").val(),
                            description: "",
                            is_mandatory: 0,
                            resolution_time: ""
                        }

                        axios({
                            method: 'POST',
                            url: "/sessions/register-activity",
                            data: activity_data
                        }).then((response) => {
                            $(this).append("<input type='hidden' id='activity_id' name='activity_id' value='" + response.data.id + "'>");
                        }).catch((error) => {
                            console.log(error);
                        });

                    });

                    let vm = this;


                    $("form.send-text button").click(function (e) {
                        e.preventDefault();

                        let section = $(this).parent().parent().find("#section").val();
                        let activity_num = $(this).parent().parent().find("#activity_num").val();
                        let game = $(this).parent().parent().find("#game").val();
                        let activity_id = $(this).parent().parent().find("#activity_id").val();
                        let user_response = $(this).parent().parent().find("textarea").val();

                        let activity_user_data = {
                            access_time: vm.access_time,
                            user: vm.auth_user.id,
                            experience: vm.experience.id,
                            session: vm.session.id,
                            enrollment_progress: enrollment_id,
                            activity_id: activity_id,
                            data_type: 'xapi',
                            'data': {
                                verb: {
                                    display: {
                                        'en-US': 'answered'
                                    }
                                },
                                object: {
                                    id: $(this).parent().parent().find("#section").val() + "-" + $(this).parent().parent().find("#activity_num").val() + "-" + $(this).parent().parent().find("#game").val()
                                },
                                library_data: {
                                    versionedName: "apithy.SendText-1.0",
                                    versionedNameNoSpaces: "apithy.SendText",
                                    machineName: "apithy.SendText"
                                },
                                user_response: {
                                    activity_info: {
                                        user_input: user_response,
                                        section: section,
                                        activity_num: activity_num,
                                        game: game,
                                    }
                                },
                            },
                            library_data: {
                                versionedName: "apithy.SendText-1.0",
                                versionedNameNoSpaces: "apithy.SendText",
                                machineName: "apithy.SendText"
                            },
                            activity_info: {
                                apithy_activity_metadata:
                                    {
                                        id: 0,
                                        cid: 0,
                                        title: $(".send-text #section").val() + "-" + $(".send-text #activity_num").val() + "-" + $(".send-text #game").val(),
                                        name: $(".send-text #section").val() + "-" + $(".send-text #activity_num").val() + "-" + $(".send-text #game").val(),
                                        description: "",
                                        is_mandatory: 0,
                                        resolution_time: ""
                                    }
                            }
                        };

                        axios({
                            method: 'POST',
                            url: "/student/progress",
                            data: activity_user_data
                        }).then((response) => {
                            $(this).parent().parent().parent().append("<div><h3>Respuesta enviada, recibirás la retroalimentación de parte de tu tutor.</h3></br></div>");
                            $(this).parent().parent().remove();
                        }).catch((error) => {
                            console.log(error);
                        });

                        return 0;
                    });

                });

                this.initLibraries()

            },
            updateSession(session) {
                if (session.id != this.session_data.id) {

                    window.removeEventListener('scroll', this.setScrollEvent, true);
                    $("html, body").animate({scrollTop: 0}, "slow");

                    this.loader = this.$loading.open({
                        container: this.isFullPage ? null : this.$refs.frame.$el
                    });

                    this.sessionLoading = true;
                    this.session_data = session;

                    window.location.hash = 'session-' + session.id;
                    this.is_finished = false;

                    if (this.session.status == this.apithy_constants.SESSION_STATUS_FINISHED) {
                        this.is_finished = true;
                    }
                }

            },
            isAdventureFinished() {
                this.$snotify.confirm('¿Has finalizado con este reto?', '¡Reto finalizado!', {
                    showProgressBar: true,
                    closeOnClick: false,
                    buttons: [
                        {text: 'Si', action: (toast) => this.sendFinishedData(toast), bold: false},
                        {
                            text: 'Aún no', action: (toast) => {
                               // console.log('Clicked: No');
                                this.$snotify.remove(toast.id);
                            }
                        }
                    ]
                });
            },
            initLibraries() {
                try {
                    $(".vertical-center").slick({
                        dots: true,
                        vertical: true,
                        centerMode: true,
                    });

                    $(".regular").slick({
                        dots: true,
                        infinite: true,
                        slidesToShow: 3,
                        slidesToScroll: 3
                    });
                } catch (e) {
                }
            }
        },
        data() {
            return {
                access_time: Math.round(+new Date() / 1000),
                session_data: this.session,
                is_finished: false,
                sessions: this.experience.sessions,
                adventures: this.experience.adventures,
                apithy_iframe_params: {}
            }
        }
    }
</script>

<style scoped>
    #experience-viewer {
        position: relative;
        overflow: auto;
        -webkit-overflow-scrolling: touch;
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
        position: fixed;
        width: 100%;
        height: 100vh;
        background-color: #042A56;
        z-index: 11;
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

    #experience-viewer {
        background-color: #FFF;
    }

    main {
        padding-bottom: 30px;
        background-color: #FFF;
    }

    .experience-info-sources {
        background-color: white;
        overflow: hidden;
    }

    .experience-info-sources a {
        color: #FFA81E !important;
    }

    .container.padding {
        max-width: 720px;
        padding: 50px 50px;
    }

    .experience-controls {
        padding: 0 50px 50px 50px;
    }

    .experience-sessions-control .control-action {
        background-color: rgba(256, 256, 256, .65);
        min-height: 80px;
        display: table;
        width: 100%;
        margin-bottom: 20px;
        cursor: pointer;
        border: 1px solid #DEDEDE;
    }

    .experience-sessions-control {
        margin-top: 20px;
    }

    .experience-controls .icon {
        background-color: white;
        border-radius: 100%;
        width: 78px;
        height: 78px;
        color: #FFBB44;
        cursor: pointer;
        box-shadow: 1px 0px 4px lightgrey;
    }

    .experience-finish-control {
        background-color: #FFBB44;
        width: 100px;
        margin: 0 auto;
        border-radius: 100%;
        height: 100px;
        cursor: pointer;
        margin-bottom: 30px;
    }

    .experience-finish-control span {
        text-align: center;
        display: block;
        padding-top: 30px;
        color: white;
        font-weight: bold;
        font-size: 16px;
    }

    .experience-session-text {
        display: table-cell;
        vertical-align: middle;
        font-size: 16px;
        font-weight: bold;
    }

    .experience-sessions-prev .experience-session-text {
        padding-left: 50px !important;
        padding-right: 20px;
        height: 78px;
    }

    .experience-sessions-next .experience-session-text {
        padding-left: 15px !important;
        padding-right: 45px;
        height: 78px;
    }

    .experience-sessions-prev, .experience-sessions-next {
        min-height: 80px;
    }

    .experience-sessions-prev .icon {
        background-color: white;
        position: absolute;
        left: -22px;
    }

    .experience-sessions-next .icon {
        background-color: white;
        position: absolute;
        right: -22px;
    }

    main .img-amp {
        width: 100% !important;
    }

    .session_finished_button {
        background-color: gray;
    }

</style>
