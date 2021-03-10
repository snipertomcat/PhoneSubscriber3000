<template>
    <div class="ui grid">
        <div class="ui sixteen wide column form">
            <div class="two fields">
                <div class="field">
                    <select name="role_id" class="ui search dropdown twelve wide large screen" v-model="form.role_id">
                        <option v-for="(role, index) in source.role_list" :value="role.id">{{role.name}}</option>
                    </select>
                </div>
                <div class="field">
                    <input type="text" name="email" v-validate="validate.email" v-model="form.email" placeholder="Correo Electronico">
                    <span class="text-danger" v-show="errors.has('email')">{{ errors.first('email') }}</span>
                </div>
            </div>
            <div class="two fields">
                <div class="field">
                    <input type="text" name="name" v-validate="validate.name" v-model="form.name" placeholder="Nombre(s)">
                    <span class="text-danger" v-show="errors.has('name')">{{ errors.first('name') }}</span>
                </div>
                <div class="field">
                    <input type="text" name="surname" v-validate="validate.surname" v-model="form.surname" placeholder="Apellido(s)">
                    <span class="text-danger" v-show="errors.has('surname')">{{ errors.first('surname') }}</span>
                </div>
            </div>
            <apithy-select :companies="source.companies_list" :company_id="currentCompany" :super="root" :admin="admin" ref="select">
            </apithy-select>
            <div class="two fields">
                <div class="field">
                    <input type="text" name="phone" v-validate="validate.phone" v-model="form.phone" placeholder="Teléfono">
                    <span class="text-danger" v-show="errors.has('phone')">{{ errors.first('phone') }}</span>
                </div>
                <div class="field">
                    <select name="activated" v-model="form.activated">
                        <option v-for="(option,index) in source.activated_list" :value="option.value">{{option.label}}</option>
                    </select>
                </div>
            </div>

            <div class="field">
                <input type="text" name="career_title" v-model="form.career_title" placeholder="Profesión">
                <span class="text-danger" v-show="errors.has('career_title')">{{ errors.first('career_title') }}</span>
            </div>

            <div class="ui stackable two column grid">
                <div class="column">
                    <button class="ui button primary sixteen column" @click="send">Submit</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import ApithySelect from '../../AreasPositionsSelector';

    export default {
        name: "CreateForm",
        components: {
            'apithy-select': ApithySelect,
        },
        props: {
            mode: {
                type: String,
                required: true
            },
            email: {
                type: String,
                required: true
            },
            code: {
                type: String,
                required: true
            },
            url: {
                type: String,
                required: true
            },
            method: {
                type: String,
                required: true
            },
            companies: {
                type: Array,
                required: true
            },
            currentCompany: {
                type: Number,
                default: 0
            },
            roles: {
                type: Array,
                default() {return []}
            },
            root: {
                type: Number,
                default: 0
            },
            admin: {
                type: Number,
                default: 0
            }
        },
        created() {
            this.registerMode = ['on_site', 'invitation'].includes(this.mode) ? this.mode : 'invitation';
        },
        mounted() {
            this.$validator.extend('verify_email_exist', this.getAsyncValidatorConfig("email"));
        },
        methods:{
            send() {
                this.$validator.validate().then(result => {
                    if(result) {
                        let title = 'Guardando';
                        let message = 'Guardando datos';
                        let user_postition = this.$refs.select.getUserPosition();

                        this.config.params = this.form;
                        this.config.params.company_id = user_postition.company;
                        this.config.params.area = user_postition.area;
                        this.config.params.position = user_postition.position;
                        this.config.params._token = this.csrf;

                        this.$snotify.async(title, message, () => new Promise((resolve, reject) => {
                            return axios(this.config)
                                .then((response)=>{
                                    title = 'Guardado correctamente';
                                    resolve({
                                        title: title,
                                        body: response.data.message,
                                        config: {
                                            closeOnClick: true,
                                            html: `
                                        <div class="snotifyToast__title">`+title+`</div>
                                        <div class="snotify-icon snotify-icon--success"></div>
                                        `
                                        }
                                    });
                                })
                                .catch((response)=>{
                                    title = 'Error!!!';
                                    reject({
                                        title: title,
                                        body: response.data.message,
                                        config: {
                                            closeOnClick: true,
                                            html : `
                                        <div class="snotifyToast__title">`+title+`</div>
                                        <div class="snotifyToast__body">`+response.data+`</div>
                                        <div class="snotify-icon snotify-icon--success"></div>
                                        `
                                        }
                                    });
                                })
                        }));
                    }
                    else {
                        this.$snotify.warning(
                            'Revise que los datos sean correctos',
                            'Campos inválidos',
                            {
                                timeout: 2000,
                                showProgressBar: false,
                                closeOnClick: true,
                                pauseOnHover: true
                            }
                        );
                    }
                });

            },
            setTotalUser(element) {
                if (element.users_details) {
                    this.addUser(element.users_details.ids);
                } else if (element.id) {
                    this.addUser([element.id]);
                } else {
                    this.new_users.push(element);
                }
                this.licences_number = this.all_users.length + this.new_users.length;
            },
            verifyDomain(element, force = false) {
                let validate_email = RegExp('^(([^<>()\\[\\]\\\\.,;:\\s@"ç%&]+(\\.[^<>()\\[\\]\\\\.,;:\\s@"]+)*)|(".+"))@((\\[[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}])|(([a-zA-Z\\-0-9]+\\.)+[a-zA-Z]{2,}))$', []);
                // RegExp('^([a-zA-Z0-9_\\-\\.]+)@((\\[[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.)|(([a-zA-Z0-9\\-]+\\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\\]?)$', []);

                if (validate_email.test(element)) {
                    let element_domain = element.split('@')[1];
                    if (force) {
                        this.raw.allowed_domains.push({domain: element_domain});
                    }
                    let valid_domain = (_.find(this.raw.allowed_domains, {domain: element_domain})) ? true : false;
                    if (valid_domain || force) {
                        this.setTotalUser(element);
                    }
                    else {
                        let title = 'Email no reconocido';
                        let message = 'El dominio ' + element_domain + ' no pertenece a su lista de dominios, ¿deséa añadirlo aún así?';
                        this.$snotify.warning(
                            message, title, {
                                buttons: [
                                    {
                                        text: 'Si',
                                        action: (alert) => {
                                            this.verifyDomain(element, true);
                                            this.$snotify.remove(alert.id);
                                        }
                                    },
                                    {
                                        text: 'No',
                                        action: (alert) => {
                                            let index = this.form.by_email.indexOf(element);
                                            this.form.by_email.splice(index, 1);
                                            this.$snotify.remove(alert.id);
                                        }
                                    }
                                ],
                                showProgressBar: false,
                                closeOnClick: false,
                                timeout: 0,
                            });
                    }
                } else {
                    let index = this.form.by_email.indexOf(element);
                    this.form.by_email.splice(index, 1);
                }
            },
            getAsyncValidatorConfig(field_name, db_field_name = null) {
                return {
                    getMessage: (field, params, data) => data.message,
                    validate: value => {
                        db_field_name = (db_field_name !== null) ? db_field_name : field_name;
                        return axios({
                            method: 'post',
                            url: '/signup/validate',
                            params: {
                                [db_field_name]: value
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
        },
        data() {
            return {
                registerMode: 'invitation',
                config: {
                    url: this.url,
                    method: this.method,
                    params: {}
                },
                form: {
                    register_type: 'admin',
                    role_id: 9,
                    email: '',
                    name: '',
                    surname: '',
                    company_id: '',
                    area: '',
                    position: '',
                    phone: '',
                    career_title:'',
                    activated: 1
                },
                source: {
                    role_list: this.roles,
                    companies_list: this.companies,
                    areas_list: [],
                    positions_list: [],
                    activated_list: [
                        {label: 'Inactivo', value: 0},
                        {label: 'Activo', value: 1}
                    ],
                },
                validate: {
                    email: {
                        required: true,
                        verify_email_exist: true,
                    },
                    name: {
                        required: true,
                        alpha_spaces: true
                    },
                    surname: {
                        required: true,
                        alpha_spaces: true
                    },
                    phone: {
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