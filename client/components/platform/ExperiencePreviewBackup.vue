<template>
    <div id="experience-detail"
         class="ui segment no-shadows no-borders sixteen wide tablet ten wide computer column centered">
        <div class="extra content">
            <div class="description" v-html="experience.summary">
                {{ experience.summary }}
            </div>

            <div class="meta abilities">
                <span class="ui label"
                      v-for="(ability,index) in experience.abilities">{{ ability.title }}</span>
            </div>
        </div>
        <div class="ui grid row">
            <div class="sixteen wide tablet eight wide computer column centered">
                <div class="ui segments">
                    <div v-if="!experience.video_intro" class="ui segment">
                        <div class="ui centered fluid rounded image">
                            <img v-lazy="experience.full_path_cover">
                        </div>
                    </div>

                    <div v-if="experience.video_intro" class="ui segment">
                        <div class="ui centered fluid">
                            <iframe v-bind:src="experience.video_intro" width="100%" height="100%"
                                    frameBorder="0"></iframe>
                        </div>
                    </div>

                    <div class="ui segment">
                        <div class="description" v-html="experience.description">
                            {{ experience.description }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="ui segment no-shadows no-borders sixteen wide tablet eight wide computer column centered">
                <h3 class="ui header">
                    <div class="header">Sesiones</div>
                </h3>
                <div class="row" v-if="experience.sessions">
                    <div class="sixteen wide column">
                        <div class="ui">
                            <div class="ui">
                                <div class="ui feed timeline">
                                    <div class="event" v-for="(session,index) in experience.sessions"
                                         :id="'session_'+session.id">
                                        <div class="label">
                                            <img v-lazy="session.full_path_cover" preLoad alt="label-image"/>
                                        </div>
                                        <div class="content ui segment">
                                            <div class="summary">
                                                {{ session.title }}
                                                <div class="date" v-html="session.summary">
                                                    {{ session.summary }}
                                                </div>
                                                <div v-if="session.current_enrollment_progress">
                                                    <span class="ui label"
                                                          v-bind:class="[session.current_enrollment_progress.status_text_color]">
                                                        {{session.current_enrollment_progress.status_text}}
                                                    </span>
                                                    <span v-if="session.current_enrollment_progress.stats"
                                                          v-for="stat in session.current_enrollment_progress.stats "
                                                          class="ui label gray ">
                                                          <strong>{{ stat.verb  | capitalize }}</strong> : {{stat.total}}
                                                    </span>
                                                </div>
                                                <div v-if="index==0">
                                                    <button class="ui blue labeled icon button floated"
                                                            @click="showModal('experience-item-'+session.id)"><i
                                                            class="ui eye icon"></i>Vista Previa
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="modal-container">
                                                <modal
                                                        height="90%"
                                                        width="75%"
                                                        :click-to-close="false"
                                                        :name="'experience-item-'+session.id"
                                                >
                                                    <div class="iframe-experience-wrapper ui segments">
                                                        <div class="iframe-experience-header ui segment">
                                                            <img src="/images/apithy_small.png" width="30">
                                                            <span class="iframe-experience-header-title">
                                                                {{session.title}}
                                                            </span>
                                                            <button class="ui red icon button right floated close-modal"
                                                                    @click="closeModalItem(session.id)"><i
                                                                    class="ui cancel icon"></i>
                                                            </button>
                                                        </div>
                                                        <div class="iframe-experience-content ui segment">
                                                            <iframe class="iframe-experience-item"
                                                                    :src="session.resource_url+'?track_events=true&env=dev'
                                                            +'&experience='+experience.id
                                                            +'&user='+auth_user.id
                                                            +'&session='+session.id
                                                            +(session.current_enrollment_progress  ? '&enrollment_progress='+session.current_enrollment_progress.id:'')"
                                                                    width="100%" v-bind:height="windowHeight" frameBorder="0">
                                                            </iframe>
                                                        </div>
                                                    </div>
                                                </modal>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <h3 v-else>Aun no tienes Sesiones</h3>
                <div>
                    <div  v-if="$parent.$parent.enrolled_button">
                        <button v-bind:class="{ loading: loading }"  class="ui red labeled icon button right floated"
                                @click="enrollUpdate">
                            <i class="ui calendar times icon"></i>
                            Abandonar
                        </button>
                        <button class="ui green labeled icon button right floated"@click="gotToExperience">
                            <i class="paper plane icon"></i>
                            Ir a la experiencia
                        </button>
                    </div>
                    <div v-else>
                    <button v-bind:class="{ loading: loading }" class="ui blue labeled icon button right floated"  @click="enroll">
                        <i class="calendar plus outline icon"></i>
                        Enroll
                    </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        name: 'apithy-experience-preview',
        props: {
            mode: "",
            experience: "",
            auth_user: "",
            companies_data: "",
            enrollment_progress: ""
        },
        beforeMount() {
            this.hasEnrolled();
        },
        computed:{
            windowWidth(){
                return  window.innerWidth;
            },
            windowHeight(){
                return  window.innerHeight-200;
            }
        },
        methods: {
            gotToExperience() {
                document.location.href = route('experiences.show', {experience: this.experience.system_id});
            },
            showModalItem(session_id) {
                this.$modal.show('experience-item-' + session_id)
            },
            closeModalItem(session_id) {
                this.$nextTick(() => {
                    this.$modal.hide('experience-item-' + session_id)
                })
            },
            enroll() {
                let vm=this;
                this.loading=true;
                this.$snotify.async('Enrolamiento', 'Procesando Enrolamiento', () => new Promise((resolve, reject) => {
                    return axios({
                        method: 'POST',
                        url: route('experience.enroll', {experience: this.experience.system_id}),
                        params: {
                            user: this.auth_user.id
                        }
                    }).then((response) => {

                        vm.loading=false;
                        resolve({
                            title: response.data.title,
                            body: response.data.message,
                            config: {
                                closeOnClick: true,
                                html : `
                                        <div class="snotifyToast__title">`+response.data.title+`</div>
                                        <div class="snotifyToast__body">`+response.data.message+`</div>
                                        <div class="snotify-icon snotify-icon--success"></div>
                                        `
                            }
                        })

                        this.$parent.$parent.enrolled_button = true;
                    }).catch((error) => {
                        vm.loading=false;
                        console.log(error);
                        reject({
                            title: 'Error!!!',
                            body: error.response.data.message,
                            config: {
                                closeOnClick: true,
                                html : `
                                        <div class="snotifyToast__title">`+error.data.title+`</div>
                                        <div class="snotifyToast__body">`+error.data.message+`</div>
                                        <div class="snotify-icon snotify-icon--success"></div>
                                        `
                            }
                        })
                    });

                }));
            },
            enrollUpdate() {
                let vm=this;
                this.loading=true;
                this.$snotify.async('Enrolamiento', 'Actualizando Enrolamiento', () => new Promise((resolve, reject) => {
                    return axios({
                        method: 'PUT',
                        url: route('experience.enroll', {experience: this.experience.system_id}),
                        params: {
                            user: this.auth_user.id
                        }
                    }).then((response) => {
                        vm.loading=false;
                        resolve({
                            title: response.data.title,
                            body: response.data.message,
                            config: {
                                closeOnClick: true,
                                html : `
                                        <div class="snotifyToast__title">`+response.data.title+`</div>
                                        <div class="snotifyToast__body">`+response.data.message+`</div>
                                        <div class="snotify-icon snotify-icon--success"></div>
                                        `
                            }
                        })

                        this.$parent.$parent.enrolled_button = false;
                    }).catch((error) => {
                        vm.loading=false;
                        console.log(error);
                        reject({
                            title: 'Error!!!',
                            body: error.response.data.message,
                            config: {
                                closeOnClick: true,
                                html:`
                                        <div class="snotifyToast__title">`+response.data.title+`</div>
                                        <div class="snotifyToast__body">`+response.data.message+`</div>
                                        <div class="snotify-icon snotify-icon--success"></div>
                                        `
                            }
                        })
                    });

                }));
            },
            hasEnrolled() {
                return this.$parent.$parent.enrolled_button;
            }
        },
        data() {
            return {
                visibility: ('visibility' in this.experience) ? (this.experience.visibility) : 1,
                companies: [],
                areas: [],
                positions: [],
                enrolled_button: this.$parent.$parent.enrolled_button,
                loading:false
            }
        }
    }
</script>
