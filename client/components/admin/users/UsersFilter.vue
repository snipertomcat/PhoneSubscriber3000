<template>
    <div class="container block-centered row">
        <div class="users-filter col-12">
            <hr width="2">
            <div class="row">
                <div class="col-12">
                    <span class="has-text-weight-bold">
                        Filtrar por
                    </span>
                </div>
            </div>
            <br>
            <div class="control row">
                <div class="col-12 col-lg-auto">
                    <div class="select width-100">
                        <select class="width-100" v-model="filter.roleIn.cstm">
                            <option :value="''" selected="">Todos los roles</option>
                            <option :value="role.id" v-for="role in getAllowedRoles()">{{ role.name }}</option>
                        </select>
                    </div>
                </div>
                <div class="d-block d-md-none"><br><br></div>
                <div class="col-12 col-lg-auto width-100" v-if="auth_user.is_super && filterByCompany">
                    <div class="select width-100">
                        <select class="width-100" v-model="filter.company_id">
                            <option :value="''">Todas las empresas</option>
                            <option :value="company.id" v-for="company in companies">{{ company.name }}</option>
                        </select>
                    </div>
                </div>
                <div class="d-block d-md-none"><br><br></div>
                <div class="col-12 col-lg-auto width-100">
                    <div class="select width-100">
                        <select class="width-100" v-model="filter.status">
                            <option :value="''">Todos los estados</option>
                            <option :value="status.value" v-for="status in statuses">{{ $t('messages.'+status.label) }}</option>
                        </select>
                    </div>
                </div>
                <div class="d-block d-md-none"><br><br></div>
                <div class="col-12 col-lg-4 width-100">
                    <p class="control has-icons-left has-icons-right">
                        <input type="text"
                               class="input width-100"
                               v-model="search"
                               placeholder="search"
                               @keydown.enter="send">
                        <span class="icon is-small is-left">
                            <i class="fas fa-search"></i>
                        </span>
                    </p>
                </div>
                <div class="d-block d-md-none"><br><br></div>
                <div class="col-1">
                    <button @click="send" class="button is-link">
                        <i class="fa fa-filter"></i>
                        {{ $t('messages.filter') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import qs from 'qs';
    export default {
        name: "UsersFilter",
        components: {
        },
        props: {
            roles: {
                type: Array,
                required: true
            },
            statuses: {
                type: Array,
                required: true
            },
            companies: {
                type: Array,
                required: false,
                default() {
                    return [];
                }
            },
            url: {
                type: String,
                required: true
            },
            auth_user: {
                type: Object,
                required: true
            },
            filterByCompany: {
                type: Boolean,
                required: false,
                default: false
            }
        },
        mounted() {
        },
        methods: {
            send() {
                axios({
                    url: this.url,
                    method: 'GET',
                    params: {
                        search: this.search,
                        filter: this.filter
                    },
                    paramsSerializer: function(params) {
                        return qs.stringify(params, { encode: false });
                    }
                })
                    .then((response) => {
                        this.$parent.$refs.users_list.setPagination(response.data);
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },
            create() {
                window.location.href = route('users.create');
            },
            getAllowedRoles() {
                let roles = [];

                if (this.auth_user.is_admin) {
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
            setStatus(array) {
                array.forEach(item => {
                    switch (item.status) {
                        case 0:
                            //item.status_class = 'apithy-color-status-unconfirmed';
                            item.status_text = 'Sin confirmar';
                            break;
                        case 1:
                            //item.status_class = 'apithy-color-status-active';
                            item.status_text = 'Activo';
                            break;
                        case 2:
                            //item.status_class = 'apithy-color-status-deleted';
                            item.status_text = 'Eliminado';
                            break;
                        case 3:
                            //item.status_class = 'apithy-color-status-suspended';
                            item.status_text = 'Suspendido';
                            break;
                        default:
                            break;
                    }
                });

                return array;
            },
        },
        data() {
            return {
                filter: {
                    roleIn: {
                        cstm: ''
                    },
                    company_id: '',
                    status: '',
                },
                search: '',
                float_button: {
                    position: 'top-right',
                    bg_color: '#2D7EFC',
                    actions: [
                        {
                            name: 'create',
                            icon: 'person_add',
                            tooltip: 'Nuevo usuario',
                        },
                    ],
                }
            };
        }
    }
</script>

<style scoped>

</style>