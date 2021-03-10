<template>
  <div class="ml-lg-4 mr-lg-4 users-invitations">
    <div class="row no-gutters justify-content-end helper mr-0 ml-0">
      <div class="col" v-if="show_helper">
        <div class="message">
          Puedes enviar invitaciones por correo electrónico o teléfono para los roles administrativos o de estudiantes.
        </div>
      </div>
      <div class="col-auto text-center">
        <div class="trigger" @click="toggleHelper">
          <span class="font-20"><i class="icon-info-circle"></i></span>
        </div>
      </div>
    </div>
    <div class="row mt-3 mr-0 ml-0 no-gutters">
      <div class="col-12 col-xl-9">
        <div class="row">
          <div class="col-12 col-md-3" v-if="authUser.is_super">
            <label for="company_id" class="has-text-weight-bold d-none d-md-block">
              {{ $t('messages.company') }}
            </label>
          </div>
          <div class="col-12 col-md-3">
            <label for="role_id" class="has-text-weight-bold d-none d-md-block">Rol</label>
          </div>
          <div :class="{'col-12': true, 'col-md-6':!authUser.is_super, 'col-md-3': authUser.is_super}">
            <label for="email_phone" class="has-text-weight-bold d-none d-md-block">
              {{ $t('messages.company_access_key') }}
            </label>
          </div>
        </div>
        <form @submit.prevent="validateForm">
          <div class="row">
            <div class="col-12 col-md-auto" v-if="authUser.is_super">
              <b-field :type="{'is-danger':errors.has('company_id')}" :message="errors.first('company_id')">
                <b-select
                    name="company_id"
                    id="company_id"
                    expanded
                    :disabled="loading"
                    :placeholder="$t('messages.company')"
                    v-model="form.company_id">
                  <option v-for="company in companies" :key="company.id" :value="company.id">
                    {{ company.name }}
                  </option>
                </b-select>
              </b-field>
            </div>
            <div class="d-block d-md-none"><br><br></div>
            <div class="col-12 col-md-3">
              <b-field :type="{'is-danger':errors.has('role_id')}" :message="errors.first('role_id')">
                <b-select
                    name="role_id"
                    id="role_id"
                    expanded
                    :placeholder="$t('messages.role')"
                    :disabled="loading"
                    v-model="form.role_id"
                    v-validate="form_rules.role">
                  <option v-for="role in getAllowedRoles()" :key="role.id" :value="role.id">
                    {{ role.code }} - {{ role.name }}
                  </option>
                </b-select>
              </b-field>
            </div>
            <div class="d-block d-md-none"><br><br></div>
            <div :class="{'col-12': true, 'col-md-5':!authUser.is_super, 'col-md-3':authUser.is_super}">
              <b-field :type="{'is-danger':errors.has('email_phone')}" :message="errors.first('email_phone')">
                <el-select
                    ref="email_phone"
                    id="email_phone"
                    name="email_phone"
                    class="width-100"
                    multiple
                    :multiple-limit="10"
                    filterable
                    allow-create
                    :disabled="loading"
                    v-model="form.email_phone"
                    :data-vv-as="$t('messages.company_access_key')"
                    v-validate="form_rules.email_phone"
                    @change="testEmailPhone"
                    :no-data-text="$t('messages.company_access_key')"
                    :placeholder="$t('messages.company_access_key')">
                </el-select>
              </b-field>
            </div>
            <div class="d-block d-md-none"><br><br></div>
            <div class="col-12 col-md-auto col-lg-3">
              <div class="font-14">
                <b-button
                    type="is-link"
                    native-type="submit"
                    icon-pack="fa"
                    icon-left="paper-plane"
                    :loading="loading"
                    class="no-border"
                    :class="{'send-disabled': errors.any()}"
                    :disabled="errors.any()">
                  <span class="has-text-weight-bold">
                    {{ $t('messages.send_invitation') }}
                  </span>
                </b-button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
    <div class="row invitation-list">
      <div class="col-12">
        <span class="font-20 has-text-weight-bold">
          {{ 'Invitaciones enviadas' }}
        </span>
      </div>
      <div class="col-12">
        <div class="row mt-3">
          <div class="col-12">
            <b-table
                :data="table_data"
                :striped="true"
                :mobile-cards="true">
              <template slot-scope="props">
                <b-table-column field="contact" :label="$t('messages.company_access_key')" sortable>
                  {{ props.row.contact }}
                </b-table-column>

                <b-table-column field="role" label="Rol" sortable>
                  <span :class="`tag role ${props.row.role_css}`">
                    {{ props.row.role }}
                  </span>
                </b-table-column>

                <b-table-column field="company" label="Compañia" width="200" :sortable="authUser.is_super">
                  {{ props.row.company }}
                </b-table-column>

                <b-table-column field="host_user" label="Anfitrion">
                  {{ props.row.host_user }}
                </b-table-column>

                <b-table-column field="created_at" label="Creada">
                  {{ props.row.created_at }}
                </b-table-column>

                <b-table-column field="status_name" label="Estado">
                  <span :class="`tag status ${props.row.status_css}`">
                      {{ props.row.status_name }}
                  </span>
                </b-table-column>

                <b-table-column field="options" label="Opciones" width="200">
                  <span class="font-20 invitation-resend pointer" :class="[(props.row.status !== 0) ? 'disabled': '']" @click="send(props.row)">
                    <i class="fa fa-paper-plane"></i>
                  </span>
                  <span class="font-20 invitation-delete pointer" :class="[(props.row.status !== 0) ? 'disabled': '']" @click="deleteInvitation(props.row)">
                    <i class="fa fa-trash"></i>
                  </span>
                </b-table-column>
              </template>
              <template slot="empty">
                <section class="section">
                  <div class="content has-text-grey has-text-centered">
                    <p>
                      <img src="/images/Caja.png" alt="">
                    </p>
                    <p>Aún no hay datos que mostrar.</p>
                  </div>
                </section>
              </template>
            </b-table>
          </div>
        </div>
        <template v-if="table_has_data">
          <div class="col-12 d-block"><br></div>
          <div class="row">
            <div class="col-12 col-md-4 glosary">
              <div class="row">
                <div class="col-auto">
                  <span><i class="fa fa-paper-plane"></i> Reenviar</span>
                </div>
                <div class="col-auto">
                  <span><i class="fa fa-trash"></i> Eliminar</span>
                </div>
              </div>
            </div>
            <div class="col-12 col-lg-4">
              <div class="row ml-0 mr-0 no-gutters justify-content-center">
                <div class="col-lg-12 text-center">
                  <el-pagination
                      @current-change="onPageChange"
                      :hide-on-single-page="false"
                      :current-page.sync="params.current_page"
                      layout="prev, pager, next"
                      :page-count="params.last_page">
                  </el-pagination>
                </div>
              </div>
            </div>
            <div class="col-12 col-lg-4 text-right">
              <div class="row ml-0 mr-0 no-gutters justify-content-end">
                <div class="col-auto">
                  <b-select v-model="params.per_page" @input="handleSizeChange">
                    <option value="10">
                      Ver 10
                    </option>
                    <option value="15">
                      Ver 15
                    </option>
                    <option value="20">
                      Ver 20
                    </option>
                    <option value="30">
                      Ver 30
                    </option>
                    <option value="50">
                      Ver 50
                    </option>
                  </b-select>
                </div>
              </div>
            </div>
          </div>
        </template>
      </div>
    </div>
    <apithy-modal
        :key="0"
        :active.sync="confirm_modal"
        :can-cancel="false"
        :is-loading="loading"
        :has-overflow="false"
        @show-apithy-modal="showConfirmModal">
      <template slot="body">
        <header class="modal-card-head force-white no-borders pl-4 pr-4">
          <p class="has-text-weight-bold">
            Se enviará invitación a los siguientes correos/teléfonos
          </p>
        </header>
        <div class="pt-2 pl-4 pr-4">
          <table class="table is-striped">
            <tbody>
            <tr>
              <th>Correo/Teléfono</th>
            </tr>
            <template v-for="(item, index) in form.email_phone">
              <tr :key="index">
                <td>{{ item }}</td>
              </tr>
            </template>
            </tbody>
          </table>
        </div>
        <div class="pt-4 pl-4 pr-4 pb-4 text-right">
          <b-button type="is-light" @click="showConfirmModal(false)">Cancelar</b-button>
          <b-button type="is-primary" @click="confirmInvitations">Aceptar</b-button>
        </div>
      </template>
    </apithy-modal>
    <apithy-modal
        :key="1"
        :active.sync="response_modal"
        :can-cancel="false"
        :is-loading="loading"
        :has-overflow="false"
        @show-apithy-modal="showResponseModal">
      <template slot="body">
        <header class="modal-card-head force-white no-borders pl-4 pr-4">
          <p class="has-text-weight-bold">
            Se procesaron los siguientes correos/teléfonos
          </p>
        </header>
        <div class="pt-2 pl-4 pr-4">
          <table class="table is-striped">
            <tbody>
            <tr>
              <th>Correo/Teléfono</th>
              <th>Estatus</th>
              <th>Mensaje</th>
            </tr>
            <template v-for="(item, index) in responseData">
              <tr :key="index">
                <td>{{ item.data }}</td>
                <td>{{ item.status_message }}</td>
                <td>{{ item.message }}</td>
              </tr>
            </template>
            </tbody>
          </table>
        </div>
        <div class="pt-4 pl-4 pr-4 pb-4 text-right">
          <b-button type="is-primary" @click="showResponseModal(false)">Aceptar</b-button>
        </div>
      </template>
    </apithy-modal>
  </div>
