<template>
    <div class="block-centered">
        <div class="row">
            <div class="col-12 col-lg-6">
                <b-field grouped>
                    <b-input placeholder="Buscar"
                             type="search"
                             icon="magnify"
                             expanded
                             v-model="params.search"
                             @keypress.enter="filter">
                    </b-input>
                    <p class="control">
                        <button class="button is-link" @click="filter">
                            <b-icon pack="fa" icon="filter"></b-icon>
                            <span>Filtrar</span>
                        </button>
                    </p>
                </b-field>
            </div>
            <fab :position="float_button.position"
                 :bg-color="float_button.bg_color"
                 :actions="float_button.actions"
                 @create="create"
                 ref="fab"
            ></fab>
        </div>
    </div>
</template>

<script>
    import Fab from 'vue-fab';

    export default {
        name: "AreasFilter",
        components: {
            'fab': Fab,
        },
        props: {
            companyData: {
                type: Object,
                required: true
            },
        },
        mounted() {
            this.$refs.fab.$el.style.position = 'absolute';
            this.$refs.fab.$el.style.top = '0px';
            this.$refs.fab.$el.style.right = '1%';
        },
        methods: {
            filter() {
                axios({
                    method: 'GET',
                    url: route('areas.index',[this.companyData.id]),
                    params: this.params
                })
                    .then(response => {
                        this.$parent.setTableData(response.data);
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            create() {
                window.location.href = route('areas.create',[this.companyData.id]);
            }
        },
        data() {
            return {
                params: {
                    search: null,
                },
                float_button: {
                    position: 'top-right',
                    bg_color: '#2D7EFC',
                    actions: [
                        {
                            name: 'create',
                            icon: 'ballot',
                            tooltip: 'Nueva área',
                        },
                    ],
                }
            };
        }

    }
</script>

<style scoped>

</style>