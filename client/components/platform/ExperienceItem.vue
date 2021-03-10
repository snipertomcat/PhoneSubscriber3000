<template>
    <div class="col-sm-3">
        <div class="card">
            <div v-if="(course.assignation)" :class="{
                'ui label apithy-notification': true,
                'yellow':course.assignation.type === 'mandatory',
                'blue':course.assignation.type === 'invitation'
             }"
                 style="border-radius: .28571429rem 0 !important;">
                {{ translateType(course.assignation.type) }}
            </div>
            <div class="card-image" @click="gotToExperience">
                <figure class="image is-4by3">
                    <img v-lazy="course.full_path_cover" preLoad>
                </figure>
            </div>

            


            <div class="content">
                <div class="header">{{ course.title }}</div>
                <div class="description" v-html="course.summary">
                    {{ course.summary }}
                </div>
            </div>
            <div class="extra content" v-if="hasAbilities">
                <div class="meta">
                <span class="ui label" v-for="(ability,index) in course.abilities"
                      :key="index">{{ ability.title }}</span>
                </div>
            </div>
            <div class="extra content">
            <span class="right floated">
                {{ course.created_at }}
            </span>
                <span>
                <i class="user icon"></i>
                {{ course.current_company_enrollments }}
            </span>
            </div>
            <experience-enroll-buttons :course="course" :enrolled="enrolled_button"></experience-enroll-buttons>
            <modal
                    height="auto"
                    width="75%"
                    :click-to-close="false"
                    :name="'experience-item-'+course.id">
                <div class="iframe-experience-wrapper ui segments">
                    <div class="iframe-experience-header ui segment">
                        <img src="/images/apithy_small.png" width="30">
                        <span class="iframe-experience-header-title">{{course.title}}</span>
                        <button class="ui red icon button right floated close-modal"
                                @click="closeModal('experience-item-'+course.id)"><i
                                class="ui cancel icon"></i>
                        </button>
                    </div>
                    <div class="iframe-experience-content ui segment">
                        <ExperiencePreview :auth_user="auth_user" :experience="course"></ExperiencePreview>
                    </div>
                </div>
            </modal>
        </div>
    </div>
</template>

<script>
    import ExperiencePreview from './ExperiencePreview';
    import ExperienceEnrollButtons from './ExperienceEnrollButtons';

    export default {
        name: 'experience-item',
        components: {
            ExperiencePreview,
            ExperienceEnrollButtons,
            'experience-preview': ExperiencePreview
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
        methods: {

            translateType(type) {
                return (type === 'mandatory') ? 'Obligación' : 'Invitación';
            },
            gotToExperience() {
                document.location.href = route('experiences.show', {experience: this.course.system_id});
            },
            details() {
                this.showModal('experience-item-' + this.course.id);
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
            }
        },
        computed: {
            hasAbilities() {
                return (this.course.abilities.length > 0)
            }
        },
        data() {
            return {
                course: this.dataExperience,
                status: 0,
                enrolled_button: this.enrolled,
                auth_user: this.user,
                loading: false
            };
        }
    }
</script>

<style scoped>
    .apithy-notification {
        position: absolute;
        z-index: 5;
    }
</style>