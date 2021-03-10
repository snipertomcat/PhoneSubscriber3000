<template>
    <div class="row no-gutters mr-4 ml-0 full-height">
        <div class="col-3 col-lg-2 ml-1">
            <side-menu ref="sideMenu"></side-menu>
        </div>
        <div class="col-9">
            <div class="row ml-0 mr-0 no-gutters justify-content-end">
                <div class="col-auto">
                    <el-datepicker unlink-panels
                                   type="daterange"
                                   align="right"
                                   start-placeholder="Desde"
                                   end-placeholder="Hasta"
                                   format="dd-MM-yy"
                                   value-format="yy-MM-dd"
                                   v-model="date_picker.value"
                                   :picker-options="date_picker.options" @change="reload">
                    </el-datepicker>
                </div>
            </div>
            <template v-if="is_licenses_selected">
                <licenses></licenses>
            </template>
            <template v-else-if="is_users_selected">
                <users></users>
            </template>
            <template v-else-if="is_invitations_selected">
                <invitations></invitations>
            </template>
            <template v-else-if="is_enrollments_selected">
                <enrollments-experiences></enrollments-experiences>
            </template>
        </div>
    </div>
</template>

<script>
    import { bus } from "../../../../app_platform";
    import { DatePicker } from "element-ui";
    import SideMenu from './GeneralSideMenu';
    import Licenses from './general-data-parts/licenses';
    import Users from './general-data-parts/users';
    import Invitations from './general-data-parts/invitations';
    import Enrollment from './general-data-parts/enrollmentsAndExperiences';

    export default {
        name: "AdminGeneralDashboard",
        components: {
            'el-datepicker': DatePicker,
            'side-menu': SideMenu,
            'licenses': Licenses,
            'users': Users,
            'invitations': Invitations,
            'enrollments-experiences': Enrollment,
        },
        computed: {
            is_licenses_selected: function () {
                return this.item_active === 'licenses'
            },
            is_users_selected: function () {
                return this.item_active === 'users'
            },
            is_invitations_selected: function () {
                return this.item_active === 'invitations'
            },
            is_enrollments_selected: function () {
                return this.item_active === 'enrollments'
            },
        },
        beforeMount: function () {
            bus.$on('item-active', item => {
                this.item_active = item.target
            })

            let date = new Date(Date.now())
            let now = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate()
            let subMonth = () => {
                let days = -30
                let res = date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                res = new Date(res);
                return res.getFullYear() + '-' + (res.getMonth() + 1) + '-' + res.getDate()
            }
            this.date_picker.value = [subMonth(), now]
        },
        methods: {
            getDatePickerValue: function () {
                return this.date_picker.value
            },
            reload: function () {
                bus.$emit('reload', this.item_active)
            }
        },
        data: function () {
            return {
                item_active: '',
                date_picker: {
                    value: ["2019-05-27","2019-06-26"],
                    options: {
                        disabledDate(date) {
                            return date.getTime() > new Date().getTime()
                        },
                        shortcuts: [
                            {
                                text: 'Hoy',
                                onClick(picker) {
                                    const end = new Date();
                                    const start = new Date();
                                    start.setTime(start.getTime());
                                    picker.$emit('pick', [start, end]);
                                }
                            },
                            {
                                text: 'Ayer',
                                onClick(picker) {
                                    const end = new Date();
                                    const start = new Date();
                                    start.setTime(start.getTime() - 3600 * 1000 * 24);
                                    picker.$emit('pick', [start, end]);
                                }
                            },
                            {
                                text: 'Últimos 7 días',
                                onClick(picker) {
                                    const end = new Date();
                                    const start = new Date();
                                    start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
                                    picker.$emit('pick', [start, end]);
                                }
                            },
                            {
                                text: 'Últimos 30 días',
                                onClick(picker) {
                                    const end = new Date();
                                    const start = new Date();
                                    start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
                                    picker.$emit('pick', [start, end]);
                                }
                            },
                        ]
                    },
                }
            }
        }
    }
</script>

<style>
    .card {
        box-shadow: -4px 4px 8px rgba(68, 68, 68, 0.3);
    }
    .card.information {
        min-width: 320px;
        max-width: 320px;
    }
    .has-text-ari-cyan {
        color: #0098FF;
    }
    .license-chart-label {
        min-width: 160px;
    }
    .bar {
        width: 0px;
        height: 40px;
    }
    .bar.ari-cyan {
        background-color: #49B4FC;
    }
    .bar.ari-gray {
        background-color: #B7C5DB;
    }
    .bar.ari-pink {
        background-color: #FCB399;
    }
    .has-text-ari-pink {
        color: #FCB399;
    }
    .has-text-ari-green {
        color: #22BC6B;
    }
    .has-text-ari-yellow {
        color: #FFA81E;
    }
    .has-text-ari-red {
        color: #FF5E63;
    }
    .ml-120 {
        margin-left: 120px;
    }
    a {
        text-decoration: none;
    }
     .el-date-table td {
         text-align: center !important;
     }
    .el-range-editor.is-active {
        border-color: #FFA81E;
    }
    .el-date-table td.start-date span {
        background-color: #FFA81E;
    }
    .el-date-table td.end-date span {
        background-color: #FFA81E;
    }
    .el-date-table td.in-range div {
        background-color: #fff6e8;
    }
    .el-picker-panel__shortcut:hover {
        background-color: #fff6e8;
        color: #FFA81E;
    }
</style>