<template>
    <div>
        <div class="card-wrapper row">
            <div v-for="(card,index) in get_payment_information" class="col-md-6 col-lg-4 credit-card" :id="card.id">
                <div class="credit-card-item">
                    <div class="content">
                        <div class="card-attributes">
                         <span class="is-pulled-left">
                             <span v-if="card.default_source==1" class="tag is-info">Default</span>
                        </span>
                            <span class="is-pulled-right">
                            <span class="card-type icon is-small">
                                <i class="fab fa-2x" :class="[card.card_type_icon]" aria-hidden="true"></i>
                            </span>
                         </span>
                            <div class="clearfix"></div>
                        </div>
                        <span class="card-number"> XXXX XXXX XXXX <span
                                class="card-last-four">{{ card.card_last_four }}</span>
                        </span>
                    </div>
                    <div class="extra">
                        <div class="columns">
                            <a @click="setDefaultCard(card,index)" href="#" v-if="card.default_source==0"
                               class="button">Default</a>
                            <a @click="deleteCard(card,index)" href="#" class="button is-danger">Eliminar</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 credit-card">
                <div class="new-card">
                <span class="add-card-button" v-model="add_card" @click="add_card=true">
                      <span class="icon is-small"><i class="fas fa-plus-circle" aria-hidden="true"></i></span>
                </span>
                </div>
            </div>
        </div>
        <br>
        <div v-if="add_card">
            <apithy-user-payment-information-form @cancelPaymentInfo="cancelPaymentForm($event)" :user="user" @savePaymentInfo="setPaymentInfo($event)"></apithy-user-payment-information-form>
        </div>
        <div v-if="show_edit_form">
            <apithy-user-payment-information-form-edit @cancelPaymentInfo="cancelPaymentForm($event)" :card="edit_card" @savePaymentInfo="setPaymentInfo($event)"></apithy-user-payment-information-form-edit>
        </div>
    </div>
