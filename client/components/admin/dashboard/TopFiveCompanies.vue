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
                    <div :class="column.custom_class" v-for="column in data.columns">
                        <span class="has-text-weight-bold">
                            {{ column.label }}
                        </span>
                    </div>
                </div>
                <div class="row padding-5" v-for="element in data.data">
                    <div class="col-8">
                        <div class="row align-content-center">
                            <div class="col-4">
                                <img :src="element.image" class="fluent round" width="50px">
                            </div>
                            <div class="col-8">
                            <span class="has-text-weigth-bold">
                                {{ element.name }}
                            </span>
                                <br>
                                <span>
                                {{ element.author }}
                            </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 text-center">
                    <span class="font-20 has-text-weight-bold">
                        {{ element.value }}
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
        name: "TopFiveCompanies",
        props: {
            data: {
                required: true,
            }
        }
    }
</script>

<style scoped>
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