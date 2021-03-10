<template>
  <form class="row justify-content-center" @submit.prevent="validateForm">
    <slot name="token"></slot>
    <div class="col-6 col-md-3 block-centered" style="width: 200px;">
      <div class="card-image">
        <template v-if="isMobile().any()">
          <apithy-croppa
              :id="1"
              input_name="avatar"
              name="avatar"
              field="avatar"
              :height="200"
              :width="200"
              :quality="1"
              url="/profile/avatar"
              :image="avatar">
          </apithy-croppa>
        </template>
        <template v-else>
          <apithy-image-upload
              :id="1"
              field="avatar"
              input_name="avatar"
              name="avatar"
              url='/profile/avatar'
              :width="200"
              :height="200"
              :image="avatar">
          </apithy-image-upload>
        </template>
      </div>
      <div class="col-12 mt-3">
        <b-field
            :label="$t('messages.personal_code')"
            :type="(errors.has('personal_code')) ? 'is-danger' : ''"
            :message="errors.first('personal_code')">
          <b-input
              icon-pack="fas"
              icon="id-card"
              id="personal_code"
              name="personal_code"
              v-model="form.personal_code"
              v-validate="form_rules.personal_code"
              :disabled="loader">
          </b-input>
        </b-field>
      </div>
    </div>
    <div class="col-12 col-md-9 block-centered mt-3">
      <div class="row field">
        <div class="col-6">
          <b-field
              :label="$t('messages.name')"
              :type="(errors.has('name')) ? 'is-danger' : ''"
              :message="errors.first('name')">
            <b-input
                icon-pack="fas"
                icon="user"
                id="name"
                name="name"
                v-model="form.name"
                v-validate="form_rules.name"
                :disabled="loader">
            </b-input>
          </b-field>
        </div>
        <div class="col-6">
          <b-field
              :label="$t('messages.surname')"
              :type="(errors.has('surname')) ? 'is-danger' : ''"
              :message="errors.first('surname')">
            <b-input
                icon-pack="fas"
                icon="user"
                id="surname"
                name="surname"
                v-model="form.surname"
                v-validate="form_rules.surname"
                :disabled="loader">
            </b-input>
          </b-field>
        </div>
      </div>

      <div class="row field">
        <div class="col-6">
          <b-field
              :label="$t('messages.email')"
              :type="(errors.has('email') ? 'is-danger' : '')"
              :message="errors.first('email')">
            <b-input
                icon-pack="fas"
                icon="envelope"
                :disabled="hasEmail"
                id="email"
                name="email"
                v-model.once="form.email"
                v-validate="form_rules.email">
            </b-input>
          </b-field>
        </div>
        <div class="col-6">
          <b-field
              :label="$t('messages.contact_phone')"
              :type="(errors.has('phone') ? 'is-danger' : '')"
              :message="errors.first('phone')">
            <b-input
                @keydown.native="isNumber"
                maxlength="10"
                icon-pack="fas"
                icon="phone"
                id="phone"
                name="phone"
                v-model="form.phone"
                v-validate="form_rules.phone"
                :disbaled="loader">
            </b-input>
          </b-field>
        </div>
      </div>

      <div class="row field">
        <div class="col-6">
          <b-field
              :label="$t('messages.role')">
            <b-input
                icon-pack="fas"
                icon="chalkboard-teacher"
                id="role"
                name="role"
                v-model.once="form.role"
                disabled>
            </b-input>
          </b-field>
        </div>
        <div class="col-6">
          <b-field
              :label="$t('messages.company')">
            <b-input
                icon-pack="fas"
                icon="building"
                id="company"
                name="company"
                v-model.once="form.company"
                disabled>
            </b-input>
          </b-field>
        </div>
      </div>

      <div class="row field">
        <div class="col-6">
          <b-field
              label="Áreas">
            <el-select
                id="areas"
                name="areas"
                class="width-100"
                multiple
                v-model.once="form.taxonomy_areas"
                disabled="">
              <el-option
                  v-for="item in user.taxonomy_areas"
                  :key="item.id"
                  :label="item.title"
                  :value="item.id">
              </el-option>
            </el-select>
          </b-field>
        </div>
        <div class="col-6">
          <b-field
              label="Puestos">
            <el-select
                id="positions"
                name="positions"
                class="width-100"
                multiple
                v-model.once="form.taxonomy_positions"
                disabled="">
              <el-option
                  v-for="item in user.taxonomy_positions"
                  :key="item.id"
                  :label="item.title"
                  :value="item.id">
              </el-option>
            </el-select>
          </b-field>
        </div>
      </div>

      <br>
      <div class="row field">
        <div class="col-6">
          <b-field
              label="Género">
            <el-select
                id="gender"
                name="gender"
                class="width-100"
                v-model="form.gender"
                :disabled="loader"
                placeholder="Selecciona un género">
              <el-option
                  v-for="(gender, index) in genders"
                  :key="index"
                  :label="gender.name"
                  :value="gender.id">
              </el-option>
            </el-select>
          </b-field>
        </div>
        <div class="col-6">
          <b-field
              :label="$t('messages.birth_date')">
            <b-datepicker
                id="birthday"
                name="birthday"
                placeholder="Fecha de nacimiento"
                icon-pack="fas"
                icon="calendar"
                :disabled="loader"
                v-model="form.birthday"
                editable>
            </b-datepicker>
          </b-field>
        </div>
      </div>
      <br>
      <div class="field">
        <label class="label">Historia Acad&eacute;mica</label>
        <div class="control">
          <textarea
              class="textarea"
              id="academic_history"
              name="academic_history"
              rows="10"
              v-model="form.academic_history">
          </textarea>
        </div>
      </div>
      <br>
      <div class="field">
        <label class="label">Historia Laboral</label>
        <div class="control">
          <textarea
              class="textarea"
              id="work_history"
              name="work_history"
              rows="10"
              v-model="form.work_history">
          </textarea>
        </div>
      </div>

      <div v-if="errors.any()">
        <b-message type="is-danger">
          Verifica que todos los datos sean correctos.
        </b-message>
      </div>
      <br>
      <div class="control">
        <button class="button is-primary" type="submit" :disabled="errors.any() || loader">
          <span class="icon is-medium">
              <i class="fas fa-edit"></i>
          </span>
          <span>
            {{ $t('messages.save') }}
          </span>
        </button>
      </div>
      <hr>
    </div>
  </form>
