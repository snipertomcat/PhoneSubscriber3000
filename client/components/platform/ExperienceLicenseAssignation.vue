<template>
  <div class="">
    <div class="row justify-content-center">
      <div class="col-12 col-md-8">
        <br>
        <div class="row justify-content-center">
          <div class="col-12 has-text-centered">
            <span class="padding-r-10">Asignar estudiantes</span>
            <b-switch v-model="free_licences" @input="reset(true)"></b-switch>
            <span class="padding-l-10">Comprar licencias libres</span>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-12 col-lg-8" v-if="!free_licences">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-content">
                    <div class="row">
                      <div class="col-12">
                        <span class="font-25">
                          {{$t('messages.assignation.add_students')}}
                        </span>
                      </div>
                    </div>
                    <div class="row" v-if="has_areas">
                      <div class="col-12">
                        <b-field :label="$t('messages.assignation.add_by_area')">
                          <b-taginput
                              v-model="form.by_area"
                              :data="filterAreas"
                              field="title"
                              :allow-new="false"
                              :open-on-focus="true"
                              autocomplete
                              icon="sitemap"
                              icon-pack="fas"
                              type="is-primary"
                              @typing="getFilteredAreas"
                              @add="getUsersByTaxonomy"
                              @remove="removeTotalUser">
                          </b-taginput>
                        </b-field>
                      </div>
                    </div>
                    <br v-if="has_areas">
                    <div class="row" v-if="has_positions">
                      <div class="col-12">
                        <b-field :label="$t('messages.assignation.add_by_position')">
                          <b-taginput
                              v-model="form.by_position"
                              :data="filterPositions"
                              field="title"
                              :allow-new="false"
                              :open-on-focus="true"
                              autocomplete
                              icon="briefcase"
                              icon-pack="fas"
                              type="is-primary"
                              @typing="getTaxonomyPositions"
                              @add="getUsersByTaxonomy"
                              @remove="removeTotalUser">
                          </b-taginput>
                        </b-field>
                      </div>
                    </div>
                    <br v-if="has_positions">
                    <div class="row" v-if="has_tags">
                      <div class="col-12">
                        <b-field :label="$t('messages.assignation.add_by_tags')">
                          <b-taginput
                              v-model="form.by_tags"
                              :data="filterTags"
                              field="title"
                              :allow-new="false"
                              :open-on-focus="true"
                              autocomplete
                              icon="briefcase"
                              icon-pack="fas"
                              type="is-primary"
                              @typing="getFilteredTags"
                              @add="getUsersByTaxonomy"
                              @remove="removeTotalUser">
                          </b-taginput>
                        </b-field>
                      </div>
                    </div>
                    <br v-if="has_tags">
                    <div class="row" v-if="has_users">
                      <div class="col-12">
                        <b-field :label="$t('messages.assignation.add_by_user')">
                          <b-taginput
                              v-model="form.by_user"
                              :data="raw.user"
                              field="name"
                              :allow-new="false"
                              :open-on-focus="true"
                              autocomplete
                              icon="user"
                              icon-pack="fas"
                              type="is-primary"
                              :before-adding="beforeAddingUser"
                              @typing="getFilteredUsers"
                              @add="setTotalUser"
                              @remove="removeTotalUser">
                          </b-taginput>
                        </b-field>
                      </div>
                    </div>
                    <br v-if="has_users">
                    <div class="row">
                      <div class="col-12">
                        <div id="new_users">
                          <div class="has-text-weight-bold">
                            {{$t('messages.assignation.add_by_email')}}
                          </div>
                          <div>(Ingresa correos electrónicos separados por comas)</div>
                          <b-taginput
                              v-model="form.by_email"
                              field="email"
                              :allow-new="true"
                              icon="at"
                              icon-pack="fas"
                              type="is-primary"
                              @add="verifyDomain"
                              @remove="removeTotalUser">
                          </b-taginput>
                        </div>
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-12">
                        <div id="new_phones">
                          <div class="has-text-weight-bold">
                            Añadir por teléfono
                          </div>
                          <div>(Ingresa teléfonos separados por comas)</div>
                          <b-taginput
                              v-model="form.by_phone"
                              field="phone"
                              :allow-new="true"
                              icon="phone"
                              icon-pack="fa"
                              type="is-primary"
                              @add="verifyPhone"
                              @remove="removeTotalUser">
                          </b-taginput>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-lg-8" v-else>
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-container">
                    <div class="row align-items-center">
                      <div class="col-12 col-md-8">
                        <div>
                          <span class="has-text-weight-bold">Obtener licencias</span>
                        </div>
                        <p>
                          <b>¿Para cuántas personas es la experiencia?</b><br>
                          Estas licencias quedarán libres y puedes asignarlas después a
                          cualquier
                          estudiante.
                        </p>
                      </div>
                      <div class="col-12 col-md-4">
                        <div class="">
                          <b-field
                              :type="(errors.has('licences_number')) ? 'is-danger' : ''"
                              :message="errors.first('licences_number')">
                            <b-input
                                name="licences_number"
                                v-model="licences_number"
                                v-validate="validate.licences_number"
                                @keypress.native="$root.onlyNumber"
                                placeholder="Ingresa una cantidad">
                            </b-input>
                          </b-field>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-lg-4 mt-4 mt-lg-0">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-container">
                    <div class="font-20 has-text-left available-licences">
                      <span class="has-text-weight-bold font-25 margin-r-20">{{available_licences}}</span>
                      <span>{{ $t('messages.assignation.available_licences') }}</span>
                    </div>
                    <br>
                    <div class="font-20 has-text-left">
                      <span class="has-text-weight-bold font-25 margin-r-20">{{selected_students}}</span>
                      <span>{{ $t('messages.assignation.selected_students') }}</span>
                    </div>
                    <div class="font-20 has-text-left">
                      <span class="has-text-weight-bold font-25 margin-r-20">{{licences_to_buy}}</span>
                      <span>{{ $t('messages.assignation.licences_to_buy') }}</span>
                    </div>
                    <br>
                    <div class="font-20">
                      <span>{{ $t('messages.assignation.subtotal') }}</span>
                    </div>
                    <div class="font-25">
                      <span class="has-text-weight-bold" v-money-format="subtotal"></span>
                    </div>
                    <template v-if="budget !== null && typeof budget != 'undefined'">
                      <br>
                      <div class="font-20">
                        <span>{{ $t('messages.assignation.budget') }}</span>
                      </div>
                      <div class="font-25">
                        <span class="has-text-weight-bold" v-money-format="budget"></span>
                      </div>
                      <span v-if="budget < 0" class="has-text-danger">{{ $t('messages.assignation.insufficient_budget') }}</span>
                    </template>
                  </div>
                </div>
              </div>
            </div>
            <br>
            <div class="row" v-if="!free_licences && ((available_licences > 0 || licences_number === experience.admin_available_licences) && licences_to_buy === 0)">
              <div class="col-12 margin-b-10">
                <a :class="{
                    'button width-100 text-white':true,
                    'is-primary':(licences_number > 0 && licences_to_buy == 0),
                    'gray':(licences_number == 0 && licences_to_buy >= 0)
                  }"
                  :disabled="(licences_number == 0 && licences_to_buy >= 0)"
                   @click="assignateLicences">
                    <span class="icon">
                      <i class="fas fa-wallet"></i>
                    </span>
                  <span>{{$t('messages.assignate_licences')}}</span>
                </a>
              </div>
            </div>
            <div class="row" v-else>
              <div class="col-12 margin-b-10">
                <a :class="{
                    'button width-100 text-white':true,
                    'is-primary':(parseInt(licences_to_buy) > 0),
                    'gray':(parseInt(licences_to_buy) == 0)
                   }"
                   :disabled="parseInt(licences_to_buy) == 0"
                   @click="buyNow">
                    <span class="icon">
                      <i class="fas fa-wallet"></i>
                    </span>
                  <span>{{$t('messages.buy_now')}}</span>
                </a>
              </div>
              <div class="col-12" v-if="!onlyBuy">
                <a :class="{
                    'button width-100 text-white':true,
                    'is-primary':(parseInt(licences_to_buy) > 0),
                    'gray':(parseInt(licences_to_buy) == 0)
                   }"
                   :disabled="parseInt(licences_to_buy) == 0"
                   @click="addToCart">
                  <span class="icon">
                    <i class="fas fa-shopping-cart"></i>
                  </span>
                  <span>{{$t('messages.add_to_cart')}}</span>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import qs from "qs";
  import { TaxonomyService } from "../../services/taxonomy/TaxonomyService";
  import { index } from "../../helpers";
  import {UserService} from "../../services/Users/UserService";
  import { AdminService } from "../../services/Admin/AdminService";

  export default {
    name: "ExperienceLicenseAssignation",
    watch: {
      "licences_number": function (value) {
        if (parseInt(value)) {
          this.getSubtotal();
        }
      },
    },
    mounted() {
      this.getTaxonomy()
      this.raw.allowed_domains = index.validDomains(this.companyData.valid_domains);

      if (this.cartData.id) {
        this.free_licences = this.cartData.attributes.free_licences;
        if (this.free_licences) {
          this.is_fromCart = true;
          this.cartData.attributes.licences_number = parseInt(this.cartData.attributes.licences_number);
          this.licences_number = this.cartData.attributes.licences_number;
        } else {
          this.form.by_area = this.cartData.attributes.company_areas.map(item => {
            item.is_card = true
            this.setTotalUser(item);
            return item;
          });
          this.form.by_position = this.cartData.attributes.company_positions.map(item => {
            item.is_card = true
            this.setTotalUser(item);
            return item;
          });
          this.form.by_tags = this.cartData.attributes.company_tags.map(item => {
          item.is_card = true
            this.setTotalUser(item);
            return item;
          });
          this.form.by_user = this.cartData.attributes.company_users.map(item => {
            item.is_card = true
            this.setTotalUser(item);
            return item;
          });
          this.form.by_email = this.cartData.attributes.new_users.map(item => {
            this.verifyDomain(item, true);
            return item;
          });
          this.all_users = this.cartData.attributes.inherit_users;
          this.new_users = this.cartData.attributes.new_users;
          this.getSubtotal();
        }
      }

      this.budget = (this.companyData.budget_balance > 0) ? this.companyData.budget_balance : 0;
    },
    methods: {
      getTaxonomyAreas () {
        let form = {
          type: 'areas',
          is_paginate: 0,
          ajax: 1,
        }
        return TaxonomyService.getByTaxonomy(form)
      },
      getTaxonomyPositions () {
        let form = {
          type: 'positions',
          is_paginate: 0,
          ajax: 1,
        }
        return TaxonomyService.getByTaxonomy(form)
      },
      getTaxonomyTags () {
        let form = {
          type: 'tag',
          is_paginate: 0,
          ajax: 1,
        }
        return TaxonomyService.getByTaxonomy(form)
      },
      getTaxonomy () {
        let loader = index.getLoader()
        axios.all([this.getTaxonomyAreas(), this.getTaxonomyPositions(), this.getTaxonomyTags()])
            .then(axios.spread((taxArea, taxPositions, taxTags) => {
              let taxonomyAreas = _.get(taxArea, ['data', 'data'], [])
              let taxonomyPositions = _.get(taxPositions, ['data', 'data'], [])
              let taxonomyTags = _.get(taxTags, ['data', 'data'], [])

              this.filterAreas = taxonomyAreas
              this.filterPositions = taxonomyPositions
              this.filterTags = taxonomyTags

              this.raw.companyArea = taxonomyAreas;
              this.raw.companyPosition = taxonomyPositions;
              this.raw.companyTags = taxonomyTags;

              this.has_areas = !_.isEmpty(taxonomyAreas);
              this.has_positions = !_.isEmpty(taxonomyPositions);
              this.has_tags = !_.isEmpty(taxonomyTags);
            }))
            .catch(errors => {console.log(errors)})
            .then(() => {
              loader.close()
            })
      },
      verifyPhone (element) {
        if (index.isPhone(element)) {
          let data = {
            search: element,
            experience: this.experience.id
          }
          let user, license;
          axios.post(route('utilities.verify-new-user-email'), data)
              .then(response => {
                user = _.get(response, ['data', 'user'], null) | element
                license = _.get(response, ['data', 'license'], null)
              })
              .catch(e => {
                //
              })
              .then(() => {
                if (_.isEmpty(license)) {
                  this.setTotalUser(element);
                } else {
                  let name = ((!!user.name) ? `El usuario ${user.name}` : `El teléfono ${user}`);
                  this.$snotify.warning(`${name} ya tiene esta experiencia asignada o iniciada y no se tomará en cuenta.`, {
                    timeout: 2000,
                    showProgressBar: true,
                    closeOnClick: true,
                    pauseOnHover: true
                  });
                  let index = this.form.by_phone.indexOf(element);
                  this.form.by_phone.splice(index, 1);
                }
              })
        } else {
          let index = this.form.by_phone.indexOf(element);
          this.form.by_phone.splice(index, 1);
        }
      },
      verifyDomain(element, force = false) {
        let el = document.querySelector('#new_users input')
        el.blur()
        let validate_email = RegExp('^(([^<>()\\[\\]\\\\.,;:\\s@"ç%&]+(\\.[^<>()\\[\\]\\\\.,;:\\s@"]+)*)|(".+"))@((\\[[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}])|(([a-zA-Z\\-0-9]+\\.)+[a-zA-Z]{2,}))$', []);
        // RegExp('^([a-zA-Z0-9_\\-\\.]+)@((\\[[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.)|(([a-zA-Z0-9\\-]+\\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\\]?)$', []);
        let user;
        let license;
        if (validate_email.test(element)) {
          axios({
            url: route('utilities.verify-new-user-email'),
            method: 'POST',
            params: {
              search: element,
              experience: this.experience.id
            }
          })
              .then(response => {
                user = (!!response.data.user) ? response.data.user : element;
                license = (!!response.data.license) ? response.data.license : null;
              })
              .catch(error => {
                console.log(error)
              })
              .finally(() => {
                if (!!license) {
                  let name = (!!user.name) ? 'El usuario ' + user.name : 'El correo electrónico ' + user;
                  this.$snotify.warning(name + ' ya tiene esta experiencia asignada o iniciada y no se tomará en cuenta.', {
                    timeout: 2000,
                    showProgressBar: true,
                    closeOnClick: true,
                    pauseOnHover: true
                  });
                } else {
                  if (typeof user === 'string') {
                    let element_domain = element.split('@')[1];
                    if (force) {
                      this.raw.allowed_domains.push({domain: element_domain});
                    }
                    let valid_domain = (_.find(this.raw.allowed_domains, {domain: element_domain})) ? true : false;
                    if (valid_domain || force) {
                      this.setTotalUser(element);
                      el.focus()
                    } else {
                      let title = 'Email no reconocido';
                      let message = 'El dominio ' + element_domain + ' no pertenece a su lista de dominios, ¿deséa añadirlo aún así?';
                      this.$snotify.warning(
                          message, title, {
                            buttons: [
                              {
                                text: 'Si',
                                action: (alert) => {
                                  this.verifyDomain(element, true);
                                  let aux = index.extractEmailDomain(element)
                                  if (!_.isEmpty(aux)) {
                                    setTimeout(() => {
                                      let form = {
                                        valid_domains: this.raw.allowed_domains
                                      }
                                      AdminService.updateAllowedDomain(form)
                                    }, 500)
                                  }
                                  this.$snotify.remove(alert.id);
                                }
                              },
                              {
                                text: 'No',
                                action: (alert) => {
                                  let index = this.form.by_email.indexOf(element);
                                  this.form.by_email.splice(index, 1);
                                  this.$snotify.remove(alert.id);
                                  el.focus()
                                }
                              }
                            ],
                            showProgressBar: false,
                            closeOnClick: false,
                            timeout: 0,
                            backdrop: 0.6
                          });
                    }
                  } else {
                    this.setTotalUser(user);
                    this.form.by_user.push(user);
                    let index = this.form.by_email.indexOf(element);
                    this.form.by_email.splice(index, 1);
                  }
                }

              })
        } else {
          let index = this.form.by_email.indexOf(element);
          this.form.by_email.splice(index, 1);
        }
      },
      /**
       * Format money
       * @param n Number
       * @param c Precision
       * @param d Decimal separator
       * @param t Thousands separator
       * @param s Symbol
       * @returns {string} Formated number.
       */
      formatMoney(n, c, d, t, s) {
        let money_format = this.$root.money;
        c = isNaN(c = Math.abs(c)) ? money_format.precision : c;
        d = d == undefined ? money_format.decimal : d;
        t = t == undefined ? money_format.thousands : t;
        s = s == undefined ? money_format.prefix : s;
        s = n < 0 ? "-" + s : "" + s;
        let i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c)));
        let j = (j = i.length) > 3 ? j % 3 : 0;

        return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
      },
      getSubtotal() {
        if(!this.free_licences){
          this.selected_students = this.licences_number;
          this.licences_to_buy = (this.licences_number - this.experience.admin_available_licences > 0)
              ? this.licences_number - this.experience.admin_available_licences :0;
          this.subtotal = this.experience.price * this.licences_to_buy;
          this.budget = this.companyData.budget_balance - this.subtotal;

          this.available_licences = (this.experience.admin_available_licences - this.licences_number > 0)
              ? this.experience.admin_available_licences - this.licences_number: 0;
        }else{
          this.licences_to_buy = parseInt(this.licences_number);
          this.subtotal = this.experience.price * this.licences_number;
          this.budget = this.companyData.budget_balance - this.subtotal;
        }

      },
      getUsersByTaxonomy (element) {
        let loader = index.getLoader({text: `Obteniendo usuarios de ${element.title}`})
        let form = {
          id: element.id,
          option: 1,
          experience: this.experience.id
        }
        TaxonomyService.getUserTaxonomy(form)
            .then(response => {
              let users = _.get(response, ['data', 'data'], [])
              element.users = users
              this.setTotalUser(element)
            })
            .catch(errors => {console.log(errors)})
            .then(() => {
              this.filterAreas = this.raw.companyArea
              this.filterPositions = this.raw.companyPosition
              this.filterTags = this.raw.companyTags
              loader.close()
            })
      },
      setTotalUser(element) {
        if (element.users) {
          _.each(element.users, user => {
            if (user.is_active) {
              this.addUser(user);
            }
          });
        } else if (element.id) {
          if(element.is_active) {
            this.addUser(element);
          } else if (element.is_card) {
            this.addUser(element);
          } else {
            this.$snotify.warning('El usuario que seleccionaste no se encuentra activo o fué eliminado', 'Advertencia',{
              timeout: 4000,
              showProgressBar: true,
              closeOnClick: true
            });
            _.pullAllBy(this.form.by_user, [{id: element.id}], 'id')
          }
        } else {
          let already_in = !!_.find(this.new_users, item => { return item === element });
          if (!already_in) {
            this.new_users.push(element);
          }
        }
        this.licences_number = this.all_users.length + this.new_users.length;
        this.getSubtotal();
      },
      beforeAddingUser (element) {
        let haveUser = !!_.find(this.form.by_user, {id: element.id})
        return !haveUser
      },
      addUser(user) {
        //this.all_users = _.union(this.all_users, user);
        let have_licence = !!_.find(user.licences, {'experience_id': this.experience.id, 'status': 1});
        let isnt_in_list = !_.find(this.all_users, user.id);

        if (have_licence) {
          this.$snotify.warning('El usuario ' + user.name + ' ya tiene esta experiencia iniciada, no se tomará en cuenta.', {
            timeout: 2000,
            showProgressBar: true,
            closeOnClick: true,
            pauseOnHover: true
          });
        } else {
          if (isnt_in_list) {
            this.all_users = _.union(this.all_users, [user.id]);
          }
        }
      },
      removeTotalUser(element) {
        let promise = new Promise((resolve,reject) => {
          try {
            if (element.users) {
              //this.removeUser(element.users_details.ids);
              _.each(element.users, user => {
                this.removeUser(user)
              })
            } else if (element.id) {
              this.removeUser(element);
            } else {
              this.removeUser(element);
            }
            resolve(true);
          } catch (e) {
            reject(e);
          }
        });

        promise.then(result => {
          _.each(this.form, list => {
            if (list.length) {
              _.each(list, item => {
                this.setTotalUser(item);
              });
            }
          });
        });

        this.licences_number = this.all_users.length + this.new_users.length;
        this.available_licences = Math.abs(
            (this.experience.admin_available_licences - this.licences_number)
        );

        this.licences_to_buy = (this.licences_number - this.available_licences > 0)
            ? this.licences_number - this.available_licences : 0;
        this.getSubtotal();
      },
      removeUser(element) {
        let removed_users = [];

        if (typeof element === 'string') {
          removed_users.push(
              _.remove(this.new_users, email => {
                return element === email;
              })[0]
          );
        } else {
          removed_users.push(
              _.remove(this.all_users, user_id => {
                return element.id === user_id;
              })[0]
          );
        }
      },
      reset() {
        if(!this.is_fromCart) {
          /*
          this.raw = {
            companyArea: [],
            companyPosition: [],
            user: [],
            allowed_domains: [],
          };
          */
          this.form = {
            by_area: [],
            by_position: [],
            by_tags: [],
            by_user: [],
            by_email: [],
            by_phone: []
          };
          this.subtotal = 0;
          this.all_users = [];
          this.new_users = [];
          this.licences_number = 0;
          this.licences_to_buy = 0;
          this.selected_students = 0;
        }
        this.budget = this.companyData.budget_balance;

        this.is_fromCart = false;
      },
      goToCheckout() {
        let origin = window.location.origin;
        let path = '/shopping-cart/checkout';
        let url = origin + path;

        window.location.href = url;
      },
      buyNow() {
        if (parseInt(this.licences_number) >= 1) {
          this.buy(false, true);
        }
      },
      addToCart() {
        if (parseInt(this.licences_number) >= 1) {
          this.buy(true, false, true);
        }
      },
      assignateLicences(redirect = true, to_checkout = false) {
        let params = this.formatData(this.form);
        params.quantity = this.licences_to_buy;
        params.licences_to_buy = this.licences_to_buy;
        params.licences_number = this.licences_number;
        if (this.cartData.id) {
          params.delete_before_add = true;
        }

        if(this.free_licences){
          params.free_licences = true;
        }

        if(this.licences_number > 0 && this.licences_to_buy == 0) {
          let loader = index.getLoader({text: 'Asignando licencias'})
          axios({
            method: 'POST',
            url: '/experiences/'+this.experience.system_id+'/assignments',
            params: params,
            paramsSerializer: params => {
              return qs.stringify(params)
            }
          })
              .then(response => {
                this.$snotify.success('Asignaciones', '¡Licencias asignadas!', {
                  showProgressBar: true,
                  closeOnClick: true,
                  timeout: 2000
                });
                document.location.href = route('experiences.index').toString();
              })
              .catch(error => {
                console.log(error);
              })
              .then(() => {
                loader.close()
              });
        }
      },
      buy(redirect = true, to_checkout = false, set_cookie = false) {
        if (this.licences_to_buy > this.apithy_constants.BUY_LIMIT) {
          this.$snotify.warning(this.$snotify.warning(`Limitado a ${this.apithy_constants.BUY_LIMIT} licencias por transacción`, 'Aviso'), 'Aviso')
          return
        }
        let loader = index.getLoader()
        let params = this.formatData(this.form);
        params.quantity = this.licences_to_buy;
        params.licences_to_buy = this.licences_to_buy;
        params.licences_number = this.licences_number;
        if (this.cartData.id) {
          params.delete_before_add = true;
        }

        if(this.free_licences){
          params.free_licences = true;
        }

        if(to_checkout){
            params.to_checkout = true;
        }

        axios({
          method: 'POST',
          url: route('shopping-cart.add', {experience: this.experience.system_id}),
          data: params,
        })
            .then(response => {
              this.$snotify.success('Carrito de compras', '¡Experiencia agregada!', {
                showProgressBar: true,
                closeOnClick: true,
                timeout: 2000
              });
              this.storage(response.data.card, redirect, to_checkout, set_cookie);
            })
            .catch(error => {
              console.log(error);
            })
            .then(() => {
              loader.close()
            });
      },
      storage(data, redirect = true, to_checkout = false, set_cookie = false) {
        let total_challenges = 0;
        for (let i in data) {
          total_challenges += data[i].attributes.sessions;
        }
        let obj = {
          experience: {
            name: this.experience.title,
            full_path_cover: this.experience.full_path_cover,
            challenges: {
              total: data[this.experience.id].attributes.sessions,
            }
          },
          cart: {
            total: 0,
            experiences: {
              total: Object.keys(data).length,
            },
            challenges: {
              total: total_challenges,
            }
          }
        };
        if(set_cookie) {
          this.$cookie.set('cartNotify', JSON.stringify(obj));
        }
        if (redirect) {
          document.location.href = route('experiences.index').toString();
        }
        if (to_checkout) {
          this.goToCheckout();
        }
      },
      formatData(data) {
        let new_array = {};
        for (let index in data) {
          if (index === 'by_area' || index === 'by_position' || index === 'by_tags') {
            let i = ''
            switch (index) {
              case 'by_area':
                i = 'company_areas'
                break;
              case 'by_position':
                i = 'company_positions';
                break;
              case 'by_tags':
                i = 'company_tags'
            }
            new_array[i] = data[index].map(function (item) {
              return {
                id: item.id,
                company_id: item.company_id,
                color: item.color,
                icon: item.icon,
                type: item.type,
                title: item.title,
                users: item.users
              };
            })
          } else if (index === 'by_user') {
            new_array['company_users'] = data[index].map(function (item) {
              return {
                id: item.id,
                full_name: item.full_name,
                name: item.name
              };
            })
          } else {
            new_array['new_users'] = _.concat(_.get(new_array, ['new_users'], []), data[index]);
          }
        }
        new_array['quantity'] = this.all_users.length;
        new_array['inherit_users'] = this.all_users;
        new_array['corporative_use'] = true;
        return new_array;
      },
      getFilteredAreas (text) {
        this.filterAreas = this.raw.companyArea.filter(option => {
          return index.search(text, option, ['title'])
        })
      },
      getFilteredPositions (text) {
        this.filterPositions = this.raw.companyPosition.filter(option => {
          return index.search(text, option, ['title'])
        })
      },
      getFilteredTags (text) {
        this.filterTags = this.raw.companyTags.filter(option => {
          return index.search(text, option, ['title'])
        })
      },
      getFilteredUsers: _.debounce(function (text) {
        text = text + ""
        if (text.length < 3) {
          return
        }
        let form = {
          query: text,
          experience: this.experience.id
        }
        UserService.getFilteredUsers(form)
            .then(response => {
              this.raw.user = _.get(response, ['data', 'data'], [])
            })
            .catch(errors => {console.log(errors)})
            .then(() => {})
      }, 500)
    },
    props: {
      experience: {
        required: true,
        type: Object
      },
      cartData: {
        type: Object,
        default() {
          return {};
        }
      },
      onlyBuy: {
        required: false,
        type: Boolean,
        default: false
      },
      companyData: {
        required: true,
        type: Object
      }
    },
    data() {
      return {
        filterAreas: [],
        filterPositions: [],
        filterTags: [],
        loader: false,
        has_areas: false,
        has_positions: false,
        has_tags: false,
        has_users: true,
        budget: 0,
        raw: {
          companyArea: [],
          companyPosition: [],
          companyTags: [],
          user: [],
          allowed_domains: []
        },
        form: {
          by_area: [],
          by_position: [],
          by_tags: [],
          by_user: [],
          by_email: [],
          by_phone: []
        },
        all_users: [],
        new_users: [],
        free_licences: false,
        licences_number: 0,
        available_licences: this.experience.admin_available_licences,
        selected_students: 0,
        licences_to_buy: 0,
        is_fromCart:false,
        subtotal: 0,
        currency: {
          symbol: '$',
          moneda: 'MXN'
        },
        validate: {
          licences_number: {
            required: true,
            min_value: 1,
            decimal: 0,
            max_value: 100000,
            numeric: true
          }
        }
      };
    }
  }
</script>

<style scoped>

  .experience-info {
    margin: 50px 0 0 0;
  }

  .experience-info .card {
    border-radius: 10px;
    overflow: visible;
  }

  .experience-info .card-image-inner {
    max-height: 250px;
    overflow: hidden;
  }

  .experience-info .card-image-inner .image img {
    transform: translate(0px, -100px);
  }

  .experience-info .author-image .image-author-container {
    position: absolute;
    width: 100px;
    height: 100px;
    bottom: 20%;
    right: 7%;
    box-shadow: 1px 2px 10px 0px grey;
    border-radius: 10px;
    background-color: #FFFFFF;
    overflow: hidden;
  }

  .margin-b-10 {
    margin-bottom: 10px;
  }

  .padding-l-10 {
    padding-left: 10px;
  }

  .padding-r-10 {
    padding-right: 10px;
  }

  .card-container {
    padding: 20px 15px
  }

  .available-licences {
    border-bottom: 1px solid #000000;
    margin-bottom: 10px;
  }

  .margin-r-20 {
    margin-right: 20px
  }

  .gray {
    background-color: #BEBEBE;
  }

</style>
