<template>
    <div class="container">
        <hr width="2">
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <span class="col-12 col-md-1 font-18 has-text-weight-bold">Datos</span>
                </div>
                <br>
                <div class="row font-14">
                    <div class="col-12 col-md-4">
                        <label class="">Nombre</label>
                        <b-input icon-pack="fas"
                                 icon="building"
                               name="name"
                               placeholder="Escribe un nombre"
                               v-model="form.name"
                                 v-validate="validations.name"></b-input>
                        <span class="has-text-danger" v-show="errors.has('name')">{{ errors.first('name') }}</span>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="">Nombre corto</label>
                        <b-input icon-pack="fas"
                                 icon="building"
                               name="short_name"
                               placeholder="Escribe un nombre corto"
                               v-model="form.short_name"
                                 v-validate="validations.short_name"></b-input>
                        <span class="has-text-danger" v-show="errors.has('short_name')">{{ errors.first('short_name') }}</span>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="">Nombre legal</label>
                        <b-input icon-pack="fas"
                                 icon="building"
                               name="legal_name"
                               placeholder="Escribe un nombre legal"
                               v-model="form.legal_name"
                                 v-validate="validations.legal_name"></b-input>
                        <span class="has-text-danger" v-show="errors.has('legal_name')">{{ errors.first('legal_name') }}</span>
                    </div>
                </div>
                <br>
                <div class="row font-14">
                    <div class="col-12 col-md-4">
                        <label class="">Nombre de la cuenta</label>
                        <b-input icon-pack="fas"
                                 icon="user"
                               name="account_name"
                               placeholder="Escribe un nombre de cuenta"
                               v-model="form.account_name"
                                 v-validate="validations.account_name"></b-input>
                        <span class="has-text-danger" v-show="errors.has('account_name')">{{ errors.first('account_name') }}</span>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="">País</label>
                        <div class="select width-100">
                            <select name="country_id"
                                    class="width-100"
                                    v-model="form.country_id"
                                    v-validate="validations.country_id">
                                <option value="">País</option>
                                <option :value="country.id" v-for="country in countries">{{ country.name }}</option>
                            </select>
                        </div>
                        <span class="has-text-danger" v-show="errors.has('country_id')">{{ errors.first('country_id') }}</span>
                    </div>
                </div>
                <hr width="1">
                <div class="row">
                    <span class="col-12 font-18 has-text-weight-bold">Contacto</span>
                </div>
                <br>
                <div class="row font-14">
                    <div class="col-12 col-sm-4">
                        <label>Sitio web</label>
                        <b-input icon-pack="fas"
                                 icon="globe-americas"
                               name="site"
                               placeholder="Escribe un sitio web"
                               v-model="form.site_url"
                                 v-validate="validations.site"></b-input>
                        <span class="has-text-danger" v-show="errors.has('site')">{{ errors.first('site') }}</span>
                    </div>
                    <div class="col-12 col-sm-4">
                        <label>Email de contacto</label>
                        <b-input icon-pack="fas"
                                 icon="at"
                               name="contact_email"
                               placeholder="Escribe un email de contacto"
                               v-model="form.contact_email"
                                 v-validate="validations.contact_email"></b-input>
                        <span class="has-text-danger" v-show="errors.has('contact_email')">{{ errors.first('contact_email') }}</span>
                    </div>
                    <div class="col-12 col-sm-4">
                        <label>Teléfono de contacto</label>
                        <b-input icon-pack="fas"
                                 icon="phone"
                               name="contact_phone"
                               placeholder="Escribe un teléfono de contacto"
                               v-model="form.contact_phone"
                                 v-validate="validations.contact_phone"></b-input>
                        <span class="has-text-danger" v-show="errors.has('contact_phone')">{{ errors.first('contact_phone') }}</span>
                    </div>
                </div>
            </div>
        </div>
        <hr width="1">
        <div class="row justify-content-center">
            <div class="col-12 col-md-2 has-text-centered">
                <button class="button is-link width-100" @click="sendForm">Añadir</button>
            </div>
        </div>
    </div>
</template>

<script>
    import VeeValidate from 'vee-validate';
    import es from  'vee-validate/dist/locale/es';

    export default {
        name: "CompanyForm",
        props: {
            authUser: {
                type: Object,
                required: true
            },
            countries: {
                type: Array,
                required: true
            },
            companyData: {
                type: Object,
                required: false,
                default() {
                    return {};
                }
            },
        },
        mounted() {
            this.setCompanyData();
            VeeValidate.Validator.localize('es',es);
            this.$validator.extend('verify_company_name', this.getAsyncValidatorConfig("name"));
            this.$validator.extend('verify_legal_name', this.getAsyncValidatorConfig("legal_name"));
            this.$validator.extend('verify_account_name', this.getAsyncValidatorConfig("account_name"));
        },
        methods: {
            sendForm() {
                this.$validator.validateAll().then(result => {
                    if (result) {
                        this.$snotify.async('Empresa', 'Procesando', () => new Promise((resolve, reject) => {
                            return axios({
                                method: 'POST',
                                url: route('companies.store'),
                                params: this.form,
                            }).then((response) => {
                                resolve({
                                    title: 'Petición exitosa',
                                    body: 'Empresa creada con éxito.',
                                    config: {
                                        closeOnClick: true,
                                        html: `
                                        <div class="snotifyToast__title">Petición exitosa</div>
                                        <div class="snotifyToast__body">Empresa creada con éxito.</div>
                                        <div class="snotify-icon snotify-icon--success"></div>
                                        `
                                    }
                                });
                            }).catch((error) => {
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
                    }
                });
            },
            getAsyncValidatorConfig(field_name, db_field_name = null) {
                return {
                    getMessage: (field, params, data) => data.message,
                    validate: value => {
                        db_field_name = (db_field_name !== null) ? db_field_name : field_name;
                        return axios({
                            method: 'POST',
                            url: route('validate_fields'),
                            params: {
                                [db_field_name]:value
                            }
                        }).then(response => {
                            return {
                                valid: true
                            }
                        }).catch(error => {
                            return {
                                valid: false,
                                data: {
                                    message: error.response.data.errors[db_field_name][0]
                                }
                            }
                        });
                    }
                }
            },
            setCompanyData() {
                this.form = this.companyData;
            }
        },
        data() {
            return {
                form:{
                    name: null,
                    short_name: null,
                    legal_name: null,
                    account_name: null,
                    country_id: '',
                    site_url: null,
                    contact_email: null,
                    contact_phone: null
                },
                actions: {
                    create: {
                        cancel_button: false,
                        confirm_button: true,
                        label: 'Añadir'
                    },
                    edit: {
                        cancel_button: true,
                        confirm_button: true,
                        label: 'Actualizar'
                    },
                    show: {
                        cancel_button: false,
                        confirm_button: false,
                    }
                },
                validations: {
                    name: {
                        required: true,
                        verify_company_name: true
                    },
                    short_name: {
                        required: true
                    },
                    legal_name: {
                        required: true
                    },
                    account_name: {
                        required: false
                    },
                    country_id: {
                        required: true
                    },
                    site: {
                        required: false,
                        url: true
                    },
                    contact_email: {
                        required: true
                    },
                    contact_phone: {
                        required: false,
                        min: 10,
                        regex: '^((\\+[0-9]{1,3})(-|)|([0-9]{1,3})(-|)|)([0-9]{3,4})(-|)([0-9]{2,3})(-|)([0-9]{2,4})(-|)(([0-9]{2}))$'
                    }
                }
            }
        }
    }
</script>

<style scoped>

</style>