<template>
    <div class="container block-centered">
        <div class="row">
            <div class="col-12">
                <span class="font-20 has-text-weight-bold">
                    Puestos
                </span>
            </div>
            <fab :position="float_button.position"
                 :bg-color="float_button.bg_color"
                 :actions="float_button.actions"
                 @create="create"
                 ref="fab"
            ></fab>
        </div>
        <hr width="1">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <b-table :data="this.table_data.data">
                            <template slot-scope="props">
                                <b-table-column field="name" label="Puesto" sortable>
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
                                        <div class="col-2 font-18">
                                            <a class="pointer"
                                               v-if="authUser.is_admin || authUser.is_super"
                                               :href="route('positions.edit', [companyData.id,areaData.id,props.row])">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        </div>
                                        <div class="col-2 font-18">
                                            <a class="pointer"
                                               v-if="authUser.is_admin || authUser.is_super"
                                               :href="route('positions.show', [companyData.id,areaData.id,props.row])">
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
                        <div class="col-auto">
                            <span><i class="fa fa-trash"></i> Ver</span>
                        </div>
                    </div>
                    <!--div class="col-12 col-md-4 row justify-content-between has-text-centered">
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
                    </div-->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Fab from 'vue-fab';

    export default {
        name: "PositionsList",
        components: {
            'fab': Fab,
        },
        props: {
            companyData: {
                type: Object,
                required: true
            },
            areaData: {
                type: Object,
                required: true
            },
            authUser: {
                type: Object,
                required: true
            }
        },
        mounted() {
            this.getTableData();
            this.$refs.fab.$el.style.position = 'absolute';
            this.$refs.fab.$el.style.top = '-20px';
            this.$refs.fab.$el.style.right = '1%';
        },
        methods: {
            getTableData() {
                axios({
                    method: 'GET',
                    url: route('positions.index',[this.companyData.id, this.areaData.id]),
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
            create() {
                window.location.href = route('positions.create',[this.companyData.id,this.areaData.id]);
            },
            deleteDialog(element){
                let title = '¿Eliminar '+ element.name +'?';
                let message = 'Si borras esta posición, se borrará tambien de los usuarios que tengan esta posición asignada.';
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
                    url: route('positions.destroy', {company: this.companyData.id, area: element.area_id, position: element.id}),
                    method: 'DELETE'
                })
                    .then(response => {
                        let index=_.findIndex(this.table_data.data, {id: element.id});
                        this.$delete(this.table_data.data, index);
                        this.$snotify.success(element.name + ' se ha eliminado correctamente.', 'Posiciones');
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
                },
                float_button: {
                    position: 'bottom-right',
                    bg_color: '#2D7EFC',
                    actions: [
                        {
                            name: 'create',
                            icon: 'work',
                            tooltip: 'Nueva Posición',
                        },
                    ],
                }
            };
        }
    }
</script>

<style scoped>

</style>