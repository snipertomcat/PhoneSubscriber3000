<template>
  <div class="card">
    <div class="card-content">
      <div class="">
        <span class="has-text-weight-bold">Fecha de inicio: </span>
        <span>{{ startedAt }}</span>
      </div>
      <div class="mt-3">
        <span class="has-text-weight-bold">Fecha de finalización: </span>
        <span>{{ finishedAt }}</span>
      </div>
      <div class="mt-3">
        <span class="has-text-weight-bold">Progreso: </span>
        <span>{{ progress }}%</span>
      </div>
      <div class="mt-3" v-if="duration">
        <span class="has-text-weight-bold">Tiempo efectivo: </span>
        <span>{{ duration }}</span>
      </div>
      <div class="mt-4" v-if="evaluable || score ">
        <div class="has-text-centered">
          <span class="has-text-weight-bold font-40" :class="getClass(score)">{{ score }}</span>
        </div>
        <div class="has-text-centered">
          <span>Promedio total de rendimiento</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { AdminDashboardService } from '../../../../../services/dashboard/AdminDashboardService'
import _ from 'lodash'
import { index } from '../../../../../helpers'

export default {
  name: 'EnrollmentUserDetails',
  beforeMount () {
    this.getEnrollmentUserDetails()
  },
  computed: {
    startedAt () {
      let startedAt = _.get(this.enrollment, ['started_at'], null)
      return index.parse(startedAt, 'datetime', 'minutes', this)
    },
    finishedAt () {
      let finishedAt = _.get(this.enrollment, ['finished_at'], null)
      return index.parse(finishedAt, 'datetime', 'minutes', this)
    },
    progress () {
      let progress = _.get(this.enrollment, ['progress'], null)
      return index.parse(progress, 'progress', 'minutes', this)
    },
    duration () {
      let duration = _.get(this.enrollment, ['duration'], null)
      return duration;
    },
    score () {
      let score = _.get(this.enrollment, ['score'], null)
      return index.parse(score, 'score', 'minutes', this)
    },
    evaluable () {
      let evaluable = _.get(this.enrollment, ['evaluable'], null)
      return evaluable
    }
  },
  methods: {
    getEnrollmentUserDetails () {
      let form = {
        'user_id': this.userId,
        'experience_id': this.experienceId
      }
      AdminDashboardService.getEnrollmentUserDetails(form)
          .then(response => {
            this.enrollment = _.get(response, ['data', 'data'], {})
          })
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
    }
  },
  props: {
    experienceId: {
      required: true,
      type: Number
    },
    userId: {
      required: true,
      type: Number
    }
  },
  data () {
    return {
      enrollment: {}
    }
  }
}
</script>

<style scoped>
@import '../admin-css/admin-dashboard-css.css';
</style>
