<template>
    <div class="register">
        <div class="row justify-content-center mt-5">
            <div class="col-auto">
                <img src="/images/resources/svg/ready.svg" alt="" class="img-responsive">
            </div>
        </div>
        <div class="row justify-content-center mt-5">
            <div class="col-auto">
                <div class="has-text-weight-bold font-30">
                    <span>¡Hemos reestablecido tu contraseña!</span>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mt-3">
            <div class="col-auto font-22 has-text-centered">
                <p>
                    Ya puedes ingresar con tu nueva contraseña
                </p>
            </div>
        </div>
        <div class="row justify-content-center mt-3">
            <div class="col-auto" style="width: 36vw; max-width: 36vw;">
                <div class="control has-icons-left">
                    <input class="input"
                           name="login_user"
                           placeholder="Correo electrónico o número celular"
                           :class="{'text-danger': errors.has('login_user')}"
                           v-model="access_data.access"
                           v-validate="validators.login.email">
                    <span class="icon is-small is-left" :class="{'text-danger': errors.has('login_user')}">
                        <i class="fas fab-envelope"></i>
                    </span>
                </div>
                <span class="text-danger" v-show="errors.has('login_user')">{{ errors.first('login_user') }}</span>
            </div>
        </div>
        <div class="row justify-content-center mt-3">
            <div class="col-auto" style="width: 36vw; max-width: 36vw;">
                <div class="control has-icons-left">
                    <input class="input"
                           type="password"
                           name="login_pass"
                           :placeholder="$t('messages.password')"
                           :class="{'text-danger': errors.has('login_pass')}"
                           v-model="access_data.password"
                           v-validate="validators.login.password"
                           data-vv-validate-on="change|input|keypress|focus|blur"
                           @keypress.enter="login">
                    <span class="icon is-small is-left" :class="{'text-danger': errors.has('login_pass')}">
                        <i class="fas fab-lock"></i>
                    </span>
                </div>
                <span class="text-danger" v-show="errors.has('login_pass')">{{ errors.first('login_pass') }}</span>
            </div>
        </div>
        <div class="row justify-content-center action-buttons mt-3">
            <div class="col-6 col-lg-3">
                <button class="button is-primary width-100" @click="login" :disabled="login_validate">
                    <i class="arrow circle right icon"></i>
                    {{ $t('messages.login') }}
                </button>
            </div>
        </div>
        <div class="row justify-content-center mt-3" v-if="register_welcome.message">
            <div class="col-6">
                <article class="message is-danger">
                    <div class="message-header">
                        <p></p>
                        <button class="delete" aria-label="delete" @click="register_welcome.message = null"></button>
                    </div>
                    <div class="message-body">
                        <strong>Error: </strong> {{ register_welcome.message }}
                    </div>
                </article>
            </div>
        </div>
        <div class="row justify-content-center mt-3" v-if="register_welcome.loading">
            <div class="col-auto">
                <div class="loader"></div>
            </div>
        </div>
        <div class="row justify-content-center remember-buttons mt-3">
            <div class="col-12">
                <a href="/password/reset"
                   class="is-primary tex-center block">
                    {{ $t('messages.forgot_password') }}
                </a>
            </div>
        </div>
    </div>
</template>

