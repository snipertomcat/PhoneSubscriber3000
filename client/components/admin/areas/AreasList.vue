<template>
    <div class="container block-centered">
        <hr width="1">
        <apithy-areas-filter :company-data="this.companyData"></apithy-areas-filter>
        <br>
        <div class="row">
            <div class="col-12">
                <b-table :data="this.table_data.data">
                    <template slot-scope="props">
                        <b-table-column field="name" label="Área" sortable>
                            {{ (props.row.name !== '' && props.row.name !== null) ? props.row.name : 'No definido' }}
                        </b-table-column>
                        <b-table-column field="short_name" label="Nombre corto" sortable>
                            {{ (props.row.short_name !== '' && props.row.short_name !== null) ? props.row.short_name : 'No definido' }}
                        </b-table-column>
                        <b-table-column field="created_at" label="Creado" sortable>
                            {{ (props.row.created_at !== '' && props.row.created_at !== null) ? new Date(props.row.created_at).toLocaleDateString() : 'No definido' }}
                        </b-table-column>
                        <b-table-column field="actions" label="Acción">
                            <div class="row">
                                <div class="col-2">
                                    <a class="pointer font-18"
                                       v-if="authUser.is_admin || authUser.is_super"
                                       :href="route('areas.edit', [companyData.id,props.row])">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                </div>
                                <div class="col-2 font-18">
                                    <a class="pointer"
                                       v-if="authUser.is_admin || authUser.is_super"
                                       :href="route('areas.show', [companyData.id,props.row])">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </div>
                                <div class="col-2">
                                    <a class="font-18 apithy-color"
                                       v-if="authUser.is_admin || authUser.is_super"
                                       @click="deleteDialog(props.row)">
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
    </div>
</template>

<script>
    import AreasFilter from './AreasFilter';

    export default {
        name: "AreasList",
        components: {
            'apithy-areas-filter': AreasFilter,
        },
        props: {
            authUser: {
                type: Object,
                required: true
            },
            companyData: {
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
                    url: route('areas.index',[this.companyData.id]),
                    params: this.params
                })
                    .then(response => {
                        this.setTableData(response.data);
                    })
                    .catch(error => {
                        console.log(error);
                    })
            },
            setTableData(data) {
                this.table_data = data;
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
            },
            deleteDialog(element){
                let title = '¿Eliminar '+ element.name +'?';
                let message = 'Si borras esta área, igual se eliminaran sus pociones.';
                this.$snotify.confirm(message, title, {
                    showProgressBar: true,
                    closeOnClick: false,
                    buttons: [
                        {text: 'Si', action: (toast) => this.destroy(element,toast), bold: false},
                        {
                            text: 'No', action: (toast) => {
                                this.$snotify.remove(toast.id);
                            }
                        }
                    ]
                });
            },
            destroy(element,toast) {
                if(toast){
                    this.$snotify.remove(toast.id);
                }

                axios({
                    url: route('areas.destroy', {company: element.company_id,area: element.id}),
                    method: 'DELETE'
                })
                    .then(response => {
                        let index=_.findIndex(this.table_data.data, {id: element.id});
                        this.$delete(this.table_data.data, index);
                        this.$snotify.success(element.name + ' se ha eliminado correctamente.', 'Áreas');
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
        },
        data() {
            return {
                table_data: {},
                params: {
                    search: null,
                    page: 1
                }
            };
        }
    }
</script>

<style scoped>

</style>