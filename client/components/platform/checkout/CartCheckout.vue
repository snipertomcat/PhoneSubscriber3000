<template>
    <div class="col-sm-12 col-md-5 block-centered card" v-if="cart_data.total_quantity">
        <wizard title="" subtitle="" step-size="xs" ref="wizard" back-button-text="Atras" color="darkorange">
            <tab-content title="Inicio" icon="fas fa-shopping-cart">
                <div class="">
                    <div class="col-sm-12 marg-b-2 no-padding">
                        <h3>Mi carrito</h3>
                        <div class="sub-total row mt-3 mb-3 pt-3 pb-3">
                            <div class="col-12 col-md-6 mb-2 mb-md-0 align-self-center">
                                {{ cart_data.total_quantity }} Producto{{ cart_data.total_quantity > 1 ? 's':''}} en el carrito
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="row ml-0 mr-0 no-gutters justify-content-center big-text">
                                   <div class="col-6 has-text-centered align-self-center">
                                        <p v-money-format="cart_data.subtotal"></p>
                                   </div>
                                    <div class="col-6 has-text-centered button-pay">
                                        <button v-if="cart_data.subtotal > 0" @click="next"
                                                v-bind:disabled="isLastStep">Pagar</button>
                                        <button v-if="cart_data.subtotal == 0" @click="processPayment"
                                                v-bind:disabled="isLastStep">Adquirir</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row no-gutters">
                        <div class="col-lg-12">
                            <div>
                                <div class="card p-3" v-for="(carItem,index) in cart_data.items" :id="carItem.id">
                                    <div class="row product">
                                        <div class="col-sm-3">
                                            <img class="image" v-bind:src="carItem.attributes.cover">
                                        </div>
                                        <div class="col-sm-9 pt-3 pt-sm-0">
                                            <h3>{{carItem.name}}</h3>
                                            <span class="block no-margin">
                                                    {{ carItem.attributes.sessions}} {{ (carItem.attributes.type=='journey')? "experiencias":'retos' }}
                                            </span>
                                            <span class="block no-margin">
                                                {{ carItem.attributes.use_for}}
                                            </span>
                                            <div class="row mr-0 ml-0 no-gutters">
                                                <div class="col-sm-6">
                                                    <p>X{{carItem.quantity}}</p>
                                                </div>
                                                <div class="col-sm-6">
                                                   <span class="has-text-right">
                                                        <p class="is-bold" v-money-format="carItem.price"></p>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="row mr-0 ml-0 no-gutters">
                                                <div class="col-6">
                                                    <div class="remove-product"
                                                         v-if="carItem.attributes.corporative_use">
                                                        <a :href="route('experience.edit-licences',[carItem.attributes.system_id]).toString()">Detalles</a>
                                                    </div>
                                                    <div class="remove-product" v-else>
                                                        <a v-bind:href="route('experience.preview', {'experience': carItem.attributes.system_id})">Detalles</a>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <span class="remove-product block has-text-right">
                                                        <a @click="removeItem(carItem.attributes.system_id,index)">Eliminar del carrito</a>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </tab-content>
            <tab-content v-if="cart_data.subtotal > 0" title="Pago" icon="fas fa-credit-card">
                <div class="row mr-0 ml-0 no-gutters marg-b-3">
                    <div class="col-sm-12 marg-b-3">
                        <h3>Mi carrito</h3>
                    </div>
                    <div class="col-sm-12" v-if="auth_user_data.payment_information.length">
                        <div class="marg-b-2">
                            <span class="big-text marg-b-2">Elegir Tarjeta</span>
                            <div class="payment-methods">
                                <div v-for="(payment_information,index) in auth_user_data.payment_information"
                                     class="col-12 align-self-center no-pl" :key="payment_information.id">
                                    <span class="tag">
                                        <input name="payment_method" type="radio" class="radio-method"
                                               v-bind:value="payment_information.id"
                                               v-bind:checked="payment_information.default_source"
                                               v-model="payment_method_id"/>
                                        <i class="fab fa-lg" v-bind:class="[payment_information.card_type_icon]"></i>XXXX XXXX XXXX &nbsp;{{ payment_information.card_last_four }}</span>
                                </div>
                            </div>
                        </div>
                        <!--
                        <div class="col-sm-5">
                            <span class="big-text">Código promocional</span>
                            <div class="col-10 no-pl">
                                <input type="text" class="input" placeholder="Escribe un código">
                            </div>
                        </div>
                        -->
                    </div>
                    <div class="col-sm-12 marg-b-3">
                        <apithy-payment-information-form
                                :user="auth_user_data"
                                :size_class="payment_form_size"
                                :use_ajax="true"
                                :cancel_botton="false"
                                @savePaymentInfo="setPaymentInfo($event)"
                                :payment_information="auth_user_data.payment_information">
                        </apithy-payment-information-form>
                    </div>

                    <div class="col-sm-12 marg-b-3">
                        <div class="row mr-0 ml-0 no-gutters p-2 total marg-b-2">
                            <span class="col-12 has-text marg-b-2">El precio puede variar al incluir impuestos</span>
                        </div>
                    </div>

                    <div class="col-sm-12 sub-total">
                        <div class="row ml-0 mr-0 no-gutters p-2">
                            <div class="col-12 col-sm-6 has-text description">
                                {{ cart_data.total_quantity }} Producto{{ cart_data.total_quantity > 1 ? 's':''}} en el carrito
                            </div>
                            <div class="col-12 col-sm-6 has-text-centered big-text pay-controls">
                                <div class="row mr-0 ml-0 no-gutters">
                                    <div class="col-6">
                                        <p v-money-format="cart_data.subtotal"></p>
                                    </div>
                                    <div class="col-6 has-text-centered button-pay">
                                        <button class="button is-primary" @click="processPayment"
                                                v-bind:disabled="disablePayBotton">Finalizar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <span>Al completar tu pedido, aceptas el <a href="/aviso-de-privacidad">Aviso de privacidad</a> y las <a
                            href="/terminos-y-condiciones">Condiciones de uso</a> de Apithy.com</span>
                </div>
            </tab-content>
            <tab-content title="Finalizar" icon="fas fa-check-circle">
                <div class="row">
                    <div class="col-12 marg-b-3">
                        <h3>¡Tu compra ha sido exitosa!</h3>
                    </div>
                    <div class="col-12 purchased-product marg-b-3">
                        <div v-for="(experience) in cart_data.items" class="product">
                            <img class="image-product"
                                 v-bind:src="experience.attributes.cover"
                                 alt="" width="200px" height="200px">
                            <div class="row product-go big-text">
                                <h3>
                                    {{ experience.name }}
                                </h3>
                                <span class="col-12 no-pl" v-if="experience.attributes.use_for =='Personal'">
                                    <a class="button is-primary" v-bind:href="route('experience.preview', {'experience': experience.attributes.system_id})">¡Comenzar!</a>
                                </span>
                                <span class="col-12 no-pl" v-else>
                                    <a class="button is-primary" v-bind:href="route('experience.show.licenses', {'experience': experience.attributes.system_id})">¡Administrar Licencias!</a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </tab-content>
            <button class="button is-primary" slot="prev" v-show="!isLastStep && !disableBtn">Atr&aacute;s</button>
        </wizard>
    </div>
    <div class="container h-100 mt-5" v-else>
        <div class="row align-items-center h-100">
            <div class="col-6 mx-auto">
                <img src="/images/resources/png/cart.png"/>
                <h1 class="text-center">¡Tu carrito est&aacute; vac&iacute;o!</h1>
                <p class="text-center">Visita el <a href="/experiences">Market Place</a> y encontrar&aacute;s
                    experiencias<br> que te ayudar&aacute;n a crecer</p>
            </div>
        </div>
    </div>
