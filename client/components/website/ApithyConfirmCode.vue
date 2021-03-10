<template>
  <div id="confirm-code-form" class="container">
    <div class="row">
      <div class="col-sm-9 center-block">
        <img class="img-fluid center-block"
             align="center"
             src="/images/resources/png/ready.png"/>
        <div class="col-sm-10 center-block mt-3">
          <h2 class="center">Est&aacute;s a un paso de ser parte de Apithy</h2>
          <p class="center">Ingresa tu teléfono celular y el c&oacute;digo de confirmaci&oacute;n de 4 d&iacute;gitos.</p>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12 col-md-4 apithy-with-icon center-block">
        <div class="row  padding-right-4p">
          <div class="input-group-prepend">
            <span :class="{
              'input-group-text icon': true,
              'text-danger': errors.has('phone')
            }">
              <i class="fas fa-phone"></i>
            </span>
          </div>
          <div class="width-84">
            <input
                type="text"
                name="phone"
                maxlength="10"
                placeholder="Número de teléfono"
                :disabled="hasToken"
                :class="{'col-sm-12 form-control':true,'text-danger': errors.has('phone'),}"
                v-model="form.phone"
                v-validate="validators.phone"
                @keypress="isNumber">
          </div>
        </div>
        <span class="text-danger" v-show="errors.has('phone')">
          {{ errors.first('phone') }}
        </span>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12 col-md-4 center-block apithy-with-icon mt-3">
        <div class="row  padding-right-4p">
          <div class="input-group-prepend">
            <span :class="{'input-group-text icon':true,'text-danger': errors.has('code'),}">
              <i class="fas fa-code"></i>
            </span>
          </div>
          <div class="width-84">
            <input
                type="text"
                name="code"
                maxlength="4"
                placeholder="Código de confirmación"
                :class="{'col-sm-12 form-control':true,'text-danger': errors.has('code'),}"
                v-model="form.code"
                v-validate="validators.code"
                @keypress="isNumber">
          </div>
        </div>
        <span class="text-danger" v-show="errors.has('code')">
          {{ errors.first('code') }}
        </span>
      </div>
    </div>
    <div class="row justify-content-center mt-3" v-if="message">
      <div class="col-12 col-md-4">
        <div class="alert alert-danger" role="alert">
          <button type="button" class="close" aria-label="Close" @click="message = null">
            <span aria-hidden="true">&times;</span>
          </button>
          <strong>Error: </strong> {{ message }}
        </div>
      </div>
    </div>
    <div class="row justify-content-center mt-3" v-if="hasToken">
      <div class="col-12 col-md-4">
        <div>
          <button class="btn btn-link" :disabled="loader" @click="resendCodePhone">
            Reenviar sms de verificación
          </button>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-10 col-md-4 wizard-card-footer center-block">
        <div class="continue-btn">
          <button class="confirm-button" :disabled="loader" @click="confirmValidationCode">
            Continuar
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import VeeValidate from 'vee-validate';
  import es from 'vee-validate/dist/locale/es';
  import { index } from "../../helpers";
  import { Message } from 'element-ui';
  import axios from "axios";

  const dictionary = {
    es: {
      attributes: {
        phone: 'número de teléfono',
        code: 'código',
      },
      custom: {
        phone: {
          required: 'El número de teléfono es obligatorio.',
          digits: 'El número de teléfono sólo debe contener 10 dígitos.',
          numeric: 'El número de teléfono sólo debe contener números.',
        },
        code: {
          required: 'El código de confirmación es obligatorio.',
          digits: 'El código de confirmación sólo debe contener 4 dígitos.',
          numeric: 'El código de confirmación sólo debe contener números.',
        },
      }
    }
  };

  export default {
    name: "ApithyConfirmCode",
    beforeMount() {
      if (!_.isEmpty(this.token) && !_.isEmpty(this.phone)) {
        this.form.phone = this.phone
      }
    },
    mounted() {
      VeeValidate.Validator.localize('es', es)
      VeeValidate.Validator.localize(dictionary);
    },
    computed: {
      hasToken () {
        return !_.isEmpty(this.token) && !_.isEmpty(this.phone)
      }
    },
    methods: {
      isNumber (evt) {
        return index.onlyNumbers(evt)
      },
      confirmValidationCode() {
        this.$validator.validateAll().then(result => {
          if (result) {
            this.confirmPhone()
          }
        })
      },
      confirmPhone () {
        this.loader = true
        let loader = index.getLoader()
        axios({
          method: 'GET',
          url: `/register/confirm/phone/${this.form.code}`,
          params: this.form
        })
            .then(response => {
              window.location.href = '/login'
            })
            .catch(error => {
              this.message = _.get(error, ['response', 'data', 'message'], 'Inesperado')
            })
            .then(() => {
              this.loader = false
              loader.close()
            })
      },
      resendCodePhone () {
        this.loader = true
        axios({
          method: 'GET',
          url: '/register/confirm/resend',
          params: {
            confirmation_user_id: this.token
          }
        })
            .then(response => {
              let status = _.get(response, ['data', 'status'], 200)
              if (status == 200) {
                Message.success('Se ha reenviado el código de verificación')
              }
            })
            .catch(errors => {
              this.message = _.get(errors, ['response', 'data', 'message'])
            })
            .then(() => {
              this.loader = false
            })
      }
    },
    props: {
      phone: {
        type: String,
        default: null
      },
      token: {
        type: String,
        default: null
      }
    },
    data() {
      return {
        loader: false,
        validators: {
          phone: {
            required: true,
            digits: 10,
          },
          code: {
            required: true,
            digits: 4
          }
        },
        form: {
          phone: null,
          code: null
        },
        message: null
      };
    }
  }
