<template>
    <div class="container">
        <hr width="3">
        <div class="card">
            <div class="card-content">
                <apithy-create-tag></apithy-create-tag>
                <hr width="3">
                <div class="row">
                    <div class="col-12">
                        <b-table class="table is-fullwidth has-mobile-cards" :data="getTags">
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
                                            <apithy-show-tag :id="props.row.id"></apithy-show-tag>
                                        </div>
                                        <div class="col-4">
                                            <apithy-edit-tag :id="props.row.id"></apithy-edit-tag>
                                        </div>
                                        <div class="col-4">
                                            <apithy-delete-tag :id="props.row.id"></apithy-delete-tag>
                                        </div>
                                    </div>
                                </b-table-column>
                            </template>
                        </b-table>
                        <div class="row justify-content-center has-text-centered">
                            <div @click="first" class="col-1 pointer apithy-color font-20"><span><i class="fa fa-angle-double-left"></i></span></div>
                            <div @click="prev" class="col-1 pointer apithy-color font-20"><span><i class="fa fa-angle-left"></i></span></div>
                            <div class="col-1 apithy-color font-20">{{ tag_list.current_page }}</div>
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
    import CreateTag from './CreateTag';
    import ShowTag from './ShowTag';
    import EditTag from './EditTag';
    import DeleteTag from './DeleteTag';

    export default {
        name: "TagList",
        components: {
            'apithy-create-tag': CreateTag,
            'apithy-show-tag': ShowTag,
            'apithy-edit-tag': EditTag,
            'apithy-delete-tag': DeleteTag,
        },
        props: {},
        computed: {
            getTags() {
                return this.tag_list.data;
            }
        },
        mounted() {
            this.getAjaxData();
        },
        methods: {
            getAjaxData() {
                axios({
                    url: route('tags.index'),
                    method: 'GET',
                    params: this.params
                })
                    .then((response) => {
                        if(response.data.data.length > 0) {
                            this.tag_list = response.data;
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
                if (this.tag_list.current_page < this.tag_list.last_page) {
                    this.params.page = this.tag_list.current_page + 1;
                    this.getAjaxData();
                }
            },
            prev() {
                if (this.tag_list.current_page > 1) {
                    this.params.page = this.tag_list.current_page - 1;
                    this.getAjaxData();
                }
            },
            first() {
                this.params.page = 1;
                this.getAjaxData();
            },
            last() {
                this.params.page = this.tag_list.last_page;
                this.getAjaxData();
            }
        },
        data() {
            return {
                tag_list: {},
                params: {
                    page: 1
                }
            };
        }
    }
</script>

<style scoped>

</style>