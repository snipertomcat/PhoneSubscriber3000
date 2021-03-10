<template>
  <div>
    <section class="card">
      <div class="card-content">
        <div class="row">
          <div class="col-lg-12">
            <div class="row justify-content-between">
              <div class="col-auto">
                <span class="has-text-weight-bold">Licencias pendientes de activar</span>
              </div>
              <div class="col-auto text-right">
                <h1 class="has-text-weight-bold">
                  {{ pendingLicenceCount }}
                </h1>
              </div>
            </div>
          </div>
          <div class="col-lg-2" v-if="false">
            <div class="pointer" @click="showSettings">
              <b-icon
                  type="is-primary"
                  class="font-20"
                  pack="fa"
                  icon="cog">
              </b-icon>
            </div>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-lg-12">
            <div class="">
              <b-input
                placeholder="Buscar..."
                name="search"
                type="search"
                icon="search"
                icon-pack="fas"
                v-model="pending_form.search"
                @input="searchPending"
                :loading="isPendingLoading">
              </b-input>
            </div>
            <br>
          </div>
          <div v-if="isPendingLoading" class="col-lg-12">
            <div class="text-center h-400 row ml-0 mr-0 no-gutters">
              <div class="col-12 align-self-center">Cargando...</div>
            </div>
          </div>
          <div class="col-lg-12" v-if="pending_data.length > 0 && !isPendingLoading">
            <div v-bar class="vb h-400">
              <div class="">
                <template v-for="(item, index) in pending_data" >
                  <div class="row mr-1 ml-1 no-gutters pb-2" :key=index>
                    <div class="col-lg-8 col-md-8 col-sm-12">
                      <div>
                        <b-tooltip v-if="true" :label="item.email" position="is-bottom">
                          <h5 class="text-left">{{ item.email }}</h5>
                        </b-tooltip>
                        <div class="text-left">
                          Enviada el {{ item.created_at }}
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12">
                      <div class="text-right">
                        <button class="button is-primary" @click="openModal(item)">
                        <span class="text-white">
                          <i class="fa fa-user-times" aria-hidden="true"></i>
                           Remover
                        </span>
                        </button>
                      </div>
                    </div>
                  </div>
                </template>
              </div>
            </div>
            <div class="text-center">
              <div class="d-block d-md-none">
                <el-pagination
                        small
                        :disabled="isPendingLoading"
                        @current-change="onPageChange"
                        :hide-on-single-page="false"
                        :current-page.sync="pending_form.page"
                        layout="prev, pager, next"
                        :page-count="pending_form.last_page">
                </el-pagination>
              </div>
              <div class="d-none d-md-block">
                <el-pagination
                        :disabled="isPendingLoading"
                        @current-change="onPageChange"
                        :hide-on-single-page="false"
                        :current-page.sync="pending_form.page"
                        layout="prev, pager, next"
                        :page-count="pending_form.last_page">
                </el-pagination>
              </div>
            </div>
          </div>
          <div class="col-lg-12" v-else-if="pending_data.length <= 0 && !isPendingLoading">
            <div class="d-flex no-data h-400 align-self-center">
              <div class="row mr-0 ml-0 align-items-center justify-content-center full-width">
                <div class="content col-8">
                  <div class="">
                    <div class="row has-text-centered">
                      <div class="col-12">
                        <img src="/images/Caja.png" alt="" width="100">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-12 has-text-centered">
                        <p>
                          No hay licencias asignadas
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <b-modal v-if="false" :active.sync="show_modal" @close="closeSettings">
      <div class="card">
        <div class="card-content">
          <div class="row mr-0 ml-0 no-gutters">
            <div class="col-auto">
              <div class="settings-title has-text-weight-bold font-20">
                <span>{{ $t('messages.licenses.settings') }}</span>
              </div>
            </div>
          </div>
          <div class="row mr-0 ml-0 no-gutters mt-4 pt-3">
            <div class="col">
              <el-switch v-model="settings.enable" active-color="#FFA81E" @change="toggleSettings"></el-switch>
            </div>
            <div class="col-9 col-md-10">
              <div class="row mr-0 ml-0 no-gutters">
                <div class="col-12">
                  <span class="font-14 has-text-weight-bold">{{ $t('messages.licenses.remove_license') }}</span>
                </div>
                <div class="col-12 align-self-center" style="height: 32px">
                  <div class="font-14 d-flex align-items-center full-height">
                    <span class="mr-1">Remover licencia si no ha sido comenzada después de</span>
                    <span v-if="settings.enable"><b-input v-model="settings.days" style="width: 35px"></b-input></span>
                    <span v-else>{{ settings.days }}</span>
                    <span class="ml-1">días de ser enviada</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row mr-0 ml-0 no-gutters justify-content-between mt-5">
            <div class="col-5 col-md-4">
              <b-button class="is-primary full-width" @click="closeSettings">{{ $t('messages.close') }}</b-button>
            </div>
            <div class="col-5 col-md-4">
              <b-button
                  class="full-width"
                  :class="{'is-primary': settings.enable, 'is-gray': !settings.enable}"
                  :disabled="!settings.enable"
                  @click="saveSettings">
                {{ $t('messages.save') }}
              </b-button>
            </div>
          </div>
        </div>
      </div>
    </b-modal>
  </div>
