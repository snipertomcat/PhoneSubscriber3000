<template>
    <div class="container block-centered">
        <hr width="2">
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <span class="font-20 has-text-weight-bold">
                        Datos del puesto
                    </span>
                </div>
                <br>
                <div class="row font-14">
                    <div class="col-12 col-lg-4">
                        <label class="has-text-weight-bold">Nombre</label>
                        <b-input icon-pack="fas" icon="address-card" placeholder="Escribe un nombre" v-model="form.name"></b-input>
                    </div>
                    <div class="col-12 col-lg-4">
                        <label class="has-text-weight-bold">Nombre corto</label>
                        <b-input icon-pack="fas" icon="address-card" placeholder="Escribe un nombre corto" v-model="form.short_name"></b-input>
                    </div>
                    <div class="col-12 col-lg-4">
                        <label class="has-text-weight-bold">Responde a</label>
                        <div class="select width-100">
                            <select class="width-100" name="parent_id" v-model="form.parent_id">
                                <option value="">Ninguno</option>
                                <option v-for="position in positions" :value="position.id">{{ position.name }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row font-14">
                    <div class="col-12">
                        <label class="has-text-weight-bold">Descripción</label>
                        <textarea class="input area-description" v-model="form.description"></textarea>
                    </div>
                </div>
                <hr width="1">
                <div class="row justify-content-center">
                    <div class="col-2">
                        <button class="button is-link width-100" @click="sendForm">Guardar</button>
                    </div>
                    <div class="col-2">
                        <button class="button is-link width-100" @click="cancel">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "PositionsCreate",
        props: {
            authUser: {
                type: Object,
                required: true
            },
            company: {
                type: Object,
                required: true
            },
            area: {
                type: Object,
                required: true
            },
            positions: {
                type: Array,
                required: true
            }
        },
        methods:{
            sendForm() {
                this.$snotify.async('Puesto', 'Procesando', () => new Promise((resolve, reject) => {
                    return axios({
                        method: 'POST',
                        url: route('positions.store', [this.company.id,this.area.id]),
                        params: this.form,
                    })
                        .then((response) => {
                            resolve({
                                title: 'Petición exitosa',
                                body: 'Puesto creado con éxito.',
                                config: {
                                    closeOnClick: true,
                                    html: `
                                    <div class="snotifyToast__title">Petición exitosa</div>
                                    <div class="snotifyToast__body">Puesto creado con éxito.</div>
                                    <div class="snotify-icon snotify-icon--success"></div>
                                    `
                                }
                            });

                            setTimeout(window.location.href = route('areas.show',[this.company.id,this.area.id]), 1000);
                        })
                        .catch((error) => {
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
            cancel() {
                this.form = {
                    name: null,
                    short_name: null,
                    parent_id: '',
                    description: null,
                    company_id: this.company.id,
                    area_id: this.area.id,
                };
                window.location.href = route('areas.show',[this.company.id,this.area.id]);
            }
        },
        data() {
            return {
                form: {
                    name: null,
                    short_name: null,
                    parent_id: '',
                    description: null,
                    company_id: this.company.id,
                    area_id: this.area.id,
                }
            };
        }
    }
</script>

<style scoped>
    .area-description {
        height: 75px;
    }
</style>