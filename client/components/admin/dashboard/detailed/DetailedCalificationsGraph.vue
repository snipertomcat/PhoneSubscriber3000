<template>
    <div class="">
        <div class="row">
            <div class="col-12 font-20 has-text-weight-bold">
                <span><i class="fa fa-smile font-25"></i></span>
                <span class="hast-text-weight-bold">{{$t('messages.dashboard.qualification_of_experiences')}}</span>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-5">
                <calification-graph :data="cards.rating" :show-all="false" :show-icon="false" v-if="!cards.rating.loading"></calification-graph>
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
                                        <b-table-column field="title" label="Experiencia" sortable>
                                            {{ props.row.title }}
                                        </b-table-column>

                                        <b-table-column field="provider" label="Proveedor" sortable>
                                            {{ props.row.provider }}
                                        </b-table-column>

                                        <b-table-column field="qualification" label="Calificación" sortable>
                                            <b-icon icon-pack="fas" pack="fas" :icon="getIconRating(props.row.qualification)"></b-icon>
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
    import CalificationGraph from '../CalificationGraph';

    export default {
        name: "DetailedCalificationGraph",
        components: {
            'calification-graph': CalificationGraph,
        },
        props: {
            califications: {
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
            getRatingIndex(value) {
                let index = null;

                switch (parseInt(value)) {
                    case 4:
                        index = "angry";
                        break;
                    case 3:
                        index = "bad";
                        break;
                    case 2:
                        index = "regular";
                        break;
                    case 1:
                        index = "excellent";
                        break;
                }

                return index;
            },
            getIconRating(rating) {
                return this.cards.rating.data[rating].icon;
            },
            setData(data) {
                let activities = (data) ? data : this.califications;
                Object.keys(activities).forEach(key => {
                    if(this.cards[key]) {
                        let data = this.cards[key].data;
                        let activity = activities[key];

                        if(data) {
                            switch (key) {
                                case 'rating':
                                    Object.entries(activity).forEach(entry => {
                                        let index = this.getRatingIndex(entry[0]);
                                        let value = entry[1].length;
                                        let min = 0, max = 20;

                                        data[index].data.datasets[0].data = [min,value,max]

                                        if (!!entry[1].length) {
                                            this.cards[key].has_data = true;
                                        }
                                    });
                                    this.cards[key].loading = false;
                                    break;
                                case 'list':
                                    //data.primary.value = activity.primary;
                                    activity.map(item => {
                                        data.push({
                                            title: item.title,
                                            provider: item.company_author.name,
                                            qualification: this.getRatingIndex(item.users_rating[2].rating)
                                        });
                                    });
                                    this.cards[key].loading = false;
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
                title: 'Calificación',
                icon: 'smile',
                cards: {
                    list: {
                        has_data: false,
                        data: []
                    },
                    rating: {
                        has_data: false,
                        loading: true,
                        pack_icon: 'fas',
                        data: {
                            excellent:{
                                icon: 'grin-hearts',
                                data: {
                                    labels: ['',''],
                                    datasets: [
                                        {
                                            label: 'Excelente',
                                            data: [0,0,20],
                                            backgroundColor: "#007700",
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
                                            label: 'Regular',
                                            data: [0,0,20],
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
                                            label: 'Malo',
                                            data: [0,0,20],
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
                                            label: 'Muy malo',
                                            data: [0,0,20],
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
        left: 15px;
        width: 100%;
        height: 100%;
        background-color: rgba(255,255,255,0.9);
    }

</style>