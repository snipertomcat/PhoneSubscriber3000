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
                                        <div>
                                            <span>[Área]</span>
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
                        <b-field label="Filtrar por aventura">
                            <b-autocomplete
                                    v-model="filters.adventure.name"
                                    :data="data_filters.adventures"
                                    :disabled="!data_filters.adventures.length > 0"
                                    field="title"
                                    @select="setFilter(option)">
                            </b-autocomplete>
                        </b-field>
                    </div>
                    <div class="col-12 col-md-4">
                        <b-field label="Filtrar por experiencia">
                            <b-autocomplete
                                    v-model="filters.experience.name"
                                    :data="data_filters.experiences"
                                    :disabled="!data_filters.experiences.length > 0"
                                    field="title"
                                    @select="option => filters.experience.selected = option.id">
                            </b-autocomplete>
                        </b-field>
                    </div>
                    <div class="col-12 col-md-4">
                        <b-field label="Filtrar por reto">
                            <b-autocomplete
                                    v-model="filters.challenge.name"
                                    :data="data_filters.challenges"
                                    :disabled="!data_filters.challenges.length > 0"
                                    field="title"
                                    @select="option => filters.challenge.selected = option.id">
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
                            :open-on-focus="true"
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
                            <b-table :columns="columns" :data="data_list"></b-table>
                        <span class="is-italic" v-if="!(this.user.has_data > 0)">El usuario no cuenta con registros de actividad</span>
                        </span>
                        <span v-else>
                            <b-table :columns="columns" :data="[]"></b-table>
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
                this.user.selected = selection;
                axios({
                    url: route('dashboard.users'),
                    method: 'GET',
                    params: {
                        user_id: this.user.selected.id,
                        filters: (with_filters) ? this.filters : null
                    }
                }).then(response => {
                    this.user.has_data = (response.data.activity.length > 0);
                    this.data_list = response.data.activity;
                    this.data_filters = response.data.filters;
                }).catch(error => {
                    throw error;
                }).finally(() => {

                });
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
                this.getActivityData(this.user.selected, true);
            },
            setFilter(option) {
                filters.adventure.selected = option.id
            }
        },
        data() {
            return {
                user: {
                    name: null,
                    list: [],
                    selected: null,
                    is_fetching: false,
                    has_data: false,
                },
                data_list: [],
                data_filters: {
                    adventures: [],
                    experiences: [],
                    challenges: []
                },
                filters:{
                    adventure: {
                        name: null,
                        selected: null
                    },
                    experience: {
                        name: null,
                        selected: null
                    },
                    challenge: {
                        name: null,
                        selected: null
                    },
                },
                columns: [
                    {
                        field: 'session.type',
                        label: 'Actividad',
                    },
                    {
                        field: 'session.title',
                        label: 'Reto',
                    },
                    {
                        field: 'created_at',
                        label: 'Fecha',
                        centered: true
                    },
                ],
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
</style>