<template>
    <div class="card-wrapper" v-bind:class="[collapseClass]">
        <div class="card" v-bind:class="[collapseClass]">
            <div v-if="(course.assignation)" :class="{
                'ui label apithy-notification': true,
                'yellow':course.assignation.type === 'mandatory',
                'blue':course.assignation.type === 'invitation'
             }"
                 style="border-radius: .28571429rem 0 !important;">
                {{ translateType(course.assignation.type) }}
            </div>

            <div class="card-image" @click="gotToExperience">
                <div class="card-image-inner">
                    <figure class="image is-4by3">
                        <!--img v-bind:src="course.full_path_cover"-->
                        <div v-lazy-container="{ selector: 'img' }">
                            <img :data-src="course.full_path_cover" data-loading="/images/preloader.gif">
                        </div>
                    </figure>
                </div>
            </div>
            <div class="card-content">
                <div class="card-icon">
                    <img v-bind:src="course.company_author.full_path_logo"/>
                </div>
                <div class="media">
                    <div class="media-content">
                        <p class="title is-6">{{ course.title }}</p>
                    </div>
                </div>

                <div class="media" v-if="!isCollapse">
                    <div class="expereince-summary">
                        <p class="subtitle is-7" v-html="course.summary">{{ course.summary }}</p>
                    </div>
                </div>

                <div class="media" v-if="!isCollapse">
                    <div class="experience-features font-12">
                        <div class="row justify-content-between">
                            <div class="col-auto"
                                 v-if="course.features.hours_content && course.features.hours_content.time_value">
                                <b-icon pack="far" type="is-primary" icon="clock"></b-icon>
                                <span> {{ course.features.hours_content.time_value + ' ' + $t('messages.'+getPeriod(course.features.hours_content.time_period,course.features.hours_content.time_value)) }}</span>
                            </div>
                            <div class="col-auto">
                                <b-icon pack="fas" type="is-primary" icon="dollar-sign"></b-icon>
                                <span v-if="!!course.price_default"> {{ course.price_default }}</span>
                                <span v-else> Gratis</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="media" v-if="!isCollapse">
                    <div class="expereince-abilities row no-gutters">
                        <div class="col-auto" v-for="ability in getAbilities(course)">
                            <span class="tag">{{ability.title}}</span>
                        </div>
                    </div>
                </div>

                <div class="media">
                    <p class="subtitle is-7">Creado por: <a class="primary-color">{{course.company_author.name}}</a></p>
                </div>

                <div v-if="hasProgress" class="experience-progress">
                    <span class="subtitle is-7">Mi progreso:</span>
                    <progress class="progress is-primary is-small" v-bind:value="progressPercent" max="100">{{
                        progressPercent }}%
                    </progress>
                    <div class="progress-text">
                        <span class="is-pulled-left subtitle is-7">{{ challengesFinished }} retos de {{ totalChallenges }}</span>
                        <span class="is-pulled-right subtitle is-7">{{ progressPercent }}%</span>
                        <span class="clearfix"></span>
                    </div>

                </div>

                <apithy-experience-card-links
                        :course="course"
                        :enrolled="enrolled_button"
                        :licences="licence()"
                        :in_cart_personal_use="already_in_cart_personal_use"
                        :in_cart_company_use="already_in_cart_company_use"
                >
                </apithy-experience-card-links>

                <div class="experience-collapse-icon is-pulled-right">
                    <span class="icon is-small" @click="toogleCollapse">
                        <i class="fa" v-bind:class="[collapseIcon]"></i>
                    </span>
                </div>
            </div>
        </div>
        <div v-if="hasProgress" class="experience-collapse-progress">
            <div class="collapse-progress" v-bind:style="{width:progressPercent+'%'}"></div>
        </div>
    </div>
</template>

