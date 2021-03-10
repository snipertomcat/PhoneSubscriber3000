<template>
    <div class="container">
        <hr width="3">
        <div class="card">
            <div class="card-content">
                <apithy-create-ability></apithy-create-ability>
                <hr width="3">
                <div class="row">
                    <div class="col-12">
                        <b-table class="table is-fullwidth has-mobile-cards" :data="getAbilities">
                            <template slot-scope="props">
                                <b-table-column field="title" label="Nombre">
                                    {{ props.row.title }}
                                </b-table-column>

                                <b-table-column field="company_name" label="Pertenece a">
                                    {{ props.row.company_name }}
                                </b-table-column>

                                <b-table-column field="created_at" label="Creada">
                                    {{ new Date(props.row.created_at).toLocaleDateString() }}
                                </b-table-column>

                                <b-table-column field="" label="">
                                    <div class="row">
                                        <div class="col-4">
                                            <apithy-show-ability :id="props.row.id"></apithy-show-ability>
                                        </div>
                                        <div class="col-4">
                                            <apithy-edit-ability :id="props.row.id"></apithy-edit-ability>
                                        </div>
                                        <div class="col-4">
                                            <apithy-delete-ability :id="props.row.id"></apithy-delete-ability>
                                        </div>
                                    </div>
                                </b-table-column>
                            </template>
                        </b-table>
                        <div class="row justify-content-center has-text-centered">
                            <div @click="first" class="col-1 pointer apithy-color font-20"><span><i class="fa fa-angle-double-left"></i></span></div>
                            <div @click="prev" class="col-1 pointer apithy-color font-20"><span><i class="fa fa-angle-left"></i></span></div>
                            <div class="col-1 apithy-color font-20">{{ ability_list.current_page }}</div>
                            <div @click="next" class="col-1 pointer apithy-color font-20"><span><i class="fa fa-angle-right"></i></span></div>
                            <div @click="last" class="col-1 pointer apithy-color font-20"><span><i class="fa fa-angle-double-right"></i></span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import CreateAbility from './CreateAbility';
    import ShowAbility from './ShowAbility';
    import EditAbility from './EditAbility';
    import DeleteAbility from './DeleteAbility';

    export default {
        name: "AbilityList",
        components: {
            'apithy-create-ability': CreateAbility,
            'apithy-show-ability': ShowAbility,
            'apithy-edit-ability': EditAbility,
            'apithy-delete-ability': DeleteAbility,
        },
        props: {},
        computed: {
            getAbilities() {
                return this.ability_list.data;
            }
        },
        mounted() {
            this.getAjaxData();
        },
        methods: {
            getAjaxData(url) {
                axios({
                    url: route('abilities.index'),
                    method: 'GET',
                    params: this.params
                })
                    .then(response => {
                        if(response.data.data.length > 0) {
                            this.ability_list = response.data;
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },
            refresh() {
                this.getAjaxData();
            },
            next() {
                if (this.ability_list.current_page < this.ability_list.last_page) {
                    this.params.page = this.ability_list.current_page + 1;
                    this.getAjaxData();
                }
            },
            prev() {
                if (this.ability_list.current_page > 1) {
                    this.params.page = this.ability_list.current_page - 1;
                    this.getAjaxData();
                }
            },
            first() {
                this.params.page = 1;
                this.getAjaxData();
            },
            last() {
                this.params.page = this.ability_list.last_page;
                this.getAjaxData();
            }
        },
        data() {
            return {
                ability_list: {},
                params: {
                    page: 1
                }
            };
        }
    }
</script>

<style scoped>

</style>