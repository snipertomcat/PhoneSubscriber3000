<template>
  <div id="register-form" class="container">
    <div>
      <div class="confirm-code-action" v-if="show_confirm_box && isEmpty(token)">
        <div class="col-sm-12 confirm-code-action-inner row">
          <div>
            <img src="/images/sms.png"/>
          </div>
          <div class="col-sm-10 col-9">
            <span class="head">Ya tengo un c&oacute;digo</span>
            <p>He recibido un código de confirmación por SMS</p>
            <a href="/register/confirm/phone" class="button-orange">Continuar</a>
          </div>
        </div>
      </div>
      <div class="row register-form" v-if="register_form.show">
        <div class="col-sm-12">
          <div class="row no-gutters">
            <div class="col-12 col-md-3 text-center pt-2">
              <div class="row">
                <figure class="image image-center pointer profile-avatar img-company">
                  <img :src="company.full_path_logo" alt="" class="is-230x230 img-responsive">
                </figure>
                <!--
                  <div class="social-connect mt-3 mt-lg-5">
                      <h3 class="text-center mb-2">Social connect</h3>
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
                  </div>
                 -->
              </div>
            </div>
            <div class="col-sm-9">
              <div class="alert alert-danger alert-dismissible fade show" role="alert" v-if="server_errors.show">
                <span v-for="(error, index) in server_errors.data">
                  <strong>Error: </strong>
                  {{error}}
                </span>
                <button type="button" class="close" aria-label="Close" @click="server_errors.show = false">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

              <div>
                <form>
                  <div class="row justify-content-md-center">
                    <div class="col-sm-12">
                      <h2>¿Como te llamas?</h2>
                      <p>Así aparecerá tu nombre en el espacio de trabajo.</p>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12 col-md-6 apithy-with-icon">
                      <div class="input-group">
                        <span
                          :class="{
                            'input-group-text icon':true,
                            'text-danger': errors.has('firstname'),
                          }">
                          <i class="fas fa-user"></i>
                        </span>
                        <input
                          type="text"
                          maxlength="50"
                          name="firstname"
                          placeholder="Nombre(s)"
                          :class="{
                            'col-sm-12 form-control':true,
                            'text-danger': errors.has('firstname'),
                          }"
                          v-model="register_form.form_data.user.firstname"
                          v-validate="validators.firstname">
                      </div>
                      <span class="text-danger" v-show="errors.has('firstname')">{{ errors.first('firstname') }}</span>
                    </div>
                    <div class="col-sm-12 col-md-6 apithy-with-icon">
                      <div class="input-group">
                        <span
                          :class="{
                            'input-group-text icon':true,
                            'text-danger': errors.has('lastname'),
                          }">
                          <i class="fas fa-user"></i>
                        </span>
                        <input
                          type="text"
                          maxlength="50"
                          name="lastname"
                          placeholder="Apellido(s)"
                          :class="{
                            'col-sm-12 form-control':true,
                            'text-danger': errors.has('lastname'),
                          }"
                          v-model="register_form.form_data.user.lastname"
                          v-validate="validators.lastname">
                      </div>
                      <span class="text-danger" v-show="errors.has('lastname')">{{ errors.first('lastname') }}</span>
                    </div>
                  </div>
                  <br><br>
                  <div class="row">
                    <div class="col-sm-12">
                      <h2>Configura tu contraseña</h2>
                      <span>Debe tener al menos 6 caracteres, una mayúscula y un número</span>
                      <div class="apithy-with-icon mt-3">
                        <div class="input-group">
                          <span
                            :class="{
                              'input-group-text icon':true,
                              'text-danger': errors.has('password'),
                            }">
                            <i class="fas fa-lock"></i>
                          </span>
                          <input
                            type="password"
                            name="password"
                            placeholder="Contraseña"
                            ref="password"
                            :class="{
                              'col-sm-12 form-control':true,
                              'text-danger': errors.has('password'),
                            }"
                            v-model="register_form.form_data.user.password"
                            v-validate="validators.pwd"
                            @input="passwordStrength">
                        </div>
                        <div class="password-strength progress">
                          <div class="progress-bar"
                               role="progressbar"
                               ref="password_progress_bar"
                               :aria-valuenow="password_strength"
                               aria-valuemin="0"
                               aria-valuemax="100">
                          </div>
                        </div>
                        <span class="text-danger" v-show="errors.has('password')">{{ errors.first('password') }}</span>
                      </div>
                      <br>
                      <div class="apithy-with-icon">
                        <div class="input-group">
                          <span
                            :class="{
                              'input-group-text icon':true,
                              'text-danger': errors.has('confirm_password'),
                            }">
                            <i class="fas fa-lock"></i>
                          </span>
                          <input
                            type="password"
                            name="confirm_password"
                            placeholder="Confirmar contraseña"
                            :class="{
                              'col-sm-12 form-control':true,
                              'text-danger': errors.has('confirm_password'),
                            }"
                            v-model="register_form.form_data.user.password_confirmation"
                            v-validate="validators.pwd_confirmation">
                        </div>
                        <span class="text-danger" v-show="errors.has('confirm_password')">
                          {{ errors.first('confirm_password') }}
                        </span>
                      </div>
                    </div>
                  </div>
                  <br><br>
                  <div class="row justify-content-md-center">
                    <div class="col-sm-12">
                      <h2>Datos de contacto</h2>
                      <!-- Con teléfono -->
                      <template v-if="isPhone">
                        <span>Regístrate con tu teléfono</span><br><br>
                        <div class="row">
                          <div class="col-sm-12 col-md-6 apithy-with-icon">
                            <div class="input-group">
                            <span
                                :class="{
                                'input-group-text icon':true,
                                'text-danger': errors.has('phone'),
                              }">
                              <i class="fa fa-phone"></i>
                            </span>
                              <input
                                  :disabled="!isEmpty(this.token)"
                                  type="phone"
                                  name="phone"
                                  ref="phone"
                                  placeholder="Teléfono"
                                  :class="{
                                    'col-sm-12 form-control':true,
                                    'text-danger': errors.has('phone')
                                  }"
                                  v-validate="validators.phone"
                                  v-model="register_form.form_data.user.phone">
                            </div>
                            <span class="text-danger" v-show="errors.has('phone')">
                              {{ errors.first('phone') }}
                            </span>
                          </div>
                          <div class="col-sm-12 col-md-6 apithy-with-icon">
                            <div class="input-group">
                            <span
                                :class="{
                                'input-group-text icon':true,
                                'text-danger': errors.has('phone_confirmation'),
                              }">
                              <i class="fa fa-phone"></i>
                            </span>
                              <input
                                  :disabled="!isEmpty(this.token)"
                                  type="phone"
                                  name="phone_confirmation"
                                  placeholder="Confirmar teléfono"
                                  :class="{
                                    'col-sm-12 form-control':true,
                                    'text-danger': errors.has('phone_confirmation')
                                  }"
                                  v-model="register_form.form_data.user.phone_confirmation"
                                  v-validate="validators.phone_confirmation">
                            </div>
                            <span class="text-danger" v-show="errors.has('phone_confirmation')">
                              {{ errors.first('phone_confirmation') }}
                            </span>
                          </div>
                        </div>
                      </template>
                      <!-- Con email -->
                      <template v-else>
                        <span>Regístrate con tu correo</span><br><br>
                        <div class="row">
                          <div class="col-sm-12 col-md-6 apithy-with-icon">
                            <div class="input-group">
                            <span
                                :class="{
                                'input-group-text icon':true,
                                'text-danger': errors.has('email'),
                              }">
                              <i class="fas fa-envelope"></i>
                            </span>
                              <input
                                  :disabled="!isEmpty(this.token)"
                                  type="email"
                                  name="email"
                                  ref="email"
                                  placeholder="Correo electrónico"
                                  :class="{
                                    'col-sm-12 form-control':true,
                                    'text-danger': errors.has('email')
                                  }"
                                  v-validate="validators.email"
                                  v-model="register_form.form_data.user.refer_email">
                            </div>
                            <span class="text-danger" v-show="errors.has('email')">{{ errors.first('email') }}</span>
                          </div>
                          <div class="col-sm-12 col-md-6 apithy-with-icon">
                            <div class="input-group">
                            <span
                                :class="{
                                'input-group-text icon':true,
                                'text-danger': errors.has('confirm_email'),
                              }">
                              <i class="fas fa-envelope"></i>
                            </span>
                              <input
                                  :disabled="!isEmpty(this.token)"
                                  type="email"
                                  name="confirm_email"
                                  placeholder="Confirmar correo electrónico"
                                  :class="{
                                    'col-sm-12 form-control':true,
                                    'text-danger': errors.has('confirm_email')
                                  }"
                                  v-model="register_form.form_data.user.email"
                                  v-validate="validators.email_confirmation">
                            </div>
                            <span class="text-danger" v-show="errors.has('confirm_email')">
                              {{ errors.first('confirm_email') }}
                            </span>
                          </div>
                          <br>
                          <br>
                          <div class="col-sm-12 col-md-12 apithy-with-icon">
                            <div class="input-group">
                        <span
                            :class="{
                            'input-group-text icon':true,
                            'text-danger': errors.has('personal_code'),
                          }">
                          <i class="fas fa-id-card"></i>
                        </span>
                              <input
                                  type="text"
                                  maxlength="255"
                                  name="personal_code"
                                  placeholder="Ingresa tu número de empleado"
                                  :class="{
                            'col-sm-12 form-control':true,
                            'text-danger': errors.has('personal_code'),
                          }"
                                  v-model="register_form.form_data.user.personal_code"
                                  v-validate="validators.personal_code">
                            </div>
                            <span class="text-danger" v-show="errors.has('personal_code')">{{ errors.first('personal_code') }}</span>
                          </div>
                        </div>
                      </template>
                      <br>
                    </div>
                  </div>
                  <br><br>
                </form>
              </div>
              <div>
                <div class="">
                  <div class="">
                    <div class="row align-items-center">
                      <div class="col-2 col-md-1">
                        <input type="checkbox" class="form-control width-70" v-model="legal.privacity.accepted">
                      </div>
                      <div class="col-10 col-md-11">
                        <span>
                          Al elegir "Aceptar" otorgas tu consentimiento sobre el
                          <a data-fancybox :data-src="legal.privacity.id" href="javascript:;">{{ legal.privacity.label }}</a>.
                        </span>
                      </div>
                    </div>
                    <div class="row align-items-center">
                      <div class="col-2 col-md-1">
                        <input type="checkbox" class="form-control width-70" v-model="legal.customer_services.accepted">
                      </div>
                      <div class="col-10 col-md-11">
                        <span>
                          Al elegir "Aceptar" comprendes y aceptas las
                          <a data-fancybox :data-src="legal.customer_services.id" href="javascript:;">{{ legal.customer_services.label }}</a>.
                        </span>
                      </div>
                    </div>
                    <br><br>
                    <div class="row justify-content-md-center">
                      <div class="col-sm-10 text-center">
                        <div class="continue-btn">
                          <button type="button" :disabled="accepted_legal_terms" class="btn btn-orange" @click="finishRegister">
                            Aceptar
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row justify-content-md-center register" v-if="register_email_resend.show">
        <div class="col-sm-12">
          <img src="/images/resources/svg/estasAUnPaso.svg" alt="" class="img-responsive one-step">
        </div>
        <div class="col-sm-10 mt-3">
          <h2>¡Est&aacute;s a un paso de ser parte de Apithy!</h2>
          <div>
            <p>
              Hemos enviado un correo para validar tu dirección de correo <b>{{ user.email }}</b>.
              <br>Da clic en el enlace para continuar el proceso.
              <br>
              <span v-if="register_email_resend.resend < 3">
                ¿No te ha llegado?
                <button class="btn btn-link" :disabled="!(register_email_resend.timer === 0)" @click="resend">
                  Reenvíalo
                </button>
                <br>
                <span v-if="register_email_resend.timer > 0">
                  Tienes que esperar {{ register_email_resend.timer }} segundos antes de que te podamos enviar otro correo electrónico.
                </span>
              </span>
            </p>
          </div>
        </div>
        <br><br>
      </div>
      <br><br>

      <terms-conditions></terms-conditions>
      <privacy></privacy>
      <service-agreement></service-agreement>
      <service-agreement-partner></service-agreement-partner>

    </div>
  </div>
