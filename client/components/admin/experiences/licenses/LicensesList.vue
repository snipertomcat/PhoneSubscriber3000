<template>
  <div class="mb-4">
    <div class="row mr-0 ml-0 no-gutters justify-content-center">
      <div class="col-lg-10">
        <div class="row mr-0 ml-0 no-gutters padding-tb-25">
          <div class="col-12 col-lg-4">
            <b-field label="Filtrar Por">
              <b-input
                      placeholder="Buscar..."
                      name="search"
                      type="search"
                      icon="search"
                      icon-pack="fas"
                      v-model="form.search"
                      @input="searchExperienses"
                      :loading="isLoading">
              </b-input>
            </b-field>
          </div>
        </div>
        <div class="row mr-0 ml-0 no-gutters b-table">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-content">
                <div class="table-content">
                  <b-table
                          class="table is-hoverable is-fullwidth"
                          :data="data"
                          :loading="isLoading"
                          @page-change="onPageChange">
                    <template slot="empty">
                      <div class="row align-items-center justify-content-center">
                        <div class="content col-lg-8">
                          <div class="">
                            <div class="row has-text-centered">
                              <div class="col-lg-12">
                                <img src="/images/Caja.png" alt="" width="100">
                              </div>
                            </div>
                            <br>
                            <div class="row">
                              <div class="col-lg-12 has-text-centered">
                                <p>
                                  No hay Licencias
                                </p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </template>
                    <template slot-scope="props">
                      <b-table-column label="Nombre" width="285" class="" :id="props.row.id">
                        <div class="d-flex align-items-center">
                          <div class="col-lg-3 pr-0 pl-0 mr-3">
                            <img :src="props.row.full_path_cover" class="image" style="min-width: 60px">
                          </div>
                          <div class="col-lg-9 pr-0 pl-0">
                          <span class="">
                            {{ props.row.title }}
                          </span>
                          </div>
                        </div>
                      </b-table-column>
                      <b-table-column centered label="Licencias compradas" width="40">
                        <div class="license-padding">{{ props.row.licenses_count }}</div>
                      </b-table-column>
                      <b-table-column centered label="Disponibles" width="40">
                        <div class="license-padding">{{ props.row.unused_license_count }}</div>
                      </b-table-column>
                      <b-table-column centered label="Pendientes de activar" width="40">
                        <div class="license-padding">{{ props.row.pending_license_count }}</div>
                      </b-table-column>
                      <b-table-column centered label="Licencias asignadas" width="40">
                        <div class="license-padding">{{ props.row.used_license_count }}</div>
                      </b-table-column>
                      <b-table-column centered label="Acciones" width="40">
                        <div class="row padding-tb-10 justify-content-center">
                          <div class="col-auto">
                            <button class="button is-primary" @click="toExperience(props.row.system_id)">
                      <span class="text-white">
                        <i class="icon-user-clipboard"></i>
                         Gestionar licencias
                      </span>
                            </button>
                          </div>
                        </div>
                      </b-table-column>
                    </template>
                  </b-table>
                </div>
                <div class="pb-2 pt-2">
                  <div class="text-center">
                    <el-pagination
                            :disabled="isLoading"
                            @current-change="onPageChange"
                            :hide-on-single-page="false"
                            :current-page.sync="form.page"
                            layout="prev, pager, next"
                            :page-count="form.last_page">
                    </el-pagination>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Pagination } from 'element-ui'
export default {
	name: "ApithyLicensesList",
  components: {
	  'el-pagination': Pagination
  },
  beforeMount () {
	  this.getLicences()
  },
  methods: {
	  getLicences () {
	    this.isLoading = true
	    axios.get(route('licenses.index'), {
	        params: this.form
      })
          .then(response => {
            let data = response.data
            this.data = data.data
            this.form.page = data.meta.current_page
            this.form.total = data.meta.total
            this.form.per_page = data.meta.per_page
            this.form.last_page = data.meta.last_page
          })
          .catch(e => {})
          .then(() => {
            this.isLoading = false
          })
    },
    onPageChange (page) {
	    this.form.page = page
	    this.getLicences()
    },
    toExperience (id) {
	    window.location = route('experience.show.licenses', id)
    },
    searchExperienses: _.debounce(function () {
      this.form.page = 1
      this.getLicences()
    }, 500)
  },
  data() {
    return {
      data: [],
      form: {
        search: null,
        list: 1,
        page: 1,
        total: 0,
        per_page: 15,
        last_page: 1
      },
      isLoading: false
    }
  }
}
</script>

<style scoped>
  .card-content {
    overflow-x: auto;
  }
  .padding-tb-10 {
    padding: 10px 0;
  }
  @media (min-width: 800px) {
    .license-padding {
      line-height: 60px;
    }
  }
</style>
