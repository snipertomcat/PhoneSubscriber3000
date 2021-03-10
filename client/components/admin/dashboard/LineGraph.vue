<template>
    <div class="card padding-10">
        <div class="card-container">
            <div class="row">
                <div class="col-12">
                    <b-icon :pack="data.pack_icon" :icon="data.icon"></b-icon>
                    <span class="font-16 has-text-weight-bold">{{data.label}}</span>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-12">
                    <div class="canvas-container">
                        <line-chart ref="chart" :height="170"></line-chart>
                    </div>
                </div>
            </div>
        </div>
        <div class="no_data row align-items-center justify-content-center" v-if="!data.has_data">
            <div class="content col-8">
                <div class="">
                    <div class="has-text-centered">
                        <h3>Aún faltan datos por recopilar.</h3>
                        <p>Regresa en unos días para monitorear los avances</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { Line } from 'vue-chartjs';
    export default {
        name: "LineGraph",
        components: {
            'line-chart': Line,
        },
        props: {
            data: {
                required: true,
            },
        },
        methods: {},
        mounted () {
            this.$refs.chart.renderChart(this.data.data, this.data.options)
        }
    }
</script>

<style scoped>
    .no_data {
        position: absolute;
        top: 0;
        left: 15px;
        width: 100%;
        height: 100%;
        background-color: rgba(255,255,255,0.9);
    }
</style>