<template>
  <div class="card">
    <div class="card-content">
      <div class="row mr-0 ml-0 no-gutters">
        <div class="col-auto">
          <span class="has-text-weight-bold font-20">
            Recurrencia
          </span>
        </div>
      </div>
      <div class="row mr-0 ml-0 mt-4 no-gutters justify-content-center">
        <div class="col has-text-centered">
          <div class="recurrence chart canvas-container">
            <canvas ref="UserExperienceRecurrence"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { AdminDashboardService } from '../../../../../services/dashboard/AdminDashboardService'
import _ from 'lodash'

export default {
  name: 'UserExperienceRecurrence',
  mounted () {
    this.getExperienceEnrollmentUserRecurrence()
  },
  methods: {
    getExperienceEnrollmentUserRecurrence () {
      let form = {
        'user_id': this.userId,
        'experience_id': this.experienceId
      };
      AdminDashboardService.getExperienceEnrollmentUserRecurrence(form)
          .then(response => {
            this.recurrence = _.get(response, ['data'], [])
            this.fillChart()
          })
    },
    fillChart () {
      let labels = []
      let data = []

      Object.entries(this.recurrence).forEach((entry) => {
        labels.push(entry[0])
        data.push(entry[1].total)
      });

      let chart = {
        type: 'line',
        data: {
          labels: labels,
          datasets: [
            {
              label: this.$t('messages.dashboard.datasets.recurrence'),
              borderColor: '#000000',
              data: data,
              fill: false,
            }
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          legend: {
            display: false
          },
          scales: {
            yAxes: [{
              ticks: {
                beginAtZero: true,
                stepSize: 10,
                fontSize: 14
              }
            }],
            xAxes: [{
              ticks: {
                fontSize: 14
              }
            }]
          },
          tooltips: {
            bodyFontSize: 14,
            titleFontSize: 14
          }
        }
      }
      new Chart(this.$refs.UserExperienceRecurrence, chart)
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
      recurrence: []
    }
  }
}
</script>

<style scoped>
@import '../admin-css/admin-dashboard-css.css';
</style>