</script>

<style scoped>
  .width-83 {
    width: 83%;
  }

  .width-84 {
    width: 84%;
  }

  .account-type {
    border: #b2b2b2 1px solid;
    border-radius: 5px;
    cursor: pointer;
    height: 50px;
    padding: 12px 0;
  }

  .account-type.active {
    color: #ffffff;
    background-color: #1C6BF9;
  }

  .contact-preference {
    border: #b2b2b2 1px solid;
    border-radius: 10px;
    cursor: pointer;
    height: 50px;
    padding: 12px;
  }

  .contact-preference.active {
    color: #ffffff;
    background-color: #1C6BF9;
  }

  .phone-code {
    -webkit-border-radius: 10px;
    -moz-border-radius: 10px;
    border-radius: 10px;
  }

  .company-account-form {
    margin-top: 40px;
  }

  .padding-right-4p {
    padding: 0px 0px 0 2%;
  }

  .form-control:focus {
    outline: 0 !important;
    border-color: initial;
    box-shadow: none;
  }

  .is-valid {
    border-color: #28a745 !important;
  }

  .text-danger {
    border-color: red !important;
  }

  .apithy-with-icon span.icon {
    border-top-right-radius: 0 !important;
    border-bottom-right-radius: 0 !important;
    border-bottom-left-radius: 5px !important;
    border-top-left-radius: 5px !important;
    background-color: transparent !important;
    border: solid 1px #B3B3B3;
    border-right: none !important;
    padding: 5px 10px;
  }

  .apithy-with-icon input {
    margin-top: 0 !important;
    border-top-left-radius: 0 !important;
    border-bottom-left-radius: 0 !important;
    border-left: none !important;
    padding-left: 5px !important;
  }

  .one-step {
    height: 300px;
  }

  .container-domains .domain-item .pre {
    border-top-left-radius: 10px;
    border-bottom-left-radius: 10px;
    background-color: #FFA81E;
  }

  .container-domains .domain-item .mdl {
    border-top-left-radius: 0px !important;
    border-bottom-left-radius: 0px !important;
    border-top-right-radius: 10px;
    border-bottom-right-radius: 10px;
    background-color: #FFE066;
  }

  .container-domains .domain-item .nxt {
    padding-left: 2rem;
    border-top-right-radius: 10px;
    border-bottom-right-radius: 10px;
    cursor: pointer;
  }

  .back-btn > button {
    font-size: 1.2rem !important;
    background-color: transparent !important;
    color: #FFA81E !important;
    border: none !important;
    box-shadow: none !important;
    cursor: pointer;
  }

  .continue-btn-valid {
    color: #FFA81E !important;
  }

  .continue-btn-invalid {
    color: #B2B2B2 !important;
  }

  .continue-btn > button {
    font-size: 1.2rem !important;
    border: none !important;
    cursor: pointer;
    color: white !important;
  }

  .register-form {
    min-height: 620px;
  }

  .contenedor-persona-apithy {
    position: relative;
  }

  .persona-apithy {
    position: absolute;
    left: 10px;
    bottom: 0px;
    height: 70%;
  }

  .vue-form-wizard {
    margin: 0 auto;
  }

  .container {
    background-color: white;
  }

  .register {
    margin-top: 50px;
    text-align: center;
  }

  .wizard-card-footer button {
    background-color: #FFA81E;
    color: white;
    border-radius: 5px;
    border: none;
    font-family: 'AvenirRoman', sans-serif;
    box-shadow: 2px 2px 6px 0 rgba(88, 88, 88, 0.41);
    font-size: .8rem;
    padding: 5px 20px 5px 20px;
    margin: 30px auto 10px auto;
    display: block;
  }

  .company-space-join {
    margin-top: 50px;
  }

  .btn-primary-alter {
    color: #FFFFFF !important;
    background-color: #FFA81E !important;
  }

  .user-register-button-top {
    background-color: #FFA81E;
    color: white;
    border-radius: 40px;
    border: none;
    font-family: 'AvenirRoman', sans-serif;
    box-shadow: 2px 2px 6px 0 rgba(88, 88, 88, 0.41);
    border-top-left-radius: 40px !important;
    border-bottom-left-radius: 40px !important;
    font-size: .8rem;
    padding: 10px 50px 10px 50px;
    cursor: pointer;
  }

  .custom-select.is-valid, .form-control.is-valid, .was-validated .custom-select:valid, .was-validated .form-control:valid {
    border-color: #28a745 !important;
  }

  input:focus {
    z-index: 1 !important;
  }

  input {
    border: solid 1px !important;
    text-align: left;
    border-radius: 5px !important;
    border: solid 1px lightgrey;
  }

  input.text-danger {
    border: solid 1px red !important;
    border-left: none !important;
  }

  span.text-danger {
    color: red;
    font-size: 0.8rem;
    padding-left: 0px;
  }

  span.head {
    font-size: 1.5rem;
    font-weight: bold;
    font-family: 'Exo 2', sans-serif;
    display: block;
  }

  .h2, h2 {
    font-size: 1.5rem;
    font-weight: bold;
    font-family: 'Exo 2', sans-serif;
    margin-bottom: 15px;
  }

  .h3, h3 {
    font-size: 1.7rem;
    font-family: 'Exo 2', sans-serif;
    font-weight: bold
  }

  .select-rounded {
    border-radius: 10px !important;
  }

  .password-strength {
    height: 10px;
    margin-top: 0.5rem;
  }

  .password-strength {
    width: 99% !important;
    margin-left: 4px !important;
  }

  .domain-url {
    display: block;
    margin-top: 10px;
    font-weight: bold;
    padding-left: 5px;
    font-size: 1.2rem;
  }

  .legal {
    text-align: center;
  }

  .confirm-code-action-inner {
    background-color: #FFF5B0;
    padding: 10px;
    margin-left: 5px;
    border-radius: 5px;
  }

  .confirm-code-action-inner a {
    background-color: orange;
    padding: 10px 20px;
    border-radius: 5px;
    color: white !important;
    text-decoration: none;
    margin-bottom: 10px;
    display: block;
    width: 180px;
    text-align: center;
  }

  @media all and (max-width: 641px) {
    .stepTitle {
      display: none;
    }

    .password-strength {
      width: 99% !important;
      margin-left: 2px !important;
    }

    .input-group {
      margin-bottom: 10px;
    }

    .fancybox-close-small {
      position: fixed !important;
    }
  }
</style>