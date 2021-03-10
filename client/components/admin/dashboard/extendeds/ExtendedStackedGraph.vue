<template>
    <div class="card padding-10" heigth="300">
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
                    <span class="font-12 has-text-weight-bold">{{data.description}}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <horizontal-chart ref="chart" :height="100" :data="data"></horizontal-chart>
                </div>
            </div>
            <br><br>
            <div class="row justify-content-between" v-if="data.top_five">
                <div class="col-auto">
                    <span class="font-16 has-text-weight-bold">{{ data.top_five.label }}</span>
                </div>
            </div>
            <div v-if="data.top_five.data" v-for="(item,index) in data.top_five.data">
                <br>
                <div class="row">
                    <div class="col-12 font-14">{{index}}. {{item.name}} por {{item.partner}}</div>
                    <div class="col-12">
                        <horizontal-chart :ref="'chart_'+index" :height="50" :data="item"></horizontal-chart>
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
    import { HorizontalBar } from 'vue-chartjs';
    export default {
        name: "ExtendedStackedGraph",
        components: {
            'horizontal-chart': HorizontalBar,
        },
        props: {
            data: {
                required: true
            },
        },
        methods: {},
        mounted () {
            Object.values(this.$refs).forEach(item => {
                if(item[0]) {
                    item = item[0];
                }
                item.renderChart(item.$attrs.data, this.data.top_five.options);
            });
            this.$refs.chart.renderChart(this.data.data, this.data.options);
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