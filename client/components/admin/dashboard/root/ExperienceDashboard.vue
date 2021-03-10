<template>
    <div class="margin-tb-25">
        <div class="row">
            <div class="col-12">
                <span class="font-20 has-text-weight-bold">{{$t('messages.dashboard.compliment')}}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-6">
                <line-graph :data="cards.recurrence" v-if="!cards.recurrence.loading"></line-graph>
            </div>
            <div class="col-12 col-md-6">
                <line-graph :data="cards.projection" v-if="!cards.projection.loading"></line-graph>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-12 col-md-8">
                <extended-average-graph :data="cards.enrollments" v-if="!cards.enrollments.loading" url="details.experiences_enrollments"></extended-average-graph>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-12">
                <span class="font-20 has-text-weight-bold">Entusiasmo</span>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-6">
                <extended-average-graph :data="cards.popularity" alternative v-if="!cards.popularity.loading" url="details.experiences_popularity"></extended-average-graph>
            </div>
            <div class="col-12 col-md-6">
                <calification-graph :data="cards.rating" alternative v-if="!cards.rating.loading"></calification-graph>
            </div>
        </div>
    </div>
</template>

<script>
    import LineGraph from '../LineGraph';
    import ExtendedAverageGraph from '../extendeds/ExtendedAverageGraph';
    import CalificationGraph from '../CalificationGraph';

    export default {
        name: "AdminExperienceDashboard",
        components: {
            'line-graph': LineGraph,
            'calification-graph': CalificationGraph,
            'extended-average-graph': ExtendedAverageGraph,
        },
        props: {
            experiences: {
                required: true,
                type: Object
            }
        },
        created() {
            this.setExperiencesStats();
        },
        methods: {
            getRatingIndex(value) {
                let index = null;

                switch (parseInt(value)) {
                    case 1:
                        index = "angry";
                        break;
                    case 2:
                        index = "bad";
                        break;
                    case 3:
                        index = "regular";
                        break;
                    case 4:
                        index = "good";
                        break;
                    case 5:
                        index = "excellent";
                        break;
                }

                return index;
            },
            setExperiencesStats(data){
                let activities = (data) ? data : this.experiences;
                Object.keys(activities).forEach(key => {
                    if(this.cards[key]) {
                        let data = this.cards[key].data;
                        let activity = activities[key];

                        if(data) {
                            switch (key) {
                                case 'enrollments':
                                    data.primary.value = (activity.total > 0) ? (activity.enrolled / activity.total) * 100 : 0;
                                    data.secondary.total.value = activity.total;
                                    data.secondary.actual.value = activity.enrolled;
                                    data.top_five.data = activity.list.map(exp => {
                                        return {
                                            name: exp.title,
                                            author: exp.company_author.name,
                                            total: exp.total,
                                            quantity: exp.quantity
                                        }
                                    });
                                    this.cards[key].loading = false;
                                    this.cards[key].has_data = (activity.total > 0) ? true : false;
                                    break;
                                case 'popularity':
                                    data.primary.value = activity.primary;
                                    data.top_five.data = activity.list.map(item => {
                                        return {
                                            name: item.title,
                                            author: item.author.name,
                                            image: item.cover,
                                            quantity: item.quantity,
                                        }
                                    });
                                    this.cards[key].loading = false;
                                    this.cards[key].has_data = (activity.list.length > 0) ? true : false;
                                    break;
                                case 'rating':
                                    Object.entries(activity).forEach(entry => {
                                        let index = this.getRatingIndex(entry[0]);
                                        let value = entry[1].length;
                                        let min = 0, max = 20;

                                        data[index].data.datasets[0].data = [min,value,max]
                                    });
                                    this.cards[key].loading = false;
                                    this.cards[key].has_data = (activity.length > 0) ? true : false;
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

                                    this.cards[key].loading = false;
                                    this.cards[key].has_data = (activity.length > 0) ? true : false;

                                    break;
                                case 'projection':
                                    this.cards[key].loading = false;
                                    this.cards[key].has_data = (activity.length > 0) ? true : false;
                                    break;
                                default:
                                    break;
                            }
                            //this.has_data = true;
                        }
                    }
                });
            }
        },
        data() {
            return {
                has_data: false,
                cards: {
                    recurrence: {
                        loading: true,
                        icon: 'child',
                        pack_icon: 'fas',
                        label: this.$t('messages.dashboard.recurrence_ratio'),
                        data: {
                            labels: ['-5','-4','-3','-2','-1','Hoy'],
                            datasets: [
                                {
                                    label: this.$t('messages.progress'),
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
                    projection: {
                        loading: true,
                        icon: 'chart-line',
                        pack_icon: 'fas',
                        label: this.$t('messages.dashboard.compliance_projection'),
                        data: {
                            labels: ['1','2','3','4','5','6'],
                            datasets: [
                                {
                                    label: this.$t('messages.progress'),
                                    borderColor: '##8FD2FE',
                                    backgroundColor: '#8FD2FEAA',
                                    data: [17, 20,45,50,63,80],
                                    fill: true,
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
                    enrollments: {
                        loading: true,
                        icon: 'clipboard-list',
                        pack_icon: 'fas',
                        label: this.$t('messages.dashboard.enrollments'),
                        data: {
                            primary: {
                                label: this.$t('messages.dashboard.enrollment_rate'),
                                value: 0
                            },
                            secondary: {
                                total: {
                                    label: this.$t('messages.dashboard.courses_assigned'),
                                    value: 0,
                                },
                                actual: {
                                    label: this.$t('messages.dashboard.courses_started'),
                                    value: 0,
                                },
                            },
                            top_five: {
                                label: this.$t('messages.dashboard.top_five')+' '+this.$t('messages.dashboard.enrollments'),
                                data: []
                            },
                        }
                    },
                    popularity: {
                        loading: true,
                        icon: 'fire',
                        pack_icon: 'fas',
                        label: this.$t('messages.dashboard.popularity'),
                        data: {
                            primary: {
                                label: this.$t('messages.dashboard.average_revenue')+' '+this.$t('messages.by')+' '+this.$t('messages.dashboard.users'),
                                value: '4.5'
                            },
                            top_five: {
                                label: this.$t('messages.dashboard.top_five')+' '+this.$t('messages.dashboard.enrollments'),
                                data: []
                            },
                        }
                    },
                    rating: {
                        loading: true,
                        icon: 'smile',
                        pack_icon: 'fas',
                        label: this.$t('messages.dashboard.qualification'),
                        data: {
                            excellent:{
                                icon: 'grin-hearts',
                                data: {
                                    labels: ['',''],
                                    datasets: [
                                        {
                                            label: this.$t('messages.dashboard.excellent'),
                                            data: [],
                                            backgroundColor: "#007700",
                                        },
                                    ]
                                },
                            },
                            good:{
                                icon: 'smile',
                                data: {
                                    labels: ['',''],
                                    datasets: [
                                        {
                                            label: this.$t('messages.dashboard.good'),
                                            data: [],
                                            backgroundColor: "#00CC00",
                                        },
                                    ]
                                },
                            },
                            regular:{
                                icon: 'meh',
                                data: {
                                    labels: ['',''],
                                    datasets: [
                                        {
                                            label: this.$t('messages.dashboard.regular'),
                                            data: [],
                                            backgroundColor: "#8FD2FE",
                                        },
                                    ]
                                },
                            },
                            bad:{
                                icon: 'frown-open',
                                data: {
                                    labels: ['',''],
                                    datasets: [
                                        {
                                            label: this.$t('messages.dashboard.bad'),
                                            data: [],
                                            backgroundColor: "#ffb300",
                                        },
                                    ]
                                },
                            },
                            angry:{
                                icon: 'angry',
                                data: {
                                    labels: ['',''],
                                    datasets: [
                                        {
                                            label: this.$t('messages.dashboard.very_bad'),
                                            data: [],
                                            backgroundColor: "#FF0000",
                                        },
                                    ]
                                },
                            },
                        },
                        options: {
                            animation: {
                                duration: 10,
                            },
                            scales: {
                                xAxes: [{
                                    gridLines: { display: false },
                                    display: true,
                                    ticks: {
                                        beginAtZero: true,
                                    }
                                }],
                                yAxes: [{
                                    gridLines: { display: false },
                                    display: false
                                }],
                            },
                            legend: {
                                display: false,
                                position: 'bottom',
                            },
                            tooltips: {
                                enabled: true,
                            }
                        }
                    },
                }
            }
        }
    }
</script>

<style scoped>
    .no_data {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255,255,255,0.9);
    }
</style>