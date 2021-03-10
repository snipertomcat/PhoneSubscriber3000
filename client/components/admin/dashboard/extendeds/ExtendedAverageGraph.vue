<template>
    <div class="card padding-10">
        <div class="card-container">
            <div v-if="!alternative">
                <div class="row">
                    <div class="col-12">
                        <b-icon :pack="data.pack_icon" :icon="data.icon"></b-icon>
                        <span class="font-16 has-text-weight-bold">{{data.label}}</span>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-12">
                        <div><span class="font-12">{{data.data.primary.label}}</span></div>
                        <div>
                            <span class="has-text-weight-bold font-50">{{Math.floor(data.data.primary.value)}}%</span>
                        </div>
                    </div>
                    <div class="col-12 font-14">
                        <div class="padding-10">
                            <div class="row justify-content-between">
                                <div class="col-auto">
                                    <span class="font-16 has-text-weight-bold">{{ data.label }}</span>
                                </div>
                                <div class="col-auto">
                                    <b-icon :pack="data.pack_icon" icon="caret-down"></b-icon>
                                    <a :href="route(url)">
                                        <span class="font-16 has-text-weight-bold has-text-weight-bold is-link">{{ $t('messages.dashboard.show_all') }}</span>
                                    </a>
                                </div>
                            </div>
                            <br>
                            <div class="row padding-5" v-for="(element, index) in data.data.top_five.data">
                                <div class="col-auto">
                                    <span>{{ index + 1 }} -</span>
                                </div>
                                <div class="col-auto">
                                    <span class="has-text-weigth-bold font-14">{{ element.name }} - {{ element.author }}</span> :
                                </div>
                                <div class="col-auto padding-lr-0">
                                    <span class="font-16 has-text-weight-bold">{{ element.quantity }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" v-else>
                <div class="col-6">
                    <div class="row">
                        <div class="col-12">
                            <b-icon :pack="data.pack_icon" :icon="data.icon"></b-icon>
                            <span class="font-16 has-text-weight-bold">{{data.label}}</span>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-6">
                            <div><span class="font-12">{{data.data.primary.label}}</span></div>
                            <div>
                                <span class="has-text-weight-bold font-50">{{Math.floor(data.data.primary.value)}}%</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="row" v-for="item in data.data.secondary">
                                <div class="col-12">
                                    <div><span class="font-12">{{item.label}}</span></div>
                                    <div>
                                        <span class="has-text-weight-bold font-25">{{item.value}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 font-14">
                    <div class="padding-10">
                        <div class="row justify-content-between">
                            <div class="col-auto">
                                <span class="font-16 has-text-weight-bold">{{ data.label }}</span>
                            </div>
                            <div class="col-auto">
                                <b-icon :pack="data.pack_icon" icon="caret-down"></b-icon>
                                <a :href="route(url)">
                                    <span class="font-16 has-text-weight-bold has-text-weight-bold is-link">{{ $t('messages.dashboard.show_all') }}</span>
                                </a>
                            </div>
                        </div>
                        <br>
                        <div class="row padding-5" v-for="(element, index) in data.data.top_five.data">
                            <div class="col-auto">
                                <span>{{ index + 1 }} -</span>
                            </div>
                            <div class="col-10">
                                <span class="has-text-weigth-bold font-14">
                                    {{ element.name }} {{ element.author }}
                                </span>
                            </div>
                            <div class="offset-2 col-3">
                                <span class="font-16 has-text-weight-bold">
                                    {{ getPercent(element.total,element.quantity) }}%
                                </span>
                            </div>
                            <div class="col-auto">
                                <span class="font-16">
                                    {{ element.total }} de {{ element.quantity }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="no_data row align-items-center justify-content-center" v-if="!data.has_data">
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
    export default {
        name: "ExtendedAverageGraph",
        props: {
            data: {
                required: true,
            },
            alternative: {
                required: false,
                default: true
            },
            url: {
                type: String,
                required: true
            }
        },
        methods: {
            getPercent(total,quantity) {
                return (total / quantity) * 100;
            }
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
    .padding-lr-0 {
        padding-left: 0;
        padding-right: 0;
    }
</style>