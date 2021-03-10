<template>
    <div class="">
        <div class="margin-50">
            <div class="row">
                <div class="col-12">
                    <h1>Experiencias que más gustan</h1>
                </div>
                <div class="col-12">
                    <div class="card padding-25 ">
                        <div class="card-content">
                            <div class="border-bottom margin-tb-20" v-for="row in conciliation.list">
                                <div class="row align-items-center">
                                    <div class="col-1 text-center is-bold">
                                        {{ row.date }}
                                    </div>
                                    <div class="col-7">
                                        <div class="row padding-tb-20 border-bottom">
                                            <div class="col-6 is-bold">
                                                {{ row.sales.apithy.label }}
                                            </div>
                                            <div class="col-2">
                                                $ {{ row.sales.apithy.total }}.00 MXN
                                            </div>
                                            <div class="col-2">
                                                $ {{ row.sales.apithy.partner }}.00 MXN
                                            </div>
                                            <div class="col-2">
                                                $ {{ row.sales.apithy.quot }}.00 MXN
                                            </div>
                                        </div>
                                        <div class="row padding-tb-20">
                                            <div class="col-6 is-bold">
                                                {{ row.sales.partner.label }}
                                            </div>
                                            <div class="col-2">
                                                $ {{ row.sales.partner.total }}.00 MXN
                                            </div>
                                            <div class="col-2">
                                                $ {{ row.sales.partner.partner }}.00 MXN
                                            </div>
                                            <div class="col-2">
                                                $ {{ row.sales.partner.quot }}.00 MXN
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-2 text-center">
                                        <div class="font-50">
                                            <i :class="row.status.icon"></i>
                                        </div>
                                        <div class="">
                                            {{ row.status.label }}
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="row align-items-center text-center padding-tb-20 border-bottom">
                                            <div class="col-6">
                                                <span class="button is-primary is-small" @click="download(row.sales.apithy.documents.pdf)">
                                                    <span><i class="fa fa-download"></i> PDF</span>
                                                </span>
                                            </div>
                                            <div class="col-6">
                                                <span class="button is-primary is-small" @click="download(row.sales.apithy.documents.xml)">
                                                    <span><i class="fa fa-download"></i> XML</span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row align-items-center text-center padding-tb-20">
                                            <div class="col-6">
                                                <span class="button is-primary is-small" @click="download(row.sales.partner.documents.pdf)">
                                                    <span><i class="fa fa-download"></i> PDF</span>
                                                </span>
                                            </div>
                                            <div class="col-6">
                                                <span class="button is-primary is-small" @click="download(row.sales.partner.documents.xml)">
                                                    <span><i class="fa fa-download"></i> XML</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                            </div>
                        </div>
                        <div class="no_data row align-items-center justify-content-center" v-if="true">
                            <div class="content col-8">
                                <div class="">
                                    <div class="has-text-centered">
                                        <img src="/images/Caja.png" alt="">
                                        <h3>Esta gráfica está vacía</h3>
                                        <p>
                                            Faltan datos por recopilar. <br>
                                            Regresa más adelante para monitorear el avance.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Experiences",
        data() {
            return {
                conciliation: {
                    list: [
                        {
                            date: 'Diciembre 2018',
                            sales: {
                                apithy: {
                                    label: 'Venta Apithy',
                                    total: 1000,
                                    partner: 250,
                                    quot: 50,
                                    documents: {
                                        pdf: '',
                                        xml: ''
                                    }
                                },
                                partner: {
                                    label: 'Venta [Nombre de Aliado]',
                                    total: 1000,
                                    partner: 450,
                                    quot: 50,
                                    documents: {
                                        pdf: '',
                                        xml: ''
                                    }
                                },
                            },
                            status: {
                                label: 'pagado',
                                icon: 'icon-status-paid-out'
                            },
                        },
                        {
                            date: 'Enero 2019',
                            sales: {
                                apithy: {
                                    label: 'Venta Apithy',
                                    total: 1000,
                                    partner: 250,
                                    quot: 50
                                },
                                partner: {
                                    label: 'Venta [Nombre de Aliado]',
                                    total: 1000,
                                    partner: 450,
                                    quot: 50
                                },
                            },
                            status: {
                                label: 'procesando',
                                icon: 'icon-status-pending'
                            },
                            documents: {
                                pdf: '',
                                xml: ''
                            }
                        },
                        {
                            date: 'Febrero 2019',
                            sales:{
                                apithy: {
                                    label: 'Venta Apithy',
                                    total: 1000,
                                    partner: 250,
                                    quot: 50
                                },
                                partner: {
                                    label: 'Venta [Nombre de Aliado]',
                                    total: 1000,
                                    partner: 450,
                                    quot: 50
                                },
                            },
                            status: {
                                label: 'corriente',
                                icon: 'icon-status-up-to-date'
                            },
                            documents: {
                                pdf: '',
                                xml: ''
                            }
                        },
                    ],
                },
            };
        }
    }
</script>

<style scoped>
    .margin-tb-20 {
        margin-bottom: 10px;
        margin-top: 10px;
    }
    .padding-tb-20 {
        padding-bottom: 10px;
        padding: 10px;
    }
    .margin-50 {
        margin: 50px;
    }
    .margin-r-25 {
        margin-right: 25px;
    }
    .padding-25 {
        padding: 25px;
    }
    .no_data {
        position: absolute;
        top: 0;
        left: 15px;
        width: 100%;
        height: 100%;
        background-color: rgba(255,255,255,0.9);
        z-index: 10;
    }
</style>