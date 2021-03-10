<template>
    <div class="">
        <div class="margin-50">
            <div class="row">
                <div class="col-12">
                    <h1>Esquema de beneficios</h1>
                </div>
                <div class="col-8">
                    <div class="card padding-25 ">
                        <div class="card-content">
                            <div class="">
                                <b-table :data="experiences.list" mobile-cards>
                                    <template slot-scope="props">
                                        <b-table-column field="row">
                                            {{ props.row.row }}
                                        </b-table-column>

                                        <b-table-column field="apithy" label="Apithy">
                                            {{ props.row.apithy }}
                                        </b-table-column>

                                        <b-table-column field="name" label="[Nombre de Aliado]">
                                            {{ props.row.name }}
                                        </b-table-column>

                                        <b-table-column field="quot" label="Cuota">
                                            {{ props.row.quot }}
                                        </b-table-column>
                                    </template>
                                </b-table>
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
                experiences: {
                    list: [
                        { 'row': 'Venta Apithy', 'apithy': 70, 'name': 25, 'quot': 5 },
                        { 'row': 'Venta [Nombre de Aliado]', 'apithy': 50, 'name': 45, 'quot': 5 },
                    ],
                },
            };
        }
    }
</script>

<style scoped>
    .card {
        height: 500px;
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