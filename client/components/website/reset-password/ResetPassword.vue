<template>
  <div class="container">
    <template v-if="!show_login">
      <template v-if="showForm">
      <div class="row">
        <div class="col-lg-6 col-sm-12 justify-content-center align-self-center">
          <div class="row col-lg-8 col-sm-12">
            <div class="col-lg-12 col-sm-12">
              <h1>Cambiar Contrase&ntilde;a</h1>
              <p>Reestablece tu contraseña</p>
            </div>
            <div class="col-lg-12 col-sm-12">
              <form class="row" @submit.prevent="validateForm">
                <slot name="token"></slot>
                <div class="col-12">
                  <div class="pt-3"></div>
                </div>

                <div class="col-lg-12 col-sm-12 pb-3" v-if="!isPhone">
                  <b-field
                      :type="{'is-danger': errors.first('email')}"
                      :message="errors.first('email')">
                    <b-input
                        name="email"
                        :placeholder="$t('messages.email')"
                        type="email"
                        icon="envelope"
                        icon-pack="fas"
                        v-model="form.email"
                        v-validate="form_rules.email"
                        :disabled="loader">
                    </b-input>
                  </b-field>
                </div>

                <div class="col-lg-12 col-sm-12 pb-3">
                  <b-field
                      :type="{'is-danger': errors.first('password')}"
                      :message="errors.first('password')">
                    <b-input
                        ref="password"
                        name="password"
                        :placeholder="$t('messages.password')"
                        type="password"
                        icon="lock"
                        icon-pack="fas"
                        v-model="form.password"
                        v-validate="form_rules.password"
                        :disabled="loader">
                    </b-input>
                  </b-field>
                </div>

                <div class="col-lg-12 col-sm-12">
                  <b-field
                      :type="{'is-danger': errors.first('password_confirmation')}"
                      :message="errors.first('password_confirmation')">
                    <b-input
                        name="password_confirmation"
                        :placeholder="$t('messages.password_confirmation')"
                        type="password"
                        icon="lock"
                        icon-pack="fas"
                        v-model="form.password_confirmation"
                        :data-vv-as="$t('messages.password')"
                        v-validate="form_rules.password_confirmation"
                        :disabled="loader">
                    </b-input>
                  </b-field>
                </div>

                <div class="col-12 mt-3" v-if="!!message">
                  <article class="message is-success">
                    <div class="message-body">
                      {{ this.message }}
                    </div>
                  </article>
                </div>

                <div class="col-12">
                  <div class="pt-3"></div>
                </div>

                <div class="col-lg-12 col-sm-12">
                  <b-button
                      :disabled="errors.any() || loader"
                      native-type="submit"
                      class="button is-primary">
                    {{ $t('messages.password_reset') }}
                  </b-button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-sm-12 justify-content-center align-self-center">
          <img src="/images/resources/png/recuperar_contrasena.png" />
        </div>
      </div>
    </template>
    </template>
    <template v-else>
      <login></login>
    </template>
  </div>
</template>

<script>
import { index } from "../../../helpers";
import Login from "../Login";

export default {
  name: "ResetPassword",
  components: {'login': Login },
  beforeMount () {
    if (this.isPhone) {
      this.form.email = 'apithy@apithy.com'
      this.form.user = this.user
    }
    this.form.token = this.token
  },
  methods: {
    validateForm () {
      this.$validator.validateAll()
          .then((result) => {
            if (result) {
              this.resetPassword()
              return false;
            }
          }).catch(() => {});
    },
    resetPassword () {
      this.loader = true
      let url = (this.isPhone) ? '/password/reset/phone' : '/password/reset'
      axios.post(url, this.form)
          .then(response => {
            this.message = ((this.isPhone) ? 'Contraseña actualizada' : _.get(response, ['data', 'data'], 'Contraseña actualizada'))

            setTimeout(() => {
              //window.location.href = route('login')
              this.show_login = true;
            }, 2500)
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
    }
  },
  props: {
    token: {
      required: true,
      type: String
    },
    user: {
      required: true,
      default: null
    },
    isPhone: {
      required: true,
      default: false
    }
  },
  data () {
    return {
      show_login: false,
      message: null,
      loader: false,
      showForm: true,
      form: {
        email: null,
        password: null,
        password_confirmation: null,
        token: null,
        user: null
      },
      form_rules: {
        email: 'required|email|max:255',
        password: 'required|min:6',
        password_confirmation: 'required|confirmed:password',
        token: 'required'
      },
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