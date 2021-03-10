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
                        <b-input icon-pack="fas" icon="address-card" placeholder="Escribe un nombre" v-model="form.name" :disabled="!editing"></b-input>
                    </div>
                    <div class="col-12 col-lg-4">
                        <label class="has-text-weight-bold">Nombre corto</label>
                        <b-input icon-pack="fas" icon="address-card" placeholder="Escribe un nombre corto" v-model="form.short_name" :disabled="!editing"></b-input>
                    </div>
                    <div class="col-12 col-lg-4">
                        <label class="has-text-weight-bold">Pertenece a</label>
                        <div class="select width-100">
                            <select class="width-100" name="parent_id" v-model="form.parent_id" :disabled="!editing">
                                <option :value="null">Ninguno</option>
                                <option v-for="position in positions" :value="position.id">{{ position.name }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row font-14">
                    <div class="col-12">
                        <label class="has-text-weight-bold">Descripción</label>
                        <textarea class="input area-description" v-model="form.description" :disabled="!editing"></textarea>
                    </div>
                </div>
                <hr width="1">
                <div class="row justify-content-center">
                    <div class="col-6 col-md-3">
                        <button class="button is-link width-100" @click="doAction">{{get_label}}</button>
                    </div>
                    <div class="col-6 col-md-3" v-if="editing">
                        <button class="button is-link width-100" @click="cancel">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "PositionsView",
        props: {
            startEditing: {
                type: Boolean,
                required: false,
                default: false
            },
            company: {
                type: Object,
                required: true
            },
            area: {
                type: Object,
                 required: false
            },
            positions: {
                type: Array,
                required: true
            },
            positionData: {
                type: Object,
                required: true
            }
        },
        computed: {
            get_label: function () {
                let label;
                if (this.editing) {
                    label = "Guardar";
                }
                else {
                    label = "Editar";
                }
                return label;
            }
        },
        methods: {
            doAction() {
                if (this.editing) {
                    this.save()
                }
                else {
                    this.editing = true;
                }
            },
            save() {
                this.$snotify.async('Puesto', 'Procesando', () => new Promise((resolve, reject) => {
                    return axios({
                        method: 'POST',
                        url: route('positions.update',[this.form.area.company_id, this.form.area_id, this.form.id]),
                        params: this.form,
                    }).then((response) => {
                        this.editing = false;
                        this.form = response.data;
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
                    }).catch((error) => {
                        this.editing = false;
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
                this.form = this.positionData;
                this.editing = false;
            }
        },
        data() {
            return {
                form: this.positionData,
                editing: this.startEditing,
            };
        }
    }
</script>

<style scoped>
    .area-description {
        height: 75px;
    }
</style>