</template>

<script>
  import VeeValidate from 'vee-validate';
  import es from 'vee-validate/dist/locale/es';
  import axios from 'axios';
  import TermsConditions from "./TermsConditions";
  import Privacy from "./Privacy";
  import ServiceAgreement from "./ServiceAgreement";
  import ServiceAgreementPartner from "./ServiceAgreementPartner";
  import { index } from "../../helpers";

  const dictionary = {
    es: {
      attributes: {
        firstname: 'nombre',
        lastname: 'apellido',
        email: 'correo electrónico',
        confirm_email: "correo electrónico",
        new_email: 'correo electrónico',
        confirm_new_email: 'confirmar correo electrónico',
        pwd: 'contraseña',
        pwd_confirmation: 'contraseña',
        confirm_password: 'confirmar contraseña',
        valid_domain: 'dominios validos',
        workspace: 'espacio de trabajo',
      },
      custom: {
        firstname: {
          required: 'El nombre es obligatorio.',
          alpha_spaces: 'El nombre sólo puede contener letras y espacios.',
          min: 'La longitud mínima del nombre es de 2 caracteres.',
          max: 'La longitud máxima del nombre es de 50 caracteres.',
        },
        lastname: {
          required: 'El apellido es obligatorio.',
          alpha_spaces: 'El apellido sólo puede contener letras y espacios.',
          min: 'La longitud mínima del apellido es de 2 caracteres.',
          max: 'La longitud máxima del apellido es de 50 caracteres.',
        },
        email: {
          required: 'La dirección de correo electrónico es obligatoria.',
          email: 'No es una dirección de correo electrónico válida.',
          regex: 'No es una dirección de correo electrónico válida.'
        },
        confirm_email: {
          required: 'La confirmación de la dirección de correo electrónico es obligatoria.',
          email: 'No es una dirección de correo electrónico válida.',
          confirmed: 'Las direcciones de correo electrónico no coinciden.'
        },
        email_confirmation: {
          required: 'La confirmación de la dirección de correo electrónico es obligatoria.',
          email: 'No es una dirección de correo electrónico válida.',
          confirmed: 'Las direcciones de correo electrónico no coinciden.'
        },
        new_email: {
          required: 'La dirección de correo electrónico es obligatoria.',
          email: 'No es una dirección de correo electrónico válida.',
          regex: 'No es una dirección de correo electrónico válida.'
        },
        confirm_new_email: {
          required: 'La confirmación de la dirección de correo electrónico es obligatoria.',
          email: 'No es una dirección de correo electrónico válida.',
          confirmed: 'Las direcciones de correo electrónico no coinciden.'
        },
        password: {
          required: 'La contraseña es obligatoria.',
          min: 'La longitud mínima de la contraseña es de 6 caracteres.'
        },
        confirm_password: {
          required: 'La confirmación de contraseña es obligatoria.',
          confirmed: 'Las contraseñas no coinciden.'
        },
        pwd: {
          required: 'La contraseña es obligatoria.',
          min: 'La longitud mínima de la contraseña es de 6 caracteres.'
        },
        pwd_confirmation: {
          required: 'La confirmación de contraseña es obligatoria.',
          confirmed: 'Las contraseñas no coinciden.'
        },
      }
    }
  };

  export default {
    name: "CompanyUserRegister",
    components: {
      ServiceAgreement,
      ServiceAgreementPartner,
      Privacy,
      TermsConditions,
    },
    beforeMount () {
      if (!this.isEmpty(this.email)) {
        this.register_form.form_data.user.refer_email = this.email;
        this.register_form.form_data.user.email = this.email;
      }
      if (!this.isEmpty(this.phone)) {
        if (index.isPhone(this.phone)) {
          this.setPhoneRules ();
          this.register_form.form_data.user.phone = this.phone;
          this.register_form.form_data.user.phone_confirmation = this.phone;
        }
      } else {
        this.setEmailRules()
      }
    },
    mounted () {
      VeeValidate.Validator.localize('es', es)
      VeeValidate.Validator.localize(dictionary);

      VeeValidate.Validator.extend('company_email', {
        getMessage: field => `El dominio de ${this.register_form.form_data.user.refer_email} no está aprobado para su registro en ${this.company.name}. Prueba usando tu cuenta de correo empresarial, o contacta con tu administrador para obtener una invitación.`,
        validate: value => {
          let valid_domains = _.get(this.company, ['valid_domains'], [])
          if (!_.isEmpty(valid_domains)) {
            let domains = index.validDomains(valid_domains)
            let domain = value.split('@');
            if (_.has(domain, [1])) {
              let find = _.find(domains, x => { return x.domain === domain[1] });
              return !!find
            }
          }
          return true
        }
      });

      VeeValidate.Validator.extend('verify_password', {
        getMessage: field => `La contraseña debe contener al menos: 6 caracteres, 1 letra mayúscula, 1 letra minúscula y 1 número.`,
        validate: value => {
          var strongRegex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{6,})");
          return strongRegex.test(value);
        }
      });

      this.$validator.extend('verify_email_exist', index.getAsyncValidatorConfig("email"));
      this.$validator.extend('verify_personal_code_exist', index.getAsyncValidatorConfig("personal_code"));

    },
    computed: {
      isPhone () {
        return index.isPhone(this.phone)
      },
      confirmation_code () {
        let digits = this.register_email_resend.digits_code;
        if(this.completed_confirmation_code) {
          return '' + digits.one + digits.two + digits.three + digits.four;
        }
        else {
          return null;
        }
      },
      completed_confirmation_code () {
        let digits = this.register_email_resend.digits_code;
        return (digits.one && digits.two && digits.three && digits.four);
      },
      terminos_y_condiciones() {
        return route('terminos-y-condiciones').toString();
      },
      accepted_legal_terms () {
        return !(this.legal.privacity.accepted && this.legal.customer_services.accepted);
      }
    },
    methods: {
      setEmailRules () {
        this.validators.email = {
          required: true,
          email: true,
          verify_email_exist: true,
          company_email: true,
          regex: '^[a-z0-9_+]([a-z0-9_+.]+[a-z0-9_+])?\\@[a-z0-9.-]+$'
        }
        this.validators.email_confirmation = {
          required: true,
          email: true,
          confirmed: 'email'
        }
      },
      setPhoneRules () {
        this.validators.phone = {
          required: true,
          regex: index.getOnlyNumberPhoneRegex()
        }
        this.validators.phone_confirmation = {
          required: true,
          regex: index.getOnlyNumberPhoneRegex(),
          confirmed: 'phone'
        }
      },
      resend () {
        if (this.register_email_resend.resend < 3) {
          axios({
            url: this.user.confirmation_url,
            method: 'GET'
          })
              .then(response => {
                this.register_email_resend.resend++;
                switch (this.register_email_resend.resend) {
                  case 1:
                    this.register_email_resend.timer = 60;
                    break;
                  case 2:
                    this.register_email_resend.timer = 180;
                    break;
                  default:
                    this.register_email_resend.timer = 60;
                    break;
                }
                this.countdown();
              })
              .catch(error => {
                console.log(error)
              })
        }
      },
      countdown () {
        setTimeout(() => {
          if (this.register_email_resend.timer > 0) {
            this.register_email_resend.timer--;
            this.countdown();
          }
        },1000);
      },
      passwordStrength (value) {
        let pwd = this.register_form.form_data.user.password;
        let pwd_strong = this.password_strength;
        let el = this.$refs.password_progress_bar;
        let strongRegex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[\.\,!@#\$%\^&\*])(?=.{8,})");
        let mediumRegex = new RegExp("^(((?=.*[a-z])(?=.*[0-9]))|((?=.*[A-Z])(?=.*[0-9])))(?=.{7,})");
        let lowRegex = new RegExp("^([A-Za-z]{6,})");

        if (pwd.length > 0) {
          if (strongRegex.test(pwd)) {
            pwd_strong = 100;
            el.style.backgroundColor = "#205500"; //Dark Green
            el.style.width = pwd_strong + '%';
          } else if (mediumRegex.test(pwd)) {
            pwd_strong = 75;
            el.style.backgroundColor = "#00E33F"; //Green
            el.style.width = pwd_strong + '%';
          } else if (lowRegex.test(pwd)) {
            pwd_strong = 50;
            el.style.backgroundColor = "#FFD800"; //Yellow
            el.style.width = pwd_strong + '%';
          } else {
            pwd_strong = 25;
            el.style.backgroundColor = "#FF2E09"; //Red
            el.style.width = pwd_strong + '%';
          }
        } else {
          pwd_strong = 0;
          el.style.backgroundColor = "#FF2E09"; //Red
          el.style.width = pwd_strong + '%';
        }
      },
      showRegister (target) {
        switch (target) {
          case 'form':
            this.register_type.show = false;
            this.register_form.show = true;
            this.register_email_resend.show = false;
            this.register_welcome.show = false;
            this.wrong.show = false;
            let token_field = document.getElementsByName('_token')[0];
            this.register_form.form_data.token = {name: token_field.name, value: token_field.value};
            break;
          case 'email_resend':
            this.register_type.show = false;
            this.register_form.show = false;
            this.register_email_resend.show = true;
            this.register_welcome.show = false;
            this.wrong.show = false;

            break;
          case 'welcome':
            this.register_type.show = false;
            this.register_form.show = false;
            this.register_email_resend.show = false;
            this.register_welcome.show = true;
            this.wrong.show = false;
            break;
          case 'wrong':
            this.wrong.show = true;
            this.register_type.show = false;
            this.register_form.show = false;
            this.register_email_resend.show = false;
            this.register_welcome.show = false;
            break;
          default:
            this.register_type.show = true;
            this.register_form.show = false;
            this.register_email_resend.show = false;
            this.register_welcome.show = false;
            this.wrong.show = false;
            break;
        }

        this.errors.clear();
      },
      finishRegister () {
        this.$validator.validateAll().then(result => {
          if (result) {
            this.prepareData();
          }
        });
      },
      prepareData () {
        let base_url = document.location.origin;

        let user = this.register_form.form_data.user;

        let contact_preferences = ((!_.isEmpty(this.phone)) ? 'sms' : this.register_form.form_data.contact_preferences)

        let user_params = {
          name: user.firstname,
          email: user.email,
          personal_code: user.personal_code,
          password: user.password,
          password_confirmation: user.password_confirmation,
          surname: user.lastname,
          phone: user.phone,
          phone_code: user.phone_code,
          company_id: this.company.id,
          area_id: '',
          position_id: '',
          register_type: ((this.isEmpty(this.invType)) ? 'on_site' : 'invitation'),
          role_id: 7,
          activated: 0,
          contact_preference: contact_preferences,
          by_phone: Number(!_.isEmpty(this.phone))
        };

        if (!this.isEmpty(this.token)) {
          user_params.invitation_code = this.token
        }

        this.send(base_url + '/register', 'POST', user_params);
      },
      send (url, method, params) {
        params[this.register_form.form_data.token.label] = this.register_form.form_data.token.value;
        axios({
          method: method,
          url: url,
          params: params
        }).then((response) => {
          this.user = _.get(response, ['data'], {});
          if (!this.isEmpty(this.token)) {
            if (this.isPhone) {
              window.location.href = `register/confirm/phone?token=${this.user.id}&phone=${this.phone}`
              return;
            }
          }
          this.show_confirm_box = false;
          this.user.confirmation_url = "/register/confirm/resend/" + this.user.confirmation_code;
          this.showRegister('email_resend');
        }).catch((error) => {
          let errors_list = error.response.data.errors;

          if (Array.isArray(errors_list)) {
            this.server_errors.data = errors_list;
          } else {
            this.server_errors.data = new Array(errors_list);
          }

          this.server_errors.show = true;

        });
      },
      setContactPreference (value) {
        this.register_form.form_data.contact_preferences = value;

        // set form for wrong contact email or phone
        if(value === 'sms') {
          this.wrong.phone = true;
        }
      },
      confirmValidationCode () {
        axios({
          method: 'GET',
          url: '/register/confirm/phone/' + this.confirmation_code,
          params: {
            user_id: this.user.id
          }
        })
            .then(response => {
              let status = response.data.status;
              let message = response.data.message;
              this.register_email_resend.message = message;

              if(status === 200) {
                setTimeout(() => {
                  this.showRegister('welcome');
                },2000);
                this.register_email_resend.class = 'text-success';
              } else {
                this.register_email_resend.class = 'text-danger';
              }
            })
            .catch(error => {
              let message = (error.response.data) ? error.response.data.message : error.message;
              let status = (error.response.data) ? error.response.data.status : error.status;

              this.register_email_resend.message = message;
              this.register_email_resend.class = 'text-red';
            })
      },
      isEmpty (value) {
        return _.isEmpty(value)
      }
    },
    props: {
      company: {
        required: true,
        type: Object
      },
      email: {
        type: String,
        default: ''
      },
      phone: {
        type: String,
        default: null
      },
      token: {
        type: String,
        default: ''
      },
      invType: {
        type: String,
        default: ''
      }
    },
    data () {
      return {
        access_data: {
          access: null,
          password: null
        },
        wrong: {
          counter: 0,
          show: false,
          phone: false,
          new_phone: null,
          confirm_new_phone: null,
          new_email: null,
          confirm_new_email: null
        },
        show_confirm_box: true,
        legal: {
          privacity: {
            id: '#privacy',
            label: 'Aviso de Privacidad',
            accepted: false,
          },
          customer_services: {
            id: '#terms_conditions',
            label: 'Condiciones de Servicio a Cliente',
            accepted: false,
          },
          company_services: {
            id: '#service_agreement',
            label: 'Acuerdos de servicio',
            accepted: false,
          },
          partner_services: {
            id: '#service_agreement_partner',
            label: 'Acuerdos de servicio',
            accepted: false,
          }
        },
        user: [],
        current_step: 1,
        server_errors: {
          show: false,
          data: []
        },
        register_email_resend: {
          resend: 0,
          timer: 0,
          show: false,
          message: null,
          class: '',
          digits_code: {
            one: null,
            two: null,
            three: null,
            four: null
          }
        },
        password_strength: 0,
        register_form: {
          show: true,
          type: {
            company: false,
            user: false
          },
          new_domain: '',
          form_data: {
            user_account: true,
            company_account: false,
            partner_account: false,
            contact_preferences: 'mail',
            token: {
              label: '',
              value: ''
            },
            user: {
              firstname: '',
              lastname: '',
              email: '',
              personal_code:'',
              refer_email: '',
              phone: '',
              phone_confirmation: '',
              phone_code: 52,
              country: 484,
              city: '',
              state: '',
              postal_code: '',
              password: '',
              password_confirmation: ''
            },
            company: {
              sector: '',
              size: '',
              name: '',
              website: '',
              r_social: '',
              rfc: '',
              country: 484,
              state: '',
              postal_code: '',
              workspace: '',
              domain: '',
              city: '',
              valid_domains: []
            }
          }
        },
        register_type: {
          show: false,
          company_domain: '',
          dont_exist: false
        },
        register_welcome: {
          show: false,
          loading: false,
          message: null,
        },
        validators: {
          firstname: {
            required: true,
            alpha_spaces: true,
            min: 2,
            max: 50
          },
          lastname: {
            required: true,
            alpha_spaces: true,
            min: 2,
            max: 50
          },
          personal_code: {
            required: false,
            alpha_spaces: false,
            min: 2,
            max: 50,
            verify_personal_code_exist: true,
          },
          new_email: {
            required: true,
            email: true,
            company_email: true,
            verify_email_exist: true,
            regex: '^[a-z0-9_+]([a-z0-9_+.]+[a-z0-9_+])?\\@[a-z0-9.-]+$'
          },
          confirm_new_email: {
            required: true,
            email: true,
            confirmed: 'new_email'
          },
          pwd: {
            required: true,
            min: 6,
            verify_password: true
          },
          pwd_confirmation: {
            required: true,
            confirmed: 'password'
          }
        }
      };
    }
  }
