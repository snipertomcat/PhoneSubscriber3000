<template>
    <div class="">
        <div class="row">
            <div class="col-8 col-md-3">
                <b-field>
                    <b-input placeholder="Buscar"
                             type="search"
                             icon="magnify"
                             v-model="params.search"
                             @keypress.enter="filter">
                    </b-input>
                </b-field>
            </div>
            <div class="d-block d-md-none"><br><br></div>
            <div class="col-4 col-md-1">
                <span>
                    <button class="button is-link" @click="filter">
                        <b-icon pack="fa" icon="filter"></b-icon>
                        <span>Filtrar</span>
                    </button>
                </span>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        name: "SessionsFilter",
        components: {
        },
        props: {
            experience: {
                type: Object,
                required: true
            },
        },
        mounted() {
        },
        methods: {
            filter() {
                axios({
                    method: 'GET',
                    url: route('sessions.index',[this.experience.system_id]),
                    params: this.params
                })
                    .then(response => {
                        this.$parent.setListData(response.data);
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            create() {
                window.location.href = route('sessions.create',[this.experience.system_id]);
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
                            tooltip: 'Nueva sesión',
                        },
                    ],
                }
            };
        }
    }
</script>

<style scoped>

</style>