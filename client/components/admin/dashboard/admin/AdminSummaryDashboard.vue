<template>
    <div class="container">
        <div class="row justify-content-end">
            <div class="col-auto">
                <el-datepicker unlink-panels
                               type="daterange"
                               align="right"
                               start-placeholder="Desde"
                               end-placeholder="Hasta"
                               format="dd-MM-yy"
                               value-format="yy-MM-dd"
                               v-model="date_picker.value"
                               :picker-options="date_picker.options"
                               @change="getSummaryDataStats">
                </el-datepicker>
            </div>
        </div>
        <div>
            <div class="row mt-4">
                <div class="col-12">
                    <span class="font-20 has-text-weight-bold">{{$t('messages.dashboard.compliment')}}</span>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12 col-lg-6">
                    <licenses-average v-if="!tab.cards.licenses_average.loading"></licenses-average>
                    <div class="row mr-0 ml-0 no-gutters card" v-else style="heigth:270px;">
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
                <div class="col-12 col-lg-6 mt-4 mt-lg-0">
                    <average-graph :data="tab.cards.invitations" v-if="!tab.cards.invitations.loading"></average-graph>
                    <div class="row mr-0 ml-0 no-gutters card" v-if="tab.cards.invitations.loading" style="heigth:270px; opacity: 0.5">
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
            <div class="row mt-4">
                <div class="col-12 col-lg-6">
                    <line-graph :data="tab.cards.recurrence" v-if="!tab.cards.recurrence.loading"></line-graph>
                    <div class="row mr-0 ml-0 no-gutters card" v-else style="heigth:270px;">
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
                <div class="col-12 col-lg-6" v-if="false">
                    <line-graph :data="tab.cards.projection" v-if="!tab.cards.projection.loading"></line-graph>
                    <div class="row mr-0 ml-0 no-gutters card" v-else style="heigth:270px;">
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
                <div class="col-12 col-lg-6">
                    <div class="row">
                        <div class="col-12">
                            <licenses-stacked :data="tab.cards.licenses_stacked" v-if="!tab.cards.licenses_stacked.loading" :show-all="false"></licenses-stacked>
                            <div class="row mr-0 ml-0 no-gutters card" v-else style="heigth:270px;">
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
            <div class="row mt-4 mb-4">
                <div class="col-12 col-lg-6">
                    <top-five :data="tab.cards.top_five" v-if="!tab.cards.top_five.loading"></top-five>
                    <div class="row mr-0 ml-0 no-gutters card" v-else style="heigth:270px;">
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
                <div class="col-12 col-lg-6">
                    <div class="row">
                        <div class="col-12">
                            <stacked-graph :data="tab.cards.exploitation" v-if="!tab.cards.exploitation.loading" :show-all="false"></stacked-graph>
                            <div class="row mr-0 ml-0 no-gutters card" v-else style="heigth:270px;">
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
                <div class="col-12 col-lg-6" v-if="false">
                    <div class="row">
                        <div class="col-12">
                            <licenses-stacked :data="tab.cards.licenses_stacked" v-if="!tab.cards.licenses_stacked.loading" :show-all="false"></licenses-stacked>
                            <div class="row mr-0 ml-0 no-gutters card" v-else style="heigth:270px;">
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
                        <div class="col-12 mt-4">
                            <stacked-graph :data="tab.cards.exploitation" v-if="!tab.cards.exploitation.loading" :show-all="false"></stacked-graph>
                            <div class="row mr-0 ml-0 no-gutters card" v-else style="heigth:270px;">
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
        <br>
    </div>
</template>