</template>

<script>
  import { Pagination, Select, Message, Table, TableColumn } from 'element-ui'
  import ApithyModal from "../../ApithyModal";
  import { email } from 'vee-validate/dist/rules.esm'
  import { index } from "../../../helpers";
  import {AdminService} from "../../../services/Admin/AdminService";
  export default {
    name: "UsersInvitations",
    components: {
      ApithyModal,
      'el-pagination': Pagination,
      'el-select': Select,
      'el-table': Table,
      'el-table-column': TableColumn
    },
    mounted () {
      this.allowedDomains = index.validDomains(_.get(this.companies, [0, 'valid_domains'], []))
      this.setValidators();
      this.role = _.head(this.roles)['id'];
      this.company = _.head(this.companies)['id']
      this.form.role_id = _.head(this.roles)['id'];
      this.form.company_id = _.head(this.companies)['id']
      this.getInvitationsData();
    },
    computed: {
      table_has_data() {
        return !_.isEmpty(this.table_data);
      }
    },
    methods: {
      showConfirmModal (value) {
        this.confirm_modal = value
      },
      showResponseModal (value) {
        this.response_modal = value;
        if (!value) {
          this.getInvitationsData()
        }
      },
      confirmInvitations () {
        this.showConfirmModal(false)
        this.storeBulkInvitations();
      },
      setValidators () {
        this.$validator.extend('verify_email_exist', index.getAsyncValidatorConfig('email'));
      },
      validateForm() {
        this.$validator.validateAll()
            .then((result) => {
              if (result) {
                this.showConfirmModal(true)
                return false;
              }
            })
            .catch(() => {});
      },
      testEmailPhone (item) {
        this.form.email_phone = _.filter(item, o => {
          if (_.includes(o, '@')) {
            if (email.validate(o)) {
              let aux = index.extractEmailDomain(o)
              let valid_domain = (_.find(this.allowedDomains, {domain: aux}))
              if (valid_domain) {
                return o;
              } else {
                let title = 'Email no reconocido';
                let message = `El dominio ${aux} no pertenece a su lista de dominios, ¿deséa añadirlo aún así?`;
                this.$snotify.warning(
                    message, title, {
                      buttons: [
                        {
                          text: 'Si',
                          action: (alert) => {
                            this.allowedDomains.push({domain: aux})
                            if (!_.isEmpty(aux)) {
                              setTimeout(() => {
                                let form = {
                                  valid_domains: this.allowedDomains
                                }
                                AdminService.updateAllowedDomain(form)
                              }, 500)
                            }
                            this.$snotify.remove(alert.id);
                            this.form.email_phone.push(o);
                          }
                        },
                        {
                          text: 'No',
                          action: (alert) => {
                            this.$snotify.remove(alert.id);
                          }
                        }
                      ],
                      showProgressBar: false,
                      closeOnClick: false,
                      timeout: 0,
                      backdrop: 0.6
                    });
                return false;
              }
            }
          } else {
            let pattern = index.getOnlyNumberPhoneRegex()
            if (pattern.test(o)) {
              return o;
            }
          }
          Message.error({
            showClose: true,
            message: `El correo/teléfono: ${o} no es válido`,
            type: 'error'
          });
          return false;
        })
      },
      storeBulkInvitations () {
        let loader = index.getLoader();
        axios.post(route('invitations.store.bulk'), this.form)
            .then(response => {
              let aux = []
              let data = _.get(response, ['data', 'data'], [])
              let emails = _.get(data, 'emails', [])
              let phones = _.get(data, 'phones', [])
              let errors = _.get(data, 'errors', [])
              this.responseData = _.concat(emails, phones, errors)
              this.showResponseModal(true)
              this.form.email_phone = []
              this.form.role_id = 7
              this.$validator.reset()
            })
            .catch(errors => {
              let errorList = index.hasErrors(errors)
              if (errorList) {
                index.setErrors(this.errors, errorList)
              }
            })
            .then(() => {
              loader.close()
            })
      },
      toggleHelper () {
        this.show_helper = !this.show_helper
      },
      send (invitation) {

        if(invitation.status !==0){
          return false;
        }
        let id=invitation.id;
        let vm = this;
        this.$snotify.async('Invitación', 'Procesando Invitación', () => new Promise((resolve, reject) => {
          return axios({
            method: 'GET',
            url: `/users/invitations/resend/${id}`,
          }).then((response) => {
            vm.loading = false;
            this.getInvitationsData();
            resolve({
              title: 'Petición exitosa',
              body: 'Petición procesada con éxito.',
              config: {
                closeOnClick: true,
                backdrop: 0.6,
                timeout: 2000,
                html: `
                  <div class="snotifyToast__title">Invitaciones</div>
                  <div class="snotifyToast__body">Invitación reenviada con éxito.</div>
                  <div class="snotify-icon snotify-icon--success"></div>
                  `
              }
            });
          }).catch((error) => {
            vm.loading = false;
            reject({
              title: 'Error!!',
              body: 'No se pudo procesar la petición correctamente.',
              config: {
                closeOnClick: true,
                backdrop: 0.6,
                timeout: 2000,
                html: `
                  <div class="snotifyToast__title">Invitaciones</div>
                  <div class="snotifyToast__body">No se pudo reenviar la Invitación.</div>
                  <div class="snotify-icon snotify-icon--error"></div>
                `
              }
            });
          });
        }), {backdrop: 0.6});
      },
      sendInvitation() {
        this.$validator.validateAll().then(result => {
          if(result) {
            let vm = this;
            this.$snotify.async('Invitación', 'Procesando Invitación', () => new Promise((resolve, reject) => {
              return axios({
                method: 'POST',
                url: route('invitations.store'),
                params: this.form,
              }).then((response) => {
                vm.loading = false;
                this.form.email = null
                this.getInvitationsData();
                resolve({
                  title: 'Petición exitosa',
                  body: 'Petición procesada con éxito.',
                  config: {
                    timeout: 2000,
                    backdrop: 0.6,
                    closeOnClick: true,
                    html: `
                      <div class="snotifyToast__title">Petición exitosa</div>
                      <div class="snotifyToast__body">Petición procesada con éxito.</div>
                      <div class="snotify-icon snotify-icon--success"></div>
                      `
                  }
                });
              }).catch((error) => {
                vm.loading = false;
                reject({
                  title: 'Error!!',
                  body: 'No se pudo procesar la petición correctamente.',
                  config: {
                    closeOnClick: true,
                    timeout: 2000,
                    backdrop: 0.6,
                    html: `
                     <div class="snotifyToast__title">Error!!</div>
                      <div class="snotifyToast__body">No se pudo procesar la petición correctamente.</div>
                      <div class="snotify-icon snotify-icon--error"></div>
                    `
                  }
                });
              });

            }), {backdrop: 0.6});
          }
          else {
            return false;
          }
        })
      },
      getInvitationsData () {
        let loader = index.getLoader()
        axios({
          method:'GET',
          url: route('invitations.index'),
          params: this.params
        })
            .then(response => {
              this.table_data= _.get(response, ['data', 'data'], []);
              this.params.page = _.get(response, ['data', 'current_page'], 1)
              this.params.last_page = _.get(response, ['data', 'last_page'], 1)
            })
            .catch(error => {
              // console.log(error);
            })
            .then(() => {
              loader.close()
            })
      },
      onPageChange(page) {
        this.params.page = page;
        this.getInvitationsData();
      },
      handleSizeChange (size) {
        this.params.page = 1
        this.params.per_page = size
        this.getInvitationsData()
      },
      refresh() {
        this.params.page = 1;
        this.getInvitationsData();
      },
      deleteInvitation(row) {
        if (row.status) {
          return
        }
        this.$snotify.confirm('Eliminando', '¿Desea eliminar está invitación?', {
          showProgressBar: true,
          closeOnClick: false,
          pauseOnHover: true,
          backdrop: 0.6,
          buttons: [
            {
              text: 'Si', action: (toast) => {
                let loader = index.getLoader({text: 'Procesando petición'})
                axios({
                  method: 'DELETE',
                  url: route('invitations.destroy',[row.id])
                })
                    .then(response => {
                      this.$snotify.success('', 'Invitación eliminada con éxito', {
                        closeOnClick: true,
                        backdrop: 0.6,
                        timeout: 2000
                      })
                      this.refresh();
                    })
                    .catch(error => {
                      this.$snotify.error('', 'Hubo un problema al eliminar la invitación', {
                        closeOnClick: true,
                        backdrop: 0.6,
                        timeout: 2000
                      })
                    })
                    .then(() => {
                      this.$snotify.remove(toast.id)
                      loader.close()
                    })
              }
            },
            {
              text: 'No', action: (toast) => {this.$snotify.remove(toast.id)}
            },
          ]
        })
      },
      getAllowedRoles() {
        let roles = [];

        if (this.authUser.is_admin) {
          _.each(this.roles, role => {
            if (role.id === 9 || role.id === 7) {
              roles.push(role);
            }
          });
        } else {
          roles = this.roles;
        }
        return roles;
      }
    },
    props: {
      roles: {
        type: Array,
        required: true
      },
      companies: {
        type: Array,
        required: false
      },
      authUser: {
        type: Object,
        required: true
      }
    },
    data () {
      return {
        confirm_modal: false,
        response_modal: false,
        show_helper: false,
        allowedDomains: [],
        table_data: [],
        params: {
          page: 1,
          last_page: 1,
          per_page: 15,
          search: null
        },
        loadStatus: false,
        loading: false,
        form: {
          role_id: 7,
          company_id: this.authUser.company_id,
          email_phone: [],
        },
        responseData: [],
        form_rules: {
          email_phone: {
            required: true
          },
          role: {
            required: true
          }
        }
      }
    },
  }
</script>

<style scoped>
  @media only screen and (min-width: 767px) and (max-width: 769px) {
    .users-invitations {
      width: 92% !important;
    }
  }
  .invitation-delete {
    color: #106CFB;
  }
  .invitation-resend {
    padding-right: 20px;
    color:#106CFB;
  }
  .invitation-list {
    margin-top: 65px;
  }
  .tag.role {
    color: white;
  }
  .tag.status {
    color: white;
  }
  .helper .message {
    padding: 16px;
    background-color: #EAF2FF;
  }
  .helper .trigger span {
    padding-left: 20px;
    padding-right: 20px;
    cursor: pointer;
    color: #1A6BF7;
  }
  .send-disabled {
    background-color: gray !important;
  }
  .no-border {
    border-radius: 4px !important;
  }
  .disabled {
    color: gray;
  }
</style>
