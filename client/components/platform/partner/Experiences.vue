<template>
    <div class="">
        <div class="margin-50">
            <div class="row">
                <div class="col-8">
                    <div class="card padding-25 ">
                        <div class="card-hearder">
                            <h1>Experiencias que más gustan</h1>
                        </div>
                        <div class="card-content">
                            <div class="">
                                <b-table :data="experiences.list" :columns="columns" mobile-cards>
                                    <template slot-scope="props" slot="header">
                                        <div class="" v-if="props.column.icon">
                                            <b-tooltip :active="!!props.column.label" :label="props.column.label">
                                                <i :class="'fas fa-'+props.column.icon"></i>
                                            </b-tooltip>
                                        </div>
                                        <div class="" v-else>
                                            {{props.column.label}}
                                        </div>
                                    </template>
                                    <template slot-scope="props">
                                        <b-table-column field="name">
                                            <div class="row">
                                                <div class="col-3">
                                                    <img :src="props.row.image" width="80" class="is-rounded">
                                                </div>
                                                <div class="col-9">
                                                    {{ props.row.name }}
                                                </div>
                                            </div>
                                        </b-table-column>

                                        <b-table-column field="sales">
                                            {{ props.row.sales }}
                                        </b-table-column>

                                        <b-table-column field="quantity">
                                            {{ props.row.quantity }}
                                        </b-table-column>

                                        <b-table-column field="quantity">
                                            {{ props.row.quantity }}
                                        </b-table-column>

                                        <b-table-column field="quantity">
                                            {{ props.row.quantity }}
                                        </b-table-column>

                                        <b-table-column field="quantity">
                                            {{ props.row.quantity }}
                                        </b-table-column>

                                        <b-table-column field="quantity">
                                            {{ props.row.quantity }}
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
                    <br>
                    <div class="row justify-content-center">
                        <div class="col-4"></div>
                        <div class="col-4 text-center is-bold is-info">
                            <span class="margin-r-25 padding-lr-10">|<</span>
                            <span class="margin-r-25 padding-lr-10"><</span>
                            <span class="margin-r-25 padding-lr-10">1</span>
                            <span class="margin-r-25 padding-lr-10">></span>
                            <span class="margin-r-25 padding-lr-10">>|</span>
                        </div>
                        <div class="col-4">
                            <b-select v-model="experiences.view_by" class="text-right">
                                <option value="5">Ver 5</option>
                            </b-select>
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
                columns: [
                    {label: 'Experiencias'},
                    {label: 'Ventas'},
                    {icon: 'grin-hearts', label: 'Excelente'},
                    {icon: 'smile', label: 'Good'},
                    {icon: 'meh', label: 'Normal'},
                    {icon: 'frown-open', label: 'Bad'},
                    {icon: 'angry', label: 'Very Bad'},
                ],
                experiences: {
                    view_by: 5,
                    list: [
                        { 'id': 1, 'name': 'Marketing Digital', 'quantity': '15', 'total': '$2,250.00 MXN', 'sales':'3', 'image': 'https://s3.us-west-2.amazonaws.com/cdn.apithy.com/experience_covers/59.jpeg' },
                        { 'id': 2, 'name': 'Marketing Digital', 'quantity': '15', 'total': '$2,250.00 MXN', 'sales':'3', 'image': 'https://s3.us-west-2.amazonaws.com/cdn.apithy.com/experience_covers/59.jpeg' },
                        { 'id': 3, 'name': 'Marketing Digital', 'quantity': '15', 'total': '$2,250.00 MXN', 'sales':'3', 'image': 'https://s3.us-west-2.amazonaws.com/cdn.apithy.com/experience_covers/59.jpeg' },
                        { 'id': 4, 'name': 'Marketing Digital', 'quantity': '15', 'total': '$2,250.00 MXN', 'sales':'3', 'image': 'https://s3.us-west-2.amazonaws.com/cdn.apithy.com/experience_covers/59.jpeg' },
                        { 'id': 5, 'name': 'Marketing Digital', 'quantity': '15', 'total': '$2,250.00 MXN', 'sales':'3', 'image': 'https://s3.us-west-2.amazonaws.com/cdn.apithy.com/experience_covers/59.jpeg' }
                    ],
                },
            };
        }
    }
</script>

<style scoped>

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