</script>

<style scoped>
  .width-100 {
    width: 100%;
  }

  .image-company img{
    width:80%;
    display:block;
    margin:0 auto;
  }

  .width-70 {
    width: 70%;
  }

  .override-btn {
    background-color: transparent !important;
    color: #FFA81E;
    font-weight: bold;
  }

  .form-control:focus {
    outline: 0 !important;
    border-color: initial;
    box-shadow: none;
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
    padding: 10px 10px;
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

  .continue-btn > button {
    font-size: 1.2rem !important;
    border: none !important;
    cursor: pointer;
    color: white !important;
  }

  .register-form {
    min-height: 620px;
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
    padding: 5px 10px 5px 10px;
    margin: 30px auto 10px auto;
    display: block;
  }

  .btn-primary-alter {
    color: #FFFFFF !important;
    background-color: #FFA81E !important;
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
    padding-left: 10px;
  }

  span.head {
    font-size: 1.5rem;
    font-weight: bold;
    font-family: 'Exo 2', sans-serif;
    display: block;
  }

  .password-strength {
    height: 10px;
    margin-top: 0.5rem;
  }

  .password-strength {
    width: 99% !important;
    margin-left: 4px !important;
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

  .profile-avatar {
    width: 80%;
    margin:0 auto;
    display:block;
  }

  .is-230x230 {
    width:80%;
    display:block;
    margin:0 auto;
  }
  .btn-orange {
    background-color: orange;
  }
  h2 {
    font-size: 1.5rem;
    font-weight: bold;
    font-family: 'Exo 2', sans-serif;
    margin-bottom: 15px;
  }

  /* Safari */
  @-webkit-keyframes spin {
    0% { -webkit-transform: rotate(0deg); }
    100% { -webkit-transform: rotate(360deg); }
  }

  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }

  @media all and (max-width: 641px) {

    .password-strength {
      width: 99% !important;
      margin-left: 2px !important;
    }

    .input-group {
      margin-bottom: 10px;
    }

    .image-company img{
      width:50%;
      display:block;
      margin:0 auto;
    }
  }
</style>
