<template>
    <div class="margin-tb-25">
        <div class="row">
            <div class="col-12">
                <span class="font-20 has-text-weight-bold">{{$t('messages.dashboard.compliment')}}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-lg-4">
                <average-graph :data="tab.cards.invitations" v-if="!tab.cards.invitations.loading"></average-graph>
            </div>
            <div class="col-12 col-lg-4">
                <line-graph :data="tab.cards.recurrence" v-if="!tab.cards.recurrence.loading"></line-graph>
            </div>
            <div class="col-12 col-lg-4">
                <average-graph :data="tab.cards.enrollments" v-if="!tab.cards.enrollments.loading"></average-graph>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="row">
                    <div class="col-12">
                        <line-graph :data="tab.cards.projection" v-if="!tab.cards.projection.loading"></line-graph>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-12">
                        <top-five :data="tab.cards.top_five" v-if="!tab.cards.top_five.loading"></top-five>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-12">
                        <stacked-graph :data="tab.cards.exploitation" v-if="!tab.cards.exploitation.loading"></stacked-graph>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-12">
                        <stacked-graph :data="tab.cards.users" v-if="!tab.cards.users.loading"></stacked-graph>
                    </div>
                </div>
                <br>
            </div>
            <div class="col-12 col-md-6">
                <div class="row">
                    <div class="col-12">
                        <stacked-graph :data="tab.cards.exploitation" v-if="!tab.cards.exploitation.loading"></stacked-graph>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-12">
                        <top-five-companies :data="tab.cards.companies" v-if="!tab.cards.companies.loading"></top-five-companies>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-12">
                        <top-five-companies :data="tab.cards.last_five_users" v-if="!tab.cards.last_five_users.loading"></top-five-companies>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import StackedBar from '../StackedGraph';
    import LineGraph from '../LineGraph';
    import AverageGraph from '../AverageGraph';
    import TopFive from '../TopFive';
    import TopFiveCompanies from '../TopFiveCompanies';

    export default {
        name: "AdminDashboard",
        components: {
            'line-graph': LineGraph,
            'average-graph': AverageGraph,
            'stacked-graph': StackedBar,
            'top-five': TopFive,
            'top-five-companies': TopFiveCompanies,
        },
        props: {
            summary: {
                required: true,
                type: Object
            }
        },
        mounted() {
            this.setSummaryStats();
        },
        methods: {
            setSummaryStats(data){
                let activities = (data) ? data : this.summary;
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
                                    this.tab.cards[key].loading = false;
                                    this.tab.cards[key].has_data = (activity.sends > 0) ? true : false;
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
                                    this.tab.cards[key].has_data = (datas.length > 0) ? true : false;

                                    break;
                                case 'enrollments':
                                    data.primary.value = (activity.total > 0) ? Math.floor((activity.enrolled / activity.total) * 100) : 0;
                                    data.secondary.total.value = activity.total;
                                    data.secondary.actual.value = activity.enrolled;
                                    this.tab.cards.top_five.data = activity.list.map(i => {
                                        return {
                                            name: i.title,
                                            author: i.company_author.name,
                                            image: i.full_path_cover,
                                            quantity: i.quantity
                                        }
                                    });
                                    this.tab.cards[key].loading = false;
                                    this.tab.cards[key].has_data = (activity.total > 0) ? true : false;
                                    this.tab.cards.top_five.loading = false;
                                    this.tab.cards.top_five.has_data = (activity.list.length > 0) ? true : false;
                                    break;
                                case 'projection':
                                    this.tab.cards[key].has_data = false;
                                    this.tab.cards[key].loading = false;
                                    break;
                                case 'exploitation':
                                    let datasets = [];
                                    let top_five = [];
                                    let has_data = false;

                                    Object.entries(activity).forEach(entry => {
                                        if(typeof entry[1] === 'number') {
                                            datasets.push({
                                                label: this.getLabelFor(entry[0]),
                                                data: [entry[1]],
                                                backgroundColor: this.getColorFor(entry[0]),
                                                hoverBorderWidth: 0
                                            });
                                        }
                                    });
                                    data.datasets = datasets;
                                    this.tab.cards[key].has_data = (data.datasets[0].data.length > 1) ? true : false;
                                    this.tab.cards[key].loading = false;
                                    break;
                                case 'companies':
                                    Object.values(activity).forEach(company => {
                                        let item = {
                                            name: company.name,
                                            image: company.full_path_logo,
                                            value: company.total_users,
                                        };
                                        data.push(item);
                                    });
                                    this.tab.cards[key].has_data = (data.length > 0);
                                    this.tab.cards[key].loading = false;
                                    break;
                                case 'last_five_users':
                                    Object.values(activity).forEach(user => {
                                        let item = {
                                            name: user.full_name,
                                            image: user.full_path_avatar,
                                            value: this.$t('messages.' + user.registration_method),
                                        };
                                        data.push(item);
                                    });
                                    this.tab.cards[key].has_data = (data.length > 0);
                                    this.tab.cards[key].loading = false;
                                    break;
                                case 'users':
                                    /*
                                    {
                                        label: this.getLabelFor(entry[0]),
                                        data: [entry[1]],
                                        backgroundColor: this.getColorFor(entry[0]),
                                        hoverBorderWidth: 0
                                    }
                                    */
                                    let eos = this.tab.cards.users.data.datasets;
                                    let i = 0;
                                    Object.entries(activity).forEach(item => {
                                        if(item[0] !== 'total') {
                                            let key = item[0];
                                            let data = item[1];

                                            eos.push({
                                                label: this.$t('messages.' + key),
                                                data: [data],
                                                backgroundColor: this.getColorFor(i),
                                                hoverBorderWidth: 0
                                            });
                                        }
                                        i++;
                                    });

                                    this.tab.cards[key].has_data = (eos.length > 0);
                                    this.tab.cards[key].loading = false;
                                    break;
                                default:
                                    break;
                            }
                            //this.has_data = true;
                        }
                    }
                });
            },
            getLabelFor(value) {
                return this.$t('messages.dashboard.'+value);
            },
            getColorFor(value) {
                let color;
                switch (value) {
                    case 'assigned':
                        color = '#42B3FF';
                        break;
                    case 'started':
                        color = '#B6C5DC';
                        break;
                    case 'finished':
                        color = '#FEB396';
                        break;
                    case 0:
                        color = '#42B3FF';
                        break;
                    case 1:
                        color = '#B6C5DC';
                        break;
                    case 2:
                        color = '#FEB396';
                        break;
                    default:
                        color = '#999999';
                        break;
                }
                return color;
            },
        },
        data(){
            return {
                tab: {
                    label: 'Resume',
                    cards: {
                        invitations:{
                            loading: true,
                            icon: 'envelope',
                            pack_icon: 'fas',
                            label: this.$t('messages.dashboard.invitations_conversion'),
                            data: {
                                primary: {
                                    label: this.$t('messages.dashboard.conversion_rate'),
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
                            icon: 'child',
                            pack_icon: 'fas',
                            label: this.$t('messages.dashboard.recurrence_ratio'),
                            data: {
                                labels: ['-5','-4','-3','-2','-1','Hoy'],
                                datasets: [
                                    {
                                        label: 'Recurrencia',
                                        borderColor: '#000000',
                                        data: [27, 20,10,30,15,20],
                                        fill: false,
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: true,
                                legend: {
                                    display: false
                                },
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: true,
                                            callback: function(value, index, values) {
                                                return (value%2 === 0) ? value : '';
                                            }
                                        }
                                    }]
                                }
                            }
                        },
                        enrollments:{
                            loading: true,
                            icon: 'clipboard-list',
                            pack_icon: 'fas',
                            label: this.$t('messages.dashboard.enrollments'),
                            data: {
                                primary: {
                                    label: this.$t('messages.dashboard.enrollment_rate'),
                                    value: '50%'
                                },
                                secondary: {
                                    total: {
                                        label: this.$t('messages.dashboard.courses_assigned'),
                                        value: 100,
                                    },
                                    actual: {
                                        label: this.$t('messages.dashboard.courses_started'),
                                        value: 50,
                                    },
                                }
                            }
                        },
                        projection:{
                            loading: true,
                            icon: 'chart-line',
                            pack_icon: 'fas',
                            label: this.$t('messages.dashboard.compliance_projection'),
                            data: {
                                labels: ['1','2','3','4','5','6'],
                                datasets: [
                                    {
                                        label: this.$t('messages.dashboard.compliment'),
                                        borderColor: '#000000',
                                        data: [17, 20,45,50,63,80],
                                        fill: false,
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: true,
                                legend: {
                                    display: false
                                },
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: true,
                                            callback: function(value, index, values) {
                                                return value+' %';
                                            }
                                        }
                                    }]
                                }
                            }
                        },
                        exploitation:{
                            loading: true,
                            icon: 'lightbulb',
                            pack_icon: 'fas',
                            label: this.$t('messages.dashboard.advantage'),
                            description: this.$t('messages.dashboard.all_experiences'),
                            data: {
                                labels: [''],
                                datasets: [
                                    {
                                        label: this.$t('messages.dashboard.finished'),
                                        data: [40, 47, 44, 38, 27, 31, 25],
                                        backgroundColor: "#FEB396",
                                        hoverBorderWidth: 0
                                    },
                                    {
                                        label: this.$t('messages.dashboard.started'),
                                        data: [10, 12, 7, 5, 4, 6, 8],
                                        backgroundColor: "#B6C5DC",
                                        hoverBorderWidth: 0
                                    },
                                    {
                                        label: this.$t('messages.dashboard.assigned'),
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
                            icon: 'smile',
                            pack_icon: 'fas',
                            label: this.$t('messages.dashboard.top_five')+' '+this.$t('messages.dashboard.most_popular_experiences'),
                            data: []
                        },
                        companies:{
                            loading: true,
                            icon: 'building',
                            pack_icon: 'fas',
                            columns: [{label:'Compañias',custom_class:"col-8",value:"name"},{label:'Total de usuarios',custom_class:"col-4"}],
                            label: this.$t('messages.dashboard.top_five')+' '+this.$t('messages.dashboard.companies'),
                            data: []
                        },
                        last_five_users:{
                            loading: true,
                            icon: 'users',
                            pack_icon: 'fas',
                            columns: [{label:'Usuario',custom_class:"col-8"},{label:'Método de registro',custom_class:"col-4"}],
                            label: this.$t('messages.dashboard.top_five')+' '+this.$t('messages.users'),
                            data: []
                        },
                        users:{
                            loading: true,
                            icon: 'users',
                            pack_icon: 'fas',
                            label: this.$t('messages.dashboard.advantage'),
                            description: this.$t('messages.dashboard.all_experiences'),
                            data: {
                                labels: [''],
                                datasets: []
                            },
                            options: {
                                animation: {
                                    duration: 10,
                                },
                                scales: {
                                    xAxes: [{
                                        stacked: true,
                                        gridLines: { display: false },
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
                    }
                },
            }
        }
    }
</script>

<style scoped>
    .card {
        min-height: 240px;
    }
</style>