</template>

<script>
  import { Pagination, Switch } from 'element-ui'
  import Vuebar from 'vuebar'
  import Vue from 'vue'
  Vue.component(Vuebar)

  export default {
    name: "PendingLicensesTable",
    components: {
      'el-pagination': Pagination,
      'el-switch': Switch
    },
    beforeMount () {
      this.getExperiencePendingLicenses()
    },
    mounted () {
      /*
      let url = route('experience.license.pending.settings', { experience: this.settings.experience_id })
      let params = { target: 'get', experience_id: this.settings.experience_id }
      this.send(url, params, (response => {
        if (!('break_action' in response.data)) {
          this.settings.enable = !!response.data.enable;
          this.settings.days = response.data.days
        }
      }))
       */
    },
    computed: {
      pendingLicenceCount: {
        get () {
          return this.pending_license_count
        },
        set (value) {
          this.$emit('change-pending-count', value)
        }
      }
    },
    methods: {
      send (url = null, params = {}, callback = () => {}, secondCallback = () => {}, error = (e) => { console.error(e) }) {
        if (url === null) {
          console.error('Method and URL params are required');
          return null
        }
        axios.post(url, params)
                .then(response => { callback(response) })
                .catch(err => { error(err) })
                .then(() => { secondCallback() })
      },
      toggleSettings (value) {
        let url = route('experience.license.pending.settings', {experience: this.settings.experience_id})
        let params = {
          experience_id: this.settings.experience_id,
          enable_settings: this.settings.enable
        }
        this.send(url, params)
      },
      saveSettings () {
        if (this.settings.enable) {
          let url = route('experience.license.pending.settings', {experience: this.settings.experience_id})
          let params = {
            target: 'set',
            days: this.settings.days,
            enable_settings: this.settings.enable,
            experience_id: this.settings.experience_id
          }
          this.send(url, params, (response) => {
            if (!('break_action' in response.data)) {
              console.log(response.data)
              this.settings.sucess = true
            }
          },
          () => {
            this.closeSettings()
          })
        }
      },
      closeSettings () {
        this.show_modal = false
       if (!this.settings.sucess) {
         this.settings.enable = false

         let url = route('experience.license.pending.settings', {experience: this.settings.experience_id})
         let params = {
           enable_settings: false,
           experience_id: this.settings.experience_id
         }
         this.send(url, params)
       }
      },
      showSettings () {
        this.show_modal = true
      },
      dropLicenceUser () {
        this.isPendingLoading = true
        return axios.delete(route('experience.license.pending.delete', this.form.id), {
          data: this.form
        })
            .then (response => {
              this.pendingLicenceCount -= 1
              this.pending_data = _.get(response, ['data', 'data'], [])
              this.pending_form.page = _.get(response, ['data', 'meta', 'current_page'], 1)
              this.pending_form.total = _.get(response, ['data', 'meta', 'total'], 0)
              this.pending_form.per_page = _.get(response, ['data', 'meta', 'per_page'], 15)
              this.pending_form.last_page = _.get(response, ['data', 'meta', 'last_page'], 1)
        })
            .catch(e => {})
            .then(() => {
              this.isPendingLoading = false
            })
      },
      getExperiencePendingLicenses () {
        this.isPendingLoading = true;
        axios.get(route('experience.license.pending', this.id), {
          params: this.pending_form
        })
            .then(response => {
              this.pending_data = _.get(response, ['data', 'data'], [])
              this.pending_form.page = _.get(response, ['data', 'meta', 'current_page'], 1)
              this.pending_form.total = _.get(response, ['data', 'meta', 'total'], 0)
              this.pending_form.per_page = _.get(response, ['data', 'meta', 'per_page'], 15)
              this.pending_form.last_page = _.get(response, ['data', 'meta', 'last_page'], 1)
              this.$refs.pendingTable.$el.children[0].getElementsByTagName('table')[0].deleteTHead()
            })
            .catch(e => {})
            .then(() => {
              this.isPendingLoading = false;
            })
      },
      onPageChange (page) {
        this.pending_form.page = page
        this.getExperiencePendingLicenses()
      },
      searchPending: _.debounce(function () {
        this.pending_form.page = 1
        this.getExperiencePendingLicenses()
      }, 500),
      asyncNotify () {
        this.$snotify.async('Espere un momento por favor', 'Actualizando datos', () => new Promise((resolve, reject) => {
          this.dropLicenceUser()
              .then(() => {
                resolve({
                  title: 'Completado',
                  body: `Se ha eliminado la licencia para el usuario: ${this.form.email}`,
                  config: {
                    closeOnClick: true,
                    backdrop: 0.6,
                    timeout: 2000
                  }
                })
              })
              .catch(() => {
                reject({
                  title: 'Error',
                  body: 'Ha ocurrido un error inesperado',
                  config: {
                    closeOnClick: true,
                    backdrop: 0.6,
                    timeout: 2000
                  }
                })
              })
        }), {backdrop: 0.6})
      },
      openModal (data) {
        this.form = {
          id: data.id,
          experience_id: this.experiences.id,
          email: data.email
        }
        this.$snotify.confirm(`¿Quieres eliminar la licencia: "${this.experiences.title}" asignada a ${this.form.email}?`, 'Desasignar experiencia', {
          closeOnClick: false,
          pauseOnHover: true,
          backdrop: 0.6,
          buttons: [
            {
              text: 'Si',
              action: (toast) => {
                this.$snotify.remove(toast.id)
                this.asyncNotify()
              },
              bold: false
            },
            {
              text: 'No',
              action: (toast) => {
                this.$snotify.remove(toast.id)
              }
            }
          ]
        });
      }
    },
    props: {
      id: {
        required: true,
        type: Number
      },
      pending_license_count: {
        required: true,
        default: 0,
        type: Number
      },
      experiences: {
        required: true,
        type: Object
      }
    },
    data () {
      return {
        pending_data: [],
        pending_form: {
          search: null,
          page: 1,
          total: 1,
          per_page: 15,
          last_page: 1
        },
        form: {},
        isPendingLoading: false,
        show_modal: false,
        settings: {
          enable: false,
          days: 1,
          experience_id: this.id,
          sucess: false,
        },
      }
    }
  }
</script>

<style scoped>
  @import '../../../../../styles/vuebar.css';
  .is-gray {
    background-color: #BEBEBE;
  }
  button.button {
    box-shadow: 2px 2px 6px rgba(88, 88, 88, 0.413949);
  }
  .h-400 {
    height: 400px;
  }
</style>

<style>
  .input:focus {
    border-color: orange !important;
    box-shadow: 0 0 0 0.125em rgba(255,165,0,0.5);
  }
</style>
