<template>
    <div class="">
        <div class="row ml-0 mr-0 no-gutters mt-4">
            <div class="col-12 col-lg-6 pr-3">
                <!-- Asignaciones -->
                <div class="card">
                    <div class="card-content">
                        <div class="row mr-0 ml-0 no-gutters">
                            <div class="col-12">
                                    <span class="has-text-weight-bold">
                                        Asignaciones
                                    </span>
                            </div>
                            <template v-if="experience.assignations.total.value > 0">
                                <div class="col-12 has-text-right">
                                    <span class="">
                                        {{ experience.assignations.total.value }} asignaciones
                                    </span>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="bar ari-b-gray" :style="`width: ${experience.assignations.total.percent}%`"></div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="d-inline-flex full-width">
                                        <div class="bar big ari-b-blue" :style="`width: ${experience.assignations.started.percent}%`"></div>
                                        <div class="bar big ari-b-pink" :style="`width: ${experience.assignations.waiting.percent}%`"></div>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="row ml-0 mr-0 no-gutters justify-content-between">
                                        <div class="col-auto">
                                            <span class="has-text-weight-bold">
                                                {{ experience.assignations.started.value }} asignadas
                                            </span>
                                        </div>
                                        <div class="col-auto">
                                            <span class="ari-text-pink">
                                                {{ experience.assignations.waiting.value }} pendientes
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <template v-else-if="experience.assignations.total.value === 0">
                                <div class="col-12 has-text-centered mt-3">
                                    <span class="font-40">
                                        <i class="icon-chart"></i>
                                    </span><br>
                                    <span class="has-text-weight-bold font-20">
                                        {{ 'Aún no se han hecho asignaciones' }}
                                    </span>
                                </div>
                            </template>
                            <template v-else>
                                <div class="col-12 has-text-centered mt-3">
                                    <span class="font-40">
                                        <i class="icon-chart"></i>
                                    </span><br>
                                    <span class="has-text-weight-bold font-20">
                                        {{ 'Aún faltan datos por recopilar' }}
                                    </span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                <!-- Invitaciones -->
                <div class="card mt-4">
                    <div class="card-content">
                        <div class="row mr-0 ml-0 no-gutters">
                            <div class="col-12">
                                    <span class="has-text-weight-bold">
                                        Invitaciones
                                    </span>
                            </div>
                            <template v-if="experience.invitations.total.value > 0">
                                <div class="col-12 has-text-right">
                                    <span class="">
                                        {{ experience.invitations.total.value }} invitaciones
                                    </span>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="bar ari-b-gray" :style="`width: ${experience.invitations.total.percent}%`"></div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="d-inline-flex full-width">
                                        <div class="bar big ari-b-blue" :style="`width: ${experience.invitations.accepted.percent}%`"></div>
                                        <div class="bar big ari-b-pink" :style="`width: ${experience.invitations.waiting.percent}%`"></div>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="row ml-0 mr-0 no-gutters justify-content-between">
                                        <div class="col-auto">
                                            <span class="has-text-weight-bold">
                                                {{ experience.invitations.accepted.value }} aceptadas
                                            </span>
                                        </div>
                                        <div class="col-auto">
                                            <span class="ari-text-pink">
                                                {{ experience.invitations.waiting.value }} pendientes
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <template v-else-if="experience.invitations.total.value === 0">
                                <div class="col-12 has-text-centered mt-3">
                                    <span class="font-40">
                                        <i class="icon-chart"></i>
                                    </span><br>
                                    <span class="has-text-weight-bold font-20">
                                        {{ 'Aún no se han enviado invitaciones' }}
                                    </span>
                                </div>
                            </template>
                            <template v-else>
                                <div class="col-12 has-text-centered mt-3">
                                    <span class="font-40">
                                        <i class="icon-chart"></i>
                                    </span><br>
                                    <span class="has-text-weight-bold font-20">
                                        {{ 'Aún faltan datos por recopilar' }}
                                    </span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                <!-- Enrollments -->
                <div class="card mt-4 mb-5">
                    <div class="card-content">
                        <div class="row mr-0 ml-0 no-gutters">
                            <div class="col-12">
                                    <span class="has-text-weight-bold">
                                        Enrolamiento
                                    </span>
                            </div>
                            <template v-if="experience.enrollment.total.value > 0">
                                <div class="col-12 has-text-right">
                                    <span class="">
                                        {{ experience.enrollment.total.value }} enrolamientos
                                    </span>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="bar ari-b-gray" :style="`width: ${experience.enrollment.total.percent}%`"></div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="d-inline-flex full-width">
                                        <div class="bar big ari-b-blue" :style="`width: ${experience.enrollment.finished.percent}%`"></div>
                                        <div class="bar big ari-b-cyan" :style="`width: ${experience.enrollment.started.percent}%`"></div>
                                        <div class="bar big ari-b-pink" :style="`width: ${experience.enrollment.waiting.percent}%`"></div>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="row ml-0 mr-0 no-gutters justify-content-between">
                                        <div class="col-auto">
                                            <span class="has-text-weight-bold">
                                                {{ experience.enrollment.finished.value }} completadas
                                            </span>
                                        </div>
                                        <div class="col-auto">
                                            <span class="has-text-weight-bold">
                                                {{ experience.enrollment.started.value }} comenzadas
                                            </span>
                                        </div>
                                        <div class="col-auto">
                                            <span class="ari-text-pink">
                                                {{ experience.enrollment.waiting.value }} pendientes
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <template v-else>
                                <div class="col-12 has-text-centered mt-3">
                                    <span class="font-40">
                                        <i class="icon-chart"></i>
                                    </span><br>
                                    <span class="has-text-weight-bold font-20">
                                        {{ 'Aún faltan datos por recopilar' }}
                                    </span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <!-- Calificación Promedio -->
                <div class="card">
                    <div class="card-content">
                        <div class="row mr-0 ml-0 no-gutters">
                            <div class="col-12">
                                    <span class="has-text-weight-bold">
                                        Rendimiento promedio
                                    </span>
                            </div>
                            <div class="col-12 mt-3 has-text-centered">
                                <template v-if="experience.performance_average.value > 0">
                                    <span class="has-text-weight-bold font-50" :class="getClass(experience.performance_average.value)">
                                        {{ parse(experience.performance_average.value, 'score') }}
                                    </span>
                                </template>
                                <template v-else-if="!experience.performance_average.evaluable">
                                    <div class="col-12 has-text-centered mt-3">
                                    <span class="font-40">
                                        <i class="icon-chart"></i>
                                    </span><br>
                                        <span class="has-text-weight-bold font-20">
                                        {{ 'No evaluable' }}
                                    </span>
                                    </div>
                                </template>
                                <template v-else>
                                    <div class="col-12 has-text-centered mt-3">
                                    <span class="font-40">
                                        <i class="icon-chart"></i>
                                    </span><br>
                                        <span class="has-text-weight-bold font-20">
                                        {{ 'Aún faltan datos por recopilar' }}
                                    </span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Recurrence -->
                <div class="card mt-4">
                    <div class="card-content">
                        <div class="row mr-0 ml-0 no-gutters">
                            <div class="col-12">
                                    <span class="has-text-weight-bold">
                                        Recurrencia
                                    </span>
                            </div>
                            <div class="col-12 mt-3 has-text-centered">
                                <div class="recurrence chart canvas-container" v-show="experience.recurrence_has_data">
                                    <canvas></canvas>
                                </div>
                                <div class="" v-show="!experience.recurrence_has_data">
                                    <div class="has-text-centered mt-3">
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
                <!-- Tiempo Promedio -->
                <div class="card mt-4" v-if="false">
                    <div class="card-content">
                        <div class="row mr-0 ml-0 no-gutters">
                            <div class="col-12">
                                    <span class="has-text-weight-bold">
                                        Tiempo de finalización promedio
                                    </span>
                            </div>
                            <div class="col-12 mt-3 has-text-centered">
                                <template v-if="experience.duration_average > 0">
                                    <span class="has-text-weight-bold font-50">{{ experience.duration_average }}</span>
                                </template>
                                <template v-else>
                                    <div class="col-12 has-text-centered mt-3">
                                    <span class="font-40">
                                        <i class="icon-chart"></i>
                                    </span><br>
                                        <span class="has-text-weight-bold font-20">
                                        {{ 'Aún faltan datos por recopilar' }}
                                    </span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <template v-if="experienceSelectedId">
            <enrollment-users-details-table :experience-id.sync="experienceSelectedId" :evaluable="experience.performance_average.evaluable"></enrollment-users-details-table>
        </template>
    </div>
