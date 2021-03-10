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
                <div class="card-icon row align-items-center justify-content-center author-image">
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

                <div class="media">
                    <p class="subtitle is-7">Creado por: <a class="primary-color">{{course.company_author.name}}</a></p>
                </div>

                <div class="">
                    <div class="row justify-content-between">
                        <div class="col-auto">
                            <span>
                                <i class="fas fa-users"></i>
                                0
                            </span>
                        </div>
                        <div class="col-auto">
                            {{new Date(course.updated_at).toLocaleDateString()}}
                        </div>
                    </div>
                </div>
                <br>

                <apithy-experience-card-links
                        :course="course"
                        :enrolled="enrolled_button">
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
            'apithy-experience-card-links': ExperienceCardLinks,
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
                document.location.href = route('experience.preview', {experience: this.course.system_id});
            },
            continueExperience(){
                let origin = window.location.origin;
                let path = '/experiences/';
                let url = origin + path + this.course.system_id + '/view';

                url = url + '/' + this.lastEnrollment;

                console.log(url);

                window.location.href = url;
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
            },
            toogleCollapse() {
                this.$emit('toogleCollapse');
                this.isCollapse = !this.isCollapse;
            }
        },
        computed: {
            hasAbilities() {
                return (this.course.abilities.length > 0)
            },
            collapseIcon() {
                return this.isCollapse ? "fa-angle-down" : "fa-angle-up"
            },
            collapseClass() {
                return this.isCollapse ? "expand" : "collapse";
            },
            challengesFinished(){
                if(this.course.current_user_progress){
                    return this.course.current_user_progress.finished;
                }
            },
            totalChallenges(){
                if(this.course.current_user_progress){
                    return this.course.current_user_progress.total;
                }
            },
            progressPercent(){
                if(this.course.current_user_progress){
                    return this.course.current_user_progress.progress_percent;
                }
            },
            lastEnrollment(){
                if(this.course.current_user_progress){
                    console.log(this.course.current_user_progress.enrollments_data[0].enrollment_id);
                    return this.course.current_user_progress.enrollments_data[0].enrollment_id;
                }
            },
            hasProgress(){
                if(('progress_percent' in this.course.current_user_progress) ){
                    return true;
                }else{
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
                isCollapse: true
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
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 4px 5px 10px 0px grey;
    }

    .card.collapse .card-image {
        height: 80px;
    }

    .card.collapse .card-image-inner {
        position: absolute;
        width: 100%;
        height: 80px;
        overflow: hidden;
    }

    .card .media-content {
        height: 65px;
    }

    .card-icon {
        border-radius: 25px;
        position: absolute;
        left: 85%;
    }

    .author-image {
        position: relative;
        width: 40px;
        height: 40px;
        top: -50px;
        box-shadow: 1px 2px 10px 0px grey;
        border-radius: 10px;
        background-color: #FFFFFF;
        overflow: hidden;
    }
    .author-image img {
        max-width: 100%;
        max-height: 100%;
    }

    progress.progress {
        margin-bottom: 10px;
    }

    .progress {
        display: block;
    }

    .progress-text{
        overflow: hidden;
    }

    .card.collapse .experience-progress {
        display: none;
    }
    
    .fa-angle-down, .fa-angle-up{
        cursor: pointer;
    }

    .card-wrapper.collapse .experience-collapse-progress {
        width: 99%;
        height: 10px;
        top: -10px;
        position: relative;
        border-radius: 0px 0px 20px 20px;
        background-color: transparent;
        overflow:hidden;
        left:1px
    }

    .card-wrapper.collapse .experience-collapse-progress .collapse-progress {
        height: 10px;
        background-color: orange;
        box-shadow: 1px 1px 7px grey;
    }

</style>