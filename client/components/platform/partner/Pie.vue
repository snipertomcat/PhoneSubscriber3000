<template>
    <div class="chart-container">
        <pie-chart :chartdata="chartdata" :options="options" ref="chart"></pie-chart>
    </div>
</template>

<script>
    import { Pie } from 'vue-chartjs'

    export default {
        name: 'Pie',
        components: {
            'pie-chart': Pie,
        },
        props: {
            chartdata: {
                type: Object,
                default: null
            },
            options: {
                type: Object,
                default: null
            }
        },
        mounted () {
            this.$refs.chart.renderChart(this.chartdata, this.options)
        }
    }
</script>

<style scoped>
    .chart-container {
        position: relative;
        margin: auto;
    }
</style>