</template>
<script>
import vue from 'vue'
import { index } from "../helpers";
import { Select, Option } from 'element-ui'

vue.component(Select.name, Select);
vue.component(Option.name, Option);

export default {
  name:'apithy-profile-form',
  components: {
    'apithy-image-upload': () => import('./ImageUpload'),
    'apithy-croppa': () => import('./ApithyCroppa')
  },
  beforeMount () {
    this.setForm();
  },
  computed: {
    hasEmail () {
      return !_.isEmpty(this.user.email);
    }
  },
  methods: {
    isMobile () {
      return index.isMobile()
    },
    setForm () {
      const form = Object.assign({}, this.user);
      this.avatar = _.get(form, ['avatar'], null);
      this.form.birthday = new Date(_.get(form, ['birthday']));
      this.form.name = _.get(form, ['name'], null);
      this.form.surname = _.get(form, ['surname'], null);
      this.form.personal_code = _.get(form, ['personal_code'], null);
      this.form.gender = _.get(form, ['gender'], null);
      this.form.email = _.get(form, ['email'], null);
      this.form.phone = _.get(form, ['phone'], null);
      this.form.company = _.get(form, ['company', ['name']], null);
      this.form.role = _.get(form, ['role', ['name']], null);
      this.form.work_history = _.get(form, ['work_history'], null);
      this.form.academic_history = _.get(form, ['academic_history'], null);

      this.form.taxonomy_areas = form.taxonomy_areas.map(item => {
        return item.id;
      });
      this.form.taxonomy_positions = form.taxonomy_positions.map(item => {
        return item.id;
      });
      this.form.taxonomy_abilities = form.taxonomy_abilities.map(item => {
        return item.id;
      });
      this.form.taxonomy_teams = form.taxonomy_teams.map(item => {
        return item.id;
      });
      this.form.taxonomy_certifications = form.taxonomy_certifications.map(item => {
        return item.id;
      });
      this.form.taxonomy_customs = form.taxonomy_customs.map(item => {
        return item.id;
      });
    },
    isNumber (evt) {
      return index.onlyNumbers(evt);
    },
    validateForm () {
      this.$validator.validateAll()
          .then((result) => {
            if (result) {
              this.asyncNotify();
              return false;
            }
          }).catch(() => {});
    },
    asyncNotify () {
      this.loader = true
      let form = _.cloneDeep(this.form);
      this.$snotify.async('Espere un momento por favor', 'Actualizando datos', () => new Promise((resolve, reject) => {
        if (this.hasEmail)
          delete form.email;
        return axios.post(route('profile.update'), form)
            .then(response => {
              this.loader = false
              resolve({
                title: response.data.title,
                body: response.data.message,
                config: {
                  closeOnClick: true,
                  timeout: 2000,
                  backdrop: 0.6
                }
              })
            })
            .catch(errors => {
              this.loader = false;
              let errs = index.hasErrors(errors)
              if (errs) {
                index.setErrors(this.errors, errs)
                reject({
                  title: 'Error de validación',
                  body: 'Verifica que todos los datos sean correctos.',
                  config: {
                    closeOnClick: true,
                    timeout: 2000,
                    backdrop: 0.6
                  }
                })
              } else {
                reject({
                  title: 'Error',
                  body: 'Ha ocurrido un error inesperado',
                  config: {
                    closeOnClick: true,
                    timeout: 2000,
                    backdrop: 0.6
                  }
                })
              }
            })
      }), {backdrop: 0.6})
    }
  },
  props: {
    user: {
      required: true,
      type: Object
    },
  },
  data() {
    return {
      loader: false,
      avatar: null,
      form: {
        name: null,
        surname: null,
        personal_code: null,
        gender: null,
        birthday: null,
        email: null,
        phone: null,
        company: null,
        role: null,
        work_history: null,
        academic_history: null
      },
      form_rules: {
        name: 'required|max:255',
        surname: 'required|max:255',
        personal_code: 'max:255',
        phone: {
          digits: 10,
          numeric: true,
          regex: index.getPhoneRegex()
        },
        email: 'required|email',
        gender: 'required'
      },
      genders: index.genderList()
    }
  }
}
</script>
