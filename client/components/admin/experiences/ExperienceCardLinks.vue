<template>
    <div class="">
        <div class="row">
            <div class="col-12 col-md-6">
                <a class="button is-outlined is-link width-100">
                    <span class="icon">
                        <i class="fas fa-eye"></i>
                    </span>
                    <span>{{ $t('messages.show') }}</span>
                </a>
            </div>
            <div class="col-12 col-md-6">
                <a class="button is-outlined is-link width-100" @click="edit">
                    <span class="icon">
                        <i class="fa fa-pencil"></i>
                    </span>
                    <span>{{ $t('messages.edit') }}</span>
                </a>
            </div>
        </div>
        <br>
        <div class="row pb-2">
            <div class="col-12">
                <button
                    class="button is-outlined apithy-color-admin text-white width-100"
                    type="button"
                    @click="openInEditor">
                    <span class="icon">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                    </span>
                    <span>Abrir en editor</span>
                </button>
            </div>
        </div>
        <div class="row pb-2">
            <div class="col-12">
                <button
                    class="button is-outlined apithy-color-admin text-white width-100"
                    type="button"
                    @click="confirmClearCache">
                    <span class="icon">
                        <i class="fa fa-recycle" aria-hidden="true"></i>
                    </span>
                    <span>Limpiar cache de sesiones</span>
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <a class="button is-outlined apithy-color-desactivate text-white width-100">
                    <span class="icon">
                        <i class="fas fa-toggle-off"></i>
                    </span>
                    <span>{{ $t('messages.desactivate') }}</span>
                </a>
            </div>
        </div>
        <div class="row m-top-10">
            <div class="col-12">
                <a class="button is-outlined apithy-color-eliminate text-white width-100" @click="destroy">
                    <span class="icon">
                        <i class="fas fa-trash"></i>
                    </span>
                    <span>{{ $t('messages.delete') }}</span>
                </a>
            </div>
        </div>
    </div>
    <!--div>
        <div class="card-links" v-if="assignation">
            <a class="primary-color is-bold is-uppercase" v-bind:href="route('experience.preview',{'experience':course.system_id})">
                <i class="eye icon"></i>
                Detalles
            </a>
        </div>
        <div class="card-links" v-else>
            <a class="primary-color is-bold is-uppercase" v-bind:href="route('experience.preview',{'experience':course.system_id})"  @click.stop="$parent.details">
                Detalles
            </a>
            <a class="primary-color is-bold is-uppercase" v-bind:href="route('experience.viewer.enroll',{'experience':course.system_id,'enrollment_id': lastEnrollmentId })" v-bind:class="{ loading: $parent.loading }" v-if="$parent.enrolled_button"
               @click.stop="$parent.continueExperience">
                Continuar
            </a>

        </div>
    </div-->
</template>

<script>
    export default {
        name: "ExperienceCardLinks",
        props: {
            enrolled: {
                type: Boolean,
                default: false
            },
            course: {
                type: Object,
                required: true
            }
        },
        computed: {
            assignation() {
                if (this.course.assignation && this.course.assignation.type === 'mandatory') {
                    return true;
                }
                return false;
            },
            lastEnrollmentId(){
                if(this.course.current_user_progress.length){
                    return this.course.current_user_progress.enrollments_data[0].enrollment_id;
                }
            }
        },
        methods: {
            confirmClearCache () {
                this.$snotify.confirm(
                    'Limpiar cache de sesiones de experiencia',
                    '¿Limpiar la cache de las sesiones de esta experiencia?',
                    {
                        howProgressBar: true,
                        closeOnClick: false,
                        pauseOnHover: true,
                        backdrop: 0.6,
                        buttons: [
                            {
                                text: 'Si', action: (toast) => {
                                    this.clearSessionCache(toast)
                                }
                            },
                            {
                                text: 'No', action: (toast) => {this.$snotify.remove(toast.id)}
                            },
                        ]
                    }
                )
            },
            clearSessionCache (toast) {
                this.$snotify.remove(toast.id)
                this.$snotify.async('Limpiando cache', 'Procesando peticion', () => new Promise((resolve, reject) => {
                    axios.post(route('experience-session.clear.cache', this.$parent.dataExperience.id))
                        .then(response => {
                            resolve({
                                title: 'Petición exitosa',
                                body: 'Petición procesada con éxito.',
                                config: {
                                    timeout: 2000,
                                    backdrop: 0.6,
                                    closeOnClick: true,
                                    html: `
                                      <div class="snotifyToast__title">Petición exitosa</div>
                                      <div class="snotifyToast__body">Petición procesada con éxito.</div>
                                      <div class="snotify-icon snotify-icon--success"></div>
                                      `
                                }
                            });
                        })
                        .catch(errors => {
                            reject({
                                title: 'Error!!',
                                body: 'No se pudo procesar la petición correctamente.',
                                config: {
                                    closeOnClick: true,
                                    timeout: 2000,
                                    backdrop: 0.6,
                                    html: `
                                     <div class="snotifyToast__title">Error!!</div>
                                      <div class="snotifyToast__body">No se pudo procesar la petición correctamente.</div>
                                      <div class="snotify-icon snotify-icon--error"></div>
                                    `
                                }
                            });
                        })
                }), {backdrop: 0.6})
            },
            openInEditor() {
                window.location.href= route('experiences.edit.editor',[this.$parent.dataExperience.system_id]).toString();
            },
            edit() {
                window.location.href= route('experiences.edit',[this.$parent.dataExperience.system_id]).toString();
            },
            destroy() {
                this.$snotify.async(this.$t('experiences.delete'), 'Procesando', () => new Promise((resolve, reject) => {
                    axios({
                        url: route('experiences.destroy',[this.$parent.dataExperience]),
                        method: 'DELETE'
                    })
                        .then(response => {
                            resolve({
                                title: 'Petición exitosa',
                                body: 'Guardados los cambios con éxito.',
                                config: {
                                    closeOnClick: true,
                                    html: `
                                        <div class="snotifyToast__title">Petición exitosa</div>
                                        <div class="snotifyToast__body">Guardados los cambios con éxito.</div>
                                        <div class="snotify-icon snotify-icon--success"></div>
                                        `
                                }
                            });
                        })
                        .catch((error) => {
                            console.log(error.response.data);
                            reject({
                                title: 'Error!!',
                                body: 'No se pudo procesar la petición correctamente.',
                                config: {
                                    closeOnClick: true,
                                    html: `
                                         <div class="snotifyToast__title">Error!!</div>
                                        <div class="snotifyToast__body">No se pudo procesar la petición correctamente.</div>
                                        <div class="snotify-icon snotify-icon--error"></div>
                                        `
                                }
                            });
                        })

                }));
            }
        }
    }
</script>