<script>
    import ExperienceCardLinks from './ExperienceCardLinks';

    export default {
        name: 'experience-card',
        components: {
            'apithy-experience-card-links': ExperienceCardLinks
        },
        props: {
            dataExperience: {
                type: Object,
                default: {}
            },
            user: {
                type: Object,
                default: {}
            },
            userId: {
                type: Number,
                default: null
            },
            enrolled: {
                type: Boolean,
                default: false
            }
        },
        beforeMount() {
            if (typeof this.course.features !== 'object') {
                this.course.features = JSON.parse(this.course.features);
            }
        },
        mounted() {
            this.inCart();
        },
        methods: {
            getPeriod(period, value_of_period) {
                if (value_of_period < 2) {
                    return period.substring(0, period.length - 1);
                } else {
                    return period;
                }
            },
            getAbilities(experience) {
                let abilities = [];
                if (experience.type === this.apithy_constants.EXPERIENCE_TYPE_JOURNEY) {
                    if (_.isEmpty(experience.adventures)) {
                        abilities = experience.taxonomy_ability;
                    } else {
                        let sessions = [];

                        _.each(experience.adventures, adventure => {
                            if (_.size(adventure.sessions) > 0) {
                                sessions = _.concat(sessions, adventure.sessions);
                            }
                        });

                        _.each(sessions, session => {
                            if (_.size(session.taxonomy_ability) > 0) {
                                _.each(session.taxonomy_ability, ability => {
                                    abilities.push(ability);
                                })
                            }
                        });

                        if (_.isEmpty(abilities)) {
                            abilities = experience.taxonomy_ability;
                        }
                    }
                } else if (experience.type === this.apithy_constants.EXPERIENCE_TYPE_ADVENTURE) {
                    if (_.isEmpty(experience.sessions)) {
                        abilities = experience.taxonomy_ability;
                    } else {
                        _.each(experience.sessions, session => {
                            if (_.size(session.taxonomy_ability) === 0) {
                                abilities = experience.taxonomy_ability;
                            } else {
                                _.each(session.taxonomy_ability, ability => {
                                    abilities.push(ability);
                                })
                            }
                        });
                    }
                }
                abilities = _.uniqBy(abilities, 'id')
                return abilities;
            },
            purchase() {
                axios({
                    method: 'POST',
                    url: route('shopping-cart.add', {experience: this.dataExperience.system_id}),
                    params: {
                        user: this.auth_user.id
                    }
                }).then((response) => {
                    this.is_cart_added = true;
                    this.inCart();
                    this.$snotify.confirm('¿Ir al checkout?', '¡Experiencia agregada!', {
                        showProgressBar: true,
                        closeOnClick: false,
                        buttons: [
                            {
                                text: 'Si',
                                action: () => this.goToCheckout(),
                            },
                            {
                                text: 'Seguir explorando',
                                action: (toast) => {
                                    this.$snotify.remove(toast.id);
                                }
                            }
                        ]
                    });

                }).catch((error) => {
                    console.log(error);
                });
            },
            inCart() {
                let cookie;
                let re = new RegExp('cart_list' + "=([^;]+)");
                let value = re.exec(document.cookie);
                cookie = (value != null) ? JSON.parse(unescape(value[1])) : null;


                if (cookie) {
                    Object.entries(cookie).map((item) => {
                        if (item[1].id == this.course.id) {
                            this.already_in_cart = true;

                            if(item[1].attributes.personal_use){
                                this.already_in_cart_personal_use = true;
                            }
                            if(item[1].attributes.corporative_use){
                                this.already_in_cart_company_use= true;
                            }
                        }
                    });
                }
            },
            goToCheckout() {
                let origin = window.location.origin;
                let path = '/shopping-cart/checkout';
                let url = origin + path;

                window.location.href = url;
            },
            licence() {
                return Math.floor(Math.random() * (10 - 0) + 0);
            },
            translateType(type) {
                return (type === 'mandatory') ? 'Obligación' : 'Invitación';
            },
            gotToExperience() {
                document.location.href = route('experience.preview', {experience: this.course.system_id});
            },
            continueExperience() {
                let origin = window.location.origin;
                let path = '/experiences/';
                let url = origin + path + this.course.system_id + '/view';

                url = url + '/' + this.lastEnrollment;

                console.log(url);

                window.location.href = url;
            },
            details() {
                document.location.href = route('experience.preview', {experience: this.course.system_id});
            },
            enroll() {
                let vm = this;
                this.loading = true;
                this.$snotify.async('Enrolamiento', 'Procesando enrolamiento', () => new Promise((resolve, reject) => {
                    return axios({
                        method: 'POST',
                        url: route('experience.enroll', {experience: this.course.system_id}),
                        params: {
                            user: this.userId
                        }
                    }).then((response) => {
                        vm.loading = false;
                        resolve({
                            title: response.data.title,
                            body: response.data.message,
                            config: {
                                closeOnClick: true,
                                html: `
                                        <div class="snotifyToast__title">` + response.data.title + `</div>
                                        <div class="snotifyToast__body">` + response.data.message + `</div>
                                        <div class="snotify-icon snotify-icon--success"></div>
                                        `
                            }
                        });

                        this.enrolled_button = true;
                        document.location.href = route('experience.viewer.enroll', {
                            'experience': this.course.system_id,
                            'enrollment_id': response.data.data.id
                        })

                    }).catch((error) => {
                        vm.loading = false;
                        reject({
                            title: 'Error!!!',
                            body: error.response.data.message,
                            config: {
                                closeOnClick: true,
                                html: `
                                        <div class="snotifyToast__title">Error!</div>
                                        <div class="snotifyToast__body">` + error.response.data.message + `</div>
                                        <div class="snotify-icon snotify-icon--success"></div>
                                        `
                            }
                        });
                    });

                }));
            },
            enrollUpdate() {
                let vm = this;
                this.loading = true;
                this.$snotify.async('Enrolamiento', 'Actualizando enrolamiento', () => new Promise((resolve, reject) => {
                    return axios({
                        method: 'PUT',
                        url: route('experience.enroll', {experience: this.course.system_id}),
                        params: {
                            user: this.userId
                        }
                    }).then((response) => {
                        vm.loading = false;
                        resolve({
                            title: response.data.title,
                            body: response.data.message,
                            config: {
                                closeOnClick: true,
                                html: `
                                        <div class="snotifyToast__title">` + response.data.title + `</div>
                                        <div class="snotifyToast__body">` + response.data.message + `</div>
                                        <div class="snotify-icon snotify-icon--success"></div>
                                        `
                            }
                        })

                        this.enrolled_button = false;
                    }).catch((error) => {
                        vm.loading = false;
                        console.log(error);
                        reject({
                            title: 'Error!!!',
                            body: error.response.data.message,
                            config: {
                                closeOnClick: true,
                                html: `
                                        <div class="snotifyToast__title">Error!</div>
                                        <div class="snotifyToast__body">` + response.data.message + `</div>
                                        <div class="snotify-icon snotify-icon--success"></div>
                                        `
                            }
                        })
                    });

                }));
            },
            toogleCollapse() {
                this.$emit('toogleCollapse');
                this.isCollapse = !this.isCollapse;
            }
        },
        computed: {
            hasAbilities() {
                return (!_.isEmpty(this.course.taxonomy_ability))
            },
            collapseIcon() {
                return this.isCollapse ? "fa-angle-down" : "fa-angle-up"
            },
            collapseClass() {
                return this.isCollapse ? "collapse" : "expand"
            },
            challengesFinished() {
                if (this.course.current_user_progress) {
                    return this.course.current_user_progress.finished;
                }
            },
            totalChallenges() {
                if (this.course.current_user_progress) {
                    return this.course.current_user_progress.total;
                }
            },
            progressPercent() {
                if (this.course.current_user_progress) {
                    return this.course.current_user_progress.progress_percent;
                }
            },
            lastEnrollment() {
                let enrollmentId = 0;

                if ('enrollments_data' in this.course.current_user_progress) {
                    if (!_.isEmpty(this.course.current_user_progress.enrollments_data)) {
                        if (this.course.type === this.apithy_constants.EXPERIENCE_TYPE_JOURNEY) {
                            _.each(this.course.current_user_progress.enrollments_data, (enrolledExperience) => {
                                enrolledExperience.forEach((progress) => {
                                    if (progress.status == 2) {
                                        enrollmentId = progress.enrollment_id;
                                    }
                                });
                            });
                        } else {
                            enrollmentId = _.head(this.course.current_user_progress.enrollments_data).enrollment_id;
                        }
                    } else {
                        let last_enrollment_id = _.find(this.user.enrollments, {id: this.course.id}).pivot.id;
                        let last_enrollment_status = _.find(this.user.enrollments, {id: this.course.id}).pivot.status;

                        enrollmentId = last_enrollment_id;
                    }
                }

                /*
                if ('enrollments_data' in this.course.current_user_progress) {
                    if (this.course.current_user_progress.enrollments_data.length) {
                        if (this.course.type == 'journey') {
                            this.course.current_user_progress.enrollments_data.forEach((enrolledExperience) => {
                                enrolledExperience.forEach((progress) => {
                                    if (progress.status == 2) {
                                        enrollmentId = progress.enrollment_id;
                                    }
                                });
                            });

                        } else {
                            enrollmentId = this.course.current_user_progress.enrollments_data[0].enrollment_id;
                        }
                    }
                }
                 */
                return enrollmentId;
            },
            hasProgress() {
                if (('progress_percent' in this.course.current_user_progress)) {
                    return true;
                } else {
                    return false;
                }
            }
        },
        data() {
            return {
                course: this.dataExperience,
                status: 0,
                enrolled_button: this.enrolled,
                auth_user: this.user,
                loading: false,
                isCollapse: true,
                already_in_cart: false,
                already_in_cart_company_use:false,
                already_in_cart_personal_use:false
            };
        }
    }
