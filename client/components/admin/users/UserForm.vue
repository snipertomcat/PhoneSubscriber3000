<template>
  <form class="" @submit.prevent="validateForm">
    <div class="card-content control">
      <div class="row">
        <div class="col-12 margin-b-10">
            <span class="has-text-weight-bold font-20">
              {{ $t('messages.users_form.messages.form_title') }}
            </span>
        </div>
        <div class="col-12 row margin-b-10">
          <div class="col-12 col-md-4">
            <b-field
                custom-class="has-text-weight-bold font-18"
                :message="errors.first('name')"
                :type="errors.has('name') ? 'is-danger': ''"
                :label="$t('messages.users_form.form.name')">
              <b-input
                  class="font-18"
                  type="text"
                  name="name"
                  id="name"
                  icon="user"
                  icon-pack="fas"
                  v-model="form.name"
                  v-validate="form_rules.name"
                  :disabled="disabled"
                  :placeholder="$t('messages.users_form.form.name_label')">
              </b-input>
            </b-field>
          </div>
          <div class="col-12 col-md-4">
            <b-field
                custom-class="has-text-weight-bold font-18"
                :message="errors.first('surname')"
                :type="errors.has('surname') ? 'is-danger': ''"
                :label="$t('messages.users_form.form.last_name')">
              <b-input
                  class="font-18"
                  icon-pack="fas"
                  name="surname"
                  id="surname"
                  icon="user"
                  type="text"
                  v-model="form.surname"
                  v-validate="form_rules.surname"
                  :disabled="disabled"
                  :placeholder="$t('messages.users_form.form.last_name_label')">
              </b-input>
            </b-field>
          </div>
          <div class="col-12 col-md-4">
            <b-field
                custom-class="has-text-weight-bold font-18"
                :message="errors.first('email')"
                :type="errors.has('email') ? 'is-danger': ''"
                :label="$t('messages.users_form.form.email')">
              <b-input
                  class="font-18"
                  type="text"
                  name="email"
                  id="email"
                  icon="at"
                  icon-pack="fas"
                  v-model="form.email"
                  v-validate="form_rules.email"
                  :disabled="disabled"
                  @blur="verifyDomain(form.email)"
                  :placeholder="$t('messages.users_form.form.email_label')">
              </b-input>
            </b-field>
          </div>
        </div>
        <div class="col-12 row margin-b-10">
          <div class="col-12 col-md-4">
            <b-field
                custom-class="has-text-weight-bold font-18"
                :message="errors.first('phone')"
                :type="errors.has('phone') ? 'is-danger': ''"
                :label="$t('messages.users_form.form.phone')">
              <b-input
                  class="font-18"
                  type="text"
                  name="phone"
                  id="phone"
                  icon="phone"
                  icon-pack="fas"
                  maxlength="10"
                  v-model="form.phone"
                  v-validate="form_rules.phone"
                  :disabled="disabled"
                  @keypress.native="isNumber"
                  :placeholder="$t('messages.users_form.form.phone_label')">
              </b-input>
            </b-field>
          </div>
          <div class="col-12 col-md-4">
            <b-field
                custom-class="has-text-weight-bold font-18"
                :message="errors.first('gender')"
                :type="errors.has('gender') ? 'is-danger': ''"
                :label="$t('messages.users_form.form.gender')">
              <el-select
                  id="gender"
                  name="gender"
                  class="width-100 font-18"
                  v-model="form.gender"
                  v-validate="form_rules.gender"
                  :disabled="disabled"
                  :placeholder="$t('messages.users_form.form.gender_label')">
                <el-option
                    v-for="(gender, index) in genders"
                    :key="index"
                    :label="gender.name"
                    :value="gender.id">
                </el-option>
              </el-select>
            </b-field>
          </div>
          <div class="col-12 col-md-4">
            <b-field
                custom-class="has-text-weight-bold font-18"
                :label="$t('messages.users_form.form.birthday')">
              <b-datepicker
                  id="birthday"
                  name="birthday"
                  :placeholder="$t('messages.users_form.form.birthday_label')"
                  icon="calendar-today"
                  :disabled="disabled"
                  v-model="form.birthday"
                  editable>
              </b-datepicker>
            </b-field>
          </div>
        </div>
        <div class="col-12 row margin-b-10">
          <div class="col-12 col-md-4">
            <b-field
                custom-class="has-text-weight-bold font-18"
                :message="errors.first('role_id')"
                :type="errors.has('role_id') ? 'is-danger': ''"
                :label="$t('messages.users_form.form.role')">
              <el-select
                  id="gender"
                  name="gender"
                  class="width-100 font-18"
                  v-model="form.role_id"
                  :disabled="disabled"
                  :placeholder="$t('messages.users_form.form.role_label')">
                <el-option
                    v-for="role in roles"
                    :key="role.id"
                    :label="role.name"
                    :value="role.id">
                </el-option>
              </el-select>
            </b-field>
          </div>
          <div class="col-12 col-md-4">
            <b-field
                custom-class="has-text-weight-bold font-18"
                :message="errors.first('status')"
                :type="errors.has('status') ? 'is-danger': ''"
                :label="$t('messages.users_form.form.status')">
              <el-select
                  id="status"
                  name="status"
                  class="width-100 font-18"
                  v-model="form.status"
                  v-validate="form_rules.status"
                  :disabled="disabled"
                  :placeholder="$t('messages.users_form.form.status_label')">
                <el-option
                    v-for="status in activated_list"
                    :key="status.value"
                    :label="status.label"
                    :value="status.value">
                </el-option>
              </el-select>
            </b-field>
          </div>
          <div class="col-12 col-md-4" v-if="!is_super">
            <b-field
                custom-class="has-text-weight-bold font-18"
                :message="errors.first('personal_code')"
                :type="errors.has('personal_code') ? 'is-danger': ''"
                :label="$t('messages.users_form.form.personal_code')">
              <b-input
                  class="font-18"
                  type="text"
                  name="personal_code"
                  id="personal_code"
                  icon="id-card"
                  icon-pack="fas"
                  v-model="form.personal_code"
                  v-validate="form_rules.personal_code"
                  :disabled="disabled"
                  :placeholder="$t('messages.users_form.form.personal_code_label')">
              </b-input>
            </b-field>
          </div>
          <div class="col-12 col-md-4" v-if="is_super">
            <b-field
                custom-class="has-text-weight-bold font-18"
                :message="errors.first('company_id')"
                :type="errors.has('company_id') ? 'is-danger': ''"
                :label="$t('messages.users_form.form.company')">
              <el-select
                  id="company_id"
                  name="company_id"
                  class="width-100 font-18"
                  v-model="form.company_id"
                  v-validate="form_rules.company_id"
                  filterable
                  :filter-method="getFilteredCompany"
                  @change="getByTaxonomy"
                  :disabled="disabled"
                  :placeholder="$t('messages.users_form.form.company_label')">
                <el-option
                    v-for="company in filterCompanies"
                    :key="company.id"
                    :label="company.name"
                    :value="company.id">
                </el-option>
              </el-select>
            </b-field>
          </div>
        </div>
      </div>
      <hr width="1">
      <div class="row">
        <div class="col-12 margin-b-10">
          <span class="has-text-weight-bold font-20">
            {{ $t('messages.users_form.messages.tag_title') }}
          </span>
        </div>
        <div class="col-12 row margin-b-10">
          <div class="col-12 col-md-4">
            <b-field
                  custom-class="has-text-weight-bold font-18"
                  :label="$t('messages.taxonomy.taxonomy_title.area')">
              <el-select
                    id="areas"
                    class="width-100 font-18"
                    v-model="form.taxonomy_areas"
                    :disabled="disabled"
                    multiple
                    :multiple-limit="1"
                    filterable
                    :filter-method="getFilteredArea"
                    :placeholder="$t('messages.users_form.form.area')"
                    :no-match-text="$t('messages.users_form.form.area_label')">
                <el-option
                    v-for="item in filterAreas"
                    :key="item.id"
                    :label="item.title"
                    :value="item.id">
                </el-option>
                <div slot="empty">
                  <p v-if="areaValue" class="has-text-weight-bold pointer text-center" @click="saveArea">
                    {{ $t('messages.users_form.form.create_area') }}
                  </p>
                  <p v-if="!areaValue" class="has-text-weight-bold text-center">
                    {{ $t('messages.users_form.form.write_area') }}
                  </p>
                </div>
              </el-select>
            </b-field>
          </div>
          <div class="col-12 col-md-4">
            <b-field
              custom-class="has-text-weight-bold font-18"
              :label="$t('messages.taxonomy.taxonomy_title.ability')">
              <el-select
                  id="abilities"
                  name="abilities"
                  class="width-100 font-18"
                  :disabled="disabled"
                  v-model="form.taxonomy_abilities"
                  filterable
                  multiple
                  :filter-method="getFilteredAbility"
                  :placeholder="$t('messages.users_form.form.ability')"
                  :no-match-text="$t('messages.users_form.form.ability_label')">
                <el-option
                    v-for="item in filterAbilities"
                    :key="item.id"
                    :label="item.title"
                    :value="item.id">
                </el-option>
                <div slot="empty">
                  <p v-if="abilityValue" class="has-text-weight-bold pointer text-center" @click="saveAbility">
                    {{ $t('messages.users_form.form.create_ability') }}
                  </p>
                  <p v-if="!abilityValue" class="has-text-weight-bold text-center">
                    {{ $t('messages.users_form.form.write_ability') }}
                  </p>
                </div>
              </el-select>
            </b-field>
          </div>
          <div class="col-12 col-md-4">
            <b-field
              custom-class="has-text-weight-bold font-18"
              :label="$t('messages.taxonomy.taxonomy_title.team')">
              <el-select
                  id="teams"
                  name="teams"
                  class="width-100 font-18"
                  :disabled="disabled"
                  v-model="form.taxonomy_teams"
                  filterable
                  multiple
                  :filter-method="getFilteredTeam"
                  :placeholder="$t('messages.users_form.form.team')"
                  :no-match-text="$t('messages.users_form.form.team_label')">
                <el-option
                    v-for="item in filterTeams"
                    :key="item.id"
                    :label="item.title"
                    :value="item.id">
                </el-option>
                <div slot="empty">
                  <p v-if="teamValue" class="has-text-weight-bold pointer text-center" @click="saveTeam">
                    {{ $t('messages.users_form.form.create_team') }}
                  </p>
                  <p v-if="!teamValue" class="has-text-weight-bold text-center">
                    {{ $t('messages.users_form.form.write_team') }}
                  </p>
                </div>
              </el-select>
            </b-field>
          </div>
        </div>
        <div class="col-12 row margin-b-10">
          <div class="col-12 col-md-4">
            <b-field
              custom-class="has-text-weight-bold font-18"
              :label="$t('messages.taxonomy.taxonomy_title.position')">
              <el-select
                  id="positions"
                  name="positions"
                  class="width-100 font-18"
                  :disabled="disabled"
                  v-model="form.taxonomy_positions"
                  multiple
                  :multiple-limit="1"
                  filterable
                  :filter-method="getFilteredPosition"
                  :placeholder="$t('messages.users_form.form.position')"
                  :no-match-text="$t('messages.users_form.form.position_label')">
                <el-option
                    v-for="item in filterPositions"
                    :key="item.id"
                    :label="item.title"
                    :value="item.id">
                </el-option>
                <div slot="empty">
                  <p v-if="positionValue" class="has-text-weight-bold pointer text-center" @click="savePosition">
                    {{ $t('messages.users_form.form.create_position') }}
                  </p>
                  <p v-if="!positionValue" class="has-text-weight-bold text-center">
                    {{ $t('messages.users_form.form.write_position') }}
                  </p>
                </div>
              </el-select>
            </b-field>
          </div>
          <div class="col-12 col-md-4">
            <b-field
              custom-class="has-text-weight-bold font-18"
              :label="$t('messages.taxonomy.taxonomy_title.certification')">
              <el-select
                  id="certifications"
                  name="certifications"
                  class="width-100 font-18"
                  :disabled="disabled"
                  v-model="form.taxonomy_certifications"
                  filterable
                  multiple
                  :filter-method="getFilteredCertification"
                  :placeholder="$t('messages.users_form.form.certification')"
                  :no-match-text="$t('messages.users_form.form.certification_label')">
                <el-option
                    v-for="item in filterCertifications"
                    :key="item.id"
                    :label="item.title"
                    :value="item.id">
                </el-option>
                <div slot="empty">
                  <p v-if="certificationValue" class="has-text-weight-bold pointer text-center" @click="saveCertification">
                    {{ $t('messages.users_form.form.create_certification') }}
                  </p>
                  <p v-if="!certificationValue" class="has-text-weight-bold text-center">
                    {{ $t('messages.users_form.form.write_certification') }}
                  </p>
                </div>
              </el-select>
            </b-field>
          </div>
          <div class="col-12 col-md-4">
            <b-field
              custom-class="has-text-weight-bold font-18"
              :label="$t('messages.taxonomy.taxonomy_title.custom')">
              <el-select
                  id="customs"
                  name="customs"
                  class="width-100 font-18"
                  :disabled="disabled"
                  v-model="form.taxonomy_customs"
                  filterable
                  multiple
                  :filter-method="getFilteredCustom"
                  :placeholder="$t('messages.users_form.form.custom')"
                  :no-match-text="$t('messages.users_form.form.custom_label')">
                <el-option
                    v-for="item in filterCustoms"
                    :key="item.id"
                    :label="item.title"
                    :value="item.id">
                </el-option>
                <div slot="empty">
                  <p v-if="customValue" class="has-text-weight-bold pointer text-center" @click="saveCustom">
                    {{ $t('messages.users_form.form.create_custom') }}
                  </p>
                  <p v-if="!customValue" class="has-text-weight-bold text-center">
                    {{ $t('messages.users_form.form.write_custom') }}
                  </p>
                </div>
              </el-select>
            </b-field>
          </div>
        </div>
      </div>
      <br>
      <div class="row justify-content-center pt-5">
        <div class="col-12 col-md-3">
          <button type="submit" class="button is-link create-user-btn width-100" :disabled="errors.any() || disabled">
            {{$t('messages.add')}}
          </button>
        </div>
      </div>
      <br>
    </div>
  </form>