<script>
    import VeeValidate from 'vee-validate';
    import es from 'vee-validate/dist/locale/es';
    import axios from 'axios';
    import { email } from 'vee-validate/dist/rules.esm'

    export default {
        name: "Login",
        computed: {
            login_validate() {
                if (!this.access_data.access || !this.access_data.password) {
                    return true;
                }
                if (this.errors.has('login_user') || this.errors.has('login_pass')) {
                    return true;
                }
                return false;
            },
        },
        beforeMount () {
            this.enableValidator()
        },
        methods: {
            login() {
                this.register_welcome.loading = true;
                axios.post('/login',this.access_data)
                    .then(response => {

                        let public_experience = this.getCookie('public_experience');
                        let operation_result = this.getCookie('operation_result');

                        if (!_.isEmpty(public_experience) && !_.isEmpty(operation_result)) {
                            public_experience = JSON.parse(public_experience);
                            operation_result = JSON.parse(operation_result);

                            if (public_experience.event === 'enroll_user') {
                                window.location.href = '/experiences/' + public_experience.experience + '/view';
                            }
                            else if (public_experience.event === 'append_to_cart') {
                                window.location.href = '/experiences/' + public_experience.experience + '/preview';
                            } else {
                                window.location.href = window.location.origin + '/getting-started'
                            }
                        } else {
                            window.location.href = window.location.origin + '/getting-started'
                        }
                    })
                    .catch(error => {
                        let errors = error.response.data;
                        this.register_welcome.loading = false;
                        if (_.size(errors.errors) > 0) {
                            this.register_welcome.message = _.head(Object.values(errors.errors))[0];
                        } else {
                            this.register_welcome.message = message;
                        }
                    })
            },
            getCookie(cname) {
                let name = cname + "=";
                let decodedCookie = decodeURIComponent(document.cookie);
                let ca = decodedCookie.split(';');
                for(let i = 0; i <ca.length; i++) {
                    let c = ca[i];
                    while (c.charAt(0) == ' ') {
                        c = c.substring(1);
                    }
                    if (c.indexOf(name) == 0) {
                        return c.substring(name.length, c.length);
                    }
                }
                return "";
            },
            enableValidator () {
                const dictionary = {
                    es: {
                        messages: {
                            company_phone: () => !this.badPhone ? `El número de teléfono sólo debe contener 10 dígitos.` : `El número de teléfono debe contener solo dígitos`,
                        },
                        attributes: {
                            login_user: 'correo electrónico',
                            login_pass: 'contraseña',
                        },
                        custom: {
                            login: {
                                email: {
                                    required: 'La dirección de correo electrónico es obligatoria.',
                                    email: 'No es una dirección de correo electrónico válida.',
                                    regex: 'No es una dirección de correo electrónico válida.'
                                },
                                password: {
                                    required: 'La contraseña es obligatoria.',
                                    min: 'La longitud mínima de la contraseña es de 6 caracteres.'
                                }
                            },
                        }
                    },
                    en: {
                        messages: {
                            company_phone: () => !this.badPhone ? `The phone number must only contain 10 digits.` : `The phone number must only digits.`,
                        }
                    }
                }
                VeeValidate.Validator.localize(dictionary);
                VeeValidate.Validator.localize('es', es)
                VeeValidate.Validator.extend('custom_email', {
                    validate: value => {
                        if (!this.isPhone) {
                            return email.validate(value)
                        }
                        return true
                    }
                })
                VeeValidate.Validator.extend('company_phone', {
                    validate: value => {
                        if (this.pattern.test(value)) {
                            this.isPhone = true
                            this.badPhone = false
                            return value.length === 10;
                        } else if (!_.includes(value, '@') && this.strPattern.test(value)) {
                            this.isPhone = true
                            this.badPhone = true
                            return false
                        }
                        this.isPhone = false
                        return true
                    }
                });
            }
        },
        data() {
            return {
                access_data: {
                    access: null,
                    password: null
                },
                register_welcome: {
                    show: false,
                    loading: false,
                    message: null,
                },
                isPhone: true,
                badPhone: false,
                pattern: /^-?[0-9]{4}\.?\d*$/,
                strPattern: /^-?[0-9]{5}\.?\d*\.?\w*$/,
                validators: {
                    login: {
                        email: 'required|company_phone|custom_email',
                        password: {
                            required: true,
                            min: 6,
                            //verify_password: true
                        }
                    },
                }
            }
        }
    }
</script>

<style scoped>
    .register {
        margin-top: 50px;
        text-align: center;
    }
    .apithy-with-icon span.icon {
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
        border-bottom-left-radius: 5px !important;
        border-top-left-radius: 5px !important;
        background-color: transparent !important;
        border: solid 1px #B3B3B3;
        border-right: none !important;
        padding: 10px 10px;
    }
    .apithy-with-icon input {
        margin-top: 0 !important;
        border-top-left-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
        border-left: none !important;
        padding-left: 5px !important;
    }
    .loader {
        border: 8px solid #f3f3f3;
        border-radius: 50%;
        border-top: 8px solid #3498db;
        width: 60px;
        height: 60px;
        -webkit-animation: spin 2s linear infinite; /* Safari */
        animation: spin 2s linear infinite;
    }
    /* Safari */
    @-webkit-keyframes spin {
        0% { -webkit-transform: rotate(0deg); }
        100% { -webkit-transform: rotate(360deg); }
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    input.text-danger {
        border: solid 1px red !important;
        border-left: none !important;
    }
    input:focus {
        z-index: 1 !important;
    }
</style>
