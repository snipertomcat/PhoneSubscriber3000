<template>
    <div class="col-12 col-lg-12 b-table">
        <div class="card font-14">
            <div class="card-container">
                <div class="row">
                    <div class="col-12 has-text-left has-text-weight-bold">
                        {{ title }}
                    </div>
                </div>
                <div class="dashboard-table-container table-wrapper">
                    <b-table class="table has-mobile-cards col-12" :data="dataAnalytics" :columns="columns" :mobile-cards="true"></b-table>
                </div>
                <div class="has-text-right">
                    <a :href="linkViewAll">
                        <span class="">
                            Ver todo
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        name: "ApithyTable",
        props: {
            dataAnalytics: {
                type: Array,
                required: true
            },
            columns: {
                type: Array,
                required: false,
                default() {
                    return [];
                }
            },
            title: {
                type: String,
                required: false,
                return: "",
            },
            linkViewAll: {
                type: String,
                required: false,
                return: ""
            }
        },
        methods: {
            tinyDate(date) {
                return new Date(date).toLocaleDateString();
            },
            translateStatus(status) {
                return (status) ? 'Aceptada' : 'Pendiente';
            }
        }
    }
</script>

<style scoped>
    .card-container {
        padding: 20px;
    }
    .dashboard-table-container {
        padding: 10px;
        border-radius: 10px;
        border: solid #979797 1px;
    }
    .dashboard-table-container .table {
        width: 100%;
        font-size: 14px;
    }
</style>