</template>

<script>
    import { Input } from 'element-ui';
    import { index } from '../../../../helpers'
    import { Pagination } from 'element-ui';

    export default {
        name: "AdminExperienceStats",
        components: {
            'enrollment-users-details-table': () => import('./admin-components/EnrollmentUsersDetailsTable'),
            'el-input': Input,
            'el-pagination': Pagination
        },
        computed: {
            table_has_data: function () {
                return !_.isEmpty(this.experience.enrolled_users.items)
            },
            enrolled_users_last_page: function () {
                let parent = this.experience.enrolled_users
                let last = _.size(parent.filtered_items) / parent.per_page
                return Math.ceil(_.size(parent.filtered_items) / parent.per_page)
            },
            experienceSelectedId () {
                return _.get(this.experience_selected, ['id'])
            }
        },
        mounted () {
            let el = document.querySelector('.recurrence.chart canvas').getContext('2d');

            let chart = {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 0,
                    },
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
                        titleFontSize: 14,
                    }
                }
            }

            this.chart = new Chart(el, chart)
        },
        methods: {
            setExperience (experience) {
                this.experience_selected = experience;
                let loader = (this.$parent.loading !== null) ? this.$parent.loading : index.getLoader()
                this.send(loader)
            },
            send (give = 'experience', loaderInstance = null) {
                let loader = (loaderInstance !== null) ? loaderInstance : index.getLoader()
                if (_.isNull(this.experience_selected)) {
                    return 0
                }

                let token = document.head.querySelector('meta[name="csrf-token"]')

                let headers = new Headers()

                headers.append('X-Requested-With', 'XMLHttpRequest')
                headers.append('X-CSRF-TOKEN', token.content)
                headers.append('ajax_request', 'true')
                headers.append('Content-Type', 'application/json')

                fetch(route('dashboard.experiences'), {
                    method: 'POST',
                    headers: headers,
                    body: JSON.stringify({
                        ajax_request: 'true',
                        give: 'experience',
                        experience: {
                            id: _.get(this.experience_selected,['id'],null),
                            search: this.experience_selected.title,
                        }
                    })
                })
                    .then(response => response.json())
                    .then(response => {
                        this.setStats(response.view.activity.experience, loader)
                    })
                    .catch(error => { console.log(error) })
            },
            setStats (stats = null, loaderInstance = null) {
                let loader = (loaderInstance !== null) ? loaderInstance : index.getLoader()
                if (_.isNull(stats)) {
                    return
                }
                _.each(stats, (item, key) => {
                    if (key === 'assignations' || key === 'invitations' || key === 'enrollment') {
                        let max_value = item.total.value

                        _.each(item, (prop, name) => {
                            prop.percent = this.$parent.getPercent(prop.value, max_value)
                        })
                    }

                    if (key === 'recurrence') {
                        let labels = []
                        let datas = []

                        Object.entries(item).forEach((entry) => {
                            labels.push(entry[0])
                            datas.push(entry[1].total)
                        });

                        this.experience.recurrence_has_data = !_.isEmpty(datas)

                        let el = document.querySelector('.recurrence.chart canvas').getContext('2d');

                        let w = {
                            label: this.$t('messages.dashboard.datasets.recurrence'),
                            borderColor: '#000000',
                            data: datas,
                            fill: false,
                        }
                        this.chart.chart.data.labels = labels
                        this.chart.chart.data.datasets.push(w);
                        this.chart.update()

                    }

                    if (key === 'duration_average') {
                        let aux = item

                        item = moment.duration(aux.value, aux.unit_time).humanize()
                    }

                    if (key === 'enrolled_users') {
                        let aux = item;
                        item = {
                            items: aux,
                            filtered_items: aux,
                            per_page: 5,
                            current_page: 1,
                        }
                    }

                    if (key === 'performance_average') {
                        if (!_.isEmpty(item)) {
                            let aux = item;
                            item = {
                                class: aux.class,
                                value: Number(aux.value.toFixed(2)),
                                evaluable: aux.evaluable,
                            }
                        }
                    }

                    this.experience[key] = item
                })

                loader.close()
            },
            viewUserDetails (username) {
                this.$parent.viewUserDetails(username)
            },
            parse (value, type = 'date', time_scale = 'minutes') {
                return index.parse(value, type, time_scale, this);
            },
            getClass (performance, is_scoreable = true) {
                return this.$parent.getClass(performance, is_scoreable)
            },
            filterItems: function (event, props, array_key = 'items') {
                let filtered = []
                if (!_.isEmpty(event)) {
                    filtered = _.filter(this.experience[props.target][array_key], item => {
                        let regex = new RegExp(event, 'gim')
                        return (regex.test(item.full_name))
                    })
                } else {
                    filtered = this.experience[props.target][array_key]
                }
                this.experience[props.target].filtered_items = filtered
            },
            setPageSize: function (value, props) {
                this.experience[props.target].per_page = parseInt(value)
                this.experience[props.target].current_page = 1
                if (props.server_side) {
                    this.send()
                }
            },
            first: function (event, props) {
                this.experience[props.target].current_page = 1
                if (props.server_side) {
                    this.send()
                }
            },
            last: function (event, props) {
                let last_page = Math.round(_.size(this.experience[props.target].items) / this.experience[props.target].per_page)
                this.experience[props.target].current_page = (last_page > 1) ? last_page : 1;
                if (props.server_side) {
                    this.experience[props.target].current_page = this.experience[props.target].last_page
                    this.send()
                }
            },
            prev: function (event, props) {
                if (this.experience[props.target].current_page > 1) {
                    this.experience[props.target].current_page -= 1;
                }
                if (props.server_side) {
                    this.send()
                }
            },
            next: function (event, props) {
                let last_page = (props.server_side)
                    ? this.experience[props.target].last_page
                    : Math.ceil(_.size(this.experience[props.target].items) / this.experience[props.target].per_page)

                if (this.experience[props.target].current_page < last_page) {
                    this.experience[props.target].current_page += 1;

                    if (props.server_side) {
                        this.send()
                    }
                }
            }
        },
        data () {
            return {
                experience_selected: null,
                experience: {
                    recurrence_has_data: false,
                    assignations: {
                        total: {
                            value: 0
                        },
                        started: {
                            value: 0
                        },
                        waiting: {
                            value: 0
                        }
                    },
                    invitations: {
                        total: {
                            value: 0
                        },
                        accepted: {
                            value: 0
                        },
                        waiting: {
                            value: 0
                        }
                    },
                    enrollment: {
                        total: {
                            value: 0
                        },
                        started: {
                            value: 0
                        },
                        finished: {
                            value: 0
                        },
                        waiting: {
                            value: 0
                        }
                    },
                    duration_average: 0,
                    performance_average: {
                        value: 0,
                        class: ''
                    },
                    enrolled_users: {
                        search: '',
                        last_page: 1,
                        per_page: 5,
                        current_page: 1,
                        items: [],
                        filtered_items: [],
                    },
                },
                chart: null
            }
        }
    }
</script>

<style scoped>
    .card .canvas-container {
        height: 250px;
    }
</style>