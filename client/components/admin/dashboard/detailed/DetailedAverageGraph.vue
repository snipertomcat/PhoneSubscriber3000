<template>
    <div class="">
        <div class="row">
            <div class="col-12 font-20 has-text-weight-bold">
                <span><i class="fa font-25" :class="icon"></i></span>
                <span class="hast-text-weight-bold">{{title}}</span>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-5">
                <average-graph :data="cards.enrollments" :show-all="false" :show-icon="false" :alternative="alternate"></average-graph>
            </div>
            <div class="col-7">
                <div class="card">
                    <div class="card-content">
                        <div class="row">
                        <div class="col-12">
                                <span class="is-link">
                                    <b-icon pack="fas" icon="sort-amount-down" type="link"></b-icon>
                                </span>
                        </div>
                    </div>
                        <div class="row">
                            <div class="col-12">
                                <b-table :data="cards.list.data" default-sort="title" icon-pack="fas" striped mobile-cards>
                                    <template slot-scope="props">
                                        <b-table-column field="journey" :label="$t('messages.'+props.row.type)" sortable>
                                            {{ props.row.title }}
                                        </b-table-column>

                                        <b-table-column field="provider" label="Proveedor" sortable>
                                            {{ props.row.provider }}
                                        </b-table-column>

                                        <b-table-column field="enrollments" label="Enrolamiento" sortable>
                                            {{ props.row.enrollment }}
                                        </b-table-column>

                                        <b-table-column field="assignate" label="Asignados" sortable>
                                            {{ props.row.assigned }}
                                        </b-table-column>

                                        <b-table-column field="started" label="Iniciados" sortable>
                                            {{ props.row.started }}
                                        </b-table-column>
                                    </template>
                                </b-table>
                            </div>
                        </div>
                    </div>
                    <div class="no_data row align-items-center justify-content-center" v-if="!cards.list.has_data">
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
            </div>
        </div>
    </div>
</template>

<script>
    import AverageGraph from '../AverageGraph';

    export default {
        name: "DetailedAverageGraph",
        components: {
            'average-graph': AverageGraph,
        },
        props: {
            enrollments: {
                required: true,
                type: Object
            },
            alternate: {
                required: false,
                default: false
            },
        },
        mounted() {
            this.setData();
        },
        methods: {
            setData(data) {
                let activities = (data) ? data : this.enrollments;
                Object.keys(activities).forEach(key => {
                    if(this.cards[key]) {
                        let data = this.cards[key].data;
                        let activity = activities[key];

                        if(data) {
                            switch (key) {
                                case 'enrollments':

                                    this.title = this.$t('messages.dashboard.enrollment_of')+' '+this.$t('messages.dashboard.'+activity.label);
                                    data.primary.value = (activity.total > 0) ? Math.floor((activity.enrolled / activity.total) * 100) : 0;
                                    data.secondary.total.label = this.$t('messages.dashboard.' + activity.label + '_assigned');
                                    data.secondary.total.value = activity.total;
                                    data.secondary.actual.label = this.$t('messages.dashboard.' + activity.label + '_started');
                                    data.secondary.actual.value = activity.enrolled;
                                    this.cards[key].has_data = (activity.total > 0) ? true : false;
                                    break;
                                case 'list':
                                    //data.primary.value = activity.primary;
                                    activity.map(item => {
                                        data.push({
                                            type: item.type,
                                            title: item.title,
                                            provider: item.company_author.name,
                                            assigned: item.assigned,
                                            enrollment: item.enrolled,
                                            started: item.started,
                                        });
                                    });
                                    //this.tab.cards[key].loading = false;
                                    this.cards[key].has_data = (activity.length > 0) ? true : false;
                                    break;
                                default:
                                    break;
                            }
                        }
                    }
                });
            }
        },
        data() {
            return {
                title: '',
                icon: 'fa-clipboard',
                cards: {
                    enrollments: {
                        has_data: false,
                        data: {
                            primary: {
                                label: this.$t('messages.dashboard.average_enrollment'),
                                value: 0
                            },
                            secondary: {
                                total: {
                                    label: '',
                                    value: 0,
                                },
                                actual:{
                                    label: '',
                                    value: 0,
                                },
                            },
                        }
                    },
                    list: {
                        has_data: false,
                        label: 'Top 5 enrolamientos',
                        data: []
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
        left: 15px;
        width: 100%;
        height: 100%;
        background-color: rgba(255,255,255,0.9);
    }

</style>