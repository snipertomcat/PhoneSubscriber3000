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
                <div class="card">
                    <div class="card-content">
                        <div class="row">
                            <div class="col-10">
                                <div><span class="font-14">{{cards.average.data.primary.label}}</span></div>
                            </div>
                            <div class="col-6">
                                <div>
                                    <span class="has-text-weight-bold font-50">{{cards.average.data.primary.value}}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="no_data row align-items-center justify-content-center" v-if="!cards.average.has_data">
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
                                <b-table :data="cards.list.data" default-sort="enroll_average" icon-pack="fas" striped mobile-cards>
                                    <template slot-scope="props">
                                        <b-table-column field="title" :label="$t('messages.'+props.row.type)" sortable>
                                            {{ props.row.title }}
                                        </b-table-column>

                                        <b-table-column field="provider" label="Proveedor" sortable centered>
                                            {{ props.row.provider }}
                                        </b-table-column>

                                        <b-table-column field="enroll_average" label="Promedio de ingreso" sortable centered>
                                            {{ props.row.visit_average }}
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
            popularity: {
                required: true,
                type: Object
            },
            alternate: {
                required: false,
                default: false
            },
        },
        mounted() {
            let type = route().current().split('.')[1].split('_')[0];
            type = (type === 'journey') ? type+'s' : type;
            this.title = this.$t('messages.dashboard.popularity_of')+' '+this.$t('messages.'+type);
            this.setData();
        },
        methods: {
            setData() {
                let activities = this.popularity;
                Object.keys(activities).forEach(key => {
                    if(this.cards[key]) {
                        let data = this.cards[key].data;
                        let activity = activities[key];

                        if(data) {
                            switch (key) {
                                case 'average':
                                    data.primary.value = Math.floor(activity);
                                    this.cards[key].has_data = !!activity;
                                    break;
                                case 'list':
                                    //data.primary.value = activity.primary;
                                    activity.map(item => {
                                        data.push({
                                            title: item.title,
                                            provider: item.company_author.name,
                                            visit_average: item.enroll_average,
                                            type: item.type
                                        });
                                    });
                                    this.cards[key].has_data = !!activity.length;
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
                icon: 'fa-fire',
                cards: {
                    average: {
                        has_data: false,
                        data: {
                            primary: {
                                label: this.$t('messages.dashboard.average_enrollment')+' '+this.$t('messages.by')+' '+this.$t('messages.dashboard.users'),
                                value: 0
                            },
                        }
                    },
                    list: {
                        has_data: false,
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