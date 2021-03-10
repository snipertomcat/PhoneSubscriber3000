<template>
    <div class="card padding-10">
        <div class="card-container">
            <div class="row" v-if="data.icon&&data.label">
                <div class="col-12">
                    <b-icon :pack="data.pack_icon" :icon="data.icon"></b-icon>
                    <span class="font-16 has-text-weight-bold">{{data.label}}</span>
                </div>
            </div>
            <br v-if="data.icon&&data.label">
            <div class="row align-items-center" v-for="(item,index) in data.data">
                <div class="col-1">
                    <b-icon :pack="data.pack_icon" :icon="item.icon"></b-icon>
                </div>
                <div class="col-9">
                    <horizontal-chart :data="item.data" :ref="'chart_'+index" :height="75"></horizontal-chart>
                </div>
            </div>
            <div class="row justify-content-end" v-if="showAll">
                <div class="col-auto">
                    <a :href="route('details.experiences-califications')">
                        <span class="font-16 has-text-weight-bold has-text-weight-bold is-link">{{ $t('messages.dashboard.show_all') }}</span>
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
        name: "CalificationGraph",
        components: {
            'horizontal-chart': HorizontalBar,
        },
        props: {
            data: {
                required: true,
            },
            showAll: {
                required: false,
                default: true
            }
        },
        mounted() {
            Object.values(this.$refs).forEach(item => {
                if(item[0]) {
                    item = item[0];
                }
                item.renderChart(item.$attrs.data, this.data.options);
            });
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