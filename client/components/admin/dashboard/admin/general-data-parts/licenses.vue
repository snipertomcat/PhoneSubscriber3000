<template>
    <div class="mt-5">
        <div class="row no-gutters mr-0 ml-3">
            <div class="col pr-3">
                <div class="">
                    <div class="d-inline-flex has-text-weight-bold full-width" style="height: 40px">
                        <div class="pt-2 mr-3 d-inline-flex license-chart-label">
                            <div class="mr-3 has-text-right" style="width: 120px">Asignadas</div>
                            <div class="">{{ licenses.assigned.value }}</div>
                        </div>
                        <div class="full-width">
                            <el-tooltip :content="parseFloat(licenses.assigned.percent).toFixed(2)+'%'">
                                <div class="bar" :class="licenses.assigned.class" :data-width="licenses.assigned.percent"></div>
                            </el-tooltip>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-inline-flex has-text-weight-bold full-width" style="height: 40px">
                        <div class="pt-2 mr-3 d-inline-flex license-chart-label">
                            <div class="mr-3 has-text-right" style="width: 120px">Disponibles</div>
                            <div class="">{{ licenses.available.value }}</div>
                        </div>
                        <div class="full-width">
                            <el-tooltip :content="parseFloat(licenses.available.percent).toFixed(2)+'%'">
                                <div class="bar" :class="licenses.available.class" :data-width="licenses.available.percent"></div>
                            </el-tooltip>
                        </div>
                    </div>
                </div>
                <div class="mt-5">
                    <div class="d-inline-flex has-text-weight-bold full-width" style="height: 40px">
                        <div class="pt-2 mr-3 d-inline-flex license-chart-label">
                            <div class="mr-3 has-text-right" style="width: 120px">Activadas</div>
                            <div class="">{{ licenses.active.value }}</div>
                        </div>
                        <div class="full-width">
                            <el-tooltip :content="parseFloat(licenses.active.percent).toFixed(2)+'%'">
                                <div class="bar" :class="licenses.active.class" :data-width="licenses.active.percent"></div>
                            </el-tooltip>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-inline-flex has-text-weight-bold full-width" style="height: 40px">
                        <div class="pt-2 mr-3 d-inline-flex license-chart-label">
                            <div class="mr-3 has-text-right" style="width: 120px">Por activar</div>
                            <div class="">{{ licenses.inactive.value }}</div>
                        </div>
                        <div class="full-width">
                            <el-tooltip :content="parseFloat(licenses.inactive.percent).toFixed(2)+'%'">
                                <div class="bar" :class="licenses.inactive.class" :data-width="licenses.inactive.percent"></div>
                            </el-tooltip>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-inline-flex has-text-weight-bold full-width" style="height: 40px">
                        <div class="pt-2 mr-3 d-inline-flex license-chart-label">
                            <div class="has-text-ari-cyan mr-3 has-text-right" style="width: 120px">Compradas</div>
                            <div class="has-text-ari-cyan">{{ licenses.buyed.value }}</div>
                        </div>
                        <div class="full-width">
                            <el-tooltip :content="licenses.buyed.percent+'%'">
                                <div class="bar" :class="licenses.buyed.class" :data-width="licenses.buyed.percent"></div>
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
                                {{ buyed_available_average }}%
                            </div>
                            <div class="mt-4 font-18 has-text-centered">
                                Conversión
                            </div>
                            <div class="font-18 has-text-centered has-text-weight-bold">
                                compradas/disponibles
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="font-50 has-text-centered has-text-weight-bold has-text-ari-cyan">
                                {{ buyed_assigned_average }}%
                            </div>
                            <div class="mt-4 font-18 has-text-centered">
                                Conversión
                            </div>
                            <div class="font-18 has-text-centered has-text-weight-bold">
                                compradas/asignadas
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mr-0 ml-3 mt-3">
            <div class="col-auto">
                <a :href="route('licenses.index')">
                    <button class="button is-link has-text-weight-bold">
                        Ir al administrador de licencias
                    </button>
                </a>
            </div>
        </div>
    </div>
</template>

<script>
    import { bus } from "../../../../../app_platform";
    import { index } from "../../../../../helpers";
    import { Tooltip } from "element-ui";

    export default {
        name: "licenses",
        components: {
            'el-tooltip': Tooltip
        },
        computed: {
            buyed_available_average: function () {
                let aux = (this.licenses.buyed.value > 0) ? (this.licenses.available.value / this.licenses.buyed.value) * 100 : 0
                return parseFloat(aux).toFixed(2)
            },
            buyed_assigned_average: function () {
                let aux = (this.licenses.buyed.value > 0) ? (this.licenses.assigned.value / this.licenses.buyed.value) * 100 : 0
                return parseFloat(aux).toFixed(2)
            }
        },
        beforeMount () {
            bus.$on('reload', value => {
                if (value === 'licenses') {
                    this.getData();
                }
            })
        },
        mounted () {
            this.getData()
        },
        methods: {
            getPercent (raw, max_value) {
                if (raw > 0) {
                    return (raw / max_value) * 100
                }
            },
            getData () {
                let loader = index.getLoader()
                this.loading = true

                axios({
                    method: 'GET',
                    url: route('dashboard.general'),
                    params: {
                        ajax_request: true,
                        give: 'licenses',
                        time_period: this.$parent.getDatePickerValue()
                    }
                })
                    .then(response => {
                        let serverResponse = response.data.view.activity.licenses

                        let max_value = serverResponse.buyed.value;

                        _.each(serverResponse, (item, key) => {
                            if (key in this.licenses) {
                                let obj = _.find(this.licenses, (item, index) => {
                                    return key === index
                                })
                                obj.value = item.value
                                obj.class = item.class
                                obj.percent = this.getPercent(item.value, max_value)
                            }
                        })

                        setTimeout(() => {
                            this.redraw(loader)
                        }, 1000)
                    })
                    .catch(error => { console.log(error) })
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
        data () {
            return {
                loading: true,
                licenses: {
                    buyed: {
                        value: 0,
                        class: 'ari-cyan',
                        percent: 0
                    },
                    assigned: {
                        value: 0,
                        class: 'ari-gray',
                        percent: 0
                    },
                    available: {
                        value: 0,
                        class: 'ari-pink',
                        percent: 0
                    },
                    active: {
                        value: 0,
                        class: 'ari-gray',
                        percent: 0
                    },
                    inactive: {
                        value: 0,
                        class: 'ari-pink',
                        percent: 0
                    },
                }
            }
        }
    }
</script>

<style scoped>

</style>