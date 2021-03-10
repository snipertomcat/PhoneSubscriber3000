<template>
  <div class="container">
    <template v-if="!show_phone_recovery">
      <div class="">
        <div class="row">
          <div class="col-lg-6 col-sm-6 reset-email-form">
            <h1>Cambiar Contrase&ntilde;a</h1>
            <p v-if="hideForm">¡Hemos enviado su enlace de restablecimiento de contraseña por correo electrónico!</p>
            <template v-else>
              <p>Podemos ayudarte a restablecer la contrase&ntilde;a de tu cuenta apithy mediante tu correo electr&oacute;nico</p>
              <form class="" @submit.prevent="validateForm">
                <slot name="token"></slot>
                <b-field
                    :type="{'is-danger': errors.first('email')}"
                    :message="errors.first('email')">
                  <b-input
                      name="email"
                      placeholder="Teléfono o correo"
                      icon="user-o"
                      icon-pack="fa"
                      v-model="form.email"
                      data-vv-as="campo"
                      v-validate="form_rules.email"
                      :disabled="loader">
                  </b-input>
                </b-field>
                <div class="row">
                  <div class="col-lg-6 col-sm-6">
                    <b-button
                        :disabled="errors.any() || loader"
                        native-type="submit"
                        class="button is-primary">
                      {{ $t('messages.password_reset_link') }}
                    </b-button>
                  </div>
                </div>
              </form>
            </template>
          </div>
          <div class="col-lg-6 col-sm-6">
            <img src="/images/resources/png/recuperar_contrasena.png" />
          </div>
        </div>
      </div>
    </template>
    <template v-if="show_phone_recovery">
      <recovery-by-phone :registeredPhone.sync="form.email" :registered-user="form.id"></recovery-by-phone>
    </template>
  </div>
</template>

<script>
import {email} from 'vee-validate/dist/rules.esm'
import {index} from '../../../helpers'
import RecoveryByPhone from './RecoveryByPhone.vue';

export default {
  name: "RecoveryPassword",
  components: { 'recovery-by-phone': RecoveryByPhone },
  beforeMount () {
    this.enableValidator()
  },
  methods: {
    validateForm () {
      this.$validator.validateAll()
          .then((result) => {
            if (result) {
              if (!this.isPhone) {
                this.sendEmail()
              } else {
                this.sendPhone()
              }
              return false;
            }
          }).catch(() => {});
    },
    sendPhone () {
      this.loader = true
      axios.post('/password/phone', this.form)
          .then(response => {
            this.form.id = _.get(response, ['data', 'data', 'id'])
            this.show_phone_recovery = true
          })
          .catch(errors => {
            let list = index.hasErrors(errors)
            if (list) {
              index.setErrors(this.$validator.errors, list)
            }
          })
          .then(() => {
            this.loader = false
          })
    },
    sendEmail () {
      this.loader = true
      axios.post('/password/email', this.form)
          .then(response => {
            this.hideForm = true
          })
          .catch(errors => {
            let list = index.hasErrors(errors)
            if (list) {
              index.setErrors(this.$validator.errors, list)
            }
          })
          .then(() => {
            this.loader = false
          })
    },
    enableValidator () {
      const dictionary = {
        es: {
          messages: {
            company_phone: () => !this.badPhone ? `El número de teléfono sólo debe contener 10 dígitos.` : `El número de teléfono debe contener solo dígitos`,
          }
        },
        en: {
          messages: {
            company_phone: () => !this.badPhone ? `The phone number must only contain 10 digits.` : `The phone number must only digits.`,
          }
        }
      }
      this.$validator.localize(dictionary);
      this.$validator.extend('custom_email', {
        validate: value => {
          if (!this.isPhone) {
            return email.validate(value)
          }
          return true
        }
      })
      this.$validator.extend('company_phone', {
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
  data () {
    return {
      loader: false,
      isPhone: true,
      badPhone: false,
      show_phone_recovery: false,
      hideForm: false,
      form: {
        id: null,
        email: null
      },
      form_rules: {
        email: 'required|company_phone|custom_email'
      },
      pattern: /^-?[0-9]{4}\.?\d*$/,
      strPattern: /^-?[0-9]{5}\.?\d*\.?\w*$/
    }
  }
}
</script>

<style scoped>

</style>

<style>
  .page-content {
    background-color: white;
  }
</style>