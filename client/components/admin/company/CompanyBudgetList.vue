<template>
    <div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <b-table :data="table_data" :striped="true" :mobile-cards="true">
                            <!-- init_balance, current_balance, user_id,type, created_at -->
                            <template slot-scope="props">
                                <b-table-column field="amount" label="Monto" v-money-format="props.row.amount">
                                </b-table-column>

                                <b-table-column label="Movimiento">
                                    <div v-if="props.row.operation_type == 'out'">
                                         <span class="tag is-info">
                                            Cargo
                                         </span>
                                    </div>
                                    <div v-if="props.row.operation_type == 'in'">
                                        <span class="tag is-warning">
                                            Abono
                                         </span>
                                    </div>

                                </b-table-column>

                                <b-table-column field="current_balance" label="Balance actual" v-money-format="props.row.current_balance">
                                </b-table-column>

                                <b-table-column field="user_id" label="Usuario">
                                    {{ props.row.user.full_name }}
                                </b-table-column>

                                <b-table-column label="Tipo">
                                    <span class="tag is-success">
                                        {{ props.row.type }}
                                    </span>
                                </b-table-column>

                                <b-table-column field="date" label="Fecha" centered>
                                    {{ new Date(props.row.created_at).toLocaleDateString() }}
                                </b-table-column>
                            </template>


                            <template slot="empty">
                                <section class="section">
                                    <div class="content has-text-grey has-text-centered">
                                        <p>
                                            <img src="/images/Caja.png" alt="">
                                        </p>
                                        <p>Aún no hay datos que mostrar.</p>
                                    </div>
                                </section>
                            </template>
                        </b-table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "CompanyBudgetList",
        props: {
            'budgetList': {
                required: true,
            }
        },
        methods: {
            formatMoney(n, c, d, t, s='') {
                c = isNaN(c = Math.abs(c)) ? 2 : c,
                d = d == undefined ? "." : d;
                t = t == undefined ? "," : t;
                s = s == undefined ? "" : s;
                s = n < 0 ? "-" + s : "" + s;
                let i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c)));
                let j = (j = i.length) > 3 ? j % 3 : 0;

                return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
            },
    },
        data() {
            return {
                table_data: this.budgetList,
                money: this.$root.money,
            }
        }
    }
</script>

<style scoped>

</style>
