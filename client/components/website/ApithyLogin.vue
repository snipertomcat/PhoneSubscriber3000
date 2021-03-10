<template>
  <div class="container row justify-content-center">
    <div class="col-12 col-md-6 col-lg-4 login-wrapper">
      <h1>Inicia sesi&oacute;n en Apithy</h1>
      <span>o </span><a class="forgot-text" href="/signup">crea una cuenta</a>
      <form id="apithy_login_form" class="ui form" method="post" :action="route('login')" @submit.prevent="validateForm">
        <h1 class="ui header center aligned"></h1>

        <slot name="partials-students-flash"></slot>

        <slot name="messages"></slot>

        <slot name="token"></slot>

        <b-field :type="hasAccessError || errors.first('access') ? 'is-danger' : ''" :message="errors.has('access') ? errors.first('access') : ''">
          <b-input
              :placeholder="$t('messages.company_access_key')"
              type="text"
              icon-pack="fas"
              icon="user"
              name="access"
              v-validate="form_rules.access"
              v-model="form.access">
          </b-input>
        </b-field>

        <b-field :type="hasPasswordError || errors.first('password') ? 'is-danger' : ''" :message="errors.has('password') ? errors.first('password') : ''">
          <b-input
              :placeholder="$t('messages.password')"
              type="password"
              icon-pack="fas"
              icon="key"
              name="password"
              v-validate="form_rules.password"
              v-model="form.password">
          </b-input>
        </b-field>

        <div class="row action-buttons">
          <div class="col-sm-6 block-centered">
            <button type="submit" class="button is-primary col-12" :disabled="errors.any()">
              {{ $t('messages.login') }}
            </button>
          </div>
        </div>

        <div class="row remember-buttons mt-3">
          <div class="col-sm-8 col-md-10 block-centered">
            <a href="password/reset"
               class="is-primary tex-center block forgot-text">{{ $t('messages.forgot_password') }}
            </a>
          </div>
        </div>

          <div class="row social-connect mt-3">
              <div class="col-12">
                <h3 class="text-center mb-2">Social connect</h3>
              </div>
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
              <div class="legal-legend col-sm-12 col-md-12 block-centered">
                Al continuar, declaras que aceptas nuestros<br>
                <a target="_blank" href="https://www.apithy.com/terminos-y-condiciones">
                  Términos y condiciones de uso</a> y nuestro
                <a target="_blank" href="https://www.apithy.com/acuerdo-de-servicio">Aviso de Privacidad</a>.
              </div>
          </div>

      </form>
    </div>
  </div>
</template>

<script>
  import VeeValidate from 'vee-validate';
  import {email} from 'vee-validate/dist/rules.esm'
  export default {
    name: "ApithyLogin",
    beforeMount () {
      if (this.oldAccess) {
        this.form.access = this.oldAccess
      }
      this.enableValidator()
    },
    methods: {
      validateForm () {
        this.$validator.validateAll()
            .then((result) => {
              if (result) {
                this.login();
                return false;
              }
            }).catch(() => {});
      },
      login () {
        document.getElementById("apithy_login_form").submit();
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
        VeeValidate.Validator.localize(dictionary);
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
    props: {
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
      }
    },
    data () {
      return {
        pattern: /^-?[0-9]{4}\.?\d*$/,
        strPattern: /^-?[0-9]{5}\.?\d*\.?\w*$/,
        isPhone: true,
        badPhone: false,
        form: {
          access: '',
          password: ''
        },
        form_rules: {
          access: 'required|company_phone|custom_email',
          password: 'required'
        }
      }
    }
  }
</script>

<style>
@media (device-width: 375px) and (device-height: 812px) {
  body {
    max-height: unset;
    overflow: unset;
  }
  .page-content {
    height: 600px !important;
  }
}
@media (device-width: 360px) and (max-device-height: 640px) {
  body {
    max-height: unset;
    overflow: unset;
  }
  .page-content {
    height: 600px !important;
  }
}
</style>
<style scoped>
  .legal-legend{
    font-size:13px;
    text-align:center;
    display:block;
  }
  .legal-legend a{
    color:#11CBFF;
  }
</style>
