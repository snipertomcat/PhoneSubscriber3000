<template>
    <div class="card padding-10" heigth="300">
        <div class="card-container">
            <div class="row">
                <div class="col-12">
                    <b-icon :pack="data.pack_icon" :icon="data.icon" v-if="showIcon"></b-icon>
                    <span class="font-16 has-text-weight-bold">{{ data.label.charAt(0).toUpperCase() + data.label.slice(1) }}</span>
                </div>
            </div>
            <br>
            <div class="row justify-content-end no-gutters">
                <div class="col-auto pr-2">
                    <span class="font-12 has-text-weight-bold">
                        {{ data.data.buyed }}
                        {{ $t('messages.dashboard.licenses_buyed').charAt(0).toUpperCase() + $t('messages.dashboard.licenses_buyed').slice(1) }}
                    </span>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <horizontal-chart ref="chart" :height="height"></horizontal-chart>
                </div>
            </div>
            <br>
            <div class="row justify-content-end" v-if="showAll">
                <div class="col-auto">
                    <a :href="route('licenses.index')">
                        <span class="is-link is-uppercase has-text-weight-bold">ver todo</span>
                    </a>
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
        name: "LicensesStacked",
        components: {
            'horizontal-chart': HorizontalBar,
        },
        props: {
            data: {
                required: true
            },
            showAll: {
                required: false,
                default: true
            },
            showIcon: {
                required: false,
                default: true
            },
            height: {
                type: Number,
                required: false,
                default: 100
            }
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