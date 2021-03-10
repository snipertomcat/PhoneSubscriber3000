<template>
  <div class="">
    <div class="card">
      <div class="card-content">
        <div class="row">
          <div class="col-12">
            <form :action="route('register')" method="POST" ref="invitation_form">
              <div class="form-group invitation-method">
                <div class="row justify-content-center" v-if="is_organic">
                  <div class="col-12">
                    <b-field>
                      <b-input
                          :placeholder="$t('messages.email')"
                          v-model="form.email"
                          disabled
                          name="email"
                          icon-pack="fas"
                          icon="at">
                      </b-input>
                    </b-field>
                    <span class="text-danger" v-show="errors.has('email')">
                      {{ errors.first('email') }}
                    </span>
                  </div>
                </div>
                <div class="row justify-content-center" v-else>
                  <div class="col-12 col-md-6">
                    <b-field>
                      <b-input
                          :placeholder="$t('messages.invitation_code')"
                          icon-pack="fas"
                          icon="certificate"
                          name="invitation_code"
                          disabled
                          v-model="form.invitation_code">
                      </b-input>
                    </b-field>
                  </div>
                  <div class="col-12 col-md-6">
                    <b-field>
                      <b-input
                          :placeholder="$t('messages.email')"
                          v-model="form.email"
                          name="email"
                          icon-pack="fas"
                          icon="at"
                          ref="email"
                          disabled
                          v-validate="validations.emails">
                      </b-input>
                    </b-field>
                    <span class="text-danger" v-show="errors.has('email')">
                      {{ errors.first('email') }}
                    </span>
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-12 col-md-6">
                    <b-field class="input-group">
                      <b-input
                          :placeholder="$t('messages.name')"
                          icon="user"
                          icon-pack="fas"
                          name="name"
                          v-model="form.name"
                          v-validate="validations.name">
                      </b-input>
                    </b-field>
                    <span class="text-danger" v-show="errors.has('name')">
                      {{ errors.first('name') }}
                    </span>
                  </div>
                  <br>
                  <div class="col-12 col-md-6">
                    <b-field class="input-group">
                      <b-input
                          :placeholder="$t('messages.surname')"
                          icon="user"
                          icon-pack="fas"
                          name="surname"
                          v-model="form.surname"
                          v-validate="validations.surname">
                      </b-input>
                    </b-field>
                    <span class="text-danger" v-show="errors.has('surname')">
                      {{ errors.first('surname') }}
                    </span>
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-12 col-md-6">
                    <b-field>
                      <b-input
                          class="form-control"
                          :placeholder="$t('messages.password')"
                          icon="lock"
                          icon-pack="fas"
                          name="password"
                          type="password"
                          ref="password"
                          v-model="form.password"
                          v-validate="validations.password">
                      </b-input>
                    </b-field>
                    <span class="text-danger" v-show="errors.has('password')">
                      {{ errors.first('password') }}
                    </span>
                  </div>
                  <div class="col-12 col-md-6">
                    <b-field>
                      <b-input
                          class="form-control"
                          :placeholder="$t('messages.password_confirmation')"
                          icon="lock"
                          icon-pack="fas"
                          name="password_confirmation"
                          type="password"
                          v-model="form.password_confirmation"
                          v-validate="validations.confirm_password">
                      </b-input>
                    </b-field>
                    <span class="text-danger" v-show="errors.has('password_confirmation')">
                      {{ errors.first('password_confirmation') }}
                    </span>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
        <br>
        <div class="row justify-content-center">
          <div class="col-auto">
            <button class="button is-primary" @click="createAccount">
              {{ $t('messages.register') }}
            </button>
          </div>
        </div>
        <br>
        <div class="row justify-content-center">
          <div class="col-auto">
            <div class="visible content">
              {{ $t('messages.already_have_an_account') }}
            </div>
          </div>
          <div class="col-auto">
            <div class=" content">
              <a :href="route('login')">
                {{ $t('messages.login') }}
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import BField from "buefy/src/components/field/Field";
  import BInput from "buefy/src/components/input/Input";
  import VeeValidate from 'vee-validate';
  import es from 'vee-validate/dist/locale/es';
  import { index } from "../helpers";

  const dictionary = {
    es: {
      attributes: {
        name: 'nombre',
        surname: 'apellido',
        email: 'correo electrónico',
        confirm_email: "correo electrónico",
        password: 'contraseña',
        confirmation: 'contraseña',
        password_confirmation:'confirmar contraseña'
      }
    }
  };

  export default {
    name: "InvitationRegister",
    components: {
      BInput,
      BField
    },
    beforeMount () {
      VeeValidate.Validator.localize('es', es);
      VeeValidate.Validator.localize(dictionary);
      this.$validator.extend('email_exist', index.getAsyncValidatorConfig("email"));
      this.$validator.extend('password_format', {
        getMessage: field => `La contraseña debe contener al menos: 6 caracteres, 1 letra mayúscula, 1 letra minúscula y 1 número.`,
        validate: value => {
          let strongRegex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{6,})");
          return strongRegex.test(value);
        }
      });
    },
    mounted () {
      this.registerMode = ['on_site', 'invitation'].includes(this.mode) ? this.mode : 'invitation';
      this.form.invitation_code = (this.code) ? this.code : null;
    },
    methods: {
      createAccount () {
        this.$validator.validateAll().then(result => {
          if (result) {
            axios({
              url: route('register').toString(),
              method: 'POST',
              params: this.form
            })
                .then(response => {
                  document.location.href = route('login');
                });
          }
        })
      }
    },
    computed: {
      is_organic () {
        return (this.mode === 'on_site');
      }
    },
    props: {
      mode: {
        type: String,
        required: true
      },
      email: {
        type: String,
        required: true
      },
      code: {
        type: String,
        required: false
      }
    },
    data () {
      return {
        registerMode: 'on_site',
        form: {
          register_type: this.mode,
          invitation_code: this.code,
          email: this.email,
          name: null,
          surname: null,
          password: null,
          password_confirmation: null
        },
        validations: {
          name: {
            required: true,
            alpha_spaces: true
          },
          surname: {
            required: true,
            alpha_spaces: true
          },
          password: {
            required: true,
            password_format: true,
            //regex: "^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{6,})"
          },
          confirm_password: {
            required: true,
            confirmed: 'password'
          },
          emails: {
            required: true,
            email: true,
            email_exist: true,
            regex: '^(([^<>()\\[\\]\\\\.,;:\\s@"ç%&]+(\\.[^<>()\\[\\]\\\\.,;:\\s@"]+)*)|(".+"))@((\\[[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}])|(([a-zA-Z\\-0-9]+\\.)+[a-zA-Z]{2,}))$'
          },
        }
      };
    },
  }
</script>

<style scoped>
  .text-danger {
    color: #dc3545!important;
    font-size: .8rem;
    margin-left:5px;
  }
  .field:not(:last-child) {
    margin-bottom: 0 !important;
  }
</style>