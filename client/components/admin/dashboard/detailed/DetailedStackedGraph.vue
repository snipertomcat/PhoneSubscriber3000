<template>
    <div class="">
        <div class="row">
            <div class="col-12 font-20 has-text-weight-bold">
                <span><i class="fa fa-lightbulb font-25"></i></span>
                <span class="hast-text-weight-bold">Aprovechamiento de todos las experiencias</span>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-5">
                <div class="card" v-if="!cards.exploitation_stacked.loading">
                    <div class="card-content">
                        <stacked-graph :data="cards.exploitation_stacked" :show-all="false" :show-icon="false" :height="100"></stacked-graph>
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
                                <b-table :data="cards.exploitation.data" default-sort="title" icon-pack="fas" striped mobile-cards>
                                    <template slot-scope="props">
                                        <b-table-column field="title" :label="$t('messages.experience')" sortable>
                                            {{ props.row.title }}
                                        </b-table-column>

                                        <b-table-column field="provider" label="Proveedor" sortable>
                                            {{ props.row.provider }}
                                        </b-table-column>

                                        <b-table-column field="assigned" label="Sin comenzar" sortable centered>
                                            {{ props.row.assigned }}
                                        </b-table-column>

                                        <b-table-column field="started" label="En curso" sortable centered>
                                            {{ props.row.started }}
                                        </b-table-column>

                                        <b-table-column field="finished" label="Concluidas" sortable centered>
                                            {{ props.row.finished }}
                                        </b-table-column>
                                    </template>
                                </b-table>
                            </div>
                        </div>
                    </div>
                    <div class="no_data row align-items-center justify-content-center" v-if="!cards.exploitation.has_data">
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
    import StackedGraph from '../StackedGraph';

    export default {
        name: "DetailedStackedGraph",
        components: {
            'stacked-graph': StackedGraph,
        },
        props: {
            advantage: {
                required: true,
                type: Object
            }
        },
        mounted() {
            this.setData();
        },
        methods: {
            getLabelFor(value) {
                let label;
                switch (value) {
                    case 'assigned':
                        label = 'Sin comenzar';
                        break;
                    case 'started':
                        label = 'En curso';
                        break;
                    case 'finished':
                        label = 'Concluidas';
                        break;
                    default:
                        label = 'Undefined';
                        break;
                }
                return label;
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
            setData(data) {
                let activities = (data) ? data : this.advantage;
                Object.keys(activities).forEach(key => {
                    if(this.cards[key]) {
                        let data = this.cards[key].data;
                        let activity = activities[key];

                        if(data) {
                            switch (key) {
                                case 'exploitation':
                                    activity.map(item => {
                                        data.push({
                                            title: item.title,
                                            provider: item.company_author.name,
                                            assigned: item.not_started,
                                            started: item.started,
                                            finished: item.finished
                                        });
                                    });
                                    this.cards[key].has_data = !!data.length;
                                    break;
                                case 'exploitation_stacked':
                                    let datasets = [];
                                    let has_data = 0;
                                    let total = 0;

                                    Object.entries(activity).forEach(entry => {
                                        switch (entry[0]) {
                                            case 'total':
                                                entry[0] = 'assigned';
                                                break;
                                            case 'enrolled':
                                                entry[0] = 'started';
                                                break;
                                            default:
                                                break;

                                        }
                                        if(typeof entry[1] === 'number') {
                                            datasets.push({
                                                label: this.getLabelFor(entry[0]),
                                                data: [entry[1]],
                                                backgroundColor: this.getColorFor(entry[0]),
                                                hoverBorderWidth: 0
                                            });
                                            total += entry[1];
                                        }
                                        has_data += entry[1];
                                    });
                                    data.datasets = datasets;
                                    this.cards[key].description = total + ' Enrolamientos';
                                    this.cards[key].has_data = !!has_data;
                                    this.cards[key].loading = false;
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
                cards: {
                    exploitation:{
                        has_data: false,
                        loading: true,
                        data: [],
                    },
                    exploitation_stacked:{
                        has_data: false,
                        loading: true,
                        icon: 'lightbulb',
                        pack_icon: 'fas',
                        label: 'Aprovechamiento',
                        description: 'Todas las experiencias',
                        data: {
                            labels: [''],
                            datasets: [
                                {
                                    label: 'Terminadas',
                                    data: [40, 47, 44, 38, 27, 31, 25],
                                    backgroundColor: "#FEB396",
                                    hoverBorderWidth: 0
                                },
                                {
                                    label: 'Empezadas',
                                    data: [10, 12, 7, 5, 4, 6, 8],
                                    backgroundColor: "#B6C5DC",
                                    hoverBorderWidth: 0
                                },
                                {
                                    label: 'Asignadas',
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