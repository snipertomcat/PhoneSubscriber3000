<template>
    <div class="mt-5 ml-120">
        <div class="row no-gutters mr-0 ml-0">
            <div class="col pr-3">
                <div class="">
                    <div class="d-inline-flex has-text-weight-bold full-width" style="height: 40px">
                        <div class="pt-2 mr-3 d-inline-flex license-chart-label">
                            <div class="mr-3 has-text-right" style="width: 210px">Por invitaciones</div>
                            <div class="">{{ registered_users.invitations.value }}</div>
                        </div>
                        <div class="full-width">
                            <el-tooltip :content="parseFloat(registered_users.invitations.percent).toFixed(2)+'%'">
                                <div class="bar" :class="registered_users.invitations.class" :data-width="registered_users.invitations.percent"></div>
                            </el-tooltip>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-inline-flex has-text-weight-bold full-width" style="height: 40px">
                        <div class="pt-2 mr-3 d-inline-flex license-chart-label">
                            <div class="mr-3 has-text-right" style="width: 210px">Por signup</div>
                            <div class="">{{ registered_users.signup.value }}</div>
                        </div>
                        <div class="full-width">
                            <el-tooltip :content="parseFloat(registered_users.signup.percent).toFixed(2)+'%'">
                                <div class="bar" :class="registered_users.signup.class" :data-width="registered_users.signup.percent"></div>
                            </el-tooltip>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-inline-flex has-text-weight-bold full-width" style="height: 40px">
                        <div class="pt-2 mr-3 d-inline-flex license-chart-label">
                            <div class="mr-3 has-text-right" style="width: 210px">Por importación</div>
                            <div class="">{{ registered_users.imported.value }}</div>
                        </div>
                        <div class="full-width">
                            <el-tooltip :content="parseFloat(registered_users.imported.percent).toFixed(2)+'%'">
                                <div class="bar" :class="registered_users.imported.class" :data-width="registered_users.imported.percent"></div>
                            </el-tooltip>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-inline-flex has-text-weight-bold full-width" style="height: 40px">
                        <div class="pt-2 mr-3 d-inline-flex license-chart-label">
                            <div class="has-text-ari-cyan mr-3 has-text-right" style="width: 210px">Registrados</div>
                            <div class="has-text-ari-cyan">{{ registered_users.registered.value }}</div>
                        </div>
                        <div class="full-width">
                            <el-tooltip :content="parseFloat(registered_users.registered.percent).toFixed(2)+'%'">
                                <div class="bar" :class="registered_users.registered.class" :data-width="registered_users.registered.percent"></div>
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
                                {{ amount_of_enrollments }}
                            </div>
                            <div class="mt-4 font-18 has-text-centered">
                                Enrolamientos en el periodo
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mr-0 ml-0 no-gutters mt-5 mb-5">
            <div class="col-auto">
                <div class="row ml-0 mr-0 no-gutters">
                    <div class="col-auto">
                        <span class="font-18 has-text-weight-bold">
                            Top 10 usuarios con mejor promedio de rendimiento
                        </span>
                    </div>
                </div>
                <div class="row ml-0 mr-0 mt-4 no-gutters">
                    <div class="col">
                        <div class="card users-table">
                            <div class="card-content p-3">
                                <el-table :data="top_ten_users.items" stripe>
                                    <el-table-column type="index"></el-table-column>
                                    <el-table-column
                                            prop="email"
                                            label="Usuario">
                                    </el-table-column>
                                    <el-table-column
                                            prop="finished_experiences"
                                            width="300">
                                        <template slot="header" slot-scope="scope">
                                            <div class="full-width has-text-centered">
                                                <span>Experiencias completadas</span>
                                            </div>
                                        </template>
                                        <template slot-scope="scope">
                                            <div class="has-text-centered">
                                                <span>{{ scope.row.finished_experiences }}</span>
                                            </div>
                                        </template>
                                    </el-table-column>
                                    <el-table-column
                                            prop="average"
                                            label="Promedio"
                                            width="100">
                                        <template slot-scope="scope">
                                            <div class="has-text-centered">
                                                <span class="has-text-weight-bold" :class="scope.row.average.class">{{ getPercent(scope.row.average.value) }}</span>
                                            </div>
                                        </template>
                                    </el-table-column>
                                </el-table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { Table, TableColumn, Tooltip } from 'element-ui';
    import { bus } from "../../../../../app_platform";
    import { index } from "../../../../../helpers";

    export default {
        name: "users",
        components: {
            'el-table': Table,
            'el-table-column': TableColumn,
            'el-tooltip': Tooltip
        },
        computed: {
            amount_of_enrollments: function () {
                return this.enrollments.total_amount
            }
        },
        beforeMount () {
            bus.$on('reload', value => {
                if (value === 'users') {
                    this.getData();
                }
            })
        },
        mounted: function () {
            this.getData()
        },
        methods: {
            getPercent (raw, max_value = 1) {
                let percent = 0
                if (raw > 0) {
                    percent = (raw / max_value) * 100
                }
                return percent.toFixed(2)
            },
            getData: function () {
                let loader = index.getLoader()
                let serverResponse = {
                    registered: {
                        value: 50,
                        class: 'ari-cyan',
                    },
                    invitations: {
                        value: 20,
                        class: 'ari-gray',
                    },
                    signup: {
                        value: 20,
                        class: 'ari-gray',
                    },
                    imported: {
                        value: 10,
                        class: 'ari-gray',
                    },
                    enrollments: {
                        total_amount: 75
                    },
                    top_ten_users: {
                        items: []
                    }
                };

                this.loading = true

                axios({
                    method: 'GET',
                    url: route('dashboard.general'),
                    params: {
                        ajax_request: true,
                        give: 'users',
                        time_period: this.$parent.getDatePickerValue()
                    }
                })
                    .then(response => {
                        serverResponse.registered.value = response.data.view.activity.users.registered
                        serverResponse.imported.value = response.data.view.activity.users.imported
                        serverResponse.invitations.value = response.data.view.activity.users.invitations
                        serverResponse.signup.value = response.data.view.activity.users.signup
                        serverResponse.enrollments.total_amount = response.data.view.activity.users.total_amount
                        serverResponse.top_ten_users.items = Object.values(response.data.view.activity.users.top_ten_users)

                        let max_value = serverResponse.registered.value;

                        _.each(serverResponse, (item, key) => {
                            if (key in this.registered_users) {
                                let obj = _.find(this.registered_users, (item, index) => {
                                    return key === index
                                })
                                obj.value = item.value
                                obj.class = item.class
                                obj.percent = this.getPercent(item.value, max_value)
                            }
                            if (key === 'enrollments') {
                                if ('total_amount' in item) {
                                    this.enrollments.total_amount = item.total_amount
                                }
                            }
                            if (key === 'top_ten_users') {
                                if ('items' in item) {
                                    this.top_ten_users.items = item.items
                                }
                            }
                        })

                        setTimeout(() => {
                            this.redraw(loader)
                        }, 1000)
                    })
                    .catch(error => {})

            },
            redraw (loader) {
                let items = document.querySelectorAll('.bar')
                _.each(items, element => {
                    let width = element.dataset.width;
                    element.style.width = width + '%';
                })
                this.loading = false
                loader.close()
            }
        },
        data: function () {
            return {
                loading: true,
                enrollments: {
                    total_amount: 0,
                },
                registered_users: {
                    registered: {
                        value: 0,
                        class: 'ari-cyan',
                    },
                    invitations: {
                        value: 0,
                        class: 'ari-gray',
                    },
                    signup: {
                        value: 0,
                        class: 'ari-gray',
                    },
                    imported: {
                        value: 0,
                        class: 'ari-gray',
                    },
                },
                top_ten_users: {
                    items: []
                }
            }
        },
    }
</script>

<style scoped>
    .card.users-table {
        width: 60vw;
    }
</style>