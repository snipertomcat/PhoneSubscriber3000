<template>
    <div class="ml-lg-4 mr-lg-4 users-invitations">
        <div class="row mt-3 mr-0 ml-0 no-gutters">
            <div class="col-12 col-md-9">
                <div class="row">
                    <div class="col-12 col-md-3 mt-3 mt-md-0">
                        <b-field :type="{'is-danger':errors.has('name')}" :message="errors.first('name')" label="Nombre">
                            <b-input name="name"
                                     id="user"
                                     icon="user"
                                     icon-pack="fas"
                                     :placeholder="$t('messages.name')"
                                     v-model="form.name">
                            </b-input>
                        </b-field>
                    </div>
                    <div class="col-12 col-md-3 mt-3 mt-md-0">
                        <b-field :type="{'is-danger':errors.has('surname')}" :message="errors.first('surname')" label="Apellido">
                            <b-input name="surname"
                                     id="surname"
                                     icon="user"
                                     icon-pack="fas"
                                     :placeholder="$t('messages.surname')"
                                     v-model="form.surname">
                            </b-input>
                        </b-field>
                    </div>
                    <div class="col-12 col-md-3 mt-3 mt-md-0">
                        <b-field :type="{'is-danger':errors.has('phone')}" :message="errors.first('phone')" label="Teléfono">
                            <b-input name="phone"
                                     id="phone"
                                     icon="phone"
                                     icon-pack="fas"
                                     :placeholder="$t('messages.phone')"
                                     v-model="form.phone">
                            </b-input>
                        </b-field>
                    </div>
                    <div class="col-12 col-md-3 mt-3 mt-md-0">
                        <b-field :type="{'is-danger':errors.has('email')}" :message="errors.first('email')" label="Email">
                            <b-input name="email"
                                     id="email"
                                     icon="envelope"
                                     icon-pack="fas"
                                     :placeholder="$t('messages.email')"
                                     v-model="form.email">
                            </b-input>
                        </b-field>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3 pl-md-4 mt-4 mt-md-0 align-self-end">
                <div class="font-14">
                    <button class="button send is-link width-100"
                            @click="sendInvitation">
                        <span class="icon is-small"><i class="icon-send"></i></span>
                        <span class="has-text-weight-bold">{{ $t('messages.send_invitation') }}</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="invitation-list">
            <!-- tabla -->
            <div class="">
                <span class="font-20 has-text-weight-bold">
                    {{ 'Invitaciones enviadas' }}
                </span>
            </div>
            <div class="mt-3">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <b-field>
                            <b-input v-model.trim="params.search"
                                     icon="search"
                                     icon-pack="fas"
                                     expanded
                                     placeholder="Escribe tu búsqueda y pulsa enter"
                                     @keypress.enter.native="filter">
                            </b-input>
                        </b-field>
                        <span class="pointer" style="position: absolute; padding: 4px 8px; top: 0; right: 16px; z-index: 2;" v-if="!!params.search" @click="clearFilter">
                            <i class="fa fa-times"></i>
                        </span>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <b-table :data="table.data.data"
                                 :striped="true"
                                 :mobile-cards="true">
                            <template slot-scope="props">
                                <b-table-column field="full_name" label="Nombre" sortable>
                                    <span class="">
                                        {{ props.row.full_name }}
                                    </span>
                                </b-table-column>

                                <b-table-column field="email" label="Correo electrónico" sortable>
                                    <span class="">
                                        {{ (!!props.row.email) ? props.row.email : 'No capturado' }}
                                    </span>
                                </b-table-column>

                                <b-table-column field="phone" label="Teléfono" sortable centered>
                                    <span class="">
                                        {{ (!!props.row.phone) ? props.row.phone : 'No capturado'}}
                                    </span>
                                </b-table-column>

                                <b-table-column field="created_at" label="Invitación creada" sortable centered>
                                    <span class="">
                                        {{ new Date(props.row.created_at).toLocaleDateString() }}
                                    </span>
                                </b-table-column>

                                <b-table-column field="last_session" label="Última conexión" sortable centered>
                                    <span class="">
                                        {{ last_login(props.row.logins) ? new Date(last_login(props.row.logins).created_at).toLocaleDateString() : "Sin sesiones" }}
                                    </span>
                                </b-table-column>

                                <b-table-column field="options" label="Opciones" width="200" centered>
                                    <span class="font-20 invitation-resend pointer" @click="resend(props.row)">
                                        <i class="fa fa-paper-plane"></i>
                                    </span>
                                </b-table-column>
                            </template>
                            <!-- empty table -->
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
                <div class="col-12 d-block"><br></div>
                <!-- info paginador -->
                <div class="row">
                    <div class="col-12 col-md-4 glosary" v-if="table_has_data">
                        <div class="row">
                            <div class="col-auto">
                                <span><i class="fa fa-paper-plane mr-2"></i>Reenviar</span>
                            </div>
                        </div>
                    </div>
                    <template v-if="table_has_data && params.last_page > 1">
                        <div class="col-12 col-lg-4">
                            <div class="row ml-0 mr-0 no-gutters justify-content-center">
                                <div class="col-lg-12 text-center">
                                    <el-pagination
                                        @current-change="onPageChange"
                                        :hide-on-single-page="false"
                                        :current-page.sync="params.current_page"
                                        layout="prev, pager, next"
                                        :page-count="params.last_page">
                                    </el-pagination>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4 text-right">
                            <div class="row ml-0 mr-0 no-gutters justify-content-end">
                                <div class="col-auto">
                                    <b-select v-model="params.per_page" @input="handleSizeChange">
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
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { Pagination } from 'element-ui'
    import { index } from "../../../helpers";
    const emailRE = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    export default {
        name: "DemoUsersInvitations",
        components: {
            'el-pagination': Pagination
        },
        props: {
            roles: {
                type: Array,
                required: true
            },
            companies: {
                type: Array,
                required: false
            },
            authUser: {
                type: Object,
                required: true
            }
        },
        mounted () {
            this.$validator.extend('verify_email_exist', this.getAsyncValidatorConfig("email"));

            this.role = _.head(this.roles)['id'];
            this.company = _.head(this.companies)['id']
            this.form.company_id = _.head(this.companies)['id']
            this.getInvitationsData();
        },
        computed: {
            enable () {
                return this.email && emailRE.test(this.email);
            },
            table_has_data() {
                return (this.table.data.total);
            },
            sendIconColor(item){
                if(item.status==1){
                    return 'disabled'
                }
            },
        },
        methods: {
            last_login (logins) { return _.last(logins)},
            toggleHelper () {
                this.show_helper = !this.show_helper
            },
            setColors(array) {
                array.forEach(item => {
                    if(item.role) {
                        switch (item.role.code) {
                            case "SA":
                                item.role.class = 'apithy-color-super-admin';
                                break;
                            case "SP":
                                item.role.class = 'apithy-color-support';
                                break;
                            case "AD":
                                item.role.class = 'apithy-color-admin';
                                break;
                            case "SUP":
                                item.role.class = 'apithy-color-supervisor';
                                break;
                            case "GJ":
                                item.role.class = 'apithy-color-gest-jerq';
                                break;
                            case "PC":
                                item.role.class = 'apithy-color-prov-content';
                                break;
                            case "AU":
                                item.role.class = 'apithy-color-author';
                                break;
                            case "ER":
                                item.role.class = 'apithy-color-enroller';
                                break;
                            case "STU":
                                item.role.class = 'apithy-color-student';
                                break;
                            default:
                                break;
                        }
                    }
                    else {
                        item.role = {class: 'is-danger', full_name: item.name};
                    }
                });

                return array;
            },
            setStatus(array) {
                array.forEach(item => {
                    switch (item.status) {
                        case 0:
                            item.class = 'apithy-color-status-inactive';
                            break;
                        case 1:
                            item.class = 'apithy-color-status-active';
                            break;
                        default:
                            item.role.class = 'is-danger';
                            break;
                    }
                });

                return array;
            },
            add () {
                if (!this.enable) return;
            },
            markToDelete (id) {
                this.toDelete = id;
            },
            resend (invitation) {

                if(invitation.status !==0){
                    return false;
                }

                let id=invitation.id;
                let vm = this;
                this.$snotify.async('Invitación', 'Procesando Invitación', () => new Promise((resolve, reject) => {
                    return axios({
                        method: 'GET',
                        url: `/users/invitations/resend/${id}`,
                    }).then((response) => {
                        vm.loading = false;
                        this.getInvitationsData();
                        resolve({
                            title: 'Petición exitosa',
                            body: 'Petición procesada con éxito.',
                            config: {
                                closeOnClick: true,
                                backdrop: 0.6,
                                timeout: 2000,
                                html: `
                                        <div class="snotifyToast__title">Invitaciones</div>
                                        <div class="snotifyToast__body">Invitación reenviada con éxito.</div>
                                        <div class="snotify-icon snotify-icon--success"></div>
                                        `
                            }
                        });
                    }).catch((error) => {
                        vm.loading = false;
                        reject({
                            title: 'Error!!',
                            body: 'No se pudo procesar la petición correctamente.',
                            config: {
                                closeOnClick: true,
                                backdrop: 0.6,
                                timeout: 2000,
                                html: `
                                         <div class="snotifyToast__title">Invitaciones</div>
                                        <div class="snotifyToast__body">No se pudo reenviar la Invitación.</div>
                                        <div class="snotify-icon snotify-icon--error"></div>
                                        `
                            }
                        });
                    });

                }), {backdrop: 0.6});
            },
            sendInvitation() {
                let vm = this;
                this.$snotify.async('Invitación', 'Procesando Invitación', () => new Promise((resolve, reject) => {
                    return axios({
                        method: 'POST',
                        url: '/users/create-demo-users',
                        data: {
                            users: [this.form]
                        },
                    }).then((response) => {
                        vm.loading = false;
                        this.getInvitationsData();
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
                    }).catch((error) => {
                        vm.loading = false;
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
                    });

                }), {backdrop: 0.6});
            },
            getAsyncValidatorConfig(field_name, db_field_name = null) {
                return {
                    getMessage: (field, params, data) => data.message,
                    validate: value => {
                        db_field_name = (db_field_name !== null) ? db_field_name : field_name;
                        return axios({
                            method: 'post',
                            url: route('validate_fields_form'),
                            params: {
                                [db_field_name]: value
                            }
                        }).then(response => {
                            return {
                                valid: true
                            }
                        }).catch(error => {
                            return {
                                valid: false,
                                data: {
                                    message: error.response.data.errors[db_field_name][0]
                                }
                            }
                        });
                    }
                }
            },
            getInvitationsData() {
                let loader = index.getLoader()
                let params = this.params
                //params.filter.registration_method = ['demo_invitations'];
                //params.filter['registration_method'] = 'demo_invitations'
                console.log(params)
                axios({
                    method:'GET',
                    url: route('users.index'),
                    params: params
                })
                    .then(response => {

                        //response.data.data = this.setColors(response.data.data);
                        //response.data.data = this.setStatus(response.data.data);
                        this.table.data = response.data.users;
                        this.params.page = _.get(response, ['data', 'current_page'], 1)
                        this.params.last_page = _.get(response, ['data', 'last_page'], 1)

                    })
                    .catch(error => {
                        console.log(error);
                    })
                    .then(() => {
                        loader.close()
                    })
            },
            onPageChange(page) {
                if (page !== this.params.page) {
                    this.params.page = page;
                    this.getInvitationsData();
                }
            },
            handleSizeChange (size) {
                this.params.page = 1
                this.params.per_page = size
                this.getInvitationsData()
            },
            prev() {
                if(this.table.data.current_page >= 1) {
                    this.params.page = this.table.data.current_page - 1;
                    this.getInvitationsData();
                }
            },
            next() {
                if(this.table.data.current_page < this.table.data.last_page) {
                    this.params.page = this.table.data.current_page + 1;
                    this.getInvitationsData();
                }
            },
            refresh() {
                this.params.page = 1;
                this.getInvitationsData();
            },
            deleteInvitation(id) {
                this.$snotify.confirm('Eliminando', '¿Desea eliminar está invitación?', {
                    showProgressBar: true,
                    closeOnClick: false,
                    pauseOnHover: true,
                    backdrop: 0.6,
                    buttons: [
                        {
                            text: 'Si', action: (toast) => {
                                let loader = index.getLoader({text: 'Procesando petición'})
                                axios({
                                    method: 'DELETE',
                                    url: route('invitations.destroy',[id])
                                })
                                    .then(response => {
                                        this.$snotify.success('', 'Invitación eliminada con éxito', {
                                            closeOnClick: true,
                                            backdrop: 0.6,
                                            timeout: 2000
                                        })
                                        this.refresh();
                                    })
                                    .catch(error => {
                                        this.$snotify.error('', 'Hubo un problema al eliminar la invitación', {
                                            closeOnClick: true,
                                            backdrop: 0.6,
                                            timeout: 2000
                                        })
                                    })
                                    .then(() => {
                                        this.$snotify.remove(toast.id)
                                        loader.close()
                                    })
                            }
                        },
                        {
                            text: 'No', action: (toast) => {this.$snotify.remove(toast.id)}
                        },
                    ]
                })
            },
            getAllowedRoles() {
                let roles = [];

                if (this.authUser.is_admin) {
                    _.each(this.roles, role => {
                        if (role.id === 9 || role.id === 7) {
                            roles.push(role);
                        }
                    });
                } else {
                    roles = this.roles;
                }
                return roles;
            },
            clearFilter () {
                this.params.search = null
                this.filter()
            },
            filter(event) {
                this.params.search = _.trim(this.params.search)
                this.params.page = 1
                this.getInvitationsData()
            }
        },
        data () {
            return {
                show_helper: false,
                form: {
                    role_id: 7,
                    company_id: this.authUser.company_id,
                    email: '',
                    name: '',
                    surname: '',
                    phone: '',
                    contact_preference: 'sms'
                },
                table: {
                    data: {},
                },
                params: {
                    page: 1,
                    last_page: 1,
                    per_page: 15,
                    search: null,
                    by_registration_method: "demo_invitation"
                },
                filter_value: '',
                toDelete: null,
                loadStatus: false,
                loading: true,
                validate: {
                    email: {
                        required: true,
                        email: true,
                        verify_email_exist: true,
                        regex: emailRE
                    },
                    name: {
                        alpha_spaces: true,
                        required: false
                    }
                }
            }
        },
    }
</script>

<style scoped>
    @media only screen and (min-width: 767px) and (max-width: 769px) {
        .users-invitations {
            width: 92% !important;
        }
    }
    .paginator-link {
        color: #106CFB;
    }
    .invitation-delete {
        color: #106CFB;
    }
    .invitation-resend {
        color:#106CFB;
    }
    .invitation-list {
        margin-top: 65px;
    }
    .tag.role {
        color: white;
    }
    .tag.status {
        color: white;
    }
    .helper .message {
        padding: 16px;
        background-color: #EAF2FF;
    }
    .helper .trigger span {
        padding-left: 20px;
        padding-right: 20px;
        cursor: pointer;
        color: #1A6BF7;
    }
    .send-disabled{
        background-color: gray !important;
    }
    .disabled{
        color: gray;
    }
</style>
