<template>
    <div class="container block-centered row">
        <div class="col-12">
            <span class="font-18 has-text-weight-bold">Filtrar por</span>
        </div>
        <div class="col-12 col-md-2">
            <div class="select width-100">
                <select placeholder="País" class="width-100" v-model="params.filter.country_id">
                    <option :value="null">
                        País
                    </option>
                    <option v-for="country in countries"
                            :value="country.id"
                            :key="country.id">
                        {{ country.name }}
                    </option>
                </select>
            </div>
        </div>
        <div class="d-block d-md-none"><br><br></div>
        <div class="col-12 col-md-2">
            <b-input placeholder="Search..."
                     type="search"
                     icon="magnify"
                     v-model="params.search"
                     @keydown.enter="filter">
            </b-input>
        </div>
        <div class="d-block d-md-none"><br><br></div>
        <div class="col-12 col-md-2">
            <span>
                <button class="button is-link" @click="filter">
                    <b-icon pack="fa" icon="filter"></b-icon>
                    <span>Filtrar</span>
                </button>
            </span>
        </div>
    </div>
</template>

<script>
    import qs from 'qs';
    export default {
        name: "CompanyFilter",
        components: {
        },
        props: {
            countries: {
                type: Array,
                required: true
            }
        },
        mounted() {

        },
        methods: {
            filter() {
                axios({
                    url: route('companies.index'),
                    method: 'GET',
                    params: this.params,
                    paramsSerializer: function(params) {
                        return qs.stringify(params, { encode: false });
                    }
                })
                    .then( response => {
                        this.$parent.$refs.companies_list.setTableData(response.data);
                    })
                    .catch( error => {
                        console.log(error);
                    })
            },
            create() {
                window.location.href = route('companies.create');
            }
        },
        computed: {
            new_company() {
                return this.$t('messages.new_company');
            }
        },
        data() {
            return {
                params: {
                    filter:{
                        country_id: null
                    },
                    search: '',
                    page: 1
                },
                float_button: {
                    position: 'top-right',
                    bg_color: '#2D7EFC',
                    actions: [
                        {
                            name: 'create',
                            icon: 'business',
                            tooltip: 'Nueva Empresa',
                        },
                    ],
                }
            };
        }
    }
</script>

<style scoped>

</style>