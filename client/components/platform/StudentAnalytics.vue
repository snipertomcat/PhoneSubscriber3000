<template>
    <div class="container analytics" v-if="dataAnalytics.user_activity">
        <link rel="stylesheet" href="//cdn.materialdesignicons.com/2.5.94/css/materialdesignicons.min.css">
        <!-- Estadisticas relacionadas al enrolamiento de aventuras -->
        <div class="offset-md-1 col-md-10 offset-lg-2 col-lg-8">
            <div class="row journey-container align-items-center">
                <div class="col-12 col-md-3 align has-text-right">
                    <span class="has-text-weight-bold">{{ $t('messages.journeys') }}</span>
                </div>
                <div class="col-4 col-md-3 analytic-item">
                    <div class="has-text-centered">
                        <h1>
                            <span class="icon icon-orange">
                                <i class="fas fa-clipboard-list"></i>
                            </span>
                            <span>{{ dashboard.enrolled_journeys.value }}</span>
                        </h1>
                        <span class="text">{{ dashboard.enrolled_journeys.label }}</span>
                    </div>
                </div>
                <div class="col-4 col-md-3 analytic-item">
                    <div class="has-text-centered">
                        <h1>
                            <span class="icon icon-orange">
                                <i class="fas fa-clipboard-check"></i>
                            </span>
                            <span class="text">{{ dashboard.completed_journeys.value }}</span>
                        </h1>
                        <span>{{ dashboard.completed_journeys.label }}</span>
                    </div>
                </div>
                <div class="col-4 col-md-3 analytic-item">
                    <div class="has-text-centered">
                        <h1>
                            <span class="icon icon-orange">
                                <i class="fas fa-hourglass-start"></i>
                            </span>
                            <span class="text">{{ dashboard.in_progress_journeys.value }}</span>
                        </h1>
                        <span>{{ dashboard.in_progress_journeys.label }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas relacionadas al enrolamiento de experiencias -->
        <div class="offset-md-1 col-md-10 offset-lg-2 col-lg-8">
            <div class="row adventure-container align-items-center">
                <div class="col-12 col-md-3 align has-text-right">
                    <span class="has-text-weight-bold">{{ $t('messages.experiences') }}</span>
                </div>
                <div class="col-4 col-md-3 analytic-item">
                    <div class="has-text-centered">
                        <h1>
                            <span class="icon icon-orange">
                                <i class="fas fa-clipboard-list"></i>
                            </span>
                            <span>{{ dashboard.enrolled_experiences.value }}</span>
                        </h1>
                        <span class="text">{{ dashboard.enrolled_experiences.label }}</span>
                    </div>
                </div>
                <div class="col-4 col-md-3 analytic-item">
                    <div class="has-text-centered">
                        <h1>
                            <span class="icon icon-orange">
                                <i class="fas fa-clipboard-check"></i>
                            </span>
                            <span class="text">{{ dashboard.completed_experiences.value }}</span>
                        </h1>
                        <span>{{ dashboard.completed_experiences.label }}</span>
                    </div>
                </div>
                <div class="col-4 col-md-3 analytic-item">
                    <div class="has-text-centered">
                        <h1>
                            <span class="icon icon-orange">
                                <i class="fas fa-hourglass-start"></i>
                            </span>
                            <span class="text">{{ dashboard.in_progress_experiences.value }}</span>
                        </h1>
                        <span>{{ dashboard.in_progress_experiences.label }}</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Estadísticas relacionadas al tracking de las sessiones -->
        <div class="offset-md-1 col-md-10 offset-lg-2 col-lg-8">
            <div class="row session-container align-items-center">
                <div class="col-12 col-md-3 align has-text-right">
                    <span class="has-text-weight-bold">{{ $t('messages.challenges') }}</span>
                </div>
                <div class="col-4 col-md-3 analytic-item">
                    <div class="has-text-centered">
                        <h1>
                            <span class="icon icon-orange">
                                <i class="fas fa-clipboard-list"></i>
                            </span>
                            <span>{{ dashboard.available_sessions.value }}</span>
                        </h1>
                        <span class="text">{{ dashboard.available_sessions.label }}</span>
                    </div>
                </div>
                <div class="col-4 col-md-3 analytic-item">
                    <div class="has-text-centered">
                        <h1>
                            <span class="icon icon-orange">
                                <i class="fas fa-clipboard-check"></i>
                            </span>
                            <span class="text">{{ dashboard.finished_sessions.value }}</span>
                        </h1>
                        <span>{{ dashboard.finished_sessions.label }}</span>
                    </div>
                </div>
                <div class="col-4 col-md-3 analytic-item">
                    <div class="has-text-centered">
                        <h1>
                            <span class="icon icon-orange">
                                <i class="fas fa-hourglass-start"></i>
                            </span>
                            <span class="text">{{ dashboard.in_progress_sessions.value }}</span>
                        </h1>
                        <span>{{ dashboard.in_progress_sessions.label }}</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Tabla de eventos del usuario -->
        <div class="student-activity log offset-md-1 col-md-10" v-if="false">
            <h2>Registro de actividades</h2>
            <!-- Filters -->
            <div>
                <div class="row font-12">
                    <div class="col-md-4">
                        <b-field>
                            <b-input v-model="filter_options.name" placeholder="Buscar..." icon="search" icon-pack="fas" class="height-36"></b-input>
                        </b-field>
                    </div>
                    <div class="col-md-4">
                        <b-field>
                            <b-select v-model="filter_options.verb" expanded style="font-size: 14px;">
                                <option value="null">Seleccione una acción</option>
                                <option v-for="(option,index) in filter_options.actions" :value="option.value">{{ option.label }}</option>
                            </b-select>
                        </b-field>
                    </div>
                    <div class="col-md-auto">
                        <b-field>
                            <input class="input" type="date" :max="now" v-model="filter_options.date" height="36px">
                        </b-field>
                    </div>
                    <div class="col-md-auto has-text-centered">
                        <div class="control">
                            <button class="button is-dark" @click="filter" style="font-size: 14px">Filtrar</button>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <!-- Tabla -->
            <b-table :data="paginatedResults" per-page="10" striped mobile-cards paginated>
                <template slot-scope="props">
                    <b-table-column field="experience" label="Experiencia" sorteable>
                        <a :href="props.row.experience_link">{{ props.row.experience }}</a><br>
                        <span class="tag session grey has-text-left">{{ props.row.session }}</span>
                    </b-table-column>

                    <b-table-column field="name" label="Accion" sorteable>
                        <span v-if="translateVerb(props.row.verb)" class="has-text-centered align-self-center v-center">
                            <div class="tag" :class="translateVerb(props.row.verb, props.row.raw_score).color">
                                <span>{{ translateEvent(props.row.event_type).label }} : {{ translateVerb(props.row.verb).label }}</span>
                            </div>
                        </span>
                        <span class="has-text-centered v-center" v-else>
                            <div class="tag grey"><span>{{ props.row.verb }}</span></div>
                        </span>
                    </b-table-column>

                    <b-table-column field="email" label="Duración" sortable>
                        {{ props.row.duration.replace('PT','') }}
                    </b-table-column>

                    <b-table-column field="email" label="Fecha" sortable>
                        {{ props.row.created_at }}
                    </b-table-column>
                </template>
                <template slot="empty">
                    <section class="section">
                        <div class="content has-text-grey has-text-centered">
                            <p>
                                <img src="/images/Caja.png" alt="">
                            </p>
                            <p>Aún no hay datos que mostrar.</p>
                        </div>
                    </section>
                </template>
            </b-table>
        </div>
    </div>
</template>

<script>
    export default {
        name: "StudentAnalytics",
        props: ['dataAnalytics'],
        mounted() {
            //this.loadDefaultDataForTable();
            this.paginate()
        },
        computed: {
            now() {
                let now = new Date();
                return now.toISOString().split('T')[0];
            }
        },
        created() {
            this.$validator.localize('es');
        },
        methods: {
            loadDefaultDataForTable(){
                this.dashboard.user_activity = this.default_data_for_table;
            },
            translateVerb(verb, value=false) {
                let verbs = {
                    'interacted': {label:'Interacción',color:'orange'},
                    'attempted': {label:'Acceso',color:'yellow'},
                    'completed': {label:'Completado',color:'green'},
                    'answered': {label:'Respuesta', color:(value) ? 'green' : 'red'},
                    'access': {label:'Acceso', color:'is-info'},
                    'scrolled': {label:'Hizo scroll', color:'violet'},
                    'started': {label:'Iniciado', color: 'olive'},
                    'finished': {label:'Finalizado', color: 'green'},
                    'seeking': {label:'Salto', color:'violet'},
                    'paused': {label:'Pausado', color:'purple'},
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
            paginate() {
                let target = this.dashboard.user_activity;
                this.paginatedResults = this.dashboard.user_activity;

                /*this.paginatedResults = target.filter((row, index) => {
                    let start = (this.pagination_option.currentPage - 1) * this.pagination_option.pageSize;
                    let end = this.pagination_option.currentPage * this.pagination_option.pageSize;
                    if(index >= start && index < end) return true;
                });*/
            },
            resultNextPage() {
                if((this.pagination_option.currentPage * this.pagination_option.pageSize) < this.dashboard.user_activity.length) {
                    this.pagination_option.currentPage = this.pagination_option.currentPage + 1;
                    this.paginate();
                }
            },
            resultPrevPage() {
                if (this.pagination_option.currentPage > 1) {
                    this.pagination_option.currentPage = this.pagination_option.currentPage - 1;
                    this.paginate();
                }
            },
            filter() {
                let principal_data = this.dashboard.user_activity;
                let filtered_data = [];

                let name = (this.filter_options.name) ? this.filter_options.name.toLowerCase() : null;
                let verb = (this.filter_options.verb !== 'null') ? this.filter_options.verb : null;
                let from = (this.filter_options.date) ? this.filter_options.date : null;
                let to = this.now;

                if(filtered_data.length < 1) {
                    filtered_data = principal_data;
                }

                if(name) {
                    let new_filtered_data = [];
                    filtered_data.forEach((item, data) => {
                        let name_experience = item.experience.toLowerCase();
                        let name_session = item.session.toLowerCase();

                        if (name_experience.includes(name) || name_session.includes(name)) {
                            new_filtered_data.push(item);
                        }
                    });
                    filtered_data = new_filtered_data;
                }

                if(verb) {
                    let new_filtered_data = [];
                    filtered_data.forEach((item, index) => {
                        if (item.verb === verb) {
                            new_filtered_data.push(item);
                        }
                    });
                    filtered_data = new_filtered_data;
                }

                if(from) {
                    let new_filtered_data = [];
                    filtered_data.forEach((item,index) => {
                        let created_at = item.created_at.split(' ')[0];
                        // if (created_at >= from && created_at <= to) {
                        if (created_at === from) {
                            new_filtered_data.push(item);
                        }
                    });
                    filtered_data = new_filtered_data;
                }

                this.paginatedResults = filtered_data;
            }
        },
        data() {
            return {
                dashboard: {
                    enrolled_journeys: {
                        label: 'Enrolados',
                        value: this.dataAnalytics.enrolled_journeys
                    },
                    completed_journeys: {
                        label: 'Completados',
                        value: this.dataAnalytics.completed_journeys
                    },
                    in_progress_journeys: {
                        label: 'En curso',
                        value: this.dataAnalytics.in_progress_journeys
                    },
                    enrolled_experiences: {
                        label: 'Enroladas',
                        value: this.dataAnalytics.enrolled_experiences
                    },
                    completed_experiences: {
                        label: 'Completadas',
                        value: this.dataAnalytics.completed_experiences
                    },
                    in_progress_experiences: {
                        label: 'En curso',
                        value: this.dataAnalytics.in_progress_experiences
                    },
                    available_sessions: {
                        label: 'Disponibles',
                        value: this.dataAnalytics.available_sessions
                    },
                    finished_sessions: {
                        label: 'Completados',
                        value: this.dataAnalytics.finished_sessions
                    },
                    in_progress_sessions: {
                        label: 'En curso',
                        value: this.dataAnalytics.in_progress_sessions
                    },
                    user_activity: this.dataAnalytics.user_activity
                },
                paginatedResults: [],
                pagination_option: {
                    currentPage: 1,
                    pageSize: 10
                },
                filter_options: {
                    name: null,
                    verb: null,
                    date: null,
                    actions: [
                        {value: 'access', label: 'Acceso'},
                        {value: 'completed', label: 'Completado'},
                        {value: 'scrolled', label: 'Hizo scroll'},
                        {value: 'attempted', label: 'Intento'},
                        {value: 'interacted', label: 'Interacción'},
                        {value: 'answered', label: 'Respuesta'},
                        {value: 'seeking', label:'Salto reproducción'},
                        {value: 'started', label:'Iniciado'},
                        {value: 'finished', label:'Finalizado'},
                        {value: 'paused', label:'Video pausado'}
                    ]
                },
            };
        }
    }
</script>

<style scoped>
    table td {
        justify-content: left !important;
    }
    .student-activity {
        margin-top: 25px;
        font-size: 14px;
    }
    .student-activity .label {
        font-size: 12px;
    }
    .student-activity a {
        font-size: 15px;
    }
    .tag {
        display: flex;
        color: white;
    }
    .tag.session {
        font-size: 11px;
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
    h1 {
        font-size: 1.85rem;
        font-weight: bold;
    }
    h2 {
        font-size: 1.4rem;
    }
    .analytics {
        margin-top: 50px;
    }
    .icon {
        margin-right: 5px;
    }
    .icon-orange {
        color: orange;
    }
    .journey-container {
        height: 130px;
        background-color: #f5f5f5;
        border: solid #d0d0d0 2px;
    }
    .adventure-container {
        height: 130px;
        background-color: #f5f5f5;
        border: solid #d0d0d0 2px;
    }
    .session-container {
        height: 130px;
        background-color: #f5f5f5;
        border: solid #d0d0d0 2px;
    }
    /* Small devices (portrait phones, 320px and up) */
    @media (min-width: 320px) {
        .analytic-item {
            font-size: 12px;
        }
        .analytic-item .icon-container{
            font-size: 1.4rem;
        }
        .student-activity.log .table-view {
            display: none;
        }
        .student-activity.log .card-view {
            display: block;
            margin: 15px 0;
        }
        .card {
            margin-bottom: 10px;
            background-color: white;
            box-shadow: 0 2px 3px rgba(10, 10, 10, 0.1), 0 0 0 1px rgba(10, 10, 10, 0.1) !important;
            border-radius: 0 !important;
            color: #4a4a4a;
            max-width: 100%;
            position: relative;
        }
        .card .tag.session {
            border-radius: 0;
            white-space: normal;
            height: auto;
        }
        .card .card-footer {
            padding: 0;
        }
    }

    /* Small devices (landscape phones, 576px and up) */
    @media (min-width: 576px) {
        .analytic-item {
            font-size: 16px;
        }
        .analytic-item .icon-container{
            font-size: 1.85rem;
        }
    }

    /* Medium devices (tablets, 768px and up) */
    @media (min-width: 768px) {
        .student-activity.log .table-view {
            display: block;
        }
        .student-activity.log .card-view {
            display: none;
        }
        .v-center {
            padding-top: 28px !important;
        }
        .tag.session {
            font-size: 11px;
            width: 100%;
            white-space: normal;
            height: auto;
        }
    }
</style>