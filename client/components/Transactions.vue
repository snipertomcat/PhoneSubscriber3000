<template>
    <div class="b-table">
        <div class="table-wrapper" v-if="transactions.data.length">
            <table class="table has-mobile-cards col-12">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Cantidad</th>
                        <th>Tarjeta</th>
                        <th>Status</th>
                        <th>Experiencia</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="transaction in transactions.data"
                                   v-bind:data="transaction"
                                   v-bind:key="transaction.id">
                        <td>{{ transaction.created_at }}</td>
                        <td>
                            <p v-money-format="transaction.amount"></p>
                        </td>
                        <td>
                            <span class="tag is-black" v-if="transaction.payment_source">
                            <i class="fab fa-lg" v-bind:class="[transaction.payment_source.card_type_icon]"></i>&nbsp;&nbsp;{{ transaction.payment_source.card_last_four }}
                            </span>
                        </td>
                        <td><span class="tag is-success">{{ transaction.status_text }}</span>
                        </td>
                        <td>{{ transaction.details[0].experience.title }}</td>
                    </tr>
                </tbody>
            </table>
            <apithy-resource-paginator :pagination="transactions" @paginate="getData()" :offset="4"> </apithy-resource-paginator>
        </div>
        <div v-else>
            <section class="section">
                <div class="content has-text-grey has-text-centered">
                    <p>
                        <img src="/images/Caja.png" alt="">
                    </p>
                    <p>Aún no hay datos que mostrar.</p>
                </div>
            </section>
        </div>
    </div>
</template>
<script>

    import Paginator from './ResourcePagination';

    export default {
        name: 'apithy-user-transactions',
        components: {
            'apithy-resource-paginator': Paginator

        },
        props:{
            init_load:"",
            transactions_data:{}
        },
        beforeMount() {
            if(this.init_load){
                this.getData();
            }
        },
        methods: {
            getData() {
                axios.get(`/profile/transactions?page=${this.transactions.current_page}`)
                    .then((response) => {
                        this.transactions = response.data;
                    })
                    .catch(() => {
                        console.log('handle server error from here');
                    });
            }
        },
        data() {
            return {
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                resource_url: '/profile/transactions',
                transactions: this.transactions_data
            };
        },
    }
</script>

<style scoped>
    table td{
        justify-content: left !important;
    }
</style>
