<template>
    <div class="payment-info-form block-centered" v-bind:class="[size_class]">
        <card class='stripe-card'
              ref='cardForm'
              :class='{ complete }'
              :stripe='stripeKeyEnv'
              :options='stripeOptions'
              @change='complete = $event.complete'
        />
        <div class="col-sm-12 row controls">
            <div class="col-sm-6 col-12" v-if="cancel_botton">
                <button class="button is-primary" @click="cancel" type="button">
                <span class="icon is-medium">
                  <i class="fas fa-times"></i>
                </span>
                <span>
                    Cancelar
                </span>
                </button>
            </div>
            <div class="save-card-button col-sm-6 col-12">
                <button class="button is-primary" @click="generateToken" type="button" :disabled="!complete">
                <span class="icon is-medium">
                  <i class="fas fa-save"></i>
                </span>
                    <span>
                    Guardar
                </span>
                </button>
            </div>
        </div>
    </div>
</template>
<script>

    import {Card, createToken} from 'vue-stripe-elements-plus'
    import apithy_constants from '../constants';


    export default {
        name: 'apithy-user-payment-information-form',
        props: {
            user: {required: true},
            size_class: {required: false, default: 'col-sm-11 col-md-7 col-lg-4'},
            use_ajax: {required: false, default: false},
            cancel_botton: {required: false, default: true}
        },
        components: {Card},
        methods: {
            isNumber(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                    evt.preventDefault();
                    ;
                } else {
                    return true;
                }
            },
            generateToken() {
                let vm=this;
                createToken().then(data =>
                    this.$snotify.async('Procesando tarjeta', 'Información de pago', function () {
                        return new Promise(function (resolve, reject) {
                            return axios({
                                method: 'POST',
                                url: '/profile/payment-information',
                                params: {
                                    index_redirect: 0,
                                    token: data.token
                                }
                            }).then(function (response) {
                                vm.$refs.cardForm.clear();

                                var title = "Información de pago";
                                var body = "Tu tarjeta ha sido guardada";

                                if (response.data.title) {
                                    title = response.data.title;
                                }

                                if (response.data.message) {
                                    body = response.data.message;
                                }

                                resolve({
                                    title: response.data.title,
                                    body: response.data.message,
                                    config: {
                                        timeout: 4000,
                                        html: '\n<div class="snotifyToast__title">' + title + '</div>\n                                    <div class="snotifyToast__body">' + body + '</div>\n                                    <div class="snotify-icon snotify-icon--success"></div>\n                                    '
                                    }
                                });

                                vm.$emit("savePaymentInfo", response.data);
                            }).catch(function (error) {
                                vm.$refs.cardForm.clear();
                                var title = "¡No se pudo procesar la tarjeta!";
                                var body = "Hubo un problema y no hemos guardar tu tarjeta";

                                if (error.response.data.title) {
                                    title = error.response.data.title;
                                }

                                if (error.response.data.message) {
                                    body = error.response.data.message;
                                }

                                reject({
                                    config: {
                                        closeOnClick: true,
                                        html: '\n                                    <div class="snotifyToast__title">' + title + '</div>\n                                    <div class="snotifyToast__body">' + body + '</div>\n                                    <div class="snotify-icon snotify-icon--error"></div>\n                                    '
                                    }
                                });
                            });
                        });
                    })
                )
            },
            cancel() {
                this.$emit("cancelPaymentInfo");
            }
        },
        data() {
            return {
                card: {
                    name: "",
                    number: "",
                    cvc: "",
                    exp_month: "",
                    exp_year: "",
                    token: ""
                },
                //elements
                cardNumber: '',
                cardExpiry: '',
                cardCvc: '',
                stripe: null,
                // errors
                stripeError: '',
                cardCvcError: '',
                cardExpiryError: '',
                cardNumberError: '',
                validate: {
                    number: {
                        required: true,
                    },
                    name: {
                        required: true,
                        alpha_spaces: true,
                        //regex: '^((\\b[a-zA-Z]{2,40}\\b)\\s*){2,}$',
                    },
                    cvc: {
                        required: true,
                        min: 3,
                    },
                    exp_month: {
                        required: true
                    },
                    exp_year: {
                        required: true
                    }
                },
                complete: false,
                stripeOptions: {},
                stripeKeyEnv:apithy_constants.STRIPE_KEY,
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            };
        },
    }
</script>

<style scoped>

    .StripeElement {
        background-color: white;
        height: 40px;
        width: 500px;
        padding: 10px 12px;
        border-radius: 4px;
        border: 1px solid transparent;
        box-shadow: 0 1px 3px 0 #e6ebf1;
        -webkit-transition: box-shadow 150ms ease;
        transition: box-shadow 150ms ease;
    }

    .stripe-card {
        width: 300px;
        border: 1px solid grey;
        padding: 5px;
        border-radius: 7px;
        margin: 0 auto;
        display: block;
    }

    .stripe-card.complete {
        border-color: green;
    }

    .StripeElement--focus {
        box-shadow: 0 1px 3px 0 #cfd7df;
    }

    .StripeElement--invalid {
        border-color: #fa755a;
    }

    .StripeElement--webkit-autofill {
        background-color: #fefde5 !important;
    }

    .card-errors {
        padding: 10px;
        background-color: red;
        color: white
    }

    .text-danger {
        margin-bottom: 0px;
        display: block;
        font-size: 14px;
        margin-left: 5px;
    }

    .error-bottom {
        position: relative;
        top: -30px;
    }

    .controls .button {
        width: 95%;
        margin-bottom: 20px;
        margin-top: 20px
    }

    .controls {
        width: 300px;
        margin: 0 auto;
    }

    .card-save-button {
        margin: 20px auto;
        display: block;
        width: 250px;
    }
</style>