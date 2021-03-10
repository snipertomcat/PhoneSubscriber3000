<template>
    <div class="mb-5">
        <div class="" v-if="!allow_reset_password">
            <div class="row justify-content-center mt-5">
                <div class="col-auto">
                    <img src="/images/resources/svg/llaves.svg" alt="" class="img-responsive">
                </div>
            </div>
            <div class="enter-code" v-if="!wrong.show">
                <div class="row justify-content-center mt-5">
                    <div class="col-auto">
                        <div class="has-text-weight-bold font-30">
                            <span>¡Revisa tu teléfono!</span>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center mt-3">
                    <div class="col-auto font-22 has-text-centered">
                        <p>
                            Enviamos un mensaje para validar tu número celular a <br>"{{ phone }}".
                            Ingresa el código de 4 dígitos:
                        </p>
                    </div>
                </div>
                <div class="row justify-content-center no-gutters mt-4">
                    <div class="col-auto mr-3">
                        <input class="input confirmation-code-input"
                               :class="verification.class"
                               id="one"
                               ref="one"
                               type="text"
                               maxlength="1"
                               data-next="two"
                               v-model="verification.digits_code.one"
                               @keypress="isNumber"
                               @keyup="focusNext"
                               @focusin="select">
                    </div>
                    <div class="col-auto mr-3">
                        <input class="input confirmation-code-input"
                               :class="verification.class"
                               id="two"
                               ref="two"
                               type="text"
                               maxlength="1"
                               data-next="three"
                               v-model="verification.digits_code.two"
                               @keypress="isNumber"
                               @keyup="focusNext"
                               @focusin="select">
                    </div>
                    <div class="col-auto mr-3">
                        <input class="input confirmation-code-input"
                               :class="verification.class"
                               id="three"
                               ref="three"
                               type="text"
                               maxlength="1"
                               data-next="four"
                               v-model="verification.digits_code.three"
                               @keypress="isNumber"
                               @keyup="focusNext"
                               @focusin="select">
                    </div>
                    <div class="col-auto">
                        <input class="input confirmation-code-input"
                               :class="verification.class"
                               id="four"
                               ref="four"
                               type="text"
                               maxlength="1"
                               data-next="four"
                               v-model="verification.digits_code.four"
                               @keypress="isNumber"
                               @keyup="focusNext"
                               @focusin="select">
                    </div>
                </div>
                <div class="row justify-content-center mt-4">
                    <div class="col-auto has-text-link">
                        <a @click="showWrongForm">
                            ¿Este no es tu número de celular?
                        </a>
                    </div>
                </div>
            </div>
            <div class="send-to-anothe-phone" v-if="wrong.show">
                <div class="row justify-content-center mt-5">
                    <div class="col-auto">
                        <div class="has-text-weight-bold font-30">
                            <span>Cambiar contraseña</span>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center mt-3">
                    <div class="col-auto font-22 has-text-centered">
                        <p>
                            Ingresa tu número de teléfono celular
                        </p>
                    </div>
                </div>
                <div class="row justify-content-center mt-4">
                    <div class="col-auto has-text-centered">
                        <div class="control has-icons-left">
                            <input class="input"
                                   style="min-width: 33vw"
                                   placeholder="Número de celular (10 dígitos)"
                                   v-model="wrong.new_phone" @input="verifyPhone">
                            <span class="icon is-small is-left">
                            <i class="icon-phone-alt"></i>
                        </span>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center mt-4" v-if="!!verification.message">
                    <div class="col-auto has-text-centered">
                        <article class="message is-danger" style="max-width: 33vw">
                            <div class="message-body">
                                {{ this.verification.message }}
                            </div>
                        </article>
                    </div>
                </div>
                <div class="row justify-content-center no-gutters">
                    <div class="col" style="max-width: 33vw">
                        <div class="row no-gutters mt-4" :class="{'justify-content-between': wrong.continue_button}">
                            <div class="col-auto font-22 has-text-centered">
                                <button class="button is-primary is-inverted" @click="resetWrongForm">
                                    Regresar
                                </button>
                            </div>
                            <div class="col-auto font-22 has-text-centered" v-if="wrong.continue_button">
                                <button class="button is-primary" @click="sendCodeToNewPhone">
                                    Continuar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="" v-else>
            <reset-password :user.sync="user" :is-phone="true" :token.sync="confirmation_code"></reset-password>
        </div>
    </div>
