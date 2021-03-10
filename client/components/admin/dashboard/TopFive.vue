<template>
    <div class="card padding-10">
        <div class="card-container">
            <div class="row">
                <div class="col-12">
                    <b-icon :pack="data.pack_icon" :icon="data.icon"></b-icon>
                    <span class="font-16 has-text-weight-bold">{{data.label}}</span>
                </div>
            </div>
            <br>
            <div class="padding-10">
                <div class="row">
                    <div class="col-1 has-text-weight-bold">
                        Nº
                    </div>
                    <div class="col-8">
                    <span class="has-text-weight-bold">
                        Experiencia
                    </span>
                    </div>
                    <div class="col-3">
                    <span class="has-text-weight-bold">
                        Enrolamientos
                    </span>
                    </div>
                </div>
                <div class="row padding-5" v-for="(element, index) in data.data">
                    <div class="col-1">
                        {{ index + 1 }}
                    </div>
                    <div class="col-8">
                        <div class="row align-content-center">
                            <div class="col-4">
                                <img :src="element.image" class="fluent round">
                            </div>
                            <div class="col-8">
                            <span class="has-text-weigth-bold">
                                {{ element.name }}
                            </span>
                            <br>
                            <span class="has-text-link">
                                por {{ element.author }}
                            </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-3 text-center">
                    <span class="font-20 has-text-weight-bold">
                        {{ element.quantity }}
                    </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="no_data row align-items-center justify-content-center" v-if="!data.has_data">
            <div class="content col-8">
                <div class="">
                    <div class="has-text-centered">
                        <h3>Aún faltan datos por recopilar.</h3>
                        <p>Regresa en unos días para monitorear los avances</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "TopFive",
        props: {
            data: {
                required: true,
            }
        }
    }
</script>

<style scoped>
    .card {
        height: 100%;
    }
    img.round {
        border-radius: 15px;
    }
    .padding-5 {
        padding: 5px;
    }
    .no_data {
        position: absolute;
        top: 0;
        left: 15px;
        width: 100%;
        height: 100%;
        background-color: rgba(255,255,255,0.9);
    }
</style>