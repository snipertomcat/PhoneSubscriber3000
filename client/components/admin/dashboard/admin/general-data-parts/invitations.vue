<template>
    <div class="mt-5">
        <div class="row no-gutters mr-0 ml-0">
            <div class="col pr-3 ml-120">
                <div class="">
                    <div class="d-inline-flex has-text-weight-bold full-width" style="height: 40px">
                        <div class="pt-2 mr-3 d-inline-flex license-chart-label">
                            <div class="mr-3 has-text-right" style="width: 120px">Aceptadas</div>
                            <div class="">{{ invitations.accepted.value }}</div>
                        </div>
                        <div class="full-width">
                            <el-tooltip :content="parseFloat(invitations.accepted.percent).toFixed(2)+'%'">
                                <div class="bar" :class="invitations.accepted.class" :data-width="invitations.accepted.percent"></div>
                            </el-tooltip>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-inline-flex has-text-weight-bold full-width" style="height: 40px">
                        <div class="pt-2 mr-3 d-inline-flex license-chart-label">
                            <div class="has-text-ari-pink mr-3 has-text-right" style="width: 120px">Pendientes</div>
                            <div class="has-text-ari-pink">{{ invitations.waiting.value }}</div>
                        </div>
                        <div class="full-width">
                            <el-tooltip :content="parseFloat(invitations.waiting.percent).toFixed(2)+'%'">
                                <div class="bar" :class="invitations.waiting.class" :data-width="invitations.waiting.percent"></div>
                            </el-tooltip>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-inline-flex has-text-weight-bold full-width" style="height: 40px">
                        <div class="pt-2 mr-3 d-inline-flex license-chart-label">
                            <div class="has-text-ari-cyan mr-3 has-text-right" style="width: 120px">Enviadas</div>
                            <div class="has-text-ari-cyan">{{ invitations.sends.value }}</div>
                        </div>
                        <div class="full-width">
                            <el-tooltip :content="parseFloat(invitations.sends.percent).toFixed(2)+'%'">
                                <div class="bar" :class="invitations.sends.class" :data-width="invitations.sends.percent"></div>
                            </el-tooltip>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-auto">
                <div class="card information">
                    <div class="card-content">
                        <div class="mb-4">
                            <div class="font-50 has-text-centered has-text-weight-bold has-text-ari-cyan">
                                {{ average_of_invitations }}%
                            </div>
                            <div class="mt-4 font-18 has-text-centered">
                                Conversión de invitaciones
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row no-gutters mr-0 ml-0 mt-5 mb-5">
            <div class="col ml-3">
                <div class="row no-gutters ml-0 mr-0">
                    <div class="col-auto">
                        <el-input placeholder="Buscar usuarios" v-model="invitations.list.search" @keydown.enter.native="searchUser">
                            <i slot="prefix" class="el-input__icon fas fa-search font-16"></i>
                            <i slot="suffix" class="el-input__icon fas fa-times font-16 pointer" v-if="!is_filter_empty" @click="clearFilter"></i>
                        </el-input>
                    </div>
                </div>
                <div class="row no-gutters ml-0 mr-0 mt-3">
                    <div class="col">
                        <div class="card invitations-list">
                            <div class="card-content p-3">
                                <b-table :data="invitations.list.items"
                                         :striped="true"
                                         :mobile-cards="true">
                                    <template slot-scope="props">
                                        <b-table-column field="created_at" label="Fecha de envio" width="160" sortable>
                                            {{ props.row.created_at }}
                                        </b-table-column>

                                        <b-table-column field="email" label="Usuario" width="180" sortable>
                                            {{ props.row.contact }}
                                        </b-table-column>

                                        <b-table-column field="experience.title" label="Experiencia" width="240" sortable>
                                            {{ (props.row.experience) ? props.row.experience.title : '' }}
                                        </b-table-column>

                                        <b-table-column field="company" label="Empresa" width="100">
                                            {{ props.row.company }}
                                        </b-table-column>

                                        <b-table-column field="role" label="Rol" sortable>
                                            <span :class="`tag role has-text-white ${props.row.role_css}`">
                                                {{props.row.role }}
                                            </span>
                                        </b-table-column>

                                        <b-table-column field="status_name" label="Estado" sortable>
                                            <span :class="`tag status has-text-white ${props.row.status_css}`">
                                                {{ props.row.status_name }}
                                            </span>
                                        </b-table-column>

                                        <b-table-column field="options" label="Acciones" width="100">
                                            <div class="has-text-centered" v-if="props.row.status === 0" @click="resendInvitation(props.row)">
                                                <span class="font-20 invitation-resend pointer has-text-link">
                                                    <i class="fa fa-paper-plane"></i>
                                                </span>
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
                </div>
                <div class="row mr-0 ml-0 no-gutters mt-3" v-if="table_has_data">
                    <div class="col-12 col-md-4 glosary">
                        <div class="row">
                            <div class="col-auto">
                                <span style="color:#BEBEBE;"><i class="fa fa-paper-plane"></i> Reenviar</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 justify-content-between has-text-centered">
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <el-pagination
                                    @current-change="onPageChange"
                                    :hide-on-single-page="false"
                                    :current-page.sync="invitations.list.current_page"
                                    layout="prev, pager, next"
                                    :page-count="invitations.list.last_page">
                                </el-pagination>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 has-text-centered">
                        <div class="row justify-content-end mr-0 ml-0 no-gutters">
                            <div class="col-auto">
                                <b-select v-model="invitations.list.per_page" @input="setPageSize">
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
        </div>
    </div>
