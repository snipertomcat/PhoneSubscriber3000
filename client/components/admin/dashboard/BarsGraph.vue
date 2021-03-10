<template>
  <div class="apithy-bars-graph">
    <template v-if="loading">
      <div class="no_data row align-items-center justify-content-center">
        <div class="content col-8">
          <div class="">
            <div class="has-text-centered">
              <h3>Aún faltan datos por recopilar.</h3>
              <p>Regresa en unos días para monitorear los avances</p>
            </div>
          </div>
        </div>
      </div>
    </template>
    <template v-else>
      <bars-chart ref="chart" :height="170"></bars-chart>
    </template>
  </div>
</template>

<script>
  import {bus} from "../../../app_platform";
  import { Bar } from 'vue-chartjs';
  export default {
    name: "BarsGraph",
    props: {
      chartData: {
        required: true,
      },
      chartOptions: {
        required: true
      }
    },
    components: {
      'bars-chart': Bar
    },
    methods: {
      drawChart () {
        this.loading = false
        setTimeout(() => {
          this.$refs.chart.renderChart(this.chartData, this.chartOptions)
        }, 100)
      }
    },
    created () {
      bus.$on('updateChart', () => {
        this.loading = true
        this.drawChart()
      })
    },
    mounted () {
      this.drawChart()
    },
    data () {
      return {
        loading: true,
      }
    }
  }
</script>

<style scoped>

</style>
