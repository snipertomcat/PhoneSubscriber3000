<template>
    <div class="">
        <button class="button is-outlined is-link width-100" @click="edit">
            <i class="fa fa-pencil d-block d-lg-none"></i>
            <span class="icon d-none d-lg-block">
                <i class="fa fa-pencil"></i>
            </span>
            <span class="d-none d-lg-block">{{ $t('messages.edit') }}</span>
        </button>
        <b-modal :active.sync="modal.visible" has-modal-card>
            <div class="card">
                <div class="card-content">
                    <div class="row">
                        <div class="col-12 has-text-centered">
                            <span class="has-text-weight-bold">
                            {{ $t('messages.edit') }} {{ $t('messages.tag') }}
                        </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label>{{ $t('messages.tag') }}</label>
                            <input type="text" class="input width-100" placeholder="Nombre" v-model="tag.title">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <a class="button is-outlined is-link width-100" @click="update">
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
        name: "EditTag",
        props:{
            id: {
                type: Number,
                required: true
            }
        },
        methods: {
            edit() {
                axios({
                    url: route('tags.show',[this.id]),
                    method: 'GET',
                })
                    .then((response) => {
                        this.tag = response.data;
                        this.modal.visible = true;
                    })
                    .catch((error) => {
                        window.alert('Ocurrió un problema al cargar la habilidad');
                        console.log(error.response.data);
                    });
            },
            update() {
                this.$snotify.async('Empresa', 'Procesando', () => new Promise((resolve, reject) => {
                    return axios({
                        method: 'PUT',
                        url: route('tags.update',[this.id]),
                        params: this.tag,
                    }).then((response) => {
                        // Do somethings
                        this.modal.visible = false;
                        this.$parent.refresh();
                        this.reset();
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
                    }).catch((error) => {
                        // Do somethings
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
                    });
                }));
            },
            reset() {
                this.tag = {
                    title: '',
                    company_id: ''
                };
            },
            cancel() {
                this.modal.visible = false;
                this.reset();
            }
        },
        data() {
            return {
                tag: {
                    title: '',
                    company_id: ''
                },
                modal: {
                    visible: false,
                    name: 'edit-tag-'+this.id,
                }
            };
        }
    }
</script>

<style scoped>

</style>