</template>
<script>
    import PaymentInfoForm from './PaymentInformationForm';
    import PaymentInfoFormEdit from './PaymentInformationFormEdit';


    export default {
        name: 'apithy-user-payment-information',
        components: {
            'apithy-user-payment-information-form': PaymentInfoForm,
            'apithy-user-payment-information-form-edit': PaymentInfoFormEdit
        },
        props: {
            user: {required: true},
            payment_information: {},
        },
        computed: {
            get_payment_information() {
                return this.payment_information_data;
            }
        },
        methods: {
            setPaymentInfo(paymentInfo) {
                this.payment_information_data.push(paymentInfo);

                this.payment_information_data.map(function (element) {
                    if (element.id != paymentInfo.id) {
                        element.default_source = 0;
                    }
                });

            },
            cancelPaymentForm(){
                this.show_edit_form=false;
                this.edit_card=false;
                this.add_card=false;
            },
            deleteCard(card, index) {
                this.$snotify.async('Información de pago', 'Enviando datos', () => new Promise((resolve, reject) => {
                    return axios({
                        method: 'POST',
                        url: route('payment-information.destroy', {payment_information: card.id}),
                        params: {
                            _method: 'DELETE',
                            _token: this.csrf
                        }
                    }).then((response) => {
                        this.payment_information_data=response.data;

                        let title="Información de pago";
                        let body="¡Tu tarjeta ha sido eliminada!";

                        if(response.data.title){
                            title=response.data.title;
                        }

                        if(response.data.message){
                            body=response.data.message;
                        }

                        this.show_edit_form=false;
                        this.edit_card=false;

                        resolve({
                            title: response.data.title,
                            body: response.data.message,
                            config: {
                                closeOnClick: true,
                                timeout: 3000,
                                html: `
                                        <div class="snotifyToast__title">` + title + `</div>
                                        <div class="snotifyToast__body">` + body + `</div>
                                        <div class="snotify-icon snotify-icon--success"></div>
                                        `
                            }
                        })

                    }).catch((error) => {

                        let title="Información de pago";
                        let body="¡No hemos podido procesar tu solicitud!";

                        if(error.data.title){
                            title=error.data.title;
                        }

                        if(error.data.message){
                            body=error.data.message;
                        }

                        reject({
                            title: 'Error!!!',
                            body: error.response.data.message,
                            config: {
                                closeOnClick: true,
                                timeout: 3000,
                                html: `
                                        <div class="snotifyToast__title">` + title + `</div>
                                        <div class="snotifyToast__body">` + body + `</div>
                                        <div class="snotify-icon snotify-icon--error"></div>
                                        `
                            }
                        })
                    });
                }));
            },
            editCard(card) {
                this.show_edit_form=true;
                this.edit_card=card;
            },
            setDefaultCard(card, index) {
                this.$snotify.async('Información de pago', 'Enviando datos', () => new Promise((resolve, reject) => {
                    return axios({
                        method: 'POST',
                        url: route('payment-information.set-default-card', {payment_information: card.id}),
                        params: {
                            _token: this.csrf
                        }
                    }).then((response) => {

                        this.payment_information_data.map(function (element) {
                            if (element.id != card.id) {
                                element.default_source = 0;
                            }
                        });

                        this.payment_information_data[index].default_source = 1;

                        let title="Información de pago";
                        let body="¡Tu tarjeta por defecto ha sido actualizada!";

                        if(response.data.title){
                            title=response.data.title;
                        }

                        if(response.data.message){
                            body=response.data.message;
                        }

                        resolve({
                            title: response.data.title,
                            body: response.data.message,
                            config: {
                                closeOnClick: true,
                                timeout: 3000,
                                html: `
                                        <div class="snotifyToast__title">` + title + `</div>
                                        <div class="snotifyToast__body">` + body + `</div>
                                        <div class="snotify-icon snotify-icon--success"></div>
                                        `
                            }
                        })


                    }).catch((error) => {

                        let title="Información de pago";
                        let body="¡No hemos podido procesar tu solicitud!";

                        if(error.data.title){
                            title=error.data.title;
                        }

                        if(error.data.message){
                            body=error.data.message;
                        }

                        reject({
                            title: 'Error!!!',
                            body: error.response.data.message,
                            config: {
                                closeOnClick: true,
                                timeout: 3000,
                                html: `
                                        <div class="snotifyToast__title">` + title + `</div>
                                        <div class="snotifyToast__body">` + body + `</div>
                                        <div class="snotify-icon snotify-icon--success"></div>
                                        `
                            }
                        })

                    });
                }));
            }
        },
        data() {
            return {
                payment_information_data: this.payment_information,
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                add_card: false,
                show_edit_form:false,
                edit_card:false
            };
        },
    }
</script>

<style scoped>
    .card-errors {
        padding: 10px;
        background-color: red;
        color: white
    }

    .credit-card {
        padding: 10px;
    }

    .credit-card .card-type {
        margin: 5px 5px;
    }

    .credit-card-item {
        background-color: #DDA15E;
        height: 200px;
        border: solid 1px lightgray;
        margin-bottom: 30px;
        padding: 15px;
    }

    .credit-card-item .content {
        height: 70%;
    }

    .credit-card-item .card-number {
        position: relative;
        bottom: -60%;
        font-weight: bold;
        color: white;
        letter-spacing: 5px;
        display: block;
    }

    .credit-card-item .card-last-four {
        font-weight: bold;
        color: black;
        margin-left: 10px
    }

    .new-card {
        height: 200px;
        border: dotted lightgray 5px;
        position: relative;
    }

    .add-card-button {
        top: 40%;
        left: 45%;
        position: relative;
        cursor: pointer;
    }

    .add-card-button .fas {
        font-size: 2rem;
        color: orange
    }

    .columns .button {
        margin-left: 10px;
    }

</style>