</script>

<style scoped>
    .apithy-notification {
        position: absolute;
        z-index: 5;
    }

    .card {
        padding-bottom: 10px;
        min-height: 270px !important;
    }

    .card.collapse .card-image {
        height: 200px;
    }

    .experience-carousel-wrapper .card.collapse .card-image {
        height: 150px;
    }

    .card.collapse .card-image-inner {
        position: absolute;
        width: 100%;
        height: 200px;
        overflow: hidden;
    }

    .card.expand .card-image, .card.expand .card-image-inner {
        height: 80px;
        overflow: hidden;
    }

    .card.collapse .card-image-inner {
        position: absolute;
        width: 100%;
        height: 200px;
    }

    .card .media-content {
        height: 50px;
    }

    progress.progress {
        margin-bottom: 10px;
    }

    .progress {
        display: block;
    }

    .progress-text {
        overflow: hidden;
    }

    .card.collapse .experience-progress {
        display: none;
    }

    .fa-angle-down, .fa-angle-up {
        cursor: pointer;
    }

    .card-wrapper.collapse .experience-collapse-progress {
        width: 99%;
        height: 10px;
        top: -10px;
        position: relative;
        border-radius: 0px 0px 20px 20px;
        background-color: transparent;
        overflow: hidden;
        left: 1px
    }

    .card-wrapper.collapse .experience-collapse-progress .collapse-progress {
        height: 10px;
        background-color: orange;
        box-shadow: 1px 1px 7px grey;
    }

    .tag {
        background-color: rgba(245, 166, 35, 0.4) !important;
        color: #000 !important;
        margin-right: 1px;
        font-size: 0.6rem;
    }
</style>