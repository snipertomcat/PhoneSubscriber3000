<template>
    <div class="row">
        <a class="button is-outlined is-link width-100" @click="modal.visible = true">
            <span class="icon">
                <i class="fas fa-plus"></i>
            </span>
            <span>{{ $t('messages.create_new') }}</span>
        </a>
        <b-modal :active.sync="modal.visible" has-modal-card>
            <div class="card">
                <div class="card-content">
                    <div class="row">
                        <div class="col-12 has-text-centered">
                            <span class="has-text-weight-bold">
                            {{ $t('messages.create_new') }} {{ $t('messages.tag') }}
                        </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label>{{ $t('messages.tag') }}</label>
                            <input type="text" class="input width-100" placeholder="Nombre" v-model="form.title">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <a class="button is-outlined is-link width-100" @click="send">
                                <span class="icon"><i class="fas fa-save"></i></span>
                                <span>{{ $t('messages.save') }}</span>
                            </a>
                        </div>
                        <div class="col-12 col-md-6">
                            <a class="button is-outlined is-link width-100" @click="cancel">
                                <span>{{ $t('messages.cancel') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </b-modal>
    </div>
</template>

<script>
    export default {
        name: "CreateTag",
        methods: {
            send() {
                this.$snotify.async(this.$t('messages.tag'), 'Procesando', () => new Promise((resolve, reject) => {
                    axios({
                        url: route('tags.store'),
                        method: 'POST',
                        params: this.form
                    })
                        .then((response) => {
                            this.reset();
                            this.$parent.refresh();
                            resolve({
                                title: 'Petición exitosa',
                                body: 'Guardados los cambios con éxito.',
                                config: {
                                    timeout: 2000,
                                    showProgressBar: true,
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
            },
            reset() {
                this.form = {
                    title: '',
                    company_id: this.company_id
                };
            },
            cancel() {
                this.modal.visible = false;
                this.reset();
            }
        },
        computed: {
            company_id() {
                console.log(this.$root.$refs.adminNav.$props.user.company_system);
                return this.$root.$refs.adminNav.$props.user.company_system;
            }
        },
        data() {
            return {
                form: {
                    title: '',
                    company_id: this.company_id,
                },
                modal: {
                    visible: false
                }
            };
        }
    }
</script>

<style scoped>

</style>