<template>
    <div class="users-module">
        <div class="row mr-0 ml-0">
            <div class="col-auto pl-0 pr-0 font-20 has-text-weight-bold">{{ $t('messages.users_list') }}</div>
        </div>
        <div class="row users-filter mr-0 ml-0 no-gutters">
            <div class="col-12">
                <user-filter :roles="roles" :statuses="statuses" :taxonomy="taxonomy"></user-filter>
            </div>
        </div>
        <div class="row users-list mr-0 ml-0 no-gutters">
            <div class="col">
                <b-table :data="users.data"
                         :show-detail-icon="false"
                         v-if="users_table_has_data"
                         ref="users_table"
                         detailed>
                    <template slot-scope="props">
                        <b-table-column field="full_name" :label="$t('messages.name')" width="200">{{props.row.full_name}}</b-table-column>
                        <b-table-column field="email" :label="$t('messages.email')" width="250">{{props.row.email}}</b-table-column>
                        <b-table-column field="phone" :label="$t('messages.phone')" width="180">{{props.row.phone}}</b-table-column>
                        <b-table-column field="roles" :label="$t('messages.role')" width="200">
                            <span class="tag has-text-white" :class="props.row.roles[0].class">{{getRole(props.row.roles)}}</span>
                        </b-table-column>
                        <b-table-column field="taxonomy" :label="$t('messages.tags')" width="150">
                            <span class="tags-counter button is-white width-50" @click="toggleDetails(props.row)">
                                {{props.row.taxonomy.length}}<i class="icon-eye font-20 has-text-link ml-3"></i>
                            </span>
                        </b-table-column>
                        <b-table-column field="status" :label="$t('messages.status')" width="125">{{getStatus(props.row.status)}}</b-table-column>
                        <b-table-column :label="$t('messages.actions')"  width="200">
                            <b-button v-if="props.row.is_super" type="is-link" inverted @click="impersonate(props.row.id)">
                                <i class="icon-user-in font-20"></i>
                            </b-button>
                            <b-button type="is-link" inverted @click="editUser(props.row.id)">
                                <i class="icon-user-edit font-20"></i>
                            </b-button>
                            <b-button type="is-link"
                                      inverted
                                      :disabled="!canBeDeleted(props.row)"
                                      @click="deleteUserDialog(props.row)">
                                <i class="icon-trash font-20"></i>
                            </b-button>
                        </b-table-column>
                    </template>
                    <template slot="detail" slot-scope="props">
                        <tr>
                            <td width="200" class="hidde-on-mobile"></td>
                            <td width="250" class="hidde-on-mobile"></td>
                            <td width="180" class="hidde-on-mobile"></td>
                            <td>
                                <div class="tags-container">
                                    <span class="tag font-18 mt-2 mb-2 mr-3" v-for="tag in props.row.taxonomy" :style="'background-color:'+tag.color">
                                        {{tag.title}}
                                    </span>
                                </div>
                            </td>
                        </tr>
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
                <b-table :data="[]" v-else>
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
        <div class="row paginator mb-5 mr-0 ml-0 no-gutters" v-if="users_table_has_data">
            <div class="col-12 col-lg-4">
                <div class="row ml-0 mr-0 no-gutters legend">
                    <div class="col-4">
                        <span><i class="icon-eye mr-2"></i>{{$t('messages.show')}}</span>
                    </div>
                    <div class="col-4">
                        <span><i class="icon-user-in mr-2"></i>Personificar</span>
                    </div>
                    <div class="col-4">
                        <span><i class="icon-user-edit mr-2"></i>{{$t('messages.edit')}}</span>
                    </div>
                    <div class="col-4">
                        <span><i class="icon-trash mr-2"></i>{{$t('messages.delete')}}</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="row ml-0 mr-0 no-gutters justify-content-center">
                    <div class="col-lg-12 text-center">
                        <el-pagination
                            @current-change="onPageChange"
                            :hide-on-single-page="false"
                            :current-page.sync="users.current_page"
                            layout="prev, pager, next"
                            :page-count="users.last_page">
                        </el-pagination>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4 text-right">
                <div class="row ml-0 mr-0 no-gutters justify-content-end">
                    <div class="col-auto">
                        <b-select v-model="users.per_page" @input="setPageSize">
                            <option value="10">
                                Ver 10
                            </option>
                            <option value="15">
                                Ver 15
                            </option>
                            <option value="20">
                                Ver 20
                            </option>
                            <option value="30">
                                Ver 30
                            </option>
                            <option value="50">
                                Ver 50
                            </option>
                        </b-select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { Pagination } from 'element-ui'
    import UserFilter from './OwnerUsersFilter.vue';
    import { index } from "../../../helpers";

    export default {
        name: "OwnerUsersModule",
        components: {
            'user-filter': UserFilter,
            'el-pagination': Pagination
        },
        props: {
            taxonomy: {
                default () { return [] }
            },
            statuses: {
                type: Array,
                default () { return [] }
            },
            roles: {
                type: Array,
                default () { return [] }
            },
            authUser: {
                type: Object,
                default: null
            }
        },
        computed: {
            users_table_has_data: function () {
                return !_.isEmpty(this.users.data)
            }
        },
        data () {
            return {
                users: {
                    data: null,
                    per_page: 15
                },
            }
        },
        created () {
            this.loadData();
        },
        methods: {
            impersonate (id) {
                window.location.href = route('impersonate', {id: id})
            },
            editUser (id) {
                window.location.href = route('users.edit', {user: id})
            },
            deleteUserDialog(row){
                this.$snotify.confirm(`¿Estas seguro de eliminar el usuario ${row.full_name}?`,'¡Cuidado!', {
                    showProgressBar: true,
                    closeOnClick: false,
                    buttons: [
                        {text: 'Si', action: (toast) => this.deleteUser(row.id,toast), bold: false},
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
                        let index = _.findIndex(this.users.data, {id: id});
                        this.$delete(this.users.data, index);
                        this.$snotify.success('Usuario eliminado', 'Usuarios', {
                            closeOnClick: true,
                            timeout: 2000
                        });
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            canBeDeleted (user) {
                let role_owner_id = ('ROLE_OWNER' in this.apithy_constants) ? this.apithy_constants.ROLE_OWNER : 0;
                let auth = (!_.isEmpty(this.authUser)) ? this.authUser : this.$root.$refs.adminNav.user;
                let is_owner = !_.isEmpty(_.find(user.roles, {id: role_owner_id}));

                if (auth.id === user.id) {
                    return false
                }

                if (is_owner) {
                    return false
                }
                return true
            },
            toggleDetails (row) {
                if (!_.isEmpty(row.taxonomy)) {
                    this.$refs.users_table.toggleDetails(row)
                }
            },
            getStatus (status = null) {
                let statuses = [
                  {label:'Inactivo', value: 0},
                  {label:'Activo', value: 1},
                  {label:'Estableciendo contraseña', value: 4},
                  {label:'Contraseña establecida', value: 5}
                  ];
                status = _.find(statuses, {value: status});
                return (!_.isEmpty(status)) ? status.label : ''
            },
            getRole (array = []) {
                return (!_.isEmpty(array)) ? _.head(array).name : ''
            },
            loadData (page = 1, params = {}) {
                params.ajax_request = 'true';
                params.page = page;
                params.per_page = this.users.per_page;
                let loader = index.getLoader()
                axios({
                    method: 'get',
                    url: route('users.index'),
                    params: params
                })
                    .then(response => {
                        this.users = response.data.users
                        this.users.data = this.setColors(this.users.data)
                    })
                    .catch(error => {console.log(error)})
                    .then(() => {
                        loader.close()
                    })
            },
            setColors(array) {
                _.each(array, item => {
                    if (!_.isEmpty(item.roles)) {
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
                        let roles = [];
                        roles.push({
                            name: 'Sin rol',
                            class:'apithy-color-gray',
                        })

                        item.roles = roles;
                    }
                });

                return array;
            },
            setPageSize (page_size) {
                this.users.per_page = page_size;
                this.users.current_page = 1
                this.loadData()
            },
            onPageChange (page) {
                this.users.current_page = page
                this.loadData(page)
            },
        }
    }
</script>

<style scoped>
    .content {
        margin-left: 40px;
        margin-right: 40px;
    }
    .users-filter {
        margin-top: 28px;
    }
    .users-list {
        margin-top: 45px;
    }
    .users-list .tags-counter {
        display: flex;
    }
    .users-list .tags-counter i {
        padding-top: 2px;
    }
    .paginator {
        margin-top: 22px;
    }
    .paginator .legend {
        color: #BEBEBE;
    }
    @media only screen and (min-width: 767px) and (max-width: 769px) {
        .users-module {
            width: 92% !important;
        }
        .hidde-on-mobile {
            display: none !important;
        }
    }
</style>
<style>
    .b-table .table-wrapper thead th {
        border-bottom: none !important;
    }
    .b-table .detail td {
        padding: 0.5rem 0;
    }
    .table-wrapper .detail .detail-container {
        padding: 0 1rem !important;
    }
</style>