<script>
    import { DatePicker } from 'element-ui'

    import StackedBar from '../StackedGraph';
    import LineGraph from '../LineGraph';
    import AverageGraph from '../AverageGraph';
    import TopFive from '../TopFive';

    import LicensesAverage from './summary-components/LicensesAverage';
    import LicensesStacked from './summary-components/LicensesStacked';
    import { bus } from "../../../../app_platform";
    import { index } from "../../../../helpers";

    export default {
        name: "AdminDashboard",
        components: {
            'el-datepicker': DatePicker,
            'line-graph': LineGraph,
            'average-graph': AverageGraph,
            'stacked-graph': StackedBar,
            'top-five': TopFive,
            'licenses-average': LicensesAverage,
            'licenses-stacked': LicensesStacked,
        },
        props: {
            summary: {
                required: true,
                type: Object
            }
        },
        beforeMount: function () {
            let date = new Date(Date.now())
            let now = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate()
            let subMonth = () => {
                let days = -30
                let res = date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                res = new Date(res);
                return res.getFullYear() + '-' + (res.getMonth() + 1) + '-' + res.getDate()
            }
            this.date_picker.value = [subMonth(), now]
        },
        mounted() {
            this.getSummaryDataStats();
            //this.setSummaryDataStats()
        },
        methods: {
            getLabelFor(value) {
                return this.$t('messages.dashboard.'+value);
            },
            getColorFor(value) {
                let color;
                switch (value) {
                    case 'assigned':
                        color = '#42B3FF';
                        break;
                    case 'not-started':
                        color = '#42B3FF';
                        break;
                    case 'started':
                        color = '#B6C5DC';
                        break;
                    case 'finished':
                        color = '#FEB396';
                        break;
                    case 'available':
                        color = '#B6C5DC';
                        break;
                    case 'pending':
                        color = '#FEB396';
                        break;
                    default:
                        color = '#999999';
                        break;
                }
                return color;
            },
            getAverage(quantity = 0, total = 0) {
                let average = 0
                if (total > 0) {
                    average = ( quantity / total ) * 100
                    if (!_.isInteger(average)) {
                        average = Number(average).toFixed(2)
                    }
                }
                return average;
            },
            getSummaryDataStats () {
                let loader = index.getLoader()
                _.each(this.tab.cards, (item, key) => {
                    item.loading = true;
                })
                axios({
                    method: 'GET',
                    url: route('dashboard.index'),
                    params: { time_period: this.date_picker.value, ajax_request: true }
                })
                    .then(response => {
                        this.setSummaryDataStats(response.data.view.activity, loader)
                    })
                    .catch(error => {
                        console.error(error)
                    })
            },
            setSummaryDataStats (data, loader) {
                let activities = data //(data) ? data : this.summary;
                Object.keys(activities).forEach(key => {
                    if(this.tab.cards[key]) {
                        let data = this.tab.cards[key].data;
                        let activity = activities[key];

                        if(data) {
                            switch (key) {
                                case 'invitations':
                                    data.primary.value = (activity.sends > 0) ? Math.floor((activity.accepted / activity.sends) * 100) : 0;
                                    data.secondary.total.value = activity.sends;
                                    data.secondary.actual.value = activity.accepted;
                                    this.tab.cards[key].has_data = !!activity.sends;
                                    setTimeout(() => {
                                        this.tab.cards[key].loading = false;
                                    }, 1000)
                                    break;
                                case 'recurrence':
                                    let labels = [];
                                    let datas = [];

                                    Object.entries(activity).forEach((entry) => {
                                        labels.push(entry[0]);
                                        datas.push(entry[1].total);
                                    });

                                    data.labels = labels;
                                    data.datasets[0].data = datas;

                                    this.tab.cards[key].loading = false;
                                    this.tab.cards[key].has_data = _.size(activity) > 1;

                                    break;
                                case 'top_five':
                                    _.each(activity, i => {
                                        let alread_added = _.find(data, {'id': i.id});
                                        if (_.isEmpty(alread_added)) {
                                            data.push({
                                                id: i.id,
                                                name: i.title,
                                                author: i.company_author.name,
                                                image: i.full_path_cover,
                                                quantity: i.enrollments
                                            })
                                        }
                                    });

                                    let aux = _.reverse(_.sortBy(data, item => { return item.quantity }))
                                    this.tab.cards.top_five.data = aux

                                    this.tab.cards[key].loading = false;
                                    this.tab.cards[key].has_data = !_.isEmpty(data);
                                    break;
                                case 'enrollments':
                                    data.primary.value = this.getAverage(activity.enrolled, activity.total);
                                    data.secondary.total.value = activity.total;
                                    data.secondary.actual.value = activity.enrolled;

                                    this.tab.cards[key].loading = false;
                                    this.tab.cards[key].has_data = !!activity.total;
                                    break;
                                case 'licenses_average':
                                    data.average = this.getAverage(activity.licenses_assigned, activity.licenses_buyed);
                                    data.buyed = activity.licenses_buyed;
                                    data.available = activity.licenses_available;
                                    data.assigned = activity.licenses_assigned;

                                    this.tab.cards[key].has_data = true;
                                    this.tab.cards[key].loading = false;

                                    setTimeout(()=>{bus.$emit('la_wea', this.tab.cards[key])}, 500)
                                    break;
                                case 'licenses_stacked':
                                    data.buyed = activity.licenses_buyed;
                                    data.available = activity.licenses_available;
                                    data.assigned = activity.licenses_assigned;
                                    data.pending = activity.licenses_pending;

                                    let licenses_dataset = [];

                                    Object.entries(activity).forEach(entry => {
                                        let label = entry[0].split('_')[1];

                                        if(typeof entry[1] === 'number' && label !== 'buyed') {
                                            licenses_dataset.push({
                                                label: this.getLabelFor(label),
                                                data: [entry[1]],
                                                backgroundColor: this.getColorFor(label),
                                                hoverBorderWidth: 0
                                            });
                                        }
                                        if (label === 'assigned') {
                                            this.tab.cards[key].has_data = !!entry[1];
                                        }
                                    });
                                    data.datasets = licenses_dataset;

                                    this.tab.cards[key].loading = false;
                                    break;
                                case 'projection':
                                    this.tab.cards[key].has_data = false;
                                    this.tab.cards[key].loading = false;
                                    break;
                                case 'exploitation':
                                    let datasets = [];
                                    let top_five = [];
                                    let has_data = 0;
                                    let total = 0;

                                    Object.entries(activity).forEach(entry => {
                                        let label
                                        switch (entry[0]) {
                                            case 'assigned':
                                                label = this.$t('messages.dashboard.datasets.without_starting');
                                                break;
                                            case 'started':
                                                entry[0] = 'started';
                                                label = this.$t('messages.dashboard.datasets.unfinished');
                                                break;
                                            case 'finished':
                                                label = this.$t('messages.dashboard.datasets.finished');
                                                break;
                                            default:
                                                break;
                                                
                                        }

                                        if(typeof entry[1] === 'number') {
                                            datasets.push({
                                                label: label,
                                                data: [entry[1]],
                                                backgroundColor: this.getColorFor(entry[0]),
                                                hoverBorderWidth: 0
                                            });
                                            total += entry[1];
                                        }
                                    });
                                    data.datasets = datasets;
                                    this.tab.cards[key].total = total;
                                    this.tab.cards[key].description = total + ' ' + this.$t('messages.dashboard.datasets.enrolls');
                                    this.tab.cards[key].has_data = total !== 0;
                                    this.tab.cards[key].loading = false;
                                    break;
                                default:
                                    break;
                            }
                            //this.has_data = true;
                        }
                    }
                });
                setTimeout(() => {
                    loader.close()
                },800)
            }
        },
        data(){
            return {
                tab: {
                    label: 'Resume',
                    cards: {
                        licenses_average:{
                            loading: true,
                            icon: null,
                            pack_icon: 'fas',
                            label: this.$t('messages.dashboard.licenses'),
                            data: {}
                        },
                        invitations:{
                            loading: true,
                            icon: null,
                            pack_icon: 'fas',
                            label: this.$t('messages.dashboard.invitations_conversion'),
                            data: {
                                primary: {
                                    label: 'conversión',
                                    value: 66
                                },
                                secondary: {
                                    total:{
                                        label: this.$t('messages.dashboard.invitations_sends'),
                                        value: 75,
                                    },
                                    actual:{
                                        label: this.$t('messages.dashboard.invitations_accepted'),
                                        value: 50,
                                    },
                                }
                            }
                        },
                        recurrence:{
                            loading: true,
                            icon: null,
                            pack_icon: 'fas',
                            label: 'Recurrencia',
                            data: {
                                labels: ['-5','-4','-3','-2','-1','Hoy'],
                                datasets: [
                                    {
                                        label: this.$t('messages.dashboard.datasets.recurrence'),
                                        borderColor: '#000000',
                                        data: [27, 20,10,30,15,20],
                                        fill: false,
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                legend: {
                                    display: false,
                                },
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: true,
                                            stepSize: 10,
                                            fontSize: 14,
                                            callback: function(value, index, values) {
                                                return value //(value%2 === 0) ? value : '';
                                            }
                                        }
                                    }],
                                    xAxes: [{
                                        ticks: {
                                            fontSize: 14
                                        }
                                    }]
                                },
                                tooltips: {
                                    titleFontSize: 14,
                                    bodyFontSize: 14
                                }
                            }
                        },
                        projection:{
                            loading: true,
                            icon: null,
                            pack_icon: 'fas',
                            label: this.$t('messages.dashboard.compliance_projection'),
                            data: {
                                labels: ['Hoy',' ',' ',' ',' ',new Date().toLocaleDateString('es-MX', {
                                    year: 'numeric',
                                    month: 'numeric',
                                    day: 'numeric'
                                })],
                                datasets: [
                                    {
                                        label: this.$t('messages.dashboard.compliment'),
                                        borderColor: '#000000',
                                        data: [17,20,45,50,63,80,90],
                                        fill: false,
                                    }
                                ]
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
                                            min: 0,
                                            max: 100,
                                            stepSize: 10,
                                            fontSize: 14,
                                            callback: (value, index, values) => {
                                                return value+'%'
                                            }
                                        }
                                    }],
                                    xAxes: [{
                                        ticks: {
                                            fontSize: 14
                                        }
                                    }]
                                },
                                tooltips: {
                                    titleFontSize: 14,
                                    bodyFontSize: 14
                                }
                            }
                        },
                        licenses_stacked: {
                            loading: true,
                            icon: null,
                            pack_icon: 'fas',
                            label: this.$t('messages.dashboard.datasets.licenses'),
                            data: {},
                            options: {
                                animation: {
                                    duration: 10,
                                },
                                scales: {
                                    xAxes: [{
                                        stacked: true,
                                        gridLines: { display: false },
                                        ticks: {
                                            min: 0,
                                            fontSize: 14,
                                            callback: (value, index, values) => {
                                                if (value === 0 || value === Math.max(values)) {
                                                    return value
                                                }
                                            }
                                        }
                                    }],
                                    yAxes: [{
                                        stacked: true,
                                        gridLines: { display: false },
                                        ticks: {
                                            callback: function(value) { return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); },
                                        },
                                    }],
                                },
                                legend: {
                                    display: true,
                                    position: 'bottom'
                                }
                            }
                        },
                        enrollments:{
                            loading: true,
                            icon: null,
                            pack_icon: 'fas',
                            label: this.$t('messages.dashboard.enrollments'),
                            data: {
                                primary: {
                                    label: this.$t('messages.dashboard.enrollment_rate'),
                                    value: '50%'
                                },
                                secondary: {
                                    total: {
                                        label: this.$t('messages.dashboard.datasets.experiences_assigned'),
                                        value: 100,
                                    },
                                    actual: {
                                        label: this.$t('messages.dashboard.datasets.experiences_started'),
                                        value: 50,
                                    },
                                }
                            }
                        },
                        exploitation:{
                            loading: true,
                            icon: null,
                            pack_icon: 'fas',
                            label: this.$t('messages.dashboard.advantage'),
                            description: null,//this.$t('messages.dashboard.all_experiences'),
                            data: {
                                labels: [''],
                                datasets: [
                                    {
                                        label: this.$t('messages.dashboard.datasets.finished'),
                                        data: [40, 47, 44, 38, 27, 31, 25],
                                        backgroundColor: "#FEB396",
                                        hoverBorderWidth: 0
                                    },
                                    {
                                        label: this.$t('messages.dashboard.datasets.unfinished'),
                                        data: [10, 12, 7, 5, 4, 6, 8],
                                        backgroundColor: "#B6C5DC",
                                        hoverBorderWidth: 0
                                    },
                                    {
                                        label: this.$t('messages.dashboard.datasets.without_starting'),
                                        data: [17, 11, 22, 18, 12, 7, 5],
                                        backgroundColor: "#42B3FF",
                                        hoverBorderWidth: 0
                                    },
                                ]
                            },
                            options: {
                                animation: {
                                    duration: 10,
                                },
                                scales: {
                                    xAxes: [{
                                        stacked: true,
                                        gridLines: { display: false },
                                        ticks: {
                                            min: 0,
                                            fontSize: 14,
                                            callback: (value, index, values) => {
                                                if (value === 0 || value === this.tab.cards.exploitation.total) {
                                                    return value
                                                }
                                            }
                                        }
                                    }],
                                    yAxes: [{
                                        stacked: true,
                                        gridLines: { display: false },
                                        ticks: {
                                            callback: function(value) { return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","); },
                                        },
                                    }],
                                },
                                legend: {
                                    display: true,
                                    position: 'bottom'
                                }
                            }
                        },
                        top_five:{
                            loading: true,
                            icon: null,
                            pack_icon: 'fas',
                            label: this.$t('messages.dashboard.top_five')+' '+this.$t('messages.dashboard.most_popular_experiences'),
                            data: []
                        },
                    },
                },
                raw_data: {},
                date_picker: {
                    value: ["2019-05-27","2019-06-26"],
                    options: {
                        disabledDate(date) {
                            return date.getTime() > new Date().getTime()
                        },
                        shortcuts: [
                            {
                                text: 'Hoy',
                                onClick(picker) {
                                    const end = new Date();
                                    const start = new Date();
                                    start.setTime(start.getTime());
                                    picker.$emit('pick', [start, end]);
                                }
                            },
                            {
                                text: 'Ayer',
                                onClick(picker) {
                                    const end = new Date();
                                    const start = new Date();
                                    start.setTime(start.getTime() - 3600 * 1000 * 24);
                                    picker.$emit('pick', [start, end]);
                                }
                            },
                            {
                                text: 'Últimos 7 días',
                                onClick(picker) {
                                    const end = new Date();
                                    const start = new Date();
                                    start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
                                    picker.$emit('pick', [start, end]);
                                }
                            },
                            {
                                text: 'Últimos 30 días',
                                onClick(picker) {
                                    const end = new Date();
                                    const start = new Date();
                                    start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
                                    picker.$emit('pick', [start, end]);
                                }
                            },
                        ]
                    },
                }
            }
        }
    }
</script>

<style scoped>
    .card {
        min-height: 270px;
    }
</style>

<style>
    .card .canvas-container>div {
        height: 250px;
    }
    .el-date-table td {
        text-align: center !important;
    }
    .el-range-editor.is-active {
        border-color: #FFA81E;
    }
    .el-date-table td.start-date span {
        background-color: #FFA81E;
    }
    .el-date-table td.end-date span {
        background-color: #FFA81E;
    }
    .el-date-table td.in-range div {
        background-color: #fff6e8;
    }
    .el-picker-panel__shortcut:hover {
        background-color: #fff6e8;
        color: #FFA81E;
    }
</style>