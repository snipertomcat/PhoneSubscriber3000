<template>
    <div class="card p-4">
        <div class="card-container" >
            <div class="" v-if="data.has_data">
                <div class="row">
                    <div class="col-12">
                        <span class="font-18 has-text-weight-bold">{{ data.label.charAt(0).toUpperCase() + data.label.slice(1) }}</span>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-6">
                        <div class="row">
                            <div class="col-12">
                                <div><span class="font-18">Compradas</span></div>
                                <div class="mt-2">
                                    <span class="has-text-weight-bold font-18">{{ data.data.buyed }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-12">
                                <div><span class="font-18">Asignadas</span></div>
                                <div class="mt-2">
                                    <span class="has-text-weight-bold font-18">{{ data.data.assigned }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 text-center">
                        <div>
                            <span class="has-text-weight-bold font-50">{{ data.data.average }}%</span>
                        </div>
                        <div><span class="font-18">asignadas</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="no_data row align-items-center justify-content-center card-container" v-if="!data.has_data">
            <div class="content col-8">
                <div class="">
                    <div class="has-text-centered">
                        <h3>Aún faltan datos por recopilar.</h3>
                        <p>Regresa en unos días para monitorear los avances</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { bus } from "../../../../../app_platform";

    export default {
        name: "LicensesAverage",
        beforeMount () {
            bus.$on('la_wea', (value) => {
                this.data = value;
            })
        },
        data () {
            return {
                data: {
                    loading: false,
                    has_data: false
                }
            }
        }
    }
</script>

<style scoped>
    .no_data {
        position: absolute;
        top: 0;
        left: 15px;
        width: 100%;
        height: 100%;
        background-color: rgba(255,255,255,0.9);
    }
</style>