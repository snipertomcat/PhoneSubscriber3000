<template>
    <div class="margin-tb-25">
        <div class="row">
            <div class="col-12 col-md-8">
                <extended-average-graph :data="tab.cards.enrollments" url="details.journey_enrollments"></extended-average-graph>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-12 col-md-6">
                <extended-stacked-graph :data="tab.cards.exploitation" v-if="!tab.cards.exploitation.loading" url="'details.progress'"></extended-stacked-graph>
            </div>
            <div class="col-12 col-md-6">
                <extended-average-graph :data="tab.cards.popularity" alternative url="details.journey_popularity"></extended-average-graph>
            </div>
        </div>
    </div>
</template>

<script>

    import ExtendedAverageGraph from '../extendeds/ExtendedAverageGraph';
    import ExtendedStackedGraph from "../extendeds/ExtendedStackedGraph";

    export default {
        name: "AdminJourneysDashboard",
        components: {
            'extended-average-graph': ExtendedAverageGraph,
            'extended-stacked-graph': ExtendedStackedGraph,
        },
        props: {
            journeys: {
                type: Object,
                required: true
            }
        },
        mounted() {
            this.setExperiencesStats();
        },
        methods: {
            setExperiencesStats(data){
                let activities = (data) ? data : this.journeys;
                Object.keys(activities).forEach(key => {
                    if(this.tab.cards[key]) {
                        let data = this.tab.cards[key].data;
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
                                    this.tab.cards[key].loading = false;
                                    this.tab.cards[key].has_data = (activity.total > 0) ? true : false;
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
                                    this.tab.cards[key].loading = false;
                                    this.tab.cards[key].has_data = (activity.list.length > 0) ? true : false;
                                    break;
                                case 'exploitation':
                                    let datasets = [];
                                    let top_five = [];

                                    Object.entries(activity).forEach(entry => {
                                        if(typeof entry[1] === 'number') {
                                            datasets.push({
                                                label: this.getLabelFor(entry[0]),
                                                data: [entry[1]],
                                                backgroundColor: this.getColorFor(entry[0]),
                                                hoverBorderWidth: 0
                                            });
                                        }
                                        else if(typeof entry[1] === 'object') {
                                            entry[1].forEach(journey => {
                                                top_five.push({
                                                    name: journey.title,
                                                    partner: journey.company_author.name,
                                                    datasets: [
                                                        {
                                                            label: this.$t('messages.dashboard.assigned'),
                                                            data: [journey.assigned],
                                                            backgroundColor: "#42B3FF",
                                                            hoverBorderWidth: 0
                                                        },
                                                        {
                                                            label: this.$t('messages.dashboard.started'),
                                                            data: [journey.started],
                                                            backgroundColor: "#B6C5DC",
                                                            hoverBorderWidth: 0
                                                        },
                                                        {
                                                            label: this.$t('messages.dashboard.finished'),
                                                            data: [journey.finished],
                                                            backgroundColor: "#FEB396",
                                                            hoverBorderWidth: 0
                                                        },
                                                    ]
                                                });
                                            })
                                        }
                                    });
                                    data.datasets = datasets;
                                    this.tab.cards[key].top_five.data = top_five;

                                    this.tab.cards[key].loading = false;
                                    this.tab.cards[key].has_data = (data.datasets[0].data.length > 1) ? true : false;
                                    break;
                                default:
                                    break;
                            }
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
                    default:
                        color = '#999999';
                        break;
                }
                return color;
            },
        },
        data(){
            return {
                has_data: false,
                tab: {
                    label: 'Journey',
                    cards: {
                        enrollments: {
                            icon: 'clipboard-list',
                            pack_icon: 'fas',
                            label: this.$t('messages.dashboard.enrollments'),
                            data: {
                                primary: {
                                    label: this.$t('messages.dashboard.enrollment_rate'),
                                    value: null
                                },
                                secondary: {
                                    total:{
                                        label: this.$t('messages.dashboard.journeys_assigned'),
                                        value: 0,
                                    },
                                    actual:{
                                        label: this.$t('messages.dashboard.journeys_started'),
                                        value: 0,
                                    },
                                },
                                top_five: {
                                    label: this.$t('messages.dashboard.top_five')+' '+this.$t('messages.dashboard.enrollments'),
                                    data: []
                                },
                            }
                        },
                        exploitation: {
                            loading: true,
                            icon: 'lightbulb',
                            pack_icon: 'fas',
                            label: this.$t('messages.dashboard.advantage'),
                            description: this.$t('messages.dashboard.all_journeys'),
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
                                        gridLines: {display: false},
                                    }],
                                    yAxes: [{
                                        stacked: true,
                                        gridLines: {display: false},
                                        ticks: {
                                            callback: (value) => {
                                                return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                                            }
                                        },
                                    }],
                                },
                                legend: {
                                    display: true,
                                    position: 'bottom'
                                }
                            },
                            top_five: {
                                label: this.$t('messages.dashboard.top_five')+' '+this.$t('messages.dashboard.advantage'),
                                description: this.$t('messages.dashboard.all_journeys'),
                                    data: [],
                                options: {
                                    animation: {
                                        duration: 10,
                                    },
                                    tooltips: {
                                        enabled: false,
                                    },
                                    scales: {
                                        xAxes: [{
                                            stacked: true,
                                            gridLines: {display: false},
                                        }],
                                        yAxes: [{
                                            stacked: true,
                                            gridLines: {display: false},
                                        }],
                                    },
                                    legend: {
                                        display: false,
                                        position: 'bottom'
                                    }
                                },
                            }
                        },
                        popularity: {
                            icon: 'fire',
                            pack_icon: 'fas',
                            label: this.$t('messages.dashboard.popularity'),
                            data: {
                                primary: {
                                    label: this.$t('messages.dashboard.average_revenue')+' '+this.$t('messages.by')+' '+this.$t('messages.dashboard.users'),
                                    value: 0
                                },
                                top_five: {
                                    label: this.$t('messages.dashboard.top_five')+' '+this.$t('messages.dashboard.enrollments'),
                                    data: []
                                },
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
    .no_data {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255,255,255,0.9);
    }
</style>