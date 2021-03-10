<template>
    <div class="container block-centered">
        <hr width="2">
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <span class="font-20 has-text-weight-bold">
                        Datos del área
                    </span>
                </div>
                <br>
                <div class="row font-14">
                    <div class="col-12 col-lg-4">
                        <label class="has-text-weight-bold">Nombre</label>
                        <b-input icon-pack="fas" icon="sitemap" placeholder="Escribe un nombre" v-model="form.name"></b-input>
                    </div>
                    <div class="col-12 col-lg-4">
                        <label class="has-text-weight-bold">Nombre corto</label>
                        <b-input icon-pack="fas" icon="sitemap" placeholder="Escribe un nombre corto" v-model="form.short_name"></b-input>
                    </div>
                    <div class="col-12 col-lg-4">
                        <label class="has-text-weight-bold">Pertenece a</label>
                        <div class="select width-100">
                            <select class="width-100" name="parent_id" v-model="form.parent_id">
                                <option value="">Ninguno</option>
                                <option v-for="area in areas" :value="area.id">{{ area.name }}</option>
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
                <br><br><br>
                <div class="">
                    <div class="row">
                    <span class="font-20 has-text-weight-bold">
                        Puestos del área
                    </span>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-12">
                            <b-taglist>
                                <b-tag type="is-info"
                                       closable
                                       @close="removePosition(index)"
                                       v-for="(item,index) in form.positions" :key="index">
                                    {{ item.name }}
                                </b-tag>
                            </b-taglist>
                        </div>
                    </div>
                    <br>
                    <div class="row font-14">
                        <div class="col-12 col-lg-4">
                            <label class="has-text-weight-bold">Nombre</label>
                            <b-input icon-pack="fas" icon="address-card" placeholder="Escribe un nombre" v-model="position_form.name" :disabled="empty_area"></b-input>
                        </div>
                        <div class="col-12 col-lg-4">
                            <label class="has-text-weight-bold">Nombre corto</label>
                            <b-input icon-pack="fas" icon="address-card" placeholder="Escribe un nombre corto" v-model="position_form.short_name" :disabled="empty_area"></b-input>
                        </div>
                        <div class="col-12 col-lg-4">
                            <label class="has-text-weight-bold">Responde a</label>
                            <div class="select width-100">
                                <select class="width-100" name="parent_id" v-model="position_form.parent_id" :disabled="empty_area">
                                    <option :value="null">Ninguno</option>
                                    <option v-for="position in form.positions" :value="position.name">{{ position.name }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row font-14">
                        <div class="col-12">
                            <label class="has-text-weight-bold">Descripción</label>
                            <textarea class="input area-description" v-model="position_form.description" :disabled="empty_area"></textarea>
                        </div>
                    </div>
                    <br>
                    <div class="row justify-content-center">
                        <div class="col-2">
                            <button class="button is-link width-100" :disabled="empty_position" @click="addPosition">Añadir</button>
                        </div>
                    </div>
                </div>
                <br><br><br>
                <div class="row justify-content-center">
                    <div class="col-2">
                        <button class="button is-link width-100" @click="sendForm" :disabled="empty_area">Guardar</button>
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
    import qs from 'qs';

    export default {
        name: "AreasCreate",
        props: {
            authUser: {
                type: Object,
                required: true
            },
            areas: {
                type: Array,
                required: true
            },
            company: {
                type: Object,
                required: true
            }
        },
        computed: {
            empty_area: function () {
                return !(this.form.name && this.form.short_name);
            },
            empty_position: function () {
                return !(this.position_form.name && this.position_form.short_name);
            },
        },
        methods:{
            addPosition() {
                let positions = this.form.positions;
                let new_position = this.position_form;

                if (new_position) {
                    positions.push(new_position);
                    this.position_form = {
                        name: null,
                        short_name: null,
                        parent_id: null,
                        description: null,
                        company_id: this.company.id,
                    };
                }
            },
            removePosition(index) {
                let positions = this.form.positions;
                positions.splice(index, 1);
            },
            sendForm() {
                this.$snotify.async('Área', 'Procesando', () => new Promise((resolve, reject) => {
                    return axios({
                        method: 'POST',
                        url: route('areas.store', [this.company.id]),
                        params: this.form,
                        paramsSerializer: function(params) {
                            return qs.stringify(params, { encode: false });
                        }
                    })
                        .then((response) => {
                            resolve({
                                title: 'Petición exitosa',
                                body: 'Área creada con éxito.',
                                config: {
                                    closeOnClick: true,
                                    html: `
                                    <div class="snotifyToast__title">Petición exitosa</div>
                                    <div class="snotifyToast__body">Área creada con éxito.</div>
                                    <div class="snotify-icon snotify-icon--success"></div>
                                    `
                                }
                            });
                            setTimeout(window.location.href = route('areas.index',[this.company.id]),1000);
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
                    positions: []
                };
                this.position_form = {
                    name: null,
                    short_name: null,
                    parent_id: '',
                    description: null,
                    company_id: this.company.id,
                };
                window.location.href = route('areas.index',[this.company.id]);
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
                    positions: []
                },
                position_form: {
                    name: null,
                    short_name: null,
                    parent_id: null,
                    description: null,
                    company_id: this.company.id,
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