</template>

<script>
    import { Input, Table, TableColumn, Tooltip, Pagination } from 'element-ui';
    import { bus } from "../../../../../app_platform";
    import { index } from "../../../../../helpers";

    export default {
        name: "invitations",
        components: {
            'el-input': Input,
            'el-table': Table,
            'el-table-column': TableColumn,
            'el-tooltip': Tooltip,
            'el-pagination': Pagination
        },
        beforeMount () {
            bus.$on('reload', value => {
                if (value === 'invitations') {
                    this.getData();
                }
            })
        },
        mounted: function () {
            this.getData()
        },
        computed: {
            table_has_data: function () {
                return !_.isEmpty(this.invitations.list.items)
            },
            average_of_invitations: function () {
                let aux = (this.invitations.sends.value > 0) ? (this.invitations.accepted.value / this.invitations.sends.value) * 100 : 0
                return parseFloat(aux).toFixed(1)
            },
            is_filter_empty: function () {
                return _.isEmpty(this.invitations.list.search)
            }
        },
        methods: {
            registrationAccess(row) {
                return row.email;
            },
            clearFilter: function () {
                this.invitations.list.search = ''
                this.invitations.list.current_page = 1
                this.send()
            },
            send: function () {
                axios({
                    url: route('invitations.index'),
                    method: 'GET',
                    params: {
                        search: this.invitations.list.search,
                        load: ['experience'],
                        page: this.invitations.list.current_page,
                        per_page: this.invitations.list.per_page,
                        time_period: this.$parent.getDatePickerValue()
                    }
                })
                    .then((response) => {
                        let aux = this.setColors(response.data.data);
                        aux = this.setStatus(aux);

                        this.invitations.list.items = aux;
                        this.invitations.list.current_page = response.data.current_page;
                        this.invitations.list.last_page = response.data.last_page;
                        this.invitations.list.per_page = response.data.per_page;
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },
            resendInvitation (invitation) {
                if(invitation.status !==0){
                    return false;
                }

                let id = invitation.id;
                this.$snotify.async('Invitación', 'Procesando Invitación', () => new Promise((resolve, reject) => {
                    return axios({
                        method: 'GET',
                        url: `/users/invitations/resend/${id}`,
                    }).then((response) => {
                        resolve({
                            title: 'Petición exitosa',
                            body: 'Petición procesada con éxito.',
                            config: {
                                closeOnClick: true,
                                timeout: 3000,
                                html: `
                                    <div class="snotifyToast__title">Invitaciones</div>
                                    <div class="snotifyToast__body">Invitación reenviada con éxito.</div>
                                    <div class="snotify-icon snotify-icon--success"></div>
                                    `
                            }
                        });
                    }).catch((error) => {
                        console.error(error)
                        reject({
                            title: 'Error!!',
                            body: 'No se pudo procesar la petición correctamente.',
                            config: {
                                closeOnClick: true,
                                timeout: 3000,
                                html: `
                                     <div class="snotifyToast__title">Invitaciones</div>
                                    <div class="snotifyToast__body">No se pudo reenviar la Invitación.</div>
                                    <div class="snotify-icon snotify-icon--error"></div>
                                    `
                            }
                        });
                    })

                }), {backdrop: 0.6});
                this.getData()
            },
            getPercent: function (raw, max_value) {
                if (raw > 0) {
                    return (raw / max_value) * 100
                } else {
                    return 0
                }
            },
            getData: function () {
                let serverResponse = {
                    sends: {
                        value: 50,
                        class: 'ari-cyan',
                    },
                    accepted: {
                        value: 20,
                        class: 'ari-gray',
                    },
                    waiting: {
                        value: 30,
                        class: 'ari-pink',
                    },
                };

                this.loading = true
                let loader = index.getLoader()

                axios({
                    method: 'GET',
                    url: route('dashboard.general'),
                    params: {
                        ajax_request: true,
                        give: 'invitations',
                        time_period: this.$parent.getDatePickerValue()
                    }
                })
                    .then(response => {
                        serverResponse.sends.value = response.data.view.activity.invitations.sends
                        serverResponse.accepted.value = response.data.view.activity.invitations.accepted
                        serverResponse.waiting.value = response.data.view.activity.invitations.waiting

                        let max_value = serverResponse.sends.value;

                        _.each(serverResponse, (item, key) => {
                            if (key in this.invitations) {
                                let obj = _.find(this.invitations, (item, index) => {
                                    return key === index
                                })
                                obj.value = item.value
                                obj.class = item.class
                                obj.percent = this.getPercent(item.value, max_value)
                            } else if (key === 'items') {
                                this.invitations.list.items = item
                            }
                        })

                        // Set list of items
                        this.send();

                        setTimeout(() => {
                            this.redraw(loader)
                        }, 1000)
                    })
                    .catch(error => {
                        console.log(error)
                    })

            },
            redraw: function (loader) {
                let items = document.querySelectorAll('.bar')
                _.each(items, element => {
                    let width = element.dataset.width;
                    element.style.width = width + '%';
                })
                this.loading = false
                loader.close()
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
                        item.role = {class: 'is-danger', full_name: 'Sin rol'};
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
            searchUser () {
                this.invitations.list.current_page = 1
                this.send()
            },
            setPageSize (size) {
                this.invitations.list.current_page = 1
                this.send()
            },
            onPageChange (page) {
                this.send()
            }
        },
        data: function () {
            return {
                loading: true,
                invitations: {
                    sends: {
                        value: 0,
                        class: 'ari-cyan',
                    },
                    accepted: {
                        value: 0,
                        class: 'ari-gray',
                    },
                    waiting: {
                        value: 0,
                        class: 'ari-pink',
                    },
                    list: {
                        search: '',
                        items: [],
                        current_page: 1,
                        last_page: 1,
                        per_page: 15,

                    }
                },
            }
        },
    }
</script>

<style scoped>
    .text-shorted {
        width: 100px;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }
    .card.invitations-list {
        box-shadow: 0px 2px 6px rgba(161, 161, 161, 0.5);
        overflow-x: auto;
    }
</style>

<style>
    .el-input .el-input__inner {
        height: 32px;
        line-height: 32px;
    }
    .el-input .el-input__icon {
        line-height: 32px;
    }
    .el-input.is-active .el-input__inner, .el-input__inner:focus {
        border-color: #FFA81E;
    }
</style>