</template>

<script>
    import {FormWizard, TabContent, WizardButton} from 'vue-form-wizard';
    import 'vue-form-wizard/dist/vue-form-wizard.min.css';
    import UserPaymentInfoForm from '../../PaymentInformationForm';

    export default {
        name: "CartCheckout",
        props: {
            cart: {
                required: true,
                type: Object
            },
            auth_user: {
                required: true,
                type: Object
            },
        },
        components: {
            'wizard': FormWizard,
            'tab-content': TabContent,
            'apithy-payment-information-form': UserPaymentInfoForm
        },
        mounted() {
            if (this.cart.total_quantity > this.apithy_constants.BUY_LIMIT) {
                this.$snotify.warning(`Está excediendo el número de licencias a comprar por operación (Limitado a ${this.apithy_constants.BUY_LIMIT})`, 'Aviso')
                return
            }
            if (!this.auth_user_data.payment_information.length) {
                this.disablePayBotton = true;
            }
        },
        methods: {
            setPaymentInfo(paymentInfo) {
                console.log(paymentInfo);
                this.auth_user_data.payment_information.push(paymentInfo);
                this.disablePayBotton = false;
            },
            next() {
                this.$refs.wizard.nextTab();
            },
            removeItem(experience_id, index) {
                axios({
                    method: 'POST',
                    url: route('shopping-cart.remove', {experience: experience_id}),
                    params: {
                        user: this.auth_user.id,
                        _method: 'DELETE',
                        _token: this.csrf
                    }
                }).then((response) => {
                    this.cart_data = response.data.cart;
                    this.$snotify.success('Experiencia eliminada', 'Mi carrito');
                }).catch((error) => {
                    console.log(error);
                });
            },
            processPayment() {
                if (this.cart.total_quantity > this.apithy_constants.BUY_LIMIT) {
                    this.$snotify.warning(`Limitado a ${this.apithy_constants.BUY_LIMIT} licencias por transacción`, 'Aviso')
                    return
                }
                this.$snotify.async('Procesando pago', 'Carrito de compras', () => new Promise((resolve, reject) => {
                    return axios({
                        method: 'POST',
                        url: route('shopping-cart.finish'),
                        params: {
                            user: this.auth_user.id,
                            payment_method_id: this.payment_method_id
                        }
                    }).then((response) => {
                        resolve({
                            title: response.data.title,
                            body: response.data.message,
                            config: {
                                closeOnClick: true,
                                timeout: 3000,
                                html: `
                                        <div class="snotifyToast__title">` + response.data.title + `</div>
                                        <div class="snotifyToast__body">` + response.data.message + `</div>
                                        <div class="snotify-icon snotify-icon--success"></div>
                                        `
                            }
                        })

                        this.next();
                        this.disablePayBotton = true;
                        this.disableBtn = true;


                    }).catch((error) => {
                        console.log(error);

                        let title = "¡No se pudo procesar el pago!";
                        let body = "Hubo un problema y no hemos podido procesar tu pago";

                        if (error.response.data.title) {
                            title = error.response.data.title;
                        }

                        if (error.response.data.message) {
                            body = error.response.data.message;
                        }

                        reject({
                            config: {
                                closeOnClick: true,
                                html: `
                                        <div class="snotifyToast__title">` + title + `</div>
                                        <div class="snotifyToast__body">` + body + `</div>
                                        <div class="snotify-icon snotify-icon--error"></div>
                                        `
                            }
                        })
                    });

                }), {backdrop: 0.6});
            }
        },
        data() {
            return {
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                cart_data: this.cart,
                experiences_purchased: [],
                isLastStep: false,
                payment_form_size: "col-sm-12 col-lg-12 col-md-12 no-pl no-pr",
                auth_user_data: this.auth_user,
                payment_method_id: null,
                disablePayBotton: false,
                disableBtn: false
            };
        },
    }
