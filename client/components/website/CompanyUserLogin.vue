<template>
    <div class="company-login d-flex align-items-center justify-content-center justify-content-lg-end">
        <!-- Background decoration -->
        <div class="decoration">
            <div class="gradient d-none d-lg-block"></div>
        </div>
        <div class="apithy-logo d-none d-lg-block">
            <img src="https://s3-us-west-2.amazonaws.com/cdn.apithy.com/logo/new_apithy_logo_white.png" alt="">
        </div>
        <!-- End background decoration -->

        <!-- Login card -->
        <div class="login-card">
            <!-- Apithy logo -->
            <div class="apithy-logo d-flex d-lg-none">
                <img src="https://s3-us-west-2.amazonaws.com/cdn.apithy.com/logo/new_apithy_logo_blue.svg" alt="">
            </div>
            <!-- End apithy logo -->

            <div class="content">
                <div class="d-flex justify-content-center">
                    <div class="company-logo">
                        <img :src="company.full_path_logo" alt="">
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <div class="title">
                        <span>{{ translate('login') }}</span>
                    </div>
                </div>

                <!-- Formulario -->
                <div class="d-flex justify-content-center mt-3">
                    <form id="company_login_form" class="login-form" method="post" :action="route('login')"
                          @submit.prevent="validateForm">
                        <slot name="token"></slot>

                        <!-- messages -->
                        <slot name="partials-students-flash"></slot>
                        <slot name="messages"></slot>
                        <!-- end messages -->

                        <!-- username -->
                        <b-field :type="hasAccessError || errors.first('access') ? 'is-danger' : ''"
                                 :message="errors.has('access') ? errors.first('access') : ''">
                            <b-input
                                :placeholder="translate('company_access_key')"
                                type="text"
                                icon-pack="fas"
                                icon="envelope"
                                name="access"
                                v-validate="form_rules.access"
                                v-model="form.access">
                            </b-input>
                        </b-field>
                        <!-- end username -->

                        <div v-if="show_example && false">
                            <div class="has-text-weight-bold phone-example">
                                {{ translate('company_phone') }}
                            </div>
                            <br>
                        </div>

                        <!-- password -->
                        <b-field :type="hasPasswordError || errors.first('password') ? 'is-danger' : ''"
                                 :message="errors.has('password') ? errors.first('password') : ''">
                            <b-input
                                :placeholder="translate('password')"
                                type="password"
                                icon-pack="fas"
                                icon="lock"
                                name="password"
                                v-validate="form_rules.password"
                                v-model="form.password">
                            </b-input>
                        </b-field>
                        <!-- end password -->

                        <!-- submit button -->
                        <div class="d-flex justify-content-center mt-5">
                            <div class="submit-button">
                                <button type="submit" :disabled="errors.any()">{{ translate('login') }}</button>
                            </div>
                        </div>
                        <!-- end submit button -->
                    </form>
                </div>
                <!-- End formulario -->

                <!-- forgot password -->
                <div class="d-flex justify-content-center mt-3 mt-lg-5">
                    <div class="forgot-password">
                        <a href="password/reset"><span>{{ translate('forgot_password') }}</span></a>
                    </div>
                </div>
                <!-- end forgot password -->

                <!-- company logo mobile -->
                <!-- end company logo mobile -->
                <div class="row social-connect mt-3 mt-lg-5" v-if="show_social_connect">
                    <h3 class="text-center mb-2">Social connect</h3>
                    <div class="col-sm-8 col-md-10 block-centered">
                        <a href="/social/login/google"
                           class="social-login-item google-login-button">
                        </a>
                    </div>
                    <div class="col-sm-8 col-md-10 block-centered">
                        <a href="/social/login/facebook"
                           class="social-login-item facebook-login-button">
                        </a>
                    </div>
                    <div class="col-sm-8 col-md-10 block-centered">
                        <a href="/social/login/linkedin"
                           class="social-login-item linkedin-login-button">
                        </a>
                    </div>
                </div>
            </div>


            <!-- create account button -->
            <div class="create-account" v-if="show_register">
                <a :href="signupURL">
                    <div class="create-account-button">
                        <div class="">
                            <span>{{ translate('create_account') }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <!-- end create account button -->
        </div>
        <!-- End login card -->
    </div>
</template>

<script>
    import VeeValidate from 'vee-validate';
    import {email} from 'vee-validate/dist/rules.esm'
    import {index} from "../../helpers";

    export default {
        name: "CompanyUserLogin",
        beforeMount() {
            if (this.oldAccess) {
                this.form.access = this.oldAccess
            }
            this.enableValidator();
        },
        mounted() {
            // Set background
            if (!index.isMobile().phone()) {
                let companyLogin = document.querySelector('.company-login');
                companyLogin.style.backgroundImage = 'url(' + this.company.full_path_cover + ')';
                companyLogin.style.backgroundSize = 'cover';
            }
        },
        computed: {
            signupURL() {
                return '//' + index.getCompanyURL(this.company.account_name, this.apithy_constants.ENV) + '/signup'
            }
        },
        watch: {
            "form.access"(val) {
                if (!_.isEmpty(val)) {
                    this.show_example = false;
                }
            }
        },
        methods: {
            translate(string) {
                return this.$t('messages.' + string);
            },
            validateForm() {
                this.$validator.validateAll()
                    .then((result) => {
                        if (result) {
                            this.login();
                            return false;
                        }
                    }).catch(() => {
                });
            },
            login() {
                document.getElementById("company_login_form").submit();
            },
            enableValidator() {
                const dictionary = {
                    es: {
                        messages: {
                            company_email: () => `El dominio ${this.domain_name} no está aprobado para su registro en ${this.company.name}. Prueba usando tu cuenta de correo empresarial, o contacta con tu administrador para obtener una invitación.`,
                            company_phone: () => !this.badPhone ? `El número de teléfono sólo debe contener 10 dígitos.` : `El número de teléfono debe contener solo dígitos`,
                        }
                    },
                    en: {
                        messages: {
                            company_email: () => `The domain ${this.domain_name} is not approved for registration in ${this.company.name}. Try using your corporate email account, or contact your administrator to get an invitation.`,
                            company_phone: () => !this.badPhone ? `The phone number must only contain 10 digits.` : `The phone number must only digits.`,
                        }
                    }
                }
                VeeValidate.Validator.localize(dictionary);
                VeeValidate.Validator.extend('company_email', {
                    validate: value => {
                        let domain = value.split('@');
                        this.domain_name = _.get(domain, [1]) ? _.get(domain, [1]) : ''
                        if (_.includes(value, '@') && _.get(domain, [1])) {
                            if (!_.isEmpty(this.company.valid_domains)) {
                                let domains = index.validDomains(this.company.valid_domains)
                                if (_.has(domain, [1])) {
                                    let find = _.find(domains, x => {
                                        return x.domain === domain[1]
                                    });
                                    return !!find
                                }
                            }
                            return true
                        }
                        return !!this.pattern.test(value)
                    }
                })
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
                VeeValidate.Validator.extend('no_whitespace', {
                    validate: value => {
                        return !!this.no_whitespace.test(value)
                    }
                });
            }
        },
        props: {
            company: {
                required: true,
                type: Object
            },
            hasAccessError: {
                required: true,
                type: Boolean
            },
            hasPasswordError: {
                required: true,
                type: Boolean
            },
            oldAccess: {
                type: String
            },
            show_register:{
                default:false
            },
            show_social_connect:{
                default:false
            }
        },
        data() {
            return {
                pattern: /^-?[0-9]{5}\.?\d*$/,
                strPattern: /^-?[0-9]{5}\.?\d*\.?\w*$/,
                no_whitespace: /^\S*$/,
                form: {
                    access: '',
                    password: ''
                },
                form_rules: {
                    access: 'required|company_phone|custom_email|company_email',
                    password: 'required'
                },
                isPhone: true,
                badPhone: false,
                domain_name: '',
                show_example: true
            }
        }
    }
</script>

<style scoped>
    .decoration .gradient {
        position: absolute;
        top: 0;
        left: 0;
        height: 100vh;
        width: 50%;
        background: linear-gradient(90deg, rgba(0, 0, 0, 0.5) 0%, rgba(0, 0, 0, 0) 40%);
    }

    .apithy-logo img {
        opacity: 0.8;
        height: 40px;
        position: absolute;
        top: 10px;
        left: 10px;
    }

    .company-login {
        width: 100vw;
        height: 100vh;
        padding-left: 100px;
        padding-right: 100px;
    }

    .login-card {
        position: relative;
        min-width: 400px;
        width: 531px;
        height: 800px;
        background: #FFFFFF;
        box-shadow: 0 7px 30px rgba(34, 34, 34, 0.4);
        border-radius: 11px;
        overflow: hidden;
    }

    .login-card .apithy-logo {
        height: 63px;
        align-items: center;
        justify-content: center;
        box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
    }

    .login-card .apithy-logo img {
        top: unset;
        left: unset;
        height: 40px;
        position: relative;
    }

    .login-card .content {
        padding: 20px;
        height: 593.6px;
    }

    .content .company-logo {
        max-width: 150px;
        min-height: 150px;
        max-height: 150px;
    }

    .company-logo img {
        width: 100%
    }

    .content .title {
        font-style: normal;
        font-weight: bold;
        font-size: 30px;
        line-height: 36px;
        cursor: default;
        color: #6F6F6F;
        vertical-align: middle;
        margin-top: 25px;
        display: inline-block;
    }

    .content .login-form {
        width: 300px;
    }

    .login-form .phone-example {
        font-size: 14px;
        margin-left: 5px;
    }

    .login-form .submit-button button {
        width: 250px;
        height: 48px;
        background: #1A6BF7;
        box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
        border-radius: 4px;
        border: none;
        color: #ffffff;
        cursor: pointer;
    }

    .login-form .submit-button button:hover {
        background: #095AE6;
    }

    .login-card .forgot-password {
        font-style: normal;
        font-weight: normal;
        font-size: 15px;
        line-height: 18px;
        color: #1A6BF7;
        cursor: pointer;
    }

    .login-card .create-account {
        position: absolute;
        bottom: 0px;
        width: 100%;
    }

    .login-card .create-account-button {
        display: flex;
        width: 100%;
        height: 75px;
        cursor: pointer;
        background: #FBFBFB;
        align-items: center;
        justify-content: center;
        border-top: 1px solid #BEBEBE;
        font-style: normal;
        font-weight: 600;
        font-size: 20px;
        line-height: 24px;
        color: #1A6BF7;
    }

    .login-card .create-account-button:hover {
        background: #F5F5F5;
    }

    @media only screen and (max-device-width: 765px) and (orientation: portrait) {
        .company-login {
            padding: unset;
        }

        .login-card {
            width: 100%;
            height: 100%;
            overflow: auto;
            border-radius: 0;
            max-height: 900px;
        }

        .login-card .content {
            height: calc(100vh - (63px + 75px));
            margin: unset;
        }

        .login-card .create-account {
            position: relative;
            bottom: -23px;
        }

        .login-card .create-account-button {
            height: 50px;
        }
    }
    @media only screen and (max-device-width: 1023px) and (orientation: landscape) {
        .company-login {
            padding: unset;
        }

        .login-card {
            width: 100%;
            height: 100%;
            overflow: auto;
            border-radius: 0;
            max-height: 900px;
        }

        .login-card .content {
            height: auto;
            margin: unset;
        }

        .login-card .create-account {
            position: relative;
        }
    }
    @media only screen and (max-device-width: 480px) and (orientation: portrait) {
        .login-card .apithy-logo{
            height:50px !important;
        }

        .login-card .apithy-logo img{
            height:30px !important;
        }

        .login-card .create-account {
            bottom: -60px !important;
            position: absolute !important;
        }

        .company-logo{
            max-width: 100px !important;
            min-height:100px !important;
            max-height: 100px !important;
        }
        .content .title{
            line-height: 0 !important;
            font-size:25px !important;
        }
    }

        .login-card .create-account-button {
            height: 50px !important;
        }

</style>
<style>
    .login-form .control input.input.is-danger {
        border-color: #ff3860;
    }

    .login-form .control input.input {
        box-shadow: unset;
        border: 1px solid #BEBEBE;
        box-sizing: border-box;
        border-radius: 4px !important;
        cursor: text;
    }

    .login-form .control input.input:focus {
        border-color: #1A6BF7 !important;
    }

    .login-form .control .icon i {
        color: #495057 !important;
    }

    .login-form .control input.input.is-danger ~ .icon i {
        color: #ff3860 !important;
    }

    .login-form .control input.input.is-danger:focus ~ .icon i {
        color: #495057 !important;
    }

    .login-form .message .message-body {
        padding: unset !important;
    }

    .login-form .message .message-body .list {
        background-color: unset;
        border-radius: unset;
        box-shadow: unset;
    }

    .route-login .page-content {
        height: 100vh !important;
    }

    iframe {
        display: none;
    }
</style>
