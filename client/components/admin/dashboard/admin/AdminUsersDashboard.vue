<template>
    <div class="margin-tb-25">
        <div class="row">
            <div class="col-3">
                <div class="row">
                    <div class="col-12">
                        <h1>{{ $t('messages.dashboard.users') }}</h1>
                    </div>
                </div>
            </div>
            <div class="col-9">
                <div class="row">
                    <div class="col-12">
                        <h1>{{ $t('messages.activity') }}</h1>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row align-items-end">
            <div class="col-12 col-md-3">
                <div class="row" v-if="user.selected">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-container user-card">
                                <div class="row align-items-center justify-content-center">
                                    <div class="col-6 col-lg-3">
                                        <img class="circle" width="50" height="50"
                                             :src="user.selected.full_path_avatar"/>
                                    </div>
                                    <div class="col-12 col-lg-9 font-14">
                                        <div>
                                            <span>{{user.selected.name}}</span>
                                        </div>
                                        <div v-if="user.area">
                                            <span>{{user.area}}</span>
                                        </div>
                                        <div>
                                            <span>{{user.selected.roles[0].name}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-7">
                <div class="row justify-content-between">
                    <div class="col-12 col-md-4">
                        <b-field label="Filtrar por viaje">
                            <b-autocomplete
                                    v-model="filters.journey.title"
                                    :data="data_filters.journeys"
                                    :disabled="!data_filters.journeys.length > 0"
                                    field="title"
                                    open-on-focus
                                    @select="option => {setFilter(option,'journey')}">
                            </b-autocomplete>
                        </b-field>
                    </div>
                    <div class="col-12 col-md-4">
                        <b-field label="Filtrar por experiencia">
                            <b-autocomplete
                                    v-model="filters.experience.title"
                                    :data="data_filters.experiences"
                                    :disabled="!data_filters.experiences.length > 0"
                                    field="title"
                                    open-on-focus
                                    @select="option => {setFilter(option,'experience')}">
                            </b-autocomplete>
                        </b-field>
                    </div>
                    <div class="col-12 col-md-4">
                        <b-field label="Filtrar por reto">
                            <b-autocomplete
                                    v-model="filters.session.title"
                                    :data="data_filters.sessions"
                                    :disabled="!data_filters.sessions.length > 0"
                                    field="title"
                                    open-on-focus
                                    @select="option => {setFilter(option,'session')}">
                            </b-autocomplete>
                        </b-field>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-2">
                <b-field>
                    <button class="button is-primary w-100" @click="getFilteredData">
                        <b-icon icon="filter" pack="fas" type="is-info"></b-icon>
                        <span>Filtrar</span>
                    </button>
                </b-field>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-3">
                <b-field label="">
                    <b-autocomplete
                            v-model="user.name"
                            :data="user.list"
                            placeholder="Busca un usuario"
                            field="name"
                            :loading="user.is_fetching"
                            @keyup.native="getAsyncData"
                            @select="getActivityData">
                    </b-autocomplete>
                </b-field>
            </div>
            <div class="col-9">
                <div class="card">
                    <div class="card-content">
                        <span v-if="user.selected">
                            <b-table :data="data_list"
                                     :loading="user.is_loading"
                                     icon-pack="fa"
                                     per-page="10"
                                     paginated
                                     pagination-simple
                                     mobile-cards
                                     v-if="this.user.has_data">
                                <template slot-scope="props">
                                    <b-table-column field="verb" :label="$t('messages.activity')">
                                        <div class="tag action"
                                             :class="translateVerb(props.row.verb, props.row.raw_score).color"
                                             v-if="translateVerb(props.row.verb)">
                                            <span>{{ translateEvent(props.row.event_type).label }} : {{ translateVerb(props.row.verb).label }}</span>
                                        </div>
                                        <div class="tag grey action" v-else>
                                            <span>{{ translateEvent(props.row.event_type).label }} : {{ props.row.verb }}</span>
                                        </div>
                                    </b-table-column>

                                    <b-table-column field="experience" :label="$t('messages.experience')">
                                        {{ props.row.experience }}
                                    </b-table-column>

                                    <b-table-column field="session" :label="$t('messages.challenge')">
                                        {{ props.row.session }}
                                    </b-table-column>

                                    <b-table-column field="duration" :label="$t('messages.duration')">
                                        {{ props.row.duration.replace('PT','') }}
                                    </b-table-column>

                                    <b-table-column field="created_at" :label="$t('messages.date')" centered>
                                        <span class="tag is-success">
                                            {{ new Date(props.row.created_at).toLocaleDateString() }}
                                        </span>
                                    </b-table-column>
                                </template>
                            </b-table>
                        <span class="is-italic" v-if="!this.user.has_data">El usuario no cuenta con registros de actividad</span>
                        </span>
                        <span v-else>
                            <b-table :data="[]"></b-table>
                            <span class="is-italic">Elige un usuario para ver su actividad</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import debounce from 'lodash/debounce'

    export default {
        name: "AdminUsersDashboard",
        methods: {
            getActivityData(selection, with_filters = false) {
                this.user.is_loading = true;
                this.user.selected = selection;

                if(selection) {
                    if(selection.position.length) {
                        this.user.area = selection.position[0].area.name
                    }
                    axios({
                        url: route('profile.user-activity'),
                        method: 'POST',
                        params: {
                            user_id: this.user.selected.id,
                            filters: (with_filters) ? this.filters : null
                        }
                    }).then(response => {
                        this.user.has_data = !!response.data.activity.length;
                        this.data_list = response.data.activity;
                        this.data_filters = response.data.filters;
                        this.user.is_loading = false;
                    }).catch(error => {
                        throw error;
                    }).finally(() => {

                    });
                }
            },
            getAsyncData: debounce(function () {
                if (!this.user.name.length) {
                    this.user.list = [];
                    return
                }
                this.user.is_fetching = true;
                this.user.list = [];
                axios({
                    url: route('users.index'),
                    method: 'GET',
                    params: {search: this.user.name}
                }).then(response => {
                    this.user.list = [];
                    response.data.data.forEach(item => {
                        this.user.list.push(item);
                    });
                }).catch(error => {
                    throw error;
                }).finally(() => {
                    this.user.is_fetching = false
                });
            }),
            getFilteredData() {
                let filters = this.filters;
                if(filters.journey.title || filters.experience.title || filters.session.title) {
                    this.getActivityData(this.user.selected, true);
                } else {
                    this.getActivityData(this.user.selected, false);
                }
            },
            setFilter(option_selected,target) {
                if(option_selected){
                    if (option_selected.type === 'journey') {
                        this.filters.journey.selected = option_selected.id;
                    }
                    else if (option_selected.type === 'adventure') {
                        this.filters.experience.selected = option_selected.id;
                    }
                    else {
                        this.filters.session.selected = option_selected.id;
                    }

                    if(option_selected.adventures) {
                        this.data_filters.experiences = option_selected.adventures;
                    }
                    if(option_selected.sessions) {
                        this.data_filters.sessions = option_selected.sessions;
                    }
                    if(option_selected.experience_id) {
                        let parent =_.find(this.data_filters.experiences, item => {
                            return item.id === option_selected.experience_id;
                        });
                        this.filters.experience.selected = parent.id;
                        this.filters.experience.title = parent.title;

                        if(parent.journey_id) {
                            let parent_ =_.find(this.data_filters.journeys, item => {
                                return item.id === parent.journey_id;
                            });
                            this.filters.journey.selected = parent_.id;
                            this.filters.journey.title = parent_.title;
                        }
                    }
                    if(option_selected.journey_id) {
                        let parent =_.find(this.data_filters.journeys, item => {
                            return item.id === option_selected.journey_id;
                        });
                        this.filters.journey.selected = parent.id;
                        this.filters.journey.title = parent.title;
                    }
                }
                else {
                    this.filters[target].selected = null;
                    this.filters[target].title = null;
                }
            },
            translateVerb(verb, value=false) {
                let verbs = {
                    'interacted': {label:'Interacción',color:'orange'},
                    'attempted': {label:'Acceso',color:'yellow'},
                    'started': {label:'Video iniciado', color: 'olive'},
                    'completed': {label:'Completado',color:'green'},
                    'finished': {label:'Completado', color: 'green'},
                    'answered': {label:'Respuesta', color:(value) ? 'green' : 'red'},
                    'seeking': {label:'Salto', color:'violet'},
                    'paused': {label:'Pausado', color:'purple'},
                    'access': {label:'Acceso', color:'is-info'},
                    'scrolled': {label:'Escroll', color:'violet'},
                };
                return verbs[verb];
            },
            translateEvent(event, value=false) {
                let events = {
                    'content_event': {label:'Contenido'},
                    'video_event': {label:'Video'},
                    'xapi': {label:'Actividad'}
                };
                return events[event];
            },
        },
        data() {
            return {
                user: {
                    name: null,
                    list: [],
                    selected: null,
                    is_fetching: false,
                    has_data: false,
                    is_loading: false,
                    area: null,
                },
                data_list: [],
                data_filters: {
                    journeys: [],
                    experiences: [],
                    sessions: []
                },
                filters:{
                    journey: {
                        title: null,
                        selected: null
                    },
                    experience: {
                        title: null,
                        selected: null
                    },
                    session: {
                        title: null,
                        selected: null
                    },
                },
            }
        }
    }
</script>

<style scoped>
    .circle {
        border-radius: 50% !important;
    }
    .user-card {
        padding: 10px !important;
    }
    .tag {
        display: flex;
        color: white;
    }
    .orange {
        background-color: #FF6900;
    }
    .yellow {
        background-color: #FFC600;
    }
    .olive {
        background-color: #A0DE16;
    }
    .green {
        background-color: #00CC4D;
    }
    .teal {
        background-color: #00BBAF;
    }
    .red {
        background-color: #F00000;
    }
    .violet {
        background-color: #7F00C9;
    }
    .purple {
        background-color: #BC00C6;
    }
    .grey {
        background-color: darkgray;
    }
</style>