</template>

<script>
import vue from 'vue'
import { index } from "../../../helpers";
import { Select, Option } from 'element-ui'
import {TaxonomyService} from "../../../services/taxonomy/TaxonomyService";
import {AdminService} from "../../../services/Admin/AdminService";

vue.component(Select.name, Select)
vue.component(Option.name, Option)

export default {
  name: "UserForm",
  beforeMount () {
    this.setFilters();
    this.setForm();
    this.valid_domain_list = index.validDomains(this.valid_domains);
  },
  mounted () {
    this.$validator.extend('verify_email_exist', index.getAsyncValidatorConfig('email'));
    this.$validator.extend('verify_personal_code_exist', index.getAsyncValidatorConfig('personal_code'));
  },
  computed: {
    disabled () {
      return this.loader;
    }
  },
  methods: {
    setFilters () {
      this.filterAreas = Object.assign([], this.taxonomy_areas);
      this.filterAbilities = Object.assign([], this.taxonomy_abilities);
      this.filterCustoms = Object.assign([], this.taxonomy_customs);
      this.filterPositions = Object.assign([], this.taxonomy_positions);
      this.filterCertifications = Object.assign([], this.taxonomy_certifications);
      this.filterTeams = Object.assign([], this.taxonomy_teams);
      this.filterCompanies = Object.assign([], this.companies);
    },
    setForm () {
      this.form.company_id = this.current_company;
    },
    clearFilters () {
      this.filterAreas = [];
      this.filterAbilities = [];
      this.filterCustoms = [];
      this.filterPositions = [];
      this.filterCertifications = [];
      this.filterTeams = [];
      this.form.taxonomy_abilities = [];
      this.form.taxonomy_areas = [];
      this.form.taxonomy_teams = [];
      this.form.taxonomy_positions = [];
      this.form.taxonomy_certifications = [];
      this.form.taxonomy_customs = [];
    },
    getByTaxonomy (company_id) {
      this.clearFilters()
      TaxonomyService.getByTaxonomy({type: 'all', company_id: company_id, ajax: 1})
          .then(response => {
            let data = _.get(response, ['data', 'data'])
            this.filterAreas = _.get(data, ['taxonomy_areas']);
            this.filterAbilities = _.get(data, ['taxonomy_abilities']);
            this.filterCustoms = _.get(data, ['taxonomy_abilities']);
            this.filterPositions = _.get(data, ['taxonomy_positions']);
            this.filterCertifications = _.get(data, ['taxonomy_certifications']);
            this.filterTeams = _.get(data, ['taxonomy_teams']);
          })
    },
    verifyDomain (element, force = false) {
      this.$validator.validate('email')
          .then(result => {
            if (result) {
              let valid_domains = this.valid_domain_list;
              let element_domain = element.split('@');
              if (!_.has(element_domain, [1])) {
                return false;
              }
              if (force) {
                this.valid_domain_list.push({domain: element_domain[1]});
                return true;
              }
              let isValid = _.find(valid_domains, x => { return x.domain === element_domain[1] });
              if (!isValid) {
                let title = this.$t('messages.global_form.domain.title');
                let message = this.$t('messages.global_form.domain.message', {domain: element_domain[1]});
                this.$snotify.warning(
                    message,
                    title,
                    {
                      buttons: [
                        {
                          text: this.$t('messages.users_form.messages.yes'),
                          action: (alert) => {
                            this.verifyDomain(element, true);
                            let aux = index.extractEmailDomain(element)
                            if (!_.isEmpty(aux)) {
                              setTimeout(() => {
                                let form = {
                                  valid_domains: this.valid_domain_list
                                }
                                AdminService.updateAllowedDomain(form)
                              }, 500)
                            }
                            this.$snotify.remove(alert.id);
                          }
                        },
                        {
                          text: this.$t('messages.users_form.messages.no'),
                          action: (alert) => {
                            this.form.email = null;
                            this.$snotify.remove(alert.id);
                          }
                        }
                      ],
                      showProgressBar: false,
                      closeOnClick: false,
                      backdrop: 0.6
                    });
              }
            }
          });
    },
    isNumber (evt) {
      return index.onlyNumbers(evt)
    },
    validateForm() {
      this.$validator.validateAll()
          .then((result) => {
            if (result) {
              this.store();
              return false;
            }
          })
          .catch(() => {});
    },
    store () {
      this.loader = true;
      let title = this.$t('messages.users_form.messages.store_title');
      let message = this.$t('messages.users_form.messages.store_message');
      this.$snotify.async(title, message, () => new Promise((resolve, reject) => {
        this.form.empty_ability = _.isEmpty(this.form.taxonomy_abilities);
        this.form.empty_area = _.isEmpty(this.form.taxonomy_areas);
        this.form.empty_team = _.isEmpty(this.form.taxonomy_teams);
        this.form.empty_position = _.isEmpty(this.form.taxonomy_positions);
        this.form.empty_certification = _.isEmpty(this.form.taxonomy_certifications);
        this.form.empty_custom = _.isEmpty(this.form.taxonomy_customs);

        return axios.post(route('users.store'), this.form)
            .then(response => {
              title = this.$t('messages.users_form.messages.store_success');
              this.loader = false;
              resolve({
                title: title,
                body: title,
                config: {
                  closeOnClick: true,
                  backdrop: 0.6,
                  timeout: 2000
                }
              });
              setTimeout(() => {
                document.location.href = route('users.index');
              },2000)
            })
            .catch(errors => {
              let errorList = index.hasErrors(errors)
              if (errorList) {
                index.setErrors(this.errors, errorList)
                reject({
                  title: 'Validación',
                  body: 'Hay campos no válidos',
                  config: {
                    closeOnClick: true,
                    backdrop: 0.6,
                    timeout: 2000
                  }
                });
              } else {
                title = 'Error!!!';
                reject({
                  title: title,
                  body: _.get(errors, ['data', 'message'], 'Desconocido'),
                  config: {
                    closeOnClick: true,
                    backdrop: 0.6,
                    timeout: 2000
                  }
                });
              }
              this.loader= false;
            })
      }), { backdrop: 0.6 });
    },
    saveArea () {
      if (_.isEmpty(this.areaValue)) {
        return false
      }
      let data = {
        id: this.areaValue,
        title: this.areaValue
      }
      this.taxonomy_areas.push(data)
      this.form.taxonomy_areas = []
      this.form.taxonomy_areas.push(data.id)
      return false
    },
    getFilteredArea (text) {
      this.filterAreas = this.taxonomy_areas.filter(option => {
        return index.search(text, option, ['title'])
      })
      if (_.isEmpty(this.filterAreas)) {
        this.areaValue = text
      } else {
        this.areaValue = null
      }
    },
    saveAbility () {
      if (_.isEmpty(this.abilityValue)) {
        return false
      }
      let data = {
        id: this.abilityValue,
        title: this.abilityValue
      }
      this.taxonomy_abilities.push(data)
      this.form.taxonomy_abilities.push(data.id)
      return false
    },
    getFilteredAbility (text) {
      this.filterAbilities = this.taxonomy_abilities.filter(option => {
        return index.search(text, option, ['title'])
      })
      if (_.isEmpty(this.filterAbilities)) {
        this.abilityValue = text
      } else {
        this.abilityValue = null
      }
    },
    saveTeam () {
      if (_.isEmpty(this.teamValue)) {
        return false
      }
      let data = {
        id: this.teamValue,
        title: this.teamValue
      }
      this.taxonomy_teams.push(data)
      this.form.taxonomy_teams.push(data.id)
      return false
    },
    getFilteredTeam (text) {
      this.filterTeams = this.taxonomy_teams.filter(option => {
        return index.search(text, option, ['title'])
      })
      if (_.isEmpty(this.filterTeams)) {
        this.teamValue = text
      } else {
        this.teamValue = null
      }
    },
    savePosition () {
      if (_.isEmpty(this.positionValue)) {
        return false
      }
      let data = {
        id: this.positionValue,
        title: this.positionValue
      }
      this.taxonomy_positions.push(data)
      this.form.taxonomy_positions = []
      this.form.taxonomy_positions.push(data.id)
      return false
    },
    getFilteredPosition (text) {
      this.filterPositions = this.taxonomy_positions.filter(option => {
        return index.search(text, option, ['title'])
      })
      if (_.isEmpty(this.filterPositions)) {
        this.positionValue = text
      } else {
        this.positionValue = null
      }
    },
    saveCertification () {
      if (_.isEmpty(this.certificationValue)) {
        return false
      }
      let data = {
        id: this.certificationValue,
        title: this.certificationValue
      }
      this.taxonomy_certifications.push(data)
      this.form.taxonomy_certifications.push(data.id)
      return false
    },
    getFilteredCertification (text) {
      this.filterCertifications = this.taxonomy_certifications.filter(option => {
        return index.search(text, option, ['title'])
      })
      if (_.isEmpty(this.filterCertifications)) {
        this.certificationValue = text
      } else {
        this.certificationValue = null
      }
    },
    saveCustom () {
      if (_.isEmpty(this.customValue)) {
        return false
      }
      let data = {
        id: this.customValue,
        title: this.customValue
      }
      this.taxonomy_customs.push(data)
      this.form.taxonomy_customs.push(data.id)
      return false
    },
    getFilteredCustom (text) {
      this.filterCustoms = this.taxonomy_customs.filter(option => {
        return index.search(text, option, ['title'])
      })
      if (_.isEmpty(this.filterCustoms)) {
        this.customValue = text
      } else {
        this.customValue = null
      }
    },
    getFilteredCompany (text) {
      this.filterCompanies = this.companies.filter(option => {
        return index.search(text, option, ['name'])
      })
    }
  },
  props: {
    taxonomy_areas: {
      type: Array,
      required: true
    },
    taxonomy_abilities: {
      type: Array,
      required: true
    },
    taxonomy_teams: {
      type: Array,
      required: true
    },
    taxonomy_positions: {
      type: Array,
      required: true
    },
    taxonomy_certifications: {
      type: Array,
      required: true
    },
    taxonomy_customs: {
      type: Array,
      required: true
    },
    roles: {
      type: Array,
      required: true
    },
    current_company: {
      type: Number,
      required: true
    },
    valid_domains: {
      required: true,
      type: Array
    },
    companies: {
      type: Array,
      default: []
    },
    is_super: {
      type: Number | Boolean,
      default: false
    }
  },
  data () {
    return {
      valid_domain_list: [],
      loader: false,
      filterAreas: [],
      areaValue: null,
      filterAbilities: [],
      abilityValue: null,
      filterPositions: [],
      positionValue: null,
      filterCertifications: [],
      certificationValue: null,
      filterCustoms: [],
      customValue: null,
      filterTeams: [],
      teamValue: null,
      is_editing: false,
      filterCompanies: [],
      form: {
        name: null,
        personal_code: null,
        surname: null,
        email: null,
        gender: 'M',
        role_id: 9,
        company_id: null,
        birthday: null,
        status: 4,
        activated: 1,
        register_type: 'admin',
        taxonomy_areas: [],
        taxonomy_positions: [],
        taxonomy_abilities: [],
        taxonomy_teams: [],
        taxonomy_certifications: [],
        taxonomy_customs: []
      },
      form_rules: {
        name: 'required|max:255',
        personal_code: 'max:255|verify_personal_code_exist',
        surname: 'required|max:255',
        email: 'required|email|max:255|verify_email_exist',
        phone: {
          digits: 10,
          numeric: true,
          regex: index.getPhoneRegex()
        },
        role_id: 'required|numeric',
        status: 'required|numeric',
        gender: 'required',
        company_id: 'required|numeric'
      },
      genders: index.genderList(),
      activated_list: index.activatedList()
    }
  }
}
</script>

<style scoped>
  .create-user-btn {
    margin: 0 auto;
  }
  .margin-b-10 {
    margin-bottom: 10px;
  }
</style>

<style>
  .dropdown-menu {
    display: block;
  }
</style>
