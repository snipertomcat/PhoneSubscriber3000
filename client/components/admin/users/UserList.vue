<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <b-table :data="table_data.data" :striped="true" :mobile-cards="true">
                    <template slot-scope="props">
                        <b-table-column field="id" label="ID" v-if="showsId">
                            {{ props.row.id }}
                        </b-table-column>

                        <b-table-column field="name" label="Nombre">
                            {{ props.row.name }}
                        </b-table-column>

                        <b-table-column field="email" label="Correo electrónico" sortable>
                            {{ props.row.email }}
                        </b-table-column>

                        <b-table-column v-if="authUser.is_super" field="company.name" label="Compañia" width="200" sortable>
                            {{ (props.row.company[0]) ? props.row.company[0].name : 'Apithy' }}
                        </b-table-column>

                        <b-table-column field="company" label="Rol" sortable>
                        <span :class="'tag role '+props.row.roles[0].class">
                            {{ props.row.roles[0].name }}
                        </span>
                        </b-table-column>

                        <b-table-column field="created_at" label="Creado el" v-if="showsCreated">
                            {{ new Date(props.row.created_at).toLocaleString() }}
                        </b-table-column>

                        <b-table-column field="status" label="Estatus">
                        <span :class="'tag status '+props.row.status_class">
                            {{ props.row.status_text }}
                        </span>
                        </b-table-column>

                        <b-table-column field="options" label="Acción" width="200">
                            <div class="row">
                                <div class="col-2">
                                    <a class="font-18 apithy-color"
                                       v-if="authUser.is_super"
                                       :href="route('impersonate', props.row.id)">
                                        <i class="fa fa-home"></i>
                                    </a>
                                </div>
                                <div class="col-2">
                                    <a class="font-18 apithy-color"
                                       v-if="authUser.is_admin || authUser.is_super"
                                       @click="goto('users.edit', {user:props.row.id})">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                </div>
                                <div class="col-2">
                                    <a class="font-18 apithy-color"
                                       v-if="authUser.is_admin || authUser.is_super"
                                       @click="deleteUserDialog(props.row.id)">
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
                            <span><i class="fa fa-home"></i> Personificar</span>
                        </div>
                        <div class="col-auto">
                            <span><i class="fa fa-trash"></i> Eliminar</span>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 row justify-content-between has-text-centered">
                        <span @click="first" class="col pointer font-18 apithy-color"><span><i class="fa fa-angle-double-left"></i></span></span>
                        <span @click="prev" class="col pointer font-18 apithy-color"><span><i class="fa fa-angle-left"></i></span></span>
                        <span class="col pointer apithy-color">{{ table_data.current_page }}</span>
                        <span @click="next" class="col pointer font-18 apithy-color"><span><i class="fa fa-angle-right"></i></span></span>
                        <span @click="last" class="col pointer font-18 apithy-color"><span><i class="fa fa-angle-double-right"></i></span></span>
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
    import UsersFilter from './UsersFilter';
    export default {
        name: "UserList",
        props: {
            authUser: {
                type: Object,
                required: true
            },
            showsId: {
                type: Boolean,
                required: false,
                default: false
            },
            showsCreated: {
                type: Boolean,
                required: false,
                default: true
            }
        },
        components: {
            'apithy-users-filter': UsersFilter,
        },
        mounted() {
            this.send();
        },
        methods: {
            setColors(array) {
                _.each(array, item => {
                    if (item.roles.length > 0) {
                        switch (item.roles[0].code) {
                            case "SA":
                                item.roles[0].class = 'apithy-color-super-admin';
                                break;
                            case "SP":
                                item.roles[0].class = 'apithy-color-support';
                                break;
                            case "AD":
                                item.roles[0].class = 'apithy-color-admin';
                                break;
                            case "SUP":
                                item.roles[0].class = 'apithy-color-supervisor';
                                break;
                            case "GJ":
                                item.roles[0].class = 'apithy-color-gest-jerq';
                                break;
                            case "PC":
                                item.roles[0].class = 'apithy-color-prov-content';
                                break;
                            case "AU":
                                item.roles[0].class = 'apithy-color-author';
                                break;
                            case "ER":
                                item.roles[0].class = 'apithy-color-enroller';
                                break;
                            case "STU":
                                item.roles[0].class = 'apithy-color-student';
                                break;
                            case "PT":
                                item.roles[0].class = 'apithy-color-partner';
                                break;
                            default:
                                break;
                        }
                    }
                    else {
                        item.roles[0] = {
                            name: 'Sin rol',
                            class:'apithy-color-gray',
                        }
                    }
                });

                return array;
            },
            setStatus(array) {
                _.each(array, item => {
                    switch (item.status) {
                        case 0:
                            item.status_class = 'apithy-color-status-unconfirmed';
                            item.status_text = 'Sin confirmar';
                            break;
                        case 1:
                            item.status_class = 'apithy-color-status-active';
                            item.status_text = 'Activo';
                            break;
                        case 2:
                            item.status_class = 'apithy-color-status-deleted';
                            item.status_text = 'Eliminado';
                            break;
                        case 3:
                            item.status_class = 'apithy-color-status-suspended';
                            item.status_text = 'Suspendido';
                            break;
                        case 4:
                          item.status_class = 'apithy-color-status-unconfirmed';
                          item.status_text = 'Estableciendo contraseña';
                          break;
                        case 5:
                          item.status_class = 'apithy-color-status-unconfirmed';
                          item.status_text = 'Contraseña establecida';
                          break;
                        default:
                            break;
                    }
                });

                return array;
            },
            setPagination(data) {
                data.data = this.setColors(data.data);
                data.data = this.setStatus(data.data);
                this.table_data = data;
            },
            deleteUserDialog(id){
                this.$snotify.confirm('¿Estas seguro de eliminar esta usuario?','¡Cuidado!', {
                    showProgressBar: true,
                    closeOnClick: false,
                    buttons: [
                        {text: 'Si', action: (toast) => this.deleteUser(id,toast), bold: false},
                        {
                            text: 'No', action: (toast) => {
                                this.$snotify.remove(toast.id);
                            }
                        }
                    ]
                });
            },
            deleteUser(id,toast) {
                if(toast){
                    this.$snotify.remove(toast.id);
                }

                axios({
                    url: route('users.destroy', id),
                    method: 'DELETE'
                })
                    .then(response => {
                        let index=_.findIndex(this.table_data.data, {id: id});
                        this.$delete(this.table_data.data, index);
                        this.$snotify.success('Usuario eliminado', 'Usuarios');
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            send() {
                axios({
                    url: route('users.index'),
                    method: 'GET',
                    params: this.params,
                })
                    .then((response) => {
                        this.setPagination(response.data.users);
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },
            first() {
                this.params.page = 1;
                this.send();
            },
            prev() {
                if(this.table_data.current_page >= 1) {
                    this.params.page = this.table_data.current_page - 1;
                    this.send();
                }
            },
            next() {
                if(this.table_data.current_page < this.table_data.last_page) {
                    this.params.page = this.table_data.current_page + 1;
                    this.send();
                }
            },
            last() {
                this.params.page = this.table_data.last_page;
                this.send();
            },
            goto(src,params = null) {
                window.location.href = route(src,params);
            }
        },
        data() {
            return {
                table_data: {},
                params: {
                    page: 1
                }
            }
        }
    }
</script>

<style scoped>
    table.table {
        width: 100%;
        font-size: 14px;
    }
    table td{
        justify-content: left !important;
    }
    .user-table-foot .glosary {
        font-size: 14px;
    }

    .tag.role {
        color: white;
    }
    .tag.status {
        color: white;
    }
</style>
