<template>
    <div class="container block-centered ">
        <hr width="2">
        <div class="card mb-3">
            <div class="card-content">
                <div class="row">
                    <!-- Logo -->
                    <div class="col-12 col-lg-2">
                        <div class="row">
                            <span class="font-18 has-text-weight-bold">Logo</span>
                        </div>
                        <br>
                        <div class="row justify-content-center company-logo">
                            <template v-if="isMobile().any()">
                                <apithy-croppa
                                    :id="2"
                                    input_name="logo"
                                    name="logo"
                                    field="logo"
                                    :height="200"
                                    :width="200"
                                    :quality="1"
                                    accept=".png, .jpg"
                                    :url="`${route('logo.update', [companyData.system_id])}`"
                                    :image="companyData.full_path_logo">
                                </apithy-croppa>
                            </template>
                            <template v-else>
                                <apithy-image-upload
                                    :id="1"
                                    input_name="logo"
                                    field="logo"
                                    name="logo"
                                    :url="`${route('logo.update', [companyData.system_id])}`"
                                    :width="200"
                                    :height="200"
                                    :image="companyData.full_path_logo">
                                </apithy-image-upload>
                            </template>
                            <div class="row" v-if="editing">
                                <div class="col has-text-right">
                                    <span><i class="material-icons apithy-color">edit</i></span>
                                    <span><i class="material-icons apithy-color">add_photo_alternate</i></span>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <span class="font-18 has-text-weight-bold">Portada</span>
                        </div>
                        <br>
                        <div class="row justify-content-center company-logo">
                            <template v-if="isMobile().any()">
                                <apithy-croppa
                                    :id="2"
                                    input_name="cover"
                                    name="cover"
                                    field="cover"
                                    :height="216"
                                    :width="300"
                                    :quality="5"
                                    accept=".png, .jpg"
                                    :url="`${route('company.cover.update', [companyData.system_id])}`"
                                    :image="companyData.full_path_cover">
                                </apithy-croppa>
                            </template>
                            <template v-else>
                                <apithy-image-upload
                                    :id="2"
                                    field="cover"
                                    input_name="cover"
                                    extensions="png,jpg"
                                    :height="1080"
                                    :width="1500"
                                    :url="`${route('company.cover.update', [companyData.system_id])}`"
                                    :image="companyData.full_path_cover">
                                </apithy-image-upload>
                            </template>
                        </div>
                        <div class="row">
                            <div class="col has-text-centered">
                                <p>Tamaño mínimo 1,500 x 1080 Formatos JPG o PNG</p>
                                <a class="apithy-color" v-if="false">Previsualizar login</a>
                            </div>
                        </div>
                    </div>
                    <hr width="1" class="d-lg-none">
                    <!-- Form -->
                    <div class="col-12 col-lg-10">
                        <div class="row">
                            <span class="font-18 has-text-weight-bold">Datos</span>
                        </div>
                        <br>
                        <div class="font-14">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <b-field label="Nombre" :type="(errors.has('name')) ? 'is-danger' : ''" :message="errors.first('name')">
                                        <b-input icon-pack="fas"
                                                 icon="building"
                                                 name="name"
                                                 v-model="form.name"
                                                 v-validate="validators.name"
                                                 :disabled="!editing" >
                                        </b-input>
                                    </b-field>
                                </div>
                                <div class="col-12 col-md-6">
                                    <b-field label="Nombre corto" :type="(errors.has('short_name')) ? 'is-danger' : ''" :message="errors.first('short_name')">
                                    <b-input icon-pack="fas"
                                             icon="building"
                                             name="short_name"
                                             v-model="form.short_name"
                                             v-validate="validators.short_name"
                                             :disabled="!editing" ></b-input>
                                    </b-field>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <b-field label="Sector empresarial">
                                    <b-input icon-pack="fas" icon="building" name="sector"
                                             v-model="form.sector" :disabled="!editing"></b-input>
                                    </b-field>
                                </div>
                                <div class="col-12 col-md-6">
                                    <b-field label="Tamaño de la empresa">
                                    <b-select placeholder="Selecciona un tamaño"
                                              icon-pack="fas"
                                              icon="users"
                                              name="size"
                                              expanded
                                              v-model="selected_size"
                                              :disabled="!editing"
                                              @input="updateCompanySize">
                                        <option v-for="option in sizes"
                                                :value="option.value"
                                                :key="option.value">
                                            {{ option.label }}
                                        </option>
                                    </b-select>
                                    </b-field>
                                </div>
                            </div>
                        </div>
                        <br><br><br>
                        <div class="row">
                            <span class="font-18 has-text-weight-bold">Datos de contacto</span>
                        </div>
                        <br>
                        <div class="font-14">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <b-field label="Sitio web" :type="(errors.has('website')) ? 'is-danger' : ''" :message="errors.first('website')">
                                    <b-input icon-pack="fas"
                                             icon="globe-americas"
                                             name="website"
                                             v-model="form.site_url"
                                             v-validate="validators.website"
                                             :disabled="!editing"></b-input>
                                    </b-field>
                                </div>
                                <div class="col-12 col-md-6">
                                    <b-field label="Email de contacto" :type="(errors.has('email')) ? 'is-danger' : ''" :message="errors.first('email')">
                                    <b-input icon-pack="fas"
                                             icon="at"
                                             name="email"
                                             v-model="form.contact_email"
                                             v-validate="validators.email"
                                             :disabled="!editing" ></b-input>
                                    </b-field>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <b-field label="Teléfono de contacto" :type="(errors.has('phone')) ? 'is-danger' : ''" :message="errors.first('phone')">
                                    <b-input icon-pack="fas"
                                             icon="phone"
                                             name="contact_phone"
                                             maxlength="10"
                                             v-validate="validators.phone"
                                             v-model="form.contact_phone"
                                             :disabled="!editing"
                                             @keypress.native="isNumber"></b-input>
                                    </b-field>
                                </div>
                            </div>
                        </div>
                        <br><br><br>
                        <div class="row">
                            <span class="font-18 has-text-weight-bold">Espacios de trabajo</span>
                        </div>
                        <br>
                        <div class="font-14">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <b-field label="URL">
                                    <b-input icon-pack="fas" icon="at" v-model="domain" disabled></b-input>
                                    </b-field>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <label class="label">Dominios de correo electrónico</label>
                                    <b-field>
                                        <b-input type="text"
                                                 class="width-100"
                                                 name="valid_domain"
                                                 placeholder="Ej: apithy.com, @apithy.com"
                                                 :disabled="!editing"
                                                 v-model="form.new_domain">
                                        </b-input>
                                        <p class="control">
                                            <button class="button is-primary add-domain" :disabled="!editing" @click="addEmailDomain">
                                                Añadir
                                            </button>
                                        </p>
                                    </b-field>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-12">
                                    <b-taglist>
                                        <b-tag type="is-info"
                                               closable
                                               :disabled="!editing"
                                               @close="removeEmailDomain(index)"
                                               v-for="(item,index) in form.valid_domains" :key="index">
                                            @{{ item.domain }}
                                        </b-tag>
                                    </b-taglist>
                                </div>
                            </div>
                        </div>
                        <br><br><br>
                        <div class="row">
                            <span class="font-18 has-text-weight-bold">Datos de facturación</span>
                        </div>
                        <br>
                        <div class="font-14">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <b-field label="Razón social" :type="(errors.has('r_social')) ? 'is-danger' : ''" :message="errors.first('r_social')">
                                    <b-input icon-pack="fas"
                                             icon=""
                                             name="r_social"
                                             v-model="form.legal_name"
                                             v-validate="validators.r_social"
                                             :disabled="!editing"></b-input>
                                    </b-field>
                                </div>
                                <div class="col-12 col-md-6">
                                    <b-field label="RFC" :type="(errors.has('rfc')) ? 'is-danger' : ''" :message="errors.first('rfc')">
                                    <b-input icon-pack="fas"
                                             icon=""
                                             name="rfc"
                                             v-model="form.rfc"
                                             v-validate="validators.rfc"
                                             :disabled="!editing"></b-input>
                                    </b-field>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <b-field label="Código postal" :type="(errors.has('postal_code')) ? 'is-danger' : ''" :message="errors.first('postal_code')">
                                    <b-input icon-pack="fas"
                                             icon=""
                                             name="postal_code"
                                             v-model="form.zip"
                                             v-validate="validators.postal_code"
                                             :disabled="!editing"></b-input>
                                    </b-field>
                                </div>
                                <div class="col-12 col-md-6">
                                    <b-field label="Dirección fiscal" :type="(errors.has('legal_address')) ? 'is-danger' : ''" :message="errors.first('legal_address')">
                                    <b-input icon-pack="fas"
                                             icon=""
                                             name="legal_address"
                                             v-model="form.legal_address"
                                             v-validate="validators.legal_address"
                                             :disabled="!editing"></b-input>
                                    </b-field>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <b-field label="País">
                                        <b-select placeholder="Selecciona un país"
                                                  icon-pack="fas"
                                                  icon="flag"
                                                  name="size"
                                                  expanded
                                                  v-model="form.country_id"
                                                  :disabled="!editing">
                                            <option v-for="country in countries"
                                                    :value="country.id"
                                                    :key="country.value">
                                                {{ country.name }}
                                            </option>
                                        </b-select>
                                    </b-field>
                                </div>
                                <div class="col-12 col-md-6">
                                    <b-field label="Correo electrónico de facturación" :type="(errors.has('billing_email')) ? 'is-danger' : ''" :message="errors.first('billing_email')">
                                    <b-input icon-pack="fas"
                                             icon=""
                                             name="billing_email"
                                             v-model="form.legal_email"
                                             v-validate="validators.billing_email"
                                             :disabled="!editing"></b-input>
                                    </b-field>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
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
    import { index } from "../../../helpers";

    export default {
        name: "CompanyEdit",
        components:{
            'apithy-image-upload': () => import('../../ImageUpload'),
            'apithy-croppa': () => import('../../ApithyCroppa')
        },
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
            startEditing: {
                type: Boolean,
                required: false,
                default: false
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
            },
        },
        mounted() {
            if(!this.companyData.full_path_logo) {
                this.companyData.full_path_logo="/images/apithy_small.png";
            }

            if (!this.companyData.valid_domains) {
                this.companyData.valid_domains = [];
            }

            this.domain = this.companyData.account_name+'.apithy.com';
            this.setFormData(this.companyData);
        },
        methods: {
            isMobile () {
                return index.isMobile()
            },
            isNumber: function(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                    evt.preventDefault();;
                } else {
                    return true;
                }
            },
            valid_domains(domains) {
                let valid_domains = domains;
                let aux = [];
                if(valid_domains !== null && valid_domains.length > 0) {
                    valid_domains.forEach(object => {
                        if( typeof object !== 'object') {
                            object = JSON.parse(object);
                        }
                        aux.push(object);
                    });
                }
                return aux;
            },
            updateCompanySize(size) {
                if (!!this.selected_size) {
                    let min_max = size.split(' a ');
                    if(min_max.length > 0) {
                        this.form.num_employees_min = min_max[0];
                        this.form.num_employees_max = min_max[1];
                    }
                }
            },
            setFormData(data) {
                this.selected_size = data.num_employees_min+' a '+data.num_employees_max;
                this.form = {
                    id: data.id,

                    name: this.companyData.name,
                    short_name: data.short_name,

                    sector: data.sector,
                    num_employees_min: data.num_employees_min,
                    num_employees_max: data.num_employees_max,


                    site_url: data.site_url,
                    contact_email: data.contact_email,
                    contact_phone: data.contact_phone,
                    contact_name: data.account_name,

                    valid_domains: this.valid_domains(data.valid_domains),

                    legal_name: data.legal_name,
                    rfc: data.rfc,
                    zip: data.zip,
                    legal_address: data.legal_address,
                    legal_email: data.legal_email,
                    country_id: data.country_id,
                };
            },
            addEmailDomain() {
                let valid_domains = this.form.valid_domains;
                let new_domain = this.form.new_domain;
                let template = {domain: ''};

                if(!new_domain) {
                    return;
                }

                if(!new_domain.indexOf('@')) {
                    new_domain = new_domain.split('@')[1];
                }

                if (new_domain) {
                    template.domain = new_domain;
                    valid_domains.push(template);
                    this.form.new_domain = new_domain = null;
                }
            },
            removeEmailDomain(index) {
                let valid_domains = this.form.valid_domains;
                valid_domains.splice(index, 1);
            },
            doAction() {
                if (this.editing) {
                    this.save()
                }
                else {
                    this.editing = true;
                }
            },
            save() {
                this.$validator.validateAll().then(result => {
                    if (result) {
                        this.$snotify.async('Empresa', 'Procesando', () => new Promise((resolve, reject) => {
                            return axios({
                                method: 'PUT',
                                url: route('companies.update',[this.form.id]),
                                params: this.form,
                            }).then((response) => {
                                this.editing = false;
                                this.setFormData(response.data);
                                resolve({
                                    title: 'Petición exitosa',
                                    body: 'Guardados los cambios con éxito.',
                                    config: {
                                        closeOnClick: true,
                                        timeout: 2000,
                                        showProgressBar: false,
                                        backdrop: 0.6
                                    }
                                });
                            }).catch((error) => {
                                this.editing = true;
                                reject({
                                    title: 'Error!!',
                                    body: 'No se pudo procesar la petición correctamente.',
                                    config: {
                                        closeOnClick: true,
                                        timeout: 2000,
                                        backdrop: 0.6
                                    }
                                });
                            });

                        }), {backdrop: 0.6});
                    }
                });
            },
            cancel() {
                this.setFormData(this.companyData);
                this.editing = false;
            }
        },
        data() {
            return {
                form: {
                    name: null,
                    short_name: null,
                    legal_name: null,
                    account_name: null,
                    country_id: '',
                    site_url: null,
                    contact_email: null,
                    contact_phone: null,
                    valid_domains: [],
                    new_domain: null,
                    legal_email: null,
                    legal_address: null,
                    rfc: null,
                },
                domain: null,
                editing: this.startEditing,
                selected_size: null,
                sizes: [
                    {label: '1 - 10 personas', value: '1 a 10'},
                    {label: '11 - 50 personas', value: '11 a 50'},
                    {label: '51 - 250 personas', value: '51 a 250'},
                    {label: '251 - 500 personas', value: '251 a 500'},
                    {label: '501 - 1000 personas', value: '501 a 1000'},
                    {label: '1001 - 4000 personas', value: '1001 a 4000'},
                    {label: '4000 personas o más', value: '4000 a 10000'},
                ],
                validators: {
                    billing_email: {
                        required: false,
                        email: true
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    legal_address: {
                        required: false,
                    },
                    name: {
                        required: true,
                        min: 2,
                    },
                    phone: {
                        digits: 10,
                        numeric: true,
                    },
                    postal_code: {
                        numeric: true,
                        digits: 5
                    },
                    r_social: {
                        required: false,
                    },
                    rfc: {
                        required: false,
                        alpha_num: true,
                        min: 12,
                        max: 13,
                        regex: '^((([a-zñ]|[A-ZÑ]){3,4})(([0-9]{2})([0][1-9]|[1][0-2])(([0][1-9]|[12][0-9])|[3][01]))(([a-zñ0-9]|[A-ZÑ0-9]){3}))$',
                    },
                    short_name: {
                        required: true,
                        max: 50
                    },
                    website: {
                        url: {
                            require_protocol: false
                        }
                    },
                }
            };
        }
    }
</script>

<style scoped>
    .company-logo {
        margin: 5px;
        padding: 5px;
        border-radius: 5px;
        border: gray solid 1px;
    }
    .company-logo img{
        max-width: 100%;
    }
    .input {
        cursor: auto;
    }
    .add-domain {
        height: 32px !important;
        padding: 0 10px;
    }
</style>