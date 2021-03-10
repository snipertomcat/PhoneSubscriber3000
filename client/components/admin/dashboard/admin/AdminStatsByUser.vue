<template>
    <div class="row no-gutters mr-0 ml-0 mt-4">
        <div class="col-6 pr-3">
            <!-- User's information -->
            <div class="row no-gutters mr-0 ml-0" v-if="is_user_selected">
                <div class="col-12 has-text-centered">
                    <div class="user-avatar">
                        <img class="avatar" :src="user_info.full_path_avatar" alt="">
                    </div>
                </div>
                <div class="col-12 mt-3 has-text-centered">
                    <div class="user-name has-text-weight-bold">
                        <span>{{user_info.full_name}}</span>
                    </div>
                    <div class="font-14">
                        <span class="user-position" v-if="user_position">{{user_position.name}}</span>
                        <span v-if="user_position && user_area"> | </span>
                        <span class="user-area" v-if="user_area">{{user_area.name}}</span>
                    </div>
                    <div class="user-email font-14">
                        <span>{{user_info.email}}</span>
                    </div>
                </div>
                <hr width="2">
            </div>
            <!-- User's experiences progress -->
            <div class="row no-gutters mr-0 ml-0">
                <div class="col-12 col-lg-6">
                    <div class="margin-b-10"><span class="has-text-weight-bold">Mis experiencias</span></div>
                    <div>
                        <b-select expanded
                                  placeholder="Todas"
                                  v-model="experiences_status.selected"
                                  @input="filterExperiences">
                            <option :value="null">Todas</option>
                            <option v-for="(status, index) in experiences_status.list"
                                    :value="status.value" :key="index">
                                {{ translate(status.label) }}
                            </option>
                        </b-select>
                    </div>
                    <br>
                </div>
            </div>
            <div class="row no-gutters mr-0 ml-0 mt-3">
                <div class="col-12 font-14 experiences-list" v-if="has_user_experiences">
                    <div class="experiences-header d-inline-flex">
                        <div class="align-self-center" style="min-width: 330px;">
                            <span class="has-text-weight-bold">
                                Experiencia
                            </span>
                        </div>
                        <div class="align-self-center has-text-centered" style="min-width: 150px;">
                            <span class="has-text-weight-bold">
                                Progreso
                            </span>
                        </div>
                        <div class="align-self-center">
                            <span class="has-text-weight-bold">
                                Promedio de rendimiento
                            </span>
                        </div>
                    </div>
                    <div class="card mt-3 experiences-list">
                        <div class="card-content p-0">
                            <div class="experiences-list-container">
                                <div class="d-inline-flex align-self-center experience-item full-width pointer"
                                     :class="{'opaque': index % 2}"
                                     v-for="(progress, index) in user.experiences.filtered_list"
                                     @click="$parent.selectExperience(progress.title)">
                                    <div class="pl-3 align-self-center" style="width: 335px;">
                                        <span class="">
                                            {{ progress.title }} <br>
                                            Enrolado desde {{ progressEnrollmentFromDate(progress) }}
                                        </span>
                                    </div>
                                    <div class="has-text-centered align-self-center" style="width: 150px;">
                                        <span class="">
                                            {{ getProgress(progress) }}%
                                        </span>
                                    </div>
                                    <div class="text-center align-self-center">
                                        <span class="">
                                            {{ getScore(progress) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6" v-if="is_user_selected">
            <div class="row no-gutters mr-0 ml-0">
                <!-- User's last session -->
                <div class="col-6">
                    <div class="margin-b-10">
                        <span class="has-text-weight-bold">Última sesión</span>
                    </div>
                    <div class="card">
                        <div class="card-content text-center" v-if="!!user_last_session_date">
                            <div class="has-text-weight-bold">
                                {{ user_last_session_date }}
                            </div>
                            <div class="has-text-weight-bold">
                                {{ user_last_session_time }}
                            </div>
                        </div>
                        <div class="card-content text-center" v-else>
                            <div class="has-text-weight-bold">
                                {{ 'El usuario no ha cursado una experiencia' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row no-gutters mr-0 ml-0">
                <!-- User's experiences graph -->
                <div class="col-12 mt-4">
                    <div class="">
                        <span class="has-text-weight-bold">Mis experiencias</span>
                    </div>
                    <div class="">
                        <canvas ref="my_experiences_chart" height="50" style="width: 90%" v-show="has_user_activity"></canvas>
                        <div class="row mr-0 ml-0 no-gutters" v-show="!has_user_activity">
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
            <br>
            <br>
            <div class="row">
                <!-- User's completed experiences graphs -->
                <div class="col-12">
                    <div class="row margin-b-25" v-show="!user.experiences.selected">
                        <div class="col-12 margin-b-10">
                            <div><span class="has-text-weight-bold">Experiencias</span></div>
                        </div>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-content"v-show="has_user_activity">
                                    <div class="canvas-container" >
                                        <canvas ref="global_completed_experiences" width="100%"></canvas>
                                    </div>
                                </div>
                                <div class="row mr-0 ml-0 no-gutters card-content" v-show="!has_user_activity">
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
                    <div class="row margin-tb-25">
                        <div class="margin-b-10">
                            <div><span class="has-text-weight-bold margin-b-20">Retos completados</span></div>
                        </div>
                        <div class="col-12 card">
                            <div class="card-content" v-show="has_user_activity">
                                <div class="canvas-container">
                                    <canvas ref="global_completed_sessions"></canvas>
                                </div>
                            </div>
                            <div class="row mr-0 ml-0 no-gutters card-content" v-show="!has_user_activity">
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
                    <div class="row margin-tb-25">
                        <div class="margin-b-10">
                            <div><span class="has-text-weight-bold margin-b-20">Actividades completadas</span></div>
                        </div>
                        <div class="col-12 card">
                            <div class="card-content" v-show="has_user_activity">
                                <div class="canvas-container">
                                    <canvas ref="global_completed_activities"></canvas>
                                </div>
                            </div>
                            <div class="row mr-0 ml-0 no-gutters card-content" v-show="!has_user_activity">
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
        <b-loading :is-full-page="false" :active.sync="loading" :can-cancel="false"></b-loading>
    </div>
</template>

<script>
    import ChartJS from 'chart.js';
    import { bus } from "../../../../app_platform";
    import { index } from "../../../../helpers";

    export default {
        name: "AdminStatsByUser",
        computed: {
            user_info: function () {
                return this.user.selected;
            },
            user_position: function () {
                return _.head(this.user_info.position);
            },
            user_area: function () {
                return (!!this.user_position && !!this.user_position.area) ? this.user_position.area : null;
            },
            user_last_session_date: function () {
                if (this.has_user_activity) {
                    return _.head(this.user.activities).created_at.split(' ')[0];
                }
            },
            user_last_session_time: function () {
                if (this.has_user_activity) {
                    return _.head(this.user.activities).created_at.split(' ')[1];
                }
            },
            is_user_selected: function () {
                return !_.isEmpty(this.user.selected);
            },
            is_user_experience_selected: function () {
                if (this.is_user_selected) {
                    return !!this.user.experiences.selected;
                }
            },
            has_user_activity: function () {
                return !!this.user.activities.length;
            },
            has_user_experiences: function () {
                if (this.is_user_selected) {
                    return !!this.user.experiences.list.length;
                }
            },
        },
        mounted () {},
        methods: {
            progressEnrollmentFromDate (progress) {
                let date = _.get(progress, ['enrolled_from'])
                if (date) {
                    return index.parse(date,'date')
                }
                console.error('invalid enrolled_from attribute: 265')
                return 'No disponible';
            },
            parse: function (time, type = 'date', time_scale = 'minutes') {
                return index.parse(time, type, time_scale, this)
            },
            getScore(experience) {
                return index.parse(experience.last_enrollment.score, 'score');
            },
            translateVerb(verb) {
                let translate = '';

                switch (verb) {
                    case 'access':
                        translate = ' accesó'; break;
                    case 'completed':
                        translate = ' completó'; break;
                    case 'scrolled':
                        translate = 'hizo scroll'; break;
                    case 'attempted':
                        translate = 'hizo un intento'; break;
                    case 'interacted':
                        translate = 'interactúo'; break;
                    case 'answered':
                        translate = 'respondió'; break;
                    case 'seeking':
                        translate = 'adelantó'; break;
                    case 'started':
                        translate = 'inició'; break;
                    case 'finished':
                        translate = 'finalizó'; break;
                    case 'paused':
                        translate = 'pausó'; break;
                    default:
                        translate = verb;
                        break;
                }
                return translate;
            },
            randomScalingFactor() {
                let min = 0, max = 9;
                return Math.floor(Math.random() * (max - min)) + min;
            },
            drawGraphs () {
                let barChartData = null;
                let months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                let options = {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false,
                        position: 'bottom',
                    },
                    title: {
                        display: false,
                    },
                    tooltips: {
                        enabled: true,
                    },
                    scales: {
                        yAxes: [
                            {
                                ticks: {
                                    beginAtZero: true,
                                    suggestedMax: 10,
                                    stepSize: 10,
                                    fontSize: 14
                                }
                            },
                        ],
                        xAxes: [
                            {
                                gridLines: {
                                    display: false
                                },
                                ticks: {
                                    beginAtZero: true,
                                    fontSize: 14,
                                    callback: (value, index, values) => {
                                        return value.substring(0,3)
                                    }
                                }
                            }
                        ]
                    }
                };

                if (!this.user.experiences.selected) {
                    barChartData = {
                        labels: months,
                        datasets: [
                            {
                                label: '',
                                backgroundColor: '#0098FF',
                                borderWidth: 0,
                                data: []
                            }
                        ]
                    };

                    _.each(this.user.experiences.graphs, (graph, index) => {
                        let el = null
                        let aux_array = []

                        el = this.$refs['global_completed_'+index];

                        _.each(months, month => {
                            let item = {
                                x: month,
                                y: _.size(graph.data_list[month])
                            }
                            aux_array.push(item);
                        });

                        if (!!el) {
                            if (_.isEmpty(this.graphs[index])) {
                                this.graphs[index] = new Chart(el.getContext('2d'), {
                                    type: 'bar',
                                    data: {
                                        labels: months,
                                        datasets: [
                                            {
                                                label: '',
                                                backgroundColor: '#0098FF',
                                                borderWidth: 0,
                                                data: aux_array
                                            }
                                        ]
                                    },
                                    options: options
                                });
                            } else {
                                this.graphs[index].chart.data.labels = months
                                this.graphs[index].chart.data.datasets = barChartData.datasets

                                this.graphs[index].chart.update()
                            }
                        } else {
                            setTimeout(() => {
                                this.drawGraphs();
                            }, 500);
                        }
                    });
                }
            },
            resetExperienceSelected ($e, redraw = true) {
                this.user.experiences.selected = null;
                if (redraw) {
                    this.drawGraphs();
                }
            },
            selectExperience (experience) {
                this.user.experiences.selected = experience;
                this.getExperienceActivityData(experience.id);
            },
            hasProgress (experience) {
                return !!experience.enroll_progress
            },
            getProgress (experience) {
                //return index.parse(experience.enroll_progress.progress_percent, 'progress');
                switch (experience.enroll_status) {
                    case 2:
                        return 100;
                        break;
                    default:
                        return index.parse(experience.enroll_progress.progress_percent, 'progress');
                    break;
                }
            },
            filterExperiences () {
                if (!!this.experiences_status.selected) {
                    this.user.experiences.filtered_list = [];
                    _.each(this.user.experiences.list, experience => {
                        if (experience.enroll_status === this.experiences_status.selected) {
                            this.user.experiences.filtered_list.push(experience)
                        }
                    })
                } else {
                    this.user.experiences.filtered_list = this.user.experiences.list;
                }

                this.getExperienceActivityData(null, this.experiences_status.selected)
                this.resetExperienceSelected(null, false);
            },
            getFilteredTags (term, fields = [], allow_return = false) {
                this.user.list = [];
                axios({
                    url: '/utilities/autocomplete/user',
                    method: 'GET',
                    params: {
                        fields: fields,
                        search_text: term,
                        for_dashboard: true,
                    },
                })
                    .then(response => {
                        if (!allow_return) {
                            this.user.list = response.data;
                        } else {
                            return response.data;
                        }
                    })
                    .catch(error => {
                        console.log(error)
                    })
            },
            getActivityData (selection, with_filters = false) {
                this.loading = true
                if(selection) {
                    if(selection.position && selection.position.length) {
                        this.user.area = selection.position[0].area.name
                    }

                    if (!_.isEmpty(selection)) {
                        axios({
                            url: route('profile.user-activity'),
                            method: 'POST',
                            params: {
                                user_id: selection.id,
                            }
                        }).then(response => {
                            this.user.activities = response.data.activity;
                            this.user.experiences.list = _.unionBy(response.data.experiences, response.data.journeys, 'id');
                            this.user.experiences.filtered_list = this.user.experiences.list;
                            this.experiences_status.list = response.data.experiences_status;
                            this.user.experiences.graphs = response.data.general_graphs;
                            this.prepareMyExperiencesGraph();
                            this.drawGraphs();
                            this.user.is_loading = false;
                            bus.$emit('user_enrollments', response.data.enrollments)
                        }).catch(error => {
                            throw error;
                        }).then(() => {
                            this.loading = false
                        })
                    }
                }
            },
            getExperienceActivityData (experience_id = null, enroll_status = null) {
                experience_id = experience_id
                axios({
                    url: route('profile.user-activity'),
                    method: 'POST',
                    params: {
                        user_id: this.user.selected.id,
                        experience_id: experience_id,
                        status: enroll_status,
                    }
                }).then(response => {
                    this.user.experiences.graphs = response.data.general_graphs;
                    // this.user.experience_selected.graphs = response.data.experience_graphs;
                    this.drawGraphs();
                }).catch(error => {
                    console.log(error);
                })
            },
            prepareMyExperiencesGraph () {
                let raw_data = this.user.experiences.list;
                let ctx = this.$refs.my_experiences_chart.getContext('2d');
                let options = this.user.my_experiences_chart.options;
                let datasets;

                if (!!raw_data.length) {
                    let d_in_progress = 0;
                    let d_not_sarted = 0;
                    let d_finished = 0;

                    _.each(raw_data, experiences => {
                        switch (experiences.enroll_status) {
                            // Not started
                            case 1:
                                d_not_sarted++;
                                break;
                            // Finished
                            case 2:
                                d_finished++;
                                break;
                            // In progress
                            case 7:
                                d_in_progress++;
                                break;
                            default:
                                break;
                        }
                    });

                    datasets = [
                        {
                            data: [d_finished],
                            label: this.translate('finished'),
                            hoverBorderWidth: 0,
                            backgroundColor: '#0098FF',
                        },
                        {
                            data: [d_in_progress],
                            label: this.translate('in_progress'),
                            hoverBorderWidth: 0,
                            backgroundColor: '#FFA81E',
                        },
                        {
                            data: [d_not_sarted],
                            label: this.translate('not_started'),
                            hoverBorderWidth: 0,
                            backgroundColor: '#DADADA',
                        },
                    ];

                    this.user.my_experiences_chart.data.datasets = datasets;

                    this.chart = new ChartJS(ctx, {
                        type: 'horizontalBar',
                        options: options,
                        data: {
                            labels: [''],
                            datasets: datasets,
                        }
                    })
                }
            },
            setUser (element) {
                this.user.selected = element
                this.getActivityData(element, false);
            },
            clearUser (element) {
                this.experiences_status.selected = null;
                this.filterExperiences();
                this.user.activities = [];
                this.user.my_experiences_chart.data.datasets = [];
                this.user.experiences.selected = null;
                this.user.selected = [];
                setTimeout(() => {
                    document.getElementById('user_filter').focus();
                }, 500);
            },
            translate (state) {
                if (typeof state === 'string') {
                    switch (state) {
                        case 'in_progress':
                            return 'En proceso';
                            break;
                        case 'finished':
                            return 'Completadas';
                            break;
                        case 'not_started':
                            return 'Sin comenzar';
                            break;
                        default:
                            return text;
                            break;
                    }
                } else if (typeof state === 'number') {
                    switch (state) {
                        case 7:
                            return 'En proceso';
                            break;
                        case 2:
                            return 'Completada';
                            break;
                        case 1:
                            return 'Sin comenzar';
                            break;
                        default:
                            return text;
                            break;
                    }
                }
            }
        },
        data () {
            return {
                loading: true,
                user: {
                    list: [],
                    selected: {},
                    is_fetching: false,
                    activities: [],
                    my_experiences_chart: {
                        data: {
                            labels: [''],
                            datasets: [],
                        },
                        options: {
                            animation: {
                                duration: 10
                            },
                            scales: {
                                xAxes: [
                                    {
                                        stacked: true,
                                        gridLines: {
                                            display: false
                                        },
                                        ticks: {
                                            stepSize: 2
                                        }
                                    }
                                ],
                                yAxes: [
                                    {
                                        stacked: true,
                                        gridLines: {
                                            display: false
                                        },
                                    }
                                ]
                            },
                            legend:{
                                display: true,
                                position: 'bottom'
                            }
                        }
                    },
                    experiences: {
                        list: [],
                        filtered_list: [],
                        selected: null,
                        graphs: {},
                        graphs_options: {
                            type: 'bar',
                            data: null,
                            options: {
                                legend: {
                                    display: false,
                                    position: 'top',
                                },
                                title: {
                                    display: false,
                                }
                            }
                        }
                    },
                    experience_selected : {
                        graphs: {
                            sessions: {},
                            activities: {}
                        }
                    },
                },
                experiences_status: {
                    list: [],
                    selected: null
                },
                graphs: {
                    activities: null,
                    experiences: null,
                    sessions: null
                }
            }
        },
    }
</script>

<style scoped>
    .image-rounded {
        border-radius: 50%;
        overflow: hidden;
    }
    .user-avatar .avatar {
        border-radius: 50%;
        width: 150px;
    }
    .experiences-list.card {
        box-shadow: 0px 2px 6px rgba(161, 161, 161, 0.5);
    }
    .experiences-list-container {
        max-height: 633px;
        overflow-y: auto;
    }
    .experiences-list-container .experience-item {
        height: 75px;
    }
    .experiences-list-container .experience-item.opaque {
       background-color: #F2F2F2;
    }
    .progress.is-micro {
        height: 0.50rem;
    }
    .completed-experiences-container {
        height: 80px;
    }
    .canvas-container {
        min-height: 200px;
        max-height: 200px;
    }
    .margin-b-25 {
        margin-bottom: 25px;
    }
    .margin-b-20 {
        margin-bottom: 20px;
    }
    .margin-b-10 {
        margin-bottom: 10px;
    }
    .padding-l-15 {
        padding-left: 15px;
    }
</style>
