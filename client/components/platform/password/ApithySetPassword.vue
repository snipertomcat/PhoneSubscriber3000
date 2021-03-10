<template>
  <div>
    <div class="row">
      <div class="col-lg-6 col-sm-12 d-flex align-items-center justify-content-center">
        <div class="row col-10">
          <div class="col-lg-12 col-sm-12">
            <h1>Establece tu Contrase&ntilde;a</h1>
          </div>
          <div class="col-lg-12 col-sm-12">
            <form class="row" @submit.prevent="validateForm">
              <div class="col-12 pb-3">
                <b-field
                  :type="{'is-danger': errors.first('password')}"
                  :message="errors.first('password')"
                >
                  <b-input
                    ref="password"
                    name="password"
                    :placeholder="$t('messages.password')"
                    type="password"
                    icon="lock"
                    icon-pack="fas"
                    v-model="form.password"
                    v-validate="form_rules.password"
                    :disabled="loader"
                  ></b-input>
                </b-field>
              </div>

              <div class="col-12">
                <b-field
                  :type="{'is-danger': errors.first('password_confirmation')}"
                  :message="errors.first('password_confirmation')"
                >
                  <b-input
                    name="password_confirmation"
                    :placeholder="$t('messages.password_confirmation')"
                    type="password"
                    icon="lock"
                    icon-pack="fas"
                    v-model="form.password_confirmation"
                    :data-vv-as="$t('messages.password')"
                    v-validate="form_rules.password_confirmation"
                    :disabled="loader"
                  ></b-input>
                </b-field>
              </div>

              <div class="col-12">
                <div class="row align-items-center pt-3">
                  <div class="col-12">
                    <input
                      id="condiciones"
                      name="conditions"
                      type="checkbox"
                      class="form-control width-70"
                      data-vv-as="condiciones"
                      v-validate="form_rules.conditions"
                      v-model="form.conditions"
                    />
                    <span class="ml-2">
                      Al elegir "Cambiar contrase&ntilde;a" comprendes y aceptas las
                      <u>
                        <a
                          data-fancybox
                          :data-src="customerServices.id"
                          href="javascript:void(0);"
                        >{{ customerServices.label }}</a>
                      </u>.
                    </span>
                    <div></div>
                    <p
                      class="pt-2 danger"
                      v-if="errors.has('conditions')"
                    >{{ errors.first('conditions') }}</p>
                  </div>
                </div>

                <div class="row align-items-center pt-3">
                  <div class="col-12">
                    <input
                      id="privacidad"
                      name="privacy"
                      type="checkbox"
                      class="form-control width-70"
                      data-vv-as="privacidad"
                      v-validate="form_rules.privacy"
                      v-model="form.privacy"
                    />
                    <span class="ml-2">
                      Al elegir "Cambiar contrase&ntilde;a" otorgas tu consentimiento sobre el
                      <u>
                        <a
                          data-fancybox
                          :data-src="privacy.id"
                          href="javascript:void(0);"
                          class
                        >{{ privacy.label }}</a>
                      </u>.
                    </span>
                    <div></div>
                    <p
                      class="pt-2 danger"
                      v-if="errors.has('privacy')"
                    >{{ errors.first('privacy') }}</p>
                  </div>
                </div>
              </div>

              <!-- <div class="col-12">
                <div class="pt-3"></div>
              </div>-->

              <div class="col-12 pt-3">
                <b-button
                  :disabled="errors.any() || loader"
                  native-type="submit"
                  class="button is-primary"
                >Cambiar contraseña</b-button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="col-lg-6 col-sm-12 d-flex justify-content-center">
        <img src="/images/resources/png/recuperar_contrasena.png" />
      </div>
    </div>
    <terms-conditions></terms-conditions>
    <privacy></privacy>
  </div>
</template>

<script>
import VeeValidate from "vee-validate";
import _ from "lodash";
import axios from "axios";
import { index } from "../../../helpers";

export default {
  name: "ApithySetPassword",
  components: {
    "terms-conditions": () => import("../../website/TermsConditions"),
    privacy: () => import("../../website/Privacy")
  },
  beforeMount() {
    this.setPasswordValidator();
  },
  methods: {
    setPasswordValidator() {
      VeeValidate.Validator.extend("verify_password", {
        getMessage: field =>
          `La contraseña debe contener al menos: 6 caracteres, 1 letra mayúscula, 1 letra minúscula y 1 número.`,
        validate: value => {
          let strongRegex = new RegExp(
            /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{6,}$/
          );
          return strongRegex.test(value);
        }
      });
    },
    validateForm() {
      this.$validator
        .validateAll()
        .then(result => {
          if (result) {
            this.setPassword();
            return false;
          }
        })
        .catch(() => {});
    },
    setPassword: _.debounce(function() {
      this.loader = true;
      axios
        .post(route("set-password.renewPassword", [this.dbToken]), this.form)
        .then(response => {
          return window.location.replace(
            _.get(response, ["data", "data", "login_link"], "/login")
          );
        })
        .catch(errors => {
          let errList = index.hasErrors(errors);
          if (errList) {
            index.setErrors(this.errors, errList);
          }
        })
        .finally(() => {
          this.loader = false;
        });
    })
  },
  props: {
    dbToken: {
      type: String | Number,
      default: ""
    }
  },
  data() {
    return {
      loader: false,
      form: {
        password: null,
        password_confirmation: null,
        token: this.dbToken,
        conditions: false,
        privacy: false
      },
      form_rules: {
        password: "required|verify_password",
        password_confirmation: "required|confirmed:password",
        token: "required",
        conditions: "required",
        privacy: "required"
      },
      customerServices: {
        id: "#terms_conditions",
        label: "Condiciones de Servicio a Cliente"
      },
      privacy: {
        id: "#privacy",
        label: "Aviso de Privacidad"
      }
    };
  }
};
</script>

<style scoped>
.danger {
  color: #ff3860;
}
</style>
