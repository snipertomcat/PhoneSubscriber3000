<template>
    <div class="container block-centered  row">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <b-table :data="table_data.data" class="font-14" :striped="true" :mobile-cards="true">
                        <template slot-scope="props">
                            <b-table-column field="name" label="Nombre" sortable>
                                {{ props.row.name }}
                            </b-table-column>
                            <b-table-column field="short_name" label="Nombre Corto" sortable>
                                {{ props.row.short_name }}
                            </b-table-column>
                            <b-table-column field="country" label="País" sortable>
                                {{ (props.row.country !== '' && props.row.country !== null) ? props.row.country.name : 'No definido' }}
                            </b-table-column>
                            <b-table-column field="contact_email" label="Correo electrónico" sortable>
                                {{ (props.row.contact_email !== '' && props.row.contact_email !== null) ? props.row.contact_email : 'No definido' }}
                            </b-table-column>
                            <b-table-column field="actions" label="Acción">
                                <div class="row">
                                    <div class="col-4">
                                        <a :href="route('companies.edit', [props.row])" class="pointer" v-if="authUser.is_admin || authUser.is_super">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a :href="route('companies.show', [props.row])" class="pointer" v-if="authUser.is_admin || authUser.is_super">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a @click="deleteCompany(props.row)" class="pointer has-text-danger" v-if="authUser.is_admin || authUser.is_super">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
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
        </div>
        <div class="col-12 user-table-foot" v-if="!!table_data.total">
            <br>
            <div class="row">
                <div class="col-12 col-md-4 row glosary">
                    <div class="col-auto">
                        <span><i class="fa fa-pencil"></i> Editar</span>
                    </div>
                    <div class="col-auto">
                        <span><i class="fa fa-eye"></i> Ver</span>
                    </div>
                </div>
                <div class="col-12 col-md-4 row justify-content-between has-text-centered">
                    <span @click="first" class="col pointer apithy-color font-20"><span><i class="fa fa-angle-double-left"></i></span></span>
                    <span @click="prev" class="col pointer apithy-color font-20"><span><i class="fa fa-angle-left"></i></span></span>
                    <span class="col apithy-color font-20">{{ table_data.current_page }}</span>
                    <span @click="next" class="col pointer apithy-color font-20"><span><i class="fa fa-angle-right"></i></span></span>
                    <span @click="last" class="col pointer apithy-color font-20"><span><i class="fa fa-angle-double-right"></i></span></span>
                </div>
                <div class="col-12 col-md-4 has-text-right">
                    <div class="select">
                        <select name="" id="">
                            <option value="15">Ver 15</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "CompanyList",
        props: {
            authUser: {
                type: Object,
                required: true
            }
        },
        mounted() {
            this.getTableData();
        },
        methods: {
            getTableData() {
                axios({
                    method: 'GET',
                    url: route('companies.index'),
                    params: this.params
                })
                    .then(response => {
                        this.setTableData(response.data);
                    })
                    .catch(error => {
                        console.log(error)
                    });
            },
            setTableData(data) {
                this.table_data = data;
            },
            deleteCompany(company) {
                axios({
                    method: 'DELETE',
                    url: route('companies.destroy', [company]),
                    params: ''
                })
                    .then(response => {
                        this.getTableData();
                    })
                    .catch(error => {
                        console.log(error);
                    })
            },
            first() {
                this.params.page = 1;
                this.getTableData();
            },
            prev() {
                if(this.table_data.current_page > this.table_data.first_page) {
                    this.params.page = this.table_data.current_page - 1;
                    this.getTableData();
                }
            },
            next() {
                if(this.table_data.current_page < this.table_data.last_page) {
                    this.params.page = this.table_data.current_page + 1;
                    this.getTableData();
                }
            },
            last() {
                this.params.page = this.table_data.last_page;
                this.getTableData();
            }
        },
        data() {
            return {
                table_data: {},
                params: {
                    search: ''
                }
            };
        }
    }
</script>

<style scoped>

</style>