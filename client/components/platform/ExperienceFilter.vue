<template>
    <div class="">
        <div class="row">
            <!--div class="col-12 col-md-2">
                <div class="select width-100">
                    <select class="width-100" v-model="params.filter.author">
                        <option :value="null">{{ $t('messages.author') }}</option>
                        <option :value="author.id" v-for="author in authors">
                            {{ author.name }}
                        </option>
                    </select>
                </div>
            </div-->
            <div class="d-block d-md-none"><br><br></div>
            <div class="d-block d-md-none"><br><br></div>
            <div class="col-12 col-md-4">
                <b-field>
                    <b-input placeholder="Buscar..."
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
                        <span>Filtrar</span>
                    </button>
            </div>
        </div>
    </div>
</template>

<script>
    import qs from 'qs';

    export default {
        name: "ExperienceFilter",
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
            },
            path: {
                required: true,
                type: String
            }
        },
        methods: {
            filter() {
                axios({
                    method: 'GET',
                    url: this.path,
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
                    //author: null,
                    abilities: null,
                    type: null,
                };
                this.params.search = null;
                this.filter_options.type = ["adventure","journey"];
                this.filter_options.empty = true;
                this.filter();
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
                    empty: true
                },
                params: {
                    page: 1,
                    filter: {
                        //author: null,
                        abilities: null,
                        type: null,
                    },
                    search: null,
                },
            }
        }
    }
</script>

<style scoped>

</style>