</template>

<script>
    import ResetPassword from "./ResetPassword";

    export default {
        name: "RecoveryByPhone",
        components: { 'reset-password': ResetPassword },
        props: {
            registeredPhone: {
                required: true,
                default: '55 1234 5678'
            },
            registeredUser: {
                required: true,
                default: 0
            }
        },
        data () {
            return {
                allow_reset_password: false,
                verification: {
                    message: null,
                    class: '',
                    digits_code: {
                        one: null,
                        two: null,
                        three: null,
                        four: null,
                    }
                },
                user: this.registeredUser,
                phone: this.registeredPhone,
                wrong: {
                    new_phone: null,
                    continue_button: false,
                    show: false
                }
            }
        },
        computed: {
            confirmation_code: function () {
                let digits = this.verification.digits_code;
                if(this.completed_confirmation_code) {
                    return '' + digits.one + digits.two + digits.three + digits.four;
                }
                else {
                    return null;
                }
            },
            completed_confirmation_code: function () {
                let digits = this.verification.digits_code;
                return (digits.one && digits.two && digits.three && digits.four);
            },
        },
        methods:{
            sendCodeToNewPhone () {
                axios.post('/password/phone', {
                    email: this.phone
                })
                    .then(response => {
                        this.wrong.show = false;
                    })
                    .catch(errors => {
                        console.log(errors)
                    })
            },
            showWrongForm () {
                let digits = this.verification.digits_code
                this.user = null
                this.wrong.show = true
                digits.one = null
                digits.two = null
                digits.three = null
                digits.four = null
                this.verification.class = ''
                this.verification.message = null
            },
            resetWrongForm () {
                this.user = this.registeredUser
                this.phone = this.registeredPhone
                this.wrong.show = false
                this.wrong.new_phone = null
                this.verification.message = null
            },
            verifyPhone (evt) {
                if (_.size(this.wrong.new_phone) === 10) {
                    axios({
                        method: 'POST',
                        url: route('users.verify'),
                        params: {
                            phone: this.wrong.new_phone
                        }
                    }).then(response => {
                        this.user = response.data.user
                        this.phone = this.wrong.new_phone
                        this.wrong.continue_button = true
                    }).catch(error => {
                        this.verification.message = 'Este número no está registrado.'
                    })
                }
            },
            confirmValidationCode() {
                axios({
                    method: 'GET',
                    url: '/register/confirm/phone/' + this.confirmation_code,
                    params: {
                        user_id: this.user
                    }
                })
                    .then(response => {
                        let status = response.data.status;
                        let message = response.data.message;
                        this.verification.message = message;

                        if(status === 200) {
                            this.verification.class = 'text-green';
                            this.allow_reset_password = true;
                        } else {
                            this.verification.class = 'text-red';
                        }
                    })
                    .catch(error => {
                        let message = (error.response.data) ? error.response.data.message : error.message;
                        let status = (error.response.data) ? error.response.data.status : error.status;

                        this.verification.message = message;
                        this.verification.class = 'text-red';
                    })
            },
            focusNext: function (evt) {
                if(this.completed_confirmation_code) {
                    this.confirmValidationCode();
                }

                if(evt.keyCode > 47 && evt.keyCode < 58 && !evt.shiftKey) {
                    let next = evt.target.dataset.next;
                    let element = this.$refs[next];

                    if(element) {
                        element.focus();
                        element.select();
                    }
                }
            },
            isNumber: function (evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                    evt.preventDefault();
                } else {
                    return true;
                }
            },
            select(evt) {
                evt.target.select();
            },
        }
    }
</script>

<style scoped>
    .confirmation-code-input {
        width: 36px;
        height: 36px;
        padding-left: 36%;
    }
    input.text-red {
        border: solid 1px red !important;
        color: red !important;
    }
    .text-red {
        color: red !important;
    }
    input.text-green {
        border: solid 1px limegreen !important;
        color: limegreen !important;
    }
    .text-green {
        color: limegreen !important;
    }
</style>