<template>
    <div class="">
        <div class="row">
            <div class="col-12 col-md-2">
                <div class="select width-100">
                    <select class="width-100" v-model="params.filter.author">
                        <option :value="null">{{ $t('messages.author') }}</option>
                        <option :value="author.id" v-for="author in authors">
                            {{ author.name }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="d-block d-md-none"><br><br></div>
            <div class="col-12 col-md-2">
                <div class="select width-100">
                    <select class="width-100" v-model="params.filter.abilities">
                        <option :value="null">{{ $t('messages.ability') }}</option>
                        <option :value="ability.id" v-for="ability in abilities">
                            {{ ability.title }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="d-block d-md-none"><br><br></div>
            <div class="col-12 col-md-2">
                <b-field>
                    <b-input placeholder="Search..."
                             v-model="params.search"
                             type="search"
                             icon="magnify">
                    </b-input>
                </b-field>
            </div>
            <div class="d-block d-md-none"><br><br></div>
            <div class="col-4 col-md-auto">
                <b-tooltip :label="adventure_tooltip" position="is-bottom">
                    <b-checkbox-button v-model="filter_options.type"
                                       native-value="adventure"
                                       @input="setTypeFilter"
                                       type="is-link">
                        <i class="fas fa-globe"></i>
                    </b-checkbox-button>
                </b-tooltip>
            </div>
            <div class="col-4 col-md-auto">
                <b-tooltip :label="journey_tooltip" position="is-bottom">
                    <b-checkbox-button v-model="filter_options.type"
                                       native-value="journey"
                                       @input="setTypeFilter"
                                       type="is-link">
                        <i class="icon-air-balloon"></i>
                    </b-checkbox-button>
                </b-tooltip>
            </div>
            <div class="col-12 col-md-auto" v-if="!this.filter_options.empty">
                <button class="button is-link" @click="filterClear">
                    <b-icon pack="fa" icon="times"></b-icon>
                    <span>{{$t('messages.clear')}}</span>
                </button>
            </div>
            <div class="col-4 col-md-1">
                    <button class="button is-link" @click="filter">
                        <b-icon pack="fa" icon="filter"></b-icon>
                        <span>{{$t('messages.filter')}}</span>
                    </button>
            </div>
            <div class="col-4 col-md-1">
                <button class="button is-link" @click="confirmClearCache">
                    <b-icon pack="fa" icon="filter"></b-icon>
                    <span>Limpiar cache de sesiones</span>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import qs from 'qs';

    export default {
        name: "ExperienceFilter",
        components: {
        },
        watch: {
            "params.filter": {
                deep: true,
                handler() {
                    this.checkEmpty();
                }
            },
            "params.search": {
                handler() {
                    this.checkEmpty();
                }
            }
        },
        props: {
            authors: {
                type: Array,
                required: true
            },
            abilities: {
                type: Array,
                required: true
            }
        },
        mounted() {
        },
        methods: {
            confirmClearCache () {
                this.$snotify.confirm(
                    'Limpiar cache de sesiones de experiencias',
                    '¿Limpiar la cache de las sesiones de todas las experiencias?',
                    {
                        howProgressBar: true,
                        closeOnClick: false,
                        pauseOnHover: true,
                        backdrop: 0.6,
                        buttons: [
                            {
                                text: 'Si', action: (toast) => {
                                    this.clearAllSessionCache(toast)
                                }
                            },
                            {
                                text: 'No', action: (toast) => {this.$snotify.remove(toast.id)}
                            },
                        ]
                    }
                )
            },
            clearAllSessionCache (toast) {
                this.$snotify.remove(toast.id)
                this.$snotify.async('Limpiando cache', 'Procesando peticion', () => new Promise((resolve, reject) => {
                    axios.post(route('experience-session.clear.all.cache'))
                        .then(response => {
                            resolve({
                                title: 'Petición exitosa',
                                body: 'Petición procesada con éxito.',
                                config: {
                                    timeout: 2000,
                                    backdrop: 0.6,
                                    closeOnClick: true,
                                    html: `
                                      <div class="snotifyToast__title">Petición exitosa</div>
                                      <div class="snotifyToast__body">Petición procesada con éxito.</div>
                                      <div class="snotify-icon snotify-icon--success"></div>
                                      `
                                }
                            });
                        })
                        .catch(errors => {
                            reject({
                                title: 'Error!!',
                                body: 'No se pudo procesar la petición correctamente.',
                                config: {
                                    closeOnClick: true,
                                    timeout: 2000,
                                    backdrop: 0.6,
                                    html: `
                                     <div class="snotifyToast__title">Error!!</div>
                                      <div class="snotifyToast__body">No se pudo procesar la petición correctamente.</div>
                                      <div class="snotify-icon snotify-icon--error"></div>
                                    `
                                }
                            });
                        })
                }), {backdrop: 0.6})
            },
            filter() {
                let to = route().current();
                let param = (route().current('experience.buyed')) ? {use_for:'personal_use'} : null;
                axios({
                    method: 'GET',
                    url: route(to, param),
                    params: this.params,
                    paramsSerializer: function(params) {
                        return qs.stringify(params, { encode: false });
                    }
                })
                    .then(response => {
                        this.$parent.setData(response.data);
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            filterClear() {
                this.params.filter = {
                    author: null,
                    abilities: null,
                    type: null,
                };
                this.params.search = null;
                this.filter_options.type = ["adventure","journey"];
                this.filter_options.empty = true;
                this.filter();
            },
            create() {
                window.location.href = route('experiences.create');
            },
            setTypeFilter() {
                if(this.filter_options.type.length !== 1){
                    this.params.filter.type = null;
                }
                else {
                    this.params.filter.type = this.filter_options.type[0];
                }
            },
            checkEmpty() {
                let empty = true;
                for(let inx in this.params.filter) {
                    if (this.params.filter[inx] !== null) {
                        empty = false;
                    }
                }
                if (this.params.search) {
                    empty = false;
                }
                this.filter_options.empty = empty;
            }
        },
        computed: {
            adventure_tooltip() {
                return (this.filter_options.type.find(type => {return type === 'adventure';})) ? 'Ocultar experiencias' : 'Mostrar experiencias';
            },
            journey_tooltip() {
                return (this.filter_options.type.find(type => {return type === 'journey';})) ? 'Ocultar viajes' : 'Mostrar viajes';
            }
        },
        data() {
            return {
                filter_options: {
                    type: ["adventure","journey"],
                    empty: true,
                },
                params: {
                    page: 1,
                    filter: {
                        author: null,
                        abilities: null,
                        type: null,
                    },
                    search: null,
                },
                float_button: {
                    position: 'top-right',
                    bg_color: '#2D7EFC',
                    actions: [
                        {
                            name: 'create',
                            icon: 'ballot',
                            tooltip: 'Nueva experiencia',
                        },
                    ],
                }
            }
        }
    }
</script>

<style scoped>

</style>