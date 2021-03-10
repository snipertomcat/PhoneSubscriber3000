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
                <div class="card" v-if="!cards.licenses_stacked.loading">
                    <div class="card-content">
                        <licenses-stacked :data="cards.licenses_stacked" :show-all="false" :show-icon="false" :height="125"></licenses-stacked>
                    </div>
                </div>
            </div>
            <div class="col-7">
                <div class="card">
                    <div class="card-content">
                        <div class="row justify-content-end">
                            <div class="col-auto">
                                <a href="#">
                                    <span class="is-link is-uppercase has-text-weight-bold">ver todo</span>
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <b-table :data="cards.licenses_experiences.data"
                                         default-sort="title"
                                         icon-pack="fas"
                                         striped
                                         mobile-cards>
                                    <template slot-scope="props">
                                        <b-table-column field="title" :label="$t('messages.experience')" sortable>
                                            {{ props.row.title }}
                                        </b-table-column>

                                        <b-table-column field="not_started" label="Sin comenzar" sortable>
                                            {{ props.row.not_started }}
                                        </b-table-column>

                                        <b-table-column field="started" label="Iniciadas" sortable>
                                            {{ props.row.started }}
                                        </b-table-column>

                                        <b-table-column field="finished" label="Terminadas" sortable>
                                            {{ props.row.finished }}
                                        </b-table-column>
                                    </template>
                                </b-table>
                            </div>
                        </div>
                    </div>
                    <div class="no_data row align-items-center justify-content-center" v-if="!(cards.licenses_experiences.data.length > 0)">
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
    import LicenseStacked from '../admin/summary-components/LicensesStacked';

    export default {
        name: "DetailedStackedGraph",
        components: {
            'licenses-stacked': LicenseStacked,
        },
        props: {
            licenses: {
                type: Object,
                required: true
            }
        },
        mounted() {
            this.setData();
        },
        methods: {
            getLabelFor(value) {
                let label;
                switch (value) {
                    case 'buyed':
                        label = 'Compradas';
                        break;
                    case 'available':
                        label = 'Disponibles';
                        break;
                    case 'assigned':
                        label = 'Asignadas';
                        break;
                    case 'pending':
                        label = 'Pendientes de activar';
                        break;
                    case 'not-started':
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
            setData() {
                let activities = this.licenses;
                Object.keys(activities).forEach(key => {
                    if(this.cards[key]) {
                        let data = this.cards[key].data;
                        let activity = activities[key];

                        if(data) {
                            switch (key) {
                                case 'licenses_experiences':
                                    this.cards[key].data = activity;
                                    this.cards[key].has_data = !!activity.length;
                                    this.cards[key].loading = false;

                                    break;
                                case 'licenses_stacked':
                                    data.buyed = activity.licenses_buyed;
                                    data.available = activity.licenses_available;
                                    data.assigned = activity.licenses_assigned;
                                    data.pending = activity.licenses_apending;

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
                                            this.cards[key].has_data = !!entry[1];
                                        }
                                    });
                                    data.datasets = licenses_dataset;

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
                    licenses_experiences: {
                        data: [],
                        has_data: false,
                        loading: true
                    },
                    licenses_stacked: {
                        loading: true,
                        has_data: false,
                        icon: 'id-badge',
                        pack_icon: 'fas',
                        label: 'Licenciamiento', //this.$t('messages.dashboard.licenses'),
                        data: {},
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