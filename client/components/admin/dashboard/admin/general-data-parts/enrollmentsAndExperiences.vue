<template>
    <div class="mt-5 mb-5 ml-3">
        <div class="row no-gutters mr-0 ml-0">
            <div class="col">
                <div class="card">
                    <div class="card-content p-3">
                        <b-table :data="enrollments.experiences.items"
                                 :striped="true"
                                 :mobile-cards="true"
                                 default-sort-direction="asc"
                                 sortable default-sort="enrollments">
                            <template slot-scope="props">
                                <b-table-column field="title" label="Experiencias" width="240">
                                    <div class="row no-gutters ml-0 mr-0">
                                        <div class="col-auto mr-3">
                                            <img :src="props.row.full_path_cover" style="width: 50px; height: 50px;">
                                        </div>
                                        <div class="col align-self-center">
                                            {{ props.row.title }}
                                        </div>
                                    </div>
                                </b-table-column>

                                <b-table-column field="enrollments" label="Enrolamientos" width="160" sortable centered>
                                    <div class="row no-gutters mr-0 ml-0 align-items-center justify-content-center" style="height: 50px">
                                        <div class="col text-center">
                                            {{ props.row.enrollments.total }}
                                        </div>
                                    </div>
                                </b-table-column>

                                <b-table-column field="finished" label="Finalizado" width="100" sortable centered>
                                    <div class="row no-gutters mr-0 ml-0 align-items-center justify-content-center" style="height: 50px">
                                        <div class="col text-center">
                                            {{ props.row.enrollments.finished }}
                                        </div>
                                    </div>
                                </b-table-column>

                                <b-table-column field="in_progress" label="En progreso" width="100" sortable centered>
                                    <div class="row no-gutters mr-0 ml-0 align-items-center justify-content-center" style="height: 50px">
                                        <div class="col text-center">
                                            {{ props.row.enrollments.in_progress }}
                                        </div>
                                    </div>
                                </b-table-column>

                                <b-table-column field="wait_starting" label="Por Comenzar" width="100" sortable centered>
                                    <div class="row no-gutters mr-0 ml-0 align-items-center justify-content-center" style="height: 50px">
                                        <div class="col text-center">
                                            {{ props.row.enrollments.wait_starting }}
                                        </div>
                                    </div>
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
        </div>
        <div class="row mr-0 ml-0 no-gutters mt-3" v-if="table_has_data">
            <div class="col-12 col-md-4 offset-md-4 justify-content-between has-text-centered">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <el-pagination
                            @current-change="onPageChange"
                            :hide-on-single-page="false"
                            :current-page.sync="enrollments.experiences.current_page"
                            layout="prev, pager, next"
                            :page-count="enrollments.experiences.last_page">
                        </el-pagination>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 has-text-centered">
                <div class="row justify-content-end mr-0 ml-0 no-gutters">
                    <div class="col-auto">
                        <b-select v-model="enrollments.experiences.per_page" @input="setPageSize">
                            <option :value="3">
                                Ver 3
                            </option>
                            <option :value="5">
                                Ver 5
                            </option>
                            <option :value="10">
                                Ver 10
                            </option>
                            <option :value="15">
                                Ver 15
                            </option>
                        </b-select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row no-gutters mr-0 ml-0 mt-4">
            <div class="col">
                <div class="card">
                    <div class="card-content">
                        <div class="">
                            <div class="has-text-weight-bold mb-3">
                                Recurrencia
                            </div>
                            <div class="canvas-container recurrence">
                                <canvas data-target="recurrence"></canvas>
                            </div>
                        </div>
                        <div class="row mr-0 ml-0 no-gutters card no_data" v-if="enrollments.recurrence.data.labels.length < 1">
                            <div class="col-12 align-self-center has-text-centered mt-3">
                            <span class="font-40">
                                <i class="icon-chart"></i>
                            </span><br>
                                <span class="has-text-weight-bold font-20">
                                {{ 'Aún faltan datos por recopilar' }}
                            </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row no-gutters mr-0 ml-0 mt-4" v-show="false">
            <div class="col-6">
                <div class="card">
                    <div class="card-content">
                        <div class="">
                            <div class="has-text-weight-bold mb-3">
                                Proyección de cumplimiento
                            </div>
                            <div class="canvas-container projection">
                                <canvas data-target="projection"></canvas>
                            </div>
                        </div>
                        <div class="row mr-0 ml-0 no-gutters card no_data" v-if="!enrollments.projection.has_data">
                            <div class="col-12 align-self-center has-text-centered mt-3">
                            <span class="font-40">
                                <i class="icon-chart"></i>
                            </span><br>
                                <span class="has-text-weight-bold font-20">
                                {{ 'Aún faltan datos por recopilar' }}
                            </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import { bus } from "../../../../../app_platform";
    import { index } from "../../../../../helpers";
    import { Pagination } from 'element-ui';

    export default {
        name: "enrollmentsAndExperiences",
        components: {
            'el-pagination': Pagination
        },
        computed: {
            table_has_data: function () {
                return !_.isEmpty(_.get(this.enrollments, ['experiences', 'items'], []))
            },
        },
        beforeMount () {
            bus.$on('reload', value => {
                if (value === 'enrollments') {
                    this.send();
                }
            })
        },
        mounted: function () {
            this.send()
        },
        methods: {
            drawChart: function (items = [], target = null, options = null) {
                let chart = document.querySelector('.canvas-container canvas[data-target="'+target+'"]')
                let labels = [];
                let datas = [];

                Object.entries(items).forEach((entry) => {
                    labels.push(entry[0]);
                    datas.push(entry[1].total);
                });

                this.enrollments[target].data.labels = labels;
                this.enrollments[target].data.datasets = [
                    {
                        label: this.$t('messages.dashboard.compliment'),
                        borderColor: '#000000',
                        data: datas,
                        fill: false,
                    }
                ];

                let yAxisCallback = (value, index, values) => {
                    if (value === 0 || value === 100) {
                        return value
                    }
                }
                let yAxisMaxValue = 100

                if (target === 'recurrence') {
                    yAxisMaxValue = Math.max.apply(null,datas) + 10
                    yAxisCallback = (value, index, values) => {
                        let max_value = Math.max.apply(null,values)
                        if (value === 0 || value === max_value) {
                            return value
                        }
                    }
                }

                let props = (!_.isEmpty(options)) ? options : {
                    type: 'line',
                    data: this.enrollments[target].data,
                    options: {
                        maintainAspectRatio: false,
                        legend: {
                            display: false
                        },
                        elements: {
                            line: {
                                tension: 0
                            }
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
                                    fontSize: 14,
                                    callback: (value, index, values) => {
                                        let now = new Date().toLocaleDateString('es-MX', {
                                            year: 'numeric',
                                            month: 'numeric',
                                            day: 'numeric'
                                        })
                                        return (now === value) ? 'Hoy' : value
                                    }
                                }
                            }]
                        }
                    }
                };

                new Chart(chart, props)

            },
            send: function (page = 1) {
                this.loading = true
                let loader = index.getLoader()
                let token = document.head.querySelector('meta[name="csrf-token"]')
                let headers = new Headers()

                headers.append('X-Requested-With', 'XMLHttpRequest')
                headers.append('X-CSRF-TOKEN', token.content)
                headers.append('ajax_request', 'true')
                headers.append('Content-Type', 'application/json')

                fetch(route('dashboard.general'), {
                    method: 'POST',
                    headers: headers,
                    body: JSON.stringify({
                        ajax_request: true,
                        give: 'enrollments',
                        time_period: this.$parent.getDatePickerValue(),
                        page: page,
                        per_page: this.enrollments.experiences.per_page
                    })
                })
                    .then(response => response.json())
                    .then(response => {
                        _.each(response.view.activity.enrollments, (item, key) => {
                            if (key === 'experiences'){
                                if ('data' in item) {
                                    this.enrollments.experiences.items = (typeof item.data === 'object')
                                        ? Object.values(item.data)
                                        : item.data
                                    this.enrollments.experiences.current_page = item.current_page
                                    this.enrollments.experiences.last_page = item.last_page
                                    this.enrollments.experiences.per_page = item.per_page
                                } else {
                                    this.enrollments.experiences.items = item
                                    this.enrollments.experiences.current_page = 1
                                    this.enrollments.experiences.last_page = 1
                                    this.enrollments.experiences.per_page = 5
                                }
                            }

                            if (key === 'recurrence' || key === 'projection') {
                                this.drawChart(item, key)
                            }

                        })

                        this.loading = false
                        loader.close()
                    })
                    .catch(error => {
                        console.error(error)
                    })
            },
            onPageChange (page) {
                this.send(page)
            },
            setPageSize (size) {
                this.enrollments.experiences.per_page = size
                this.enrollments.experiences.current_page = 1;
                this.send(1)
            }
        },
        data: function () {
            return {
                loading: false,
                enrollments: {
                    experiences: {
                        search: '',
                        items: [],
                        current_page: 1,
                        last_page: 1,
                        per_page: 5,

                    },
                    recurrence: {
                        data: {
                            labels: [],
                            datasets: []
                        },
                    },
                    projection: {
                        has_data: false,
                        data: {
                            labels: [],
                            datasets: []
                        },
                    }
                }
            }
        },
    }
</script>

<style scoped>
    .card {
        box-shadow: 0px 2px 6px rgba(161, 161, 161, 0.5);
    }
    .card .canvas-container {
        height: 250px
    }
    .no_data {
         position: absolute;
         top: 0;
         left: 15px;
         width: 100%;
         height: 100%;
         background-color: rgba(255,255,255,0.9);
     }
</style>
