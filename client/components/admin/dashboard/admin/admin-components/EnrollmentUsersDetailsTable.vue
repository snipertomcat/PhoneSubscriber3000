<template>
  <div class="row ml-0 mr-0 no-gutters mt-4 mb-4">
    <div class="col-12 col-lg-6">
      <div class="row mr-0 ml-0 no-gutters">
        <div class="col">
          <span class="has-text-weight-bold">Usuarios enrolados</span>
        </div>
      </div>
      <div class="row mr-0 ml-0 no-gutters mt-2">
        <div class="col">
          <el-input
              placeholder="Buscar usuario"
              v-model="form.search"
              @input="search">
            <i slot="prefix" class="el-input__icon fas fa-search"></i>
          </el-input>
        </div>
      </div>
    </div>
    <div class="col-12 mt-3">
      <div class="card">
        <div class="card-content">
          <b-table :data="enrollmentData">
            <template slot-scope="props">
              <b-table-column label="Usuario">
                {{ props.row.full_name }}
              </b-table-column>
              <b-table-column label="Progreso" centered>
                <span class="has-text-weight-bold">{{ props.row.progress }}%</span>
              </b-table-column>
              <b-table-column label="Rendimiento" centered>
                <template v-if="props.row.score">
                  <span class="has-text-weight-bold" :class="getClass(props.row.score)">
                    {{ parse(props.row.score, 'score') }}
                  </span>
                </template>
                <template v-else-if="!evaluable">
                  <span>no evaluable</span>
                </template>
                <template v-else>
                  <span>no disponible</span>
                </template>
              </b-table-column>
              <b-table-column label="Detalle" centered>
                <span class="has-text-link">
                  <i class="icon-eye pointer" @click="viewUserDetails(props.row.full_name)"></i>
                </span>
              </b-table-column>
              <b-table-column label="Inicio" centered>
                <template v-if="props.row.started_at !== null">
                  <span class="has-text-weight-bold">{{ parse(props.row.started_at, 'datetime') }}</span>
                </template>
                <template v-else>{{ 'No disponible' }}</template>
              </b-table-column>
              <b-table-column label="Fin" centered>
                <template v-if="props.row.finished_at !== null">
                  <span class="has-text-weight-bold">{{ parse(props.row.finished_at, 'datetime') }}</span>
                </template>
                <template v-else>{{ 'No disponible' }}</template>
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
    </div>
    <div class="col-12 mt-3">
      <div class="row mr-0 ml-0 no-gutters justify-content-between" v-if="hasData">
        <div class="col-12 col-md-auto has-text-centered"></div>
        <div class="col-12 col-md-auto has-text-centered">
          <el-pagination
              @current-change="onPageChange"
              :hide-on-single-page="false"
              :current-page.sync="form.current_page"
              layout="prev, pager, next"
              :page-count="form.last_page">
          </el-pagination>
        </div>
        <div class="col-12 col-md-auto has-text-centered">
          <div class="row justify-content-end mr-0 ml-0 no-gutters">
            <div class="col-auto">
              <b-select
                  v-model="form.per_page"
                  @input="onPageChange"
                  :disabled="loader">
                <option value="3">
                  Ver 3
                </option>
                <option value="5">
                  Ver 5
                </option>
                <option value="10">
                  Ver 10
                </option>
                <option value="15">
                  Ver 15
                </option>
              </b-select>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { AdminDashboardService } from '../../../../../services/dashboard/AdminDashboardService'
import {index} from "../../../../../helpers";
import { Input } from 'element-ui';
import { Pagination } from 'element-ui';

export default {
  name: 'EnrollmentUsersDetailsTable',
  components: {
    'el-input': Input,
    'el-pagination': Pagination
  },
  computed: {
    hasData () {
      return !_.isEmpty(this.enrollmentData)
    }
  },
  beforeMount () {
    this.getData()
  },
  methods: {
    getData () {
      this.loader= true
      this.form.experience_id = this.experienceId
      AdminDashboardService.getEnrollmentUserDetails(this.form)
          .then(response => {
            this.enrollmentData = _.get(response, ['data', 'data'], [])
            this.form.current_page = Number(_.get(response, ['data', 'meta', ['current_page']], 1))
            this.form.last_page = Number(_.get(response, ['data', 'meta', ['last_page']], 1))
          })
          .catch(errors => {})
          .then(() => {
            this.loader= false
          })
    },
    parse (value, type = 'date', time_scale = 'minutes') {
      return index.parse(value, type, time_scale, this);
    },
    getClass (score, isEvaluation = true) {
      if (!isEvaluation) {
        return 'text-gray-default'
      }
      switch (true) {
        case (score >= 90):
          return 'has-text-ari-green';
        case (score >= 70 && score < 90):
          return 'has-text-ari-yellow';
        case (score < 70):
          return 'has-text-ari-red';
        default:
          return ''
      }
    },
    viewUserDetails (fullName) {
      this.$parent.$parent.viewUserDetails(fullName)
    },
    search: _.debounce(function () {
      this.form.page = 1
      this.getData()
    }, 500),
    onPageChange (page) {
      this.form.page = page
      this.getData()
    }
  },
  props: {
    experienceId: {
      required: true,
      // type: Number
    },
    evaluable: {
      required: true,
      type: Boolean,
      default: false
    }
  },
  data () {
    return {
      loader: false,
      form: {
        experience_id: null,
        search: null,
        page: 1,
        per_page: 15,
        current_page: 1,
        last_page: 1,
        list: 1
      },
      enrollmentData: []
    }
  }
}
</script>

<style scoped>
  @import '../admin-css/admin-dashboard-css.css';
</style>
