<template>
    <div class="block-centered" v-bind:class="[size_class]">
        <form id="card-form" method="POST" action="/profile/payment-information-update">
            <article class="message is-danger hidden">
                <div class="message-body">
                    <p class="card-error">
                    </p>
                </div>
            </article>
            <div class="field">
                <label>
                    <span>N&uacute;mero de tarjeta de cr&eacute;dito</span>
                </label>
                <div class="control">
                    <b-input icon="credit-card"
                             icon-pack="fas"
                             name="name"
                             v-model="current_card"
                             disabled
                    />
                </div>
            </div>
            <div class="field">
                <label>
                    <span>Nombre del tarjeta habiente</span>
                </label>
                <div class="control">
                    <b-input icon="user"
                             icon-pack="fas"
                             name="name"
                             v-model="card_data.name"
                             v-validate="validate.name"
                             data-conekta="card[name]"
                    />
                    <span class="text-danger" v-show="errors.has('name')">{{ errors.first('name') }}</span>
                </div>
            </div>
            <div class="field row">
                <div class="field col-sm-4">
                    <label>
                        <span>CVC</span>
                    </label>
                    <div class="control">
                        <b-input icon="cvc"
                                 icon-pack="fas"
                                 name="cvc"
                                 v-model="card_data.cvc"
                                 v-validate="validate.cvc"
                                 maxlength="4"
                                 minlength="3"
                                 @keypress.native="isNumber"
                        />
                    </div>
                </div>

                <div class="field col-sm-4">
                    <label>
                        <span>Expiraci&oacute;n</span>
                    </label>
                    <div class="select is-primary">
                        <select v-model="card_data.exp_month" data-conekta="card[exp_month]" name="exp_month" v-validate="validate.exp_month">
                            <option value="">Mes</option>
                            <option value="1">Enero</option>
                            <option value="2">Febrero</option>
                            <option value="3">Marzo</option>
                            <option value="4">Abril</option>
                            <option value="5">Mayo</option>
                            <option value="6">Junio</option>
                            <option value="7">Julio</option>
                            <option value="8">Agosto</option>
                            <option value="9">Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>
                        </select>
                    </div>
                </div>

                <div class="field col-sm-4">
                    <label class="no-visible">
                        <span>Año</span>
                    </label>
                    <div class="select is-primary">
                        <select v-model="card_data.exp_year" data-conekta="card[exp_year]" name="exp_year" v-validate="validate.exp_year">
                            <option value="">A&ntilde;o</option>
                            <option value="2019">2019</option>
                            <option value="2020">2020</option>
                            <option value="2021">2021</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="error-bottom">
            <span class="text-danger" v-show="errors.has('cvc')">{{ '-'+errors.first('cvc') }}</span>
            <span class="text-danger" v-show="errors.has('exp_month')">{{ '-'+errors.first('exp_month') }}</span>
            <span class="text-danger" v-show="errors.has('exp_year')">{{ '-'+errors.first('exp_year') }}</span>
            </div>

            <input type="hidden" name="_token" :value="csrf">
            <div class="col-sm-12 row controls">
                <div class="col-sm-6 col-12">
                    <button class="button is-primary" @click="cancel" type="button">
                <span class="icon is-medium">
                  <i class="fas fa-times"></i>
                </span>
                        <span>
                    Cancelar
                </span>
                    </button>
                </div>
                <div class="col-sm-6 col-12">
                    <button class="button is-primary" @click="send" type="button">
                <span class="icon is-medium">
                  <i class="fas fa-save"></i>
                </span>
                        <span>
                    Guardar
                </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>
<script>
    export default {
        name: 'apithy-user-payment-information-form-edit',
        props: {
            card: {required: true},
            size_class: {required: false, default: 'col-sm-11 col-md-7 col-lg-4'},
            use_ajax: {required: false, default: true}
        },
        methods: {
            cancel(){
                this.$emit("cancelPaymentInfo");
            },
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
            send() {
                this.$validator.validate().then(result => {
                        if (result) {
                            this.$snotify.async('Procesando tarjeta', 'Información de pago', () => new Promise((resolve, reject) => {
                                return axios({
                                    method: 'POST',
                                    url: '/profile/payment-information/'+ this.card.id +'/update',
                                    params: this.card_data
                                }).then((response) => {
                                    console.log(response.response);

                                    let title="Información de Pago";
                                    let body="Tu tarjeta ha sido actualizada";

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
                                            timeout: 4000,
                                            html: `
                                        <div class="snotifyToast__title">` + title + `</div>
                                        <div class="snotifyToast__body">` + body + `</div>
                                        <div class="snotify-icon snotify-icon--success"></div>
                                        `
                                        }
                                    })

                                    this.$parent.show_edit_form=false;
                                    this.$parent.edit_card=false;

                                }).catch((error) => {

                                    let title="¡No se pudo procesar la tarjeta!";
                                    let body="Hubo un problema y no hemos guardar tu tarjeta";

                                    if(error.response.data.title){
                                        title=error.response.data.title;
                                    }

                                    if(error.response.data.message){
                                        body=error.response.data.message;
                                    }

                                    reject({
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
                        }
                });
            },
        },
        data() {
            return {
                card_data: {
                    name: "",
                    number: "",
                    cvc: "",
                    exp_month: "",
                    exp_year: "",
                    token: ""
                },
                validate: {
                    name: {
                        required: true,
                        alpha_spaces: true,
                        //regex: '^((\\b[a-zA-Z]{2,40}\\b)\\s*){2,}$',
                    },
                    cvc:{
                        required:true,
                        min: 3,
                    },
                    exp_month: {
                        required: true
                    },
                    exp_year: {
                        required:true
                    }
                },
                current_card: '**** **** **** ' + this.card.last_four,
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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

    .text-danger{
        margin-bottom: 0px;
        display: block;
        font-size: 14px;
        margin-left: 5px;
    }
    .error-bottom{
        position: relative;
        top: -30px;
    }

    .controls .button{
        width: 95%;
        margin-bottom:20px;
    }
</style>