</script>

<style scoped>

    .card{
        margin-bottom: 20px;
    }

    .card img{
        width: 100%;
    }

    .big-text {
        font-size: 18px;
        font-weight: bold;
    }

    .remove-product a {
        color: orange;
    }

    .price {
        width: 150px;
    }

    .purpose {
        width: 200px;
    }

    .sub-total {
        background-color: #FBFBFB;
    }

    .sub-total .description {
        margin-bottom: 10px;
    }

    .sub-total .price {
        margin-bottom: 10px;
    }

    .button-pay button {
        font-weight: bold;
        color: white;
        background-color: orange;
        border: none;
        border-radius: 5px;
        padding: 5px;
        cursor: pointer;
    }

    .total {
        background-color: #FBFBFB;
    }

    .total .description {
        margin-bottom: 10px;
    }

    .total .price {
        margin-bottom: 10px;
    }

    .radio-method {
        margin-right: 20px;
        height: 20px;
    }

    .purchased-product .product {
        display: flex;
        margin-bottom: 10px;
    }

    .product .image-product {
        margin-right: 30px;
    }

    .purchased-product .image-product {
        margin-right: 30px;
        width: 200px !important;
        height: 150px !important;
    }

    .marg-b-3 {
        margin-bottom: 30px;
    }

    .wizard-icon {
        display: block !important;
    }

</style>
