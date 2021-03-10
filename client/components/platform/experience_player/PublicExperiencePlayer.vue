<template>
    <div class="">
        <apithy-experience-canvas v-bind:session="session"
                                  v-bind:experience="experience"
                                  :auth_user="auth_user"
                                  :enrollment_progress="enrollment_progress"
                                  @finishSession="sendFinishedData"
                                  @nextSession="nextSession"
                                  @prevSession="prevSession">
        </apithy-experience-canvas>
        <div class="mobile-view d-block d-lg-none">
            <div class="session-list-container portrait" v-show="session_list.show">
                <div class="session-list-header">
                    <div class="row justify-content-center align-items-center mr-0 ml-0">
                        <div class="col-10 align-self-center">
                            <span class="has-text-weight-bold">
                                {{ experience.title }}
                            </span>
                        </div>
                    </div>
                    <div class="session-list-close has-text-white pointer" @click="toggleSessionList">
                        <span><i class="fa fa-times"></i></span>
                    </div>
                </div>
                <div class="session-list-content">
                    <div class="session-list-experience" v-if="is_experience">
                        <div class="row session-list-item pointer"
                             v-for="(session, key) in session_list.list"
                             :class="{'is-current': isCurrent(session)}"
                             :key="'session-'+session.id" @click="selectedSession(session)">
                            <div class="col-2 align-self-center text-right item-icon">
                                <span class="font-22"><i class="fas" :class="getStatusIcon(session)"></i></span>
                            </div>
                            <div class="col-10 align-self-center item-title">
                                <span>{{ session.title }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="adventure-list-experience" v-if="is_journey">
                        <div class="" v-for="adventure in adventure_list.list">
                            <b-collapse :aria-id="'adventure-'+adventure.id" :open="false">
                                <div slot="trigger"
                                     role="button"
                                     class="row adventure-list-item pointer"
                                     :aria-controls="'adventure-'+adventure.id" @click="selectedSession(session)">
                                    <div class="col-2 align-self-center text-right item-icon">
                                        <span class="font-22"><i class="fas fa-angle-down"></i></span>
                                    </div>
                                    <div class="col-10 align-self-center item-title">
                                        <span>{{ adventure.title }}</span>
                                    </div>
                                </div>
                                <div class="session-list-experience">
                                    <div class="row session-list-item pointer"
                                         v-for="(session, key) in adventure.sessions"
                                         :class="{'is-current': isCurrent(session)}"
                                         :key="'session-'+session.id">
                                        <div class="col-2 align-self-center text-right item-icon">
                                            <span class="font-22"><i class="fas" :class="getStatusIcon(session)"></i></span>
                                        </div>
                                        <div class="col-10 align-self-center item-title">
                                            <span>{{ session.title }}</span>
                                        </div>
                                    </div>
                                </div>
                            </b-collapse>
                        </div>
                    </div>

                </div>
            </div>
            <!-- Player controls -->
            <div class="mobile-player portrait" v-if="!rateExperience">
                <div class="player">
                    <div class="row">
                        <div :class="{
                                'text-center pointer':true,
                                'col-3':!is_landscape,
                                'col-12 pt-3 pb-3':is_landscape
                             }"
                             @click="toggleSessionList">
                            <div class="ico">
                                <span><i class="fa" :class="{'fa-list-ol':!session_list.show, 'fa-times':session_list.show}"></i></span>
                            </div>
                            <div class="font-14"><span>Retos</span></div>
                        </div>
                        <div :class="{
                                'text-center pointer':true,
                                'col-3':!is_landscape,
                                'col-12 pt-3 pb-3':is_landscape,
                                'disabled': is_finished || session_status === apithy_constants.SESSION_STATUS_FINISHED
                             }" @click="sendFinishedData">
                            <div class="ico"><span><i class="fa fa-check-circle"></i></span></div>
                            <div class="font-14"><span>Terminar</span></div>
                        </div>
                        <div :class="{
                                'text-center pointer':true,
                                'col-3':!is_landscape,
                                'col-12 pt-3 pb-3':is_landscape,
                                'disabled': !session.prev_session
                             }"
                             @click="prevSession">
                            <div class="ico"><span><i class="fa fa-fast-backward"></i></span></div>
                            <div class="font-14"><span>Anterior</span></div>
                        </div>
                        <div :class="{
                                'text-center pointer':true,
                                'col-3':!is_landscape,
                                'col-12 pt-3 pb-3':is_landscape,
                                'disabled': !session.next_session
                             }"
                             @click="nextSession">
                            <div class="ico"><span><i class="fa fa-fast-forward"></i></span></div>
                            <div class="font-14"><span>Siguiente</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="desktop-view d-none d-lg-block">
            <div class="session-list-container" v-if="session_list.show">
                <div class="session-list-header">
                    <div class="row align-items-center mr-0 ml-0">
                        <div class="col-10 align-self-center">
                            <span class="has-text-weight-bold">
                                {{ experience.title }}
                            </span>
                        </div>
                    </div>
                    <div class="session-list-close has-text-white pointer" @click="toggleSessionList">
                        <span><i class="fa fa-times"></i></span>
                    </div>
                </div>
                <div class="session-list-content">
                    <div class="session-list-experience" v-if="is_experience">
                        <div class="row ml-0 mr-0 session-list-item pointer"
                             v-for="(session, key) in session_list.list"
                             :key="'session-'+session.id"
                             :class="{'is-current': isCurrent(session)}"
                             @click="selectedSession(session)">
                            <div class="col-2 align-self-center text-right item-icon">
                                <span class="font-22"><i class="fas" :class="getStatusIcon(session)"></i></span>
                            </div>
                            <div class="col-10 align-self-center item-title">
                                <span>{{ session.title }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="adventure-list-experience" v-if="is_journey">
                        <div class="" v-for="adventure in adventure_list.list">
                            <b-collapse :aria-id="'adventure-'+adventure.id" :open="false">
                                <div slot="trigger"
                                     role="button"
                                     class="row adventure-list-item pointer"
                                     :aria-controls="'adventure-'+adventure.id">
                                    <div class="col-2 align-self-center text-right item-icon">
                                        <span class="font-22"><i class="fas fa-angle-down"></i></span>
                                    </div>
                                    <div class="col-10 align-self-center item-title">
                                        <span>{{ adventure.title }}</span>
                                    </div>
                                </div>
                                <div class="session-list-experience">
                                    <div class="row ml-0 mr-0 session-list-item pointer"
                                         v-for="(session, key) in adventure.sessions"
                                         :class="{'is-current': isCurrent(session)}"
                                         :key="'session-'+session.id"
                                         @click="selectedSession(session)">
                                        <div class="col-2 align-self-center text-right item-icon">
                                            <span class="font-22"><i class="fas" :class="getStatusIcon(session)"></i></span>
                                        </div>
                                        <div class="col-10 align-self-center item-title">
                                            <span>{{ session.title }}</span>
                                        </div>
                                    </div>
                                </div>
                            </b-collapse>
                        </div>
                    </div>

                </div>
            </div>
            <div class="desktop-player">
                <div class="player pt-2 pb-2 pl-4 pr-4" v-if="!rateExperience">
                    <div class="row">
                        <div class="text-center pointer col-3" @click="toggleSessionList">
                            <div class="ico">
                                <span><i class="fa" :class="{'fa-list-ol':!session_list.show, 'fa-times':session_list.show}"></i></span>
                            </div>
                            <div class="font-14" v-if="player_expanded"><span>Retos</span></div>
                        </div>
                        <div class="text-center pointer col-3"
                             :class="{ 'disabled': is_finished || session_status === apithy_constants.SESSION_STATUS_FINISHED }"
                             @click="sendFinishedData">
                            <div class="ico"><span><i class="fa fa-check-circle"></i></span></div>
                            <div class="font-14" v-if="player_expanded"><span>Terminar</span></div>
                        </div>
                        <div class="text-center pointer col-3"
                             :class="{ 'disabled': !session.prev_session }"
                             @click="prevSession">
                            <div class="ico"><span><i class="fa fa-fast-backward"></i></span></div>
                            <div class="font-14" v-if="player_expanded"><span>Anterior</span></div>
                        </div>
                        <div class="text-center pointer col-3"
                             :class="{ 'disabled': !session.next_session }"
                             @click="nextSession">
                            <div class="ico"><span><i class="fa fa-fast-forward"></i></span></div>
                            <div class="font-14" v-if="player_expanded"><span>Siguiente</span></div>
                        </div>
                    </div>
                </div>
                <div class="player-add-on">
                    <div class="player-expander pointer" @click="toggleExpandedPlayer">
                        <span><i :class="{'icon-collapse':this.player_expanded, 'icon-expand':!this.player_expanded}"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <apithy-experience-rating v-if="rateExperience"
                                  :experience="experience"
                                  :auth_user="auth_user">
        </apithy-experience-rating>
    </div>
</template>

<script>

import ExperienceCanvas from '../ExperienceCanvas';
import ExperienceRating from '../ExperienceRating';

export default {
    name: "ExperiencePlayer",
    props: {
        auth_user: {
            type: Object,
            required: false,
            default () { return {} }
        },
        experience: {
            type: Object,
            required: true
        },
        current_session: {
            type: Object,
            required: true
        },
        mode: {
            required: false,
            return () { return undefined }
        },
        companies_data: {
            required: false,
            default () { return undefined }
        },
        enrollment_progress: {
            required: false,
            default () { return undefined }
        },
    },
    components: {
        'apithy-experience-rating': ExperienceRating,
        'apithy-experience-canvas': ExperienceCanvas
    },
    computed: {
        session_url: function () {
            let url = this.session.resource_url;
            if (!this.is_guest) {
                url = url + '?track_events=true';
            } else {
                url = url + '?track_events=false';
            }
            url = url + '&env=' + this.apithy_constants.ENV;
            url = url + '&experience=' + this.experience.id;
            if (!this.is_guest) {
                url = url + '&user=' + this.auth_user.id;
            }
            url = url + '&session=' + this.session.id;
            url = url + '&access_time=' + this.access_time;
            if (!!this.session.current_enrollment_progress) {
                url = url + '&enrollment_progress=' + this.session.current_enrollment_progress.id
            }
            return url;
        },
        session_status: function () {
            return this.session.current_enrollment_progress.status;
        },
        access_time: function () {
            return Math.round(+new Date() / 1000);
        },
        is_experience: function () {
            return this.experience.type === this.apithy_constants.EXPERIENCE_TYPE_ADVENTURE;
        },
        is_journey: function () {
            return this.experience.type === this.apithy_constants.EXPERIENCE_TYPE_JOURNEY;
        },
        is_portrait: function () {
            return this.viewport.orientation === 'portrait';
        },
        is_landscape: function () {
            return this.viewport.orientation === 'landscape';
        },
        is_guest: function () {
            return _.isEmpty(this.auth_user);
        },
    },
    data () {
        return {
            rateExperience: false,
            iframe_height: 0,
            loading: true,
            loader: null,
            session: this.current_session,
            session_list: {
                show: false,
                list: this.experience.sessions,
            },
            adventure_list: {
                show: false,
                list: [], //this.experience.adventures,
            },
            is_finished: false,
            next_session: null,
            viewport: {
                orientation: '',
                grade: null
            },
            player_expanded: true,
        }
    },
    mounted() {
        this.loading  = true;

        // Set default device orientation
        this.setViewportOrientation();

        // Event listener for device orientation change
        window.addEventListener('orientationchange', () => {
            this.setViewportOrientation();
        }, false);

        // Adjust content height event
        this.$root.$on('apithy-iframe-size', this.adjustFrame);
        this.setJourneySessions();
    },
    watch: {
        'viewport.orientation': function (orientation) {
            let menu = document.querySelector('.mobile-player');
            let sessions = document.querySelector('.session-list-container');
            let close_button = document.querySelector('.session-list-close');
            if (!!orientation) {
                this.setOrientationClass(menu, orientation);
                this.setOrientationClass(sessions, orientation);
                this.setOrientationClass(close_button, orientation);
                this.refreshSessionList(orientation);
            }
        },
        'session_list.show': function (value) {
            if (value) {
                setTimeout(() => {
                    let orientation = this.viewport.orientation;
                    this.refreshSessionList(orientation);
                }, 100)
            }
        },
        'adventure_list.show': function (value) {
            if (value) {
                setTimeout(() => {
                    let orientation = this.viewport.orientation;
                    this.refreshSessionList(orientation);
                }, 100)
            }
        }
    },
    methods: {
        launchGuestNotification () {
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
        setJourneySessions () {
            if (this.is_journey) {
                let array = null;
                let prev_session = null;
                let next_session = null;

                array = _.each(this.experience.adventures, (experience, index) => {
                    experience.sessions = _.each(experience.sessions, (session, key) => {
                        //console.log('['+index+', '+key+']')
                        prev_session = session.prev_session;
                        next_session = session.next_session;

                        if (!prev_session) {
                            if (index > 0) {
                                prev_session = _.last(this.experience.adventures[index-1].sessions);
                            }
                        }
                        if (!next_session) {
                            if (index < this.experience.adventures.length - 1) {
                                next_session = _.head(this.experience.adventures[index+1].sessions);
                            }
                        }

                        session.prev_session = prev_session;
                        session.next_session = next_session;
                    })
                })
                this.adventure_list.list = array;
            }
        },
        toggleExpandedPlayer () {
            this.player_expanded = !this.player_expanded;
        },
        adjustFrame (new_height = 0) {
            let frame = this.$refs.frame;
            //frame.style.height = '50vh';

            if (!!new_height) {
                console.log('\n=======');
                console.log('adjusting frame')
                console.log('to new height: ',new_height)
                console.log('=======\n');
                frame.style.height = new_height+'px';
            }

            this.loading = false;
            this.loader.close();
        },
        refreshSessionList (orientation) {
            if (this.session_list.show || this.adventure_list.show) {
                let sessions = document.querySelector('.session-list-container');
                let close_button = document.querySelector('.session-list-close');

                this.setOrientationClass(sessions, orientation);
                this.setOrientationClass(close_button, orientation);
            }
            this.is_portrait;
            this.is_landscape;
        },
        setOrientationClass (el, orientation) {
            const LANDSCAPE = 'landscape';
            const PORTRAIT = 'portrait';

            if (el && orientation) {
                if (orientation === LANDSCAPE) {
                    if (!el.classList.contains(orientation)) {
                        el.classList.remove(PORTRAIT)
                        el.classList.add(orientation)
                    }
                } else {
                    if (!el.classList.contains(orientation)) {
                        el.classList.remove(LANDSCAPE)
                        el.classList.add(orientation)
                    }
                }
            }
        },
        setViewportOrientation () {
            let frame = this.$refs.frame;
            let current_orientation = window.screen.orientation.angle;

            // iOs device listener orientation
            if (
                navigator.userAgent.match(/iPhone/i) ||
                navigator.userAgent.match(/iPad/i) ||
                navigator.userAgent.match(/iPod/i)
            ) {
                current_orientation = window.orientation;
            }

            this.viewport.grade = current_orientation;

            if (current_orientation === 0 || current_orientation === 180 || current_orientation === -180) {
                this.viewport.orientation = 'portrait';
            } else if (current_orientation === 90 || current_orientation === -90 || current_orientation === 270) {
                this.viewport.orientation = 'landscape';
            }

            if (!!frame) {
                setTimeout( () => {
                    if (this.viewport.orientation === 'landscape' && window.innerWidth < 900) {
                        frame.style.width = '85vw';
                    } else {
                        frame.style.width = '100vw';
                    }
                }, 100);
            }
        },
        sendFinishedData () {
            if (this.is_guest) {
                this.launchGuestNotification();
                return 0;
            }
            if (this.is_finished || this.session.current_enrollment_progress.status === this.apithy_constants.SESSION_STATUS_FINISHED) {
                return 0;
            }
            console.log('\n=======');
            console.log("Contenido Finalizado, Enviando");
            console.log('=======\n');
            let toUnix = function (date) {
                return Math.round(date.getTime() / 1000);
            };

            let activity_finish = {
                user: this.auth_user.id,
                experience: this.experience.id,
                session: this.session.id,
                enrollment_progress: this.session.current_enrollment_progress.id,
                access_time: this.access_time,
                verb: 'finished',
                data_type: 'content_event',
                score: 0,
                maxScore: 0,
                finished: toUnix(new Date()),
            };


            /** @TODO: cambiar el snotify por la vista de error en moviles para el success */

            this.$snotify.async('Actualizando avance', 'Procesando datos', () => new Promise((resolve, reject) => {
                return axios({
                    method: 'POST',
                    url: route('user.set_progress') + '?time=' + toUnix(new Date()),
                    params: activity_finish
                }).then((response) => {
                    this.is_finished = true;
                    this.session.current_enrollment_progress.status = this.apithy_constants.SESSION_STATUS_FINISHED;
                    //vm.loading = false;


                    if (this.experience.type === this.apithy_constants.EXPERIENCE_TYPE_JOURNEY) {
                        this.adventure_list.list = response.data.adventures;
                    } else {
                        this.session_list.list = response.data.sessions;
                    }
                    //this.session = response.data.current_session;

                    /** @TODO: Habilitar la calificación de la experiencia */
                    if (response.data.current_enrollment.status === 2) {
                        console.log('\n=======');
                        console.log('disparar rate experience')
                        console.log('=======\n');


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
                    //vm.loading = false;
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
        isUnlocked (session) {
            return session.current_enrollment_progress.status !== this.apithy_constants.SESSION_STATUS_BLOCKED;
        },
        isCurrent (session) {
            return this.session.id === session.id;
        },
        getStatusIcon (session) {
            let current_status = 0;
            let status_icon = 'fa-lock';

            if (session.current_enrollment_progress) {
                current_status = session.current_enrollment_progress.status;
            }

            if (this.is_guest && session.active_class === 'is_current') {
                current_status = 1;
            }

            switch (current_status) {
                case this.apithy_constants.SESSION_STATUS_AVAILABLE:
                    status_icon = 'fa-lock-open';
                    break;
                case this.apithy_constants.SESSION_STATUS_BLOCKED:
                    status_icon = 'fa-lock';
                    break;
                case this.apithy_constants.SESSION_STATUS_FINISHED:
                    status_icon = 'fa-check-circle has-text-success';
                    break;
                case this.apithy_constants.SESSION_STATUS_IN_PROGRESS:
                    status_icon = 'fa-lock-open has-text-primary';
                    break;
                default:
                    status_icon = 'fa-lock';
                    break;
            }

            return status_icon;
        },
        toggleSessionList () {
            this.session_list.show = !this.session_list.show;
        },
        getJourneySession (experience_id, session_id, list = []) {
            if (!!experience_id && !!session_id && !!list.length) {
                let experience = _.find(list,['id',experience_id]);

                return _.find(experience.sessions,['id',session_id]);
            }
        },
        getSession (session_id, list = []) {
            if (!!session_id && !!list.length) {
                return _.find(list,['id',session_id]);
            }
        },
        selectedSession (session) {
            if (this.is_guest) {
                if (session.id !== this.session.id) {
                    this.launchGuestNotification();
                }
                return 0;
            }

            if (session.id !== this.session.id) {
                let cep = session.current_enrollment_progress;
                if (!!cep.status && cep.status !== this.apithy_constants.SESSION_STATUS_BLOCKED) {
                    this.session = session;
                    this.toggleSessionList();
                } else {
                    this.$snotify.error('Necesitas finalizar el reto anterior', '¡Reto Bloqueado!', {
                        showProgressBar: true,
                        closeOnClick: true,
                        timeout: 2000,
                    });
                }
            }
        },
        prevSession (current_session = null) {
            current_session = (!!this.session.prev_session) ? this.session : current_session;
            if (!!current_session && !!current_session.prev_session) {
                if (this.is_experience) {
                    let session = this.getSession(current_session.prev_session.id, this.session_list.list);
                    this.updateSession(session);
                } else if (this.is_journey) {
                    console.log(this.session)
                    let session = this.getJourneySession(current_session.prev_session.experience_id, current_session.prev_session.id, this.adventure_list.list);
                    this.updateSession(session);
                }
            } else {
                let session = null;
                if (this.is_journey) {
                    session = this.getJourneySession(this.session.experience_id, this.session.id, this.adventure_list.list)
                    if (!!session.prev_session) {
                        this.prevSession(session);
                    }
                }
            }
        },
        nextSession (current_session = null) {
            if (this.is_guest) {
                this.launchGuestNotification();
                return 0;
            }
            current_session = (!!this.session.next_session) ? this.session : current_session;
            if (!!current_session && !!current_session.next_session) {
                if (this.is_experience) {
                    let session = this.getSession(current_session.id, this.session_list.list);

                    if (this.is_finished || session.current_enrollment_progress.status === this.apithy_constants.SESSION_STATUS_FINISHED) {
                        let next_session = this.getSession(session.next_session.id, this.session_list.list);

                        this.is_finished = true;
                        this.updateSession(next_session);
                    } else {
                        this.$snotify.error('Necesitas finalizar el reto anterior', '¡Reto Bloqueado!', {
                            showProgressBar: true,
                            closeOnClick: true,
                            timeout: 2000,
                        });
                    }
                } else if (this.is_journey) {
                    let session = this.getJourneySession(current_session.experience_id, current_session.id, this.adventure_list.list);

                    if (this.is_finished || session.current_enrollment_progress.status === this.apithy_constants.SESSION_STATUS_FINISHED) {
                        let next_session = this.getJourneySession(session.next_session.experience_id, session.next_session.id, this.adventure_list.list);

                        this.is_finished = true;
                        this.updateSession(next_session);
                    } else {
                        this.$snotify.error('Necesitas finalizar el reto anterior', '¡Reto Bloqueado!', {
                            showProgressBar: true,
                            closeOnClick: true,
                            timeout: 2000,
                        });
                    }
                }
            } else {
                let session = null;
                if (this.is_journey) {
                    session = this.getJourneySession(this.session.experience_id, this.session.id, this.adventure_list.list);
                    if (!!session.next_session) {
                        this.nextSession(session);
                    }
                }
            }
        },
        updateSession(session) {
            if (session.id !== this.session.id) {
                this.loader = this.$loading.open({
                    container: this.isFullPage ? null : this.$refs.frame.$el
                });

                this.sessionLoading = true;
                this.session = session;

                //window.removeEventListener('scroll', this.handleScroll, true);
                window.location.hash = 'session-' + session.id;
                this.is_finished = false;
            }

        },
    }
}
</script>

<style scoped>
.iframe-experience-item {
    position: relative;
    z-index: 1;
    width: 100vw;
}
.player {
    color: #FFFFFF;
}
.player > .row {
    margin-right: 0;
    margin-left: 0;
}
.player .disabled {
    color: gray !important;
    cursor: not-allowed;
}

.mobile-player.portrait {
    left: 0;
    bottom: 0;
    width: 100%;
    height: 60px;
    z-index: 13;
    background-color: #152B53;
    position: fixed;
}
.mobile-player.landscape {
    right: 0;
    top: 50px;
    width: 15%;
    height: 100vh;
    z-index: 13;
    background-color: #152B53;
    position: fixed;
}
.mobile-player.portrait .player {
    height: 100%;
    width: 100%;
    padding: 10px 0px;
}
.mobile-player.landscape .player {
    height: 100%;
    width: 100%;
    padding: 0px 10px;
}

.desktop-view .desktop-player {
    position: fixed;
    bottom: 40px;
    left: calc(50% - 212px);
    opacity: 0.9;
    z-index: 13;
}
.desktop-view .desktop-player .player {
    width: 400px;
    color: #FFFFFF;
    background-color: #152B53;
    -webkit-border-radius: 100px;
    -moz-border-radius: 100px;
    border-radius: 100px;
}
.desktop-view .desktop-player .player-add-on {
    position: absolute;
    right: -25px;
    width: 60px;
    height: 40px;
    text-align: center;
    color: #FFFFFF;
    padding: 10px 0 10px 20px;
    top: calc(50% - 20px);
    background-color: #152B53;
    -webkit-border-radius: 100px;
    -moz-border-radius: 100px;
    border-radius: 100px;
}

.session-list-container .session-list-header {
    background-color: #152B53;
    color: #FFFFFF;
    min-height: 60px;
    width: 100%;
}
.session-list-container .session-list-header .session-list-close {
    position: absolute;
    top: 2px;
}
.session-list-container .session-list-content {
    background-color: #FFF;
    width: 100%;
    height: 100%;
    overflow-y: auto;
}
.session-list-container .session-list-content .session-list-item {
    height: 50px;
    border-bottom: 1px solid #BEBEBE;
}
.session-list-container .session-list-content .session-list-item.is-current {
    background-color: rgba(0,0,0,0.1);
}

.mobile-view .session-list-container.portrait {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 12;
    width: 100%;
    height: calc(100vh - 60px);
    height: -moz-calc(100vh - 60px);
    height: -webkit-calc(100vh - 60px);
}
.mobile-view .session-list-container.landscape {
    position: fixed;
    top: 50px;
    left: 0;
    z-index: 12;
    width: 85%;
    height: calc(100vh - 50px);
    height: -moz-calc(100vh - 50px);
    height: -webkit-calc(100vh - 50px);
}
.mobile-view .session-list-container .session-list-header .row {
    height: 100%;
    min-height: 60px;
}
.mobile-view .session-list-container .session-list-header .session-list-close.portrait{
    right: 10px;
}
.mobile-view .session-list-container .session-list-header .session-list-close.landscape{
    left: 10px;
}
.mobile-view .session-list-container .session-list-content .adventure-list-item {
    height: 40px;
    color: #FFFFFF;
    background-color: #1C6BF8;
    border-top: 1px solid #BEBEBE;
}
.mobile-view .session-list-container .session-list-content .adventure-list-item .item-icon {
    height: 100%;
}

.desktop-view .session-list-container {
    position: fixed;
    bottom: 110px;
    left: calc(50% - 250px);
    z-index: 12;
    width: 500px;
    /* height: 200px; */
    box-shadow: 0px 0px 6px -3px black;
}
.desktop-view .session-list-container .session-list-header {
    min-height: 30px;
}
.desktop-view .session-list-container .session-list-header .row {
    height: 100%;
}
.desktop-view .session-list-container .session-list-header .session-list-close {
    right: 10px;
}

@media only screen
and (min-width : 1600px) {
    .desktop-player {
        position: fixed;
        bottom: 40px;
        left: calc(50% - 212px);
        z-index: 13;
    }
}
</style>

<style>
.experience-rating {
    position: absolute;
    top: 50px;
    width: 100%;
    height: 100%;
    background-color: #042A56;
    z-index: 1000;
}
.mfp-content ul {
    display: block;
    list-style-type: disc;
    margin-block-start: 1em;
    margin-block-end: 1em;
    margin-inline-start: 0px;
    margin-inline-end: 0px;
    padding-inline-start: 40px;
}
</style>

