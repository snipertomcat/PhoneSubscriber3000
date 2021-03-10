<template>
    <div id="experience-viewer" style="position: relative">
        <apithy-experience-rating v-if="rateExperience" :experience="experience"
                                  :auth_user="auth_user"></apithy-experience-rating>
        <div v-if="experience.type==apithy_constants.EXPERIENCE_TYPE_ADVENTURE">
            <apithy-experience-sessions-list
                    :experience="experience"
                    :title="experience.title"
                    :experience-type="experience.type"
                    :sessions_list="sessions"
                    :adventures_list="adventures"
                    @changeSession="updateSession($event)">
            </apithy-experience-sessions-list>
        </div>
        <div v-else-if="experience.type==apithy_constants.EXPERIENCE_TYPE_JOURNEY">
            <apithy-experience-adventure-list
                    :experience="experience"
                    :title="experience.title"
                    :experience-type="experience.type"
                    :sessions_list="sessions"
                    :adventures_list="adventures"
                    @changeSession="updateSession($event)">
            </apithy-experience-adventure-list>
        </div>
        <div class="experience-content">
            <div class="experience-header">
                <header v-html="session_data.json_data.header"></header>
            </div>
            <div class="experience-body">
                <main v-html="session_data.json_data.main"></main>
            </div>
            <div class="experience-controls container padding">
                <div class="experience-finish-control">
                    <span>Terminar</br>Reto</span>
                </div>

                <div class="experience-sessions-control row">

                    <div class="experience-sessions-prev col-sm-6">
                        <div class="control-action">
                            <div class="icon"><i class="fas fa-chevron-left"></i></div>
                            <div class="experience-session-text">
                                Reto Anterior
                            </div>
                        </div>
                    </div>
                    <div class="experience-sessions-next col-sm-6">
                        <div class="control-action">
                            <div class="icon"><i class="fas fa-chevron-right"></i></div>
                            <div class="experience-session-text">
                                Reto Siguiente
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import ExperienceSessionsList from './ExperienceSessionsList';
    import ExperienceAdventureList from './ExperienceAdventuresList';

    import ExperienceRating from './ExperienceRating';

    export default {
        name: 'apithy-experience-viewer',
        components: {
            'apithy-experience-sessions-list': ExperienceSessionsList,
            'apithy-experience-adventure-list': ExperienceAdventureList,
            'apithy-experience-rating': ExperienceRating
        },
        props: {
            mode: "",
            experience: "",
            auth_user: "",
            companies_data: "",
            enrollment_progress: "",
            session: ""
        },
        computed: {
            iframeHeight() {
                return this.iframe_height;
            },
            getCurrentTime() {
                return Math.round(+new Date() / 1000)
            }
        },
        created() {
            if(this.$cookie.get('operation_result')){
                let operation_result = JSON.parse(this.$cookie.get('operation_result'));

                if (!!operation_result && operation_result.status === this.apithy_constants.ENROLLMENT_STATUS_ENROLLED) {
                    this.show_enrolled_notification = true;
                }

                this.$cookie.delete('public_experience');
                this.$cookie.delete('operation_result');
            }
        },
        mounted() {
            this.$root.$on('apithy-iframe-size', this.apithyIframeSizeHandle);
            this.$root.$on('apithy-html-content', this.apithyHTMLContentHandle);
            this.$root.$on('apithy-video-finished', this.sendFinishedData);

            this.$nextTick(function() {
                window.addEventListener('resize', this.apithyResizeHandle);
            });

            $('.iframe-experience-item').attr("style", "");



            this.sendAccessData();

            if (!!this.show_enrolled_notification) {
                this.$snotify.success('Te has enrolado correctamente.', '¡Enrolamienrto exitoso!', {
                    showProgressBar: true,
                    closeOnClick: true,
                    pauseOnHover: true,
                    timeout: 2000,
                });
            }

            this.loadExperienceScripts();
        },
        methods: {
            loadExperienceScripts(){
                let script_tag='';
                this.session_data.json_data.js_scripts.forEach(function(script){
                  script_tag = document.createElement('script');
                  if(script.type=='src'){
                      script_tag.setAttribute('src',script.value);
                  }else if(script.type=='inline'){
                      script_tag.innerHTML=script.value;
                  }

                  document.body.appendChild(script_tag);
                });
            },
            sessionChanged() {
            },
            loadIframe() {
                $('.iframe-experience-item').attr("style", "height:1px");
                $('.iframe-experience-item').attr("height", "1");
                this.iframe_height=1;

                console.log("loaded");
                setTimeout(() =>{
                        this.loader.close();
                        this.sessionLoading = false;
                    }
                    ,1000);
            },
            apithyResizeHandle() {
                let tmp_session=this.session_data;
                this.session_data = "";
                this.session_data=tmp_session;
            },
            apithyIframeSizeHandle(height) {
                if(height !=this.iframe_height) {
                    setTimeout(() => {
                        console.log("Estableciendo alto", height);
                        $('.iframe-experience-item').attr("style", "");
                        this.iframe_height = height;
                        $('.iframe-experience-item').attr("style", "height:" + this.iframe_height + 'px');
                    }, 500);
                }
            },
            apithyHTMLContentHandle() {
                setTimeout(() =>
                     window.addEventListener('scroll', this.setScrollEvent, true),
                2000);
            },
            setScrollEvent(){
                let vm = this;
                    // Clear our timeout throughout the scroll
                    window.clearTimeout(vm.isScrolling);
                    // Set a timeout to run after scrolling ends
                    vm.isScrolling = setTimeout(() => {
                        vm.handleScroll();
                    }, 66);

            },
            updateSession(session) {
                if (session.id != this.session_data.id) {

                    window.removeEventListener('scroll', this.setScrollEvent, true);
                    $("html, body").animate({scrollTop: 0}, "slow");

                    this.loader=this.$loading.open({
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
            handleScroll() {
                if ('id' in this.session_data.current_enrollment_progress) {
                    if ($(window).height() + $(window).scrollTop() >= $(document).height()) {
                        console.log($(window).height() + $(window).scrollTop(),"Scroll");
                        if (!this.sessionLoading && !this.is_finished && this.session_data.current_enrollment_progress.status !== this.apithy_constants.SESSION_STATUS_FINISHED) {
                            this.isAdventureFinished();
                        }
                    } else if (!this.sessionLoading && !this.is_finished && this.session_data.current_enrollment_progress.status !== this.apithy_constants.SESSION_STATUS_FINISHED) {
                        this.sendInteractedData();
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
                                console.log('Clicked: No');
                                this.$snotify.remove(toast.id);
                            }
                        }
                    ]
                });
            },
            sendInteractedData() {
                let toUnix = function (date) {
                    return Math.round(date.getTime() / 1000);
                };

                let activity_interacted = {
                    user: this.auth_user.id,
                    experience: this.experience.id,
                    session: this.session_data.id,
                    enrollment_progress: this.session_data.current_enrollment_progress.id,
                    access_time: this.access_time,
                    data_type: 'content_event',
                    verb:'scrolled',
                    score: 0,
                    maxScore: 0,
                    finished: toUnix(new Date()),
                };

                let vm = this;

                axios({
                    method: 'POST',
                    url: route('user.set_progress') + '?time=' + toUnix(new Date()),
                    params: activity_interacted
                }).then((response) => {
                    console.log(response);
                }).catch((error) => {
                    vm.loading = false;
                    console.log(error);
                });


            },
            sendAccessData() {
                let toUnix = function (date) {
                    return Math.round(date.getTime() / 1000);
                };

                let activity_interacted = {
                    user: this.auth_user.id,
                    experience: this.experience.id,
                    session: this.session_data.id,
                    enrollment_progress: this.session_data.current_enrollment_progress.id,
                    access_time: this.access_time,
                    data_type: 'content_event',
                    verb:'access',
                    score: 0,
                    maxScore: 0,
                };

                let vm = this;

                axios({
                    method: 'POST',
                    url: route('user.set_progress') + '?time=' + toUnix(new Date()),
                    params: activity_interacted
                }).then((response) => {
                    console.log(response);
                }).catch((error) => {
                    vm.loading = false;
                    console.log(error);
                });


            },
            sendFinishedData(toast) {
                console.log("Contenido Finalizado, Enviando");
                let toUnix = function (date) {
                    return Math.round(date.getTime() / 1000);
                };

                let activity_finish = {
                    user: this.auth_user.id,
                    experience: this.experience.id,
                    session: this.session_data.id,
                    enrollment_progress: this.session_data.current_enrollment_progress.id,
                    access_time: this.access_time,
                    data_type: 'content_event',
                    score: 0,
                    verb:'finished',
                    maxScore: 0,
                    finished: toUnix(new Date()),
                };

                let vm = this;

                if (toast) {
                    vm.$snotify.remove(toast.id);
                }

                toast = this.$snotify.async('Enrolamiento', 'Procesando datos', () => new Promise((resolve, reject) => {
                    return axios({
                        method: 'POST',
                        url: route('user.set_progress') + '?time=' + toUnix(new Date()),
                        params: activity_finish
                    }).then((response) => {
                        this.is_finished = true;
                        vm.loading = false;


                        if (this.experience.type == this.apithy_constants.EXPERIENCE_TYPE_JOURNEY) {
                            this.adventures = response.data.adventures;
                        } else {
                            this.sessions = response.data.sessions;
                        }

                        console.log(response.data.current_enrollment);
                        if (response.data.current_enrollment.status == 2) {
                            this.rateExperience = true;
                            $("html, body").animate({scrollTop: 0}, "slow");

                            if (document.getElementById('anim')) {
                                let animation = bodymovin.loadAnimation({
                                    container: document.getElementById('anim'),
                                    rederer: 'svg',
                                    loop: true,
                                    autoplay: true,
                                    path: '/js/data.json'
                                });
                            }
                            $('.iframe-experience-item').attr("style", "display:none");
                            this.iframe_height=0;
                        }

                        resolve({
                            title: response.data.title,
                            body: response.data.message,
                            config: {
                                closeOnClick: true,
                                timeout: 3000,
                                showProgressBar: true,
                                preventDuplicates: true,
                                onlyOne: true,
                                html: `
                                        <div class="snotifyToast__title">` + 'Reto Finalizado' + `</div>
                                        <div class="snotifyToast__body">` + 'Tu progreso ha sido guardado' + `</div>
                                        <div class="snotify-icon snotify-icon--success"></div>
                                        `
                            }
                        });

                        this.enrolled_button = true;

                    }).catch((error) => {
                        vm.loading = false;
                        console.log(error);
                        reject({
                            title: 'Error!!!',
                            body: error.response.data.message,
                            config: {
                                closeOnClick: true,
                                timeout: 3000,
                                showProgressBar: true,
                                html: `
                                        <div class="snotifyToast__title">` + error.data.title + `</div>
                                        <div class="snotifyToast__body">` + error.data.message + `</div>
                                        <div class="snotify-icon snotify-icon--success"></div>
                                        `
                            }
                        })
                    });

                }));
            },
            setRating(category, value) {
                this.rating[category] = value;
                console.log(this.rating[category]);
            }
        },
        data() {
            return {
                visibility: ('visibility' in this.experience) ? (this.experience.visibility) : 1,
                companies: [],
                areas: [],
                positions: [],
                enrolled_button: false,
                access_time: Math.round(+new Date() / 1000),
                iframe_height: 0,
                session_data: this.session,
                is_finished: false,
                loader: true,
                sessions: this.experience.sessions,
                adventures: this.experience.adventures,
                rateExperience: false,
                isScrolling: false,
                sessionLoading: true,
                isHTML:false
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

    main{
        padding-bottom:30px;
    }

    .experience-controls{
        padding: 0 50px 50px 50px;
    }

    .experience-sessions-control .control-action{
        background-color: rgba(256,256,256,.8);
        min-height: 80px;
        display: table;
        width: 100%;
        margin-bottom: 20px;
    }

    .experience-sessions-control{
        margin-top:20px;
        min-height: 80px;
    }

    .experience-controls .icon{
        background-color: white;
        border-radius: 100%;
        width: 80px;
        height: 80px;
        color:#FFBB44;
        cursor: pointer;
    }

    .experience-finish-control{
      background-color: #FFBB44;
      width:100px;
      margin: 0 auto;
      border-radius: 100%;
      height: 100px;
      cursor:pointer;
      margin-bottom: 30px;
    }

    .experience-finish-control span{
        text-align: center;
        display: block;
        padding-top: 30px;
        color:white;
        font-weight:bold;
        font-size:16px;
    }

    .experience-session-text{
        display: table-cell;
        vertical-align: middle;
        font-size:16px;
        font-weight:bold;
    }

    .experience-sessions-prev .experience-session-text{
        padding-left: 50px !important;
    }

    .experience-sessions-next .experience-session-text{
        padding-left: 15px !important;
    }

    .experience-sessions-prev, .experience-sessions-next{
    }

    .experience-sessions-prev .icon{
        background-color: white;
        position: absolute;
        left: -30px;
    }

    .experience-sessions-next .icon{
        background-color: white;
        position: absolute;
        right: -30px;
    }

    main .img-amp{
        width:100% !important;
    }

</style>