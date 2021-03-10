<template>
  <div>
    <section class="table card">
      <div class="card-content">
        <div class="row">
          <div class="col-12">
            <div class="row justify-content-between">
              <div class="col-auto">
                <span class="has-text-weight-bold">Licencias asignadas</span>
              </div>
              <div class="col-auto text-right">
                <h1 class="has-text-weight-bold">
                  {{ used_license_count }}
                </h1>
              </div>
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
                  v-model="used_form.search"
                  @input="searchUsed"
                  :loading="isUsedLoading">
              </b-input>
            </div>
            <br>
          </div>
          <div v-if="isUsedLoading" class="col-lg-12">
            <div class="text-center h-400 row ml-0 mr-0 no-gutters">
              <div class="col-12 align-self-center">Cargando...</div>
            </div>
          </div>
          <div class="col-lg-12 " v-if="used_data.length > 0 && !isUsedLoading">
            <div v-bar class="vb h-400">
              <div class="">
                <div class="row mr-1 ml-1 no-gutters" v-for="(item, index) in used_data" :key="index" style="margin-top: 20px;">
                  <div class="col-12">
                    <div class="media">
                      <figure class="mr-3 image is-32x32">
                        <img :src="item.avatar" class="is-rounded">
                      </figure>
                      <div class="media-body width-100">
                        <h5 class="mt-0">{{ item.name }}</h5>
                        Enviada el {{ item.created_at }}
                        <div>Estatus: {{ enrollStatus(item) }} - {{ progressPercent(item) }}%</div>
                        <div>
                          <progress class="progress is-mini" :class="barColor(item)" :value="progressPercent(item)" max="100"></progress>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="text-center pt-2">
              <div class="">
                <el-pagination
                        small
                        :disabled="isUsedLoading"
                        @current-change="onPageChange"
                        :hide-on-single-page="false"
                        :current-page.sync="used_form.page"
                        layout="prev, pager, next"
                        :page-count="used_form.last_page">
                </el-pagination>
              </div>
            </div>
          </div>
          <div class="col-lg-12" v-else-if="used_data.length <= 0 && !isUsedLoading">
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
  </div>
</template>

<script>
  import { Pagination } from 'element-ui'
  import Vuebar from 'vuebar'
  import Vue from 'vue'
  Vue.component(Vuebar)

  export default {
    name: "UsedLicensesTable",
    components: {
      'el-pagination': Pagination
    },
    beforeMount () {
      this.getExperienceUsedLicenses()
    },
    methods: {
      barColor (row) {
        let progress = row.experience.progress_percent ? row.experience.progress_percent : 0
        return progress <= 50 ? 'is-primary' : 'is-info'
      },
      progressPercent (row) {
        return row.experience.progress_percent ? row.experience.progress_percent : 0
      },
      enrollStatus (row) {
        return row.experience.is_finished ? 'Terminado' : 'Iniciado'
      },
      getExperienceUsedLicenses () {
        this.isUsedLoading = true;
        axios.get(route('experience.license.used', this.id), {
          params: this.used_form
        })
            .then(response => {
              this.used_data = _.get(response, ['data', 'data'], [])
              this.used_form.page = _.get(response, ['data', 'meta', 'current_page'], 1)
              this.used_form.total = _.get(response, ['data', 'meta', 'total'], 0)
              this.used_form.per_page = _.get(response, ['data', 'meta', 'per_page'], 15)
              this.used_form.last_page = _.get(response, ['data', 'meta', 'last_page'], 1)
            })
            .catch(e => {})
            .then(() => {
              this.isUsedLoading = false;
            })
      },
      searchUsed: _.debounce(function () {
        this.used_form.page = 1
        this.getExperienceUsedLicenses()
      }, 500),
      onPageChange (page) {
        this.used_form.page = page
        this.getExperienceUsedLicenses()
      }
    },
    props: {
      id: {
        required: true,
        type: Number
      },
      used_license_count: {
        required: true,
        default: 0,
        type: Number
      }
    },
    data () {
      return {
        used_data: [],
        used_form: {
          search: null,
          page: 1,
          total: 1,
          per_page: 15,
          last_page: 1
        },
        isUsedLoading: false
      }
    }
  }
</script>

<style scoped>
  @import '../../../../../styles/vuebar.css';
  .no-data {
    min-height: 183px;
  }
  .is-mini {
    height: 0.5rem;
  }
  .h-400 {
    height: 400px;
  }
</style>
