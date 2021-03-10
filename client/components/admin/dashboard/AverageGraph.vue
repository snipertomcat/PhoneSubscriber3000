<template>
    <div class="card p-4">
        <div class="row">
            <div class="col-12">
                <span class="font-18 has-text-weight-bold">{{ data.label }}</span>
            </div>
        </div>
        <div class="card-container" v-if="data.has_data">
            <div class="row mt-4">
                <div class="col-6">
                    <div class="row">
                        <div class="col-12">
                            <div><span class="font-18">{{ data.data.secondary.total.label }}</span></div>
                            <div class="mt-2">
                                <span class="has-text-weight-bold font-18">{{ data.data.secondary.total.value }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-12">
                            <div><span class="font-18">{{ data.data.secondary.actual.label }}</span></div>
                            <div class="mt-2">
                                <span class="has-text-weight-bold font-18">{{ data.data.secondary.actual.value }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 text-center">
                    <div>
                        <span class="has-text-weight-bold font-50">{{Math.floor(data.data.primary.value)}}%</span>
                    </div>
                    <div><span class="font-18">{{data.data.primary.label}}</span></div>
                </div>
            </div>
        </div>
        <div class="card-container" v-else>
            <div class="row align-items-center justify-content-center no_data">
                <div class="col-12 align-self-center has-text-centered mt-3">
                    <span class="font-40">
                        <i class="icon-chart"></i>
                    </span><br>
                    <span class="has-text-weight-bold font-20">
                        {{ 'Aún faltan datos por recopilar' }}
                    </span>
                </div>
            </div>
        </div>
        <b-loading :is-full-page="false" :active.sync="data.loading" :can-cancel="false"></b-loading>
    </div>
</template>

<script>
    export default {
        name: "AverageComponent",
        props: {
            data: {
                required: true,
            },
        },
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