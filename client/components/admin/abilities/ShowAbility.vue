<template>
    <div class="">
        <button class="button is-outlined is-link width-100" @click="show">
            <i class="fas fa-eye d-block d-lg-none"></i>
            <span class="icon d-none d-lg-block">
                <i class="fas fa-eye"></i>
            </span>
            <span class="d-none d-lg-block">{{ $t('messages.show') }}</span>
        </button>
        <b-modal :active.sync="modal.visible" has-modal-card>
            <div class="card">
                <div class="card-content">
                    <div class="row">
                        <div class="col-12 has-text-centered">
                            <span class="has-text-weight-bold">
                            {{ $t('messages.ability') }}
                        </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 has-text-centered">
                            <span class="width-100 has-text-weight-bold tag apithy-color-status-active text-white">
                                {{ ability.title }}
                            </span>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-12 has-text-centered">
                            <label class="has-text-weight-bold">
                                {{ $t('messages.description')}}
                            </label>
                        </div>
                        <div class="col-12 has-text-left">
                            <span class="width-100">
                                {{ ability.description }}
                            </span>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-12">
                            <a class="button is-outlined is-link width-100" @click="modal.visible = false">
                                <span class="icon"><i class="fas fa-times"></i></span>
                                <span>{{ $t('messages.close') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </b-modal>
    </div>
</template>

<script>
    export default {
        name: "ShowAbility",
        props:{
            id: {
                type: Number,
                required: true
            }
        },
        methods: {
            show() {
                axios({
                    url: route('abilities.show',[this.id]),
                    method: 'GET',
                    params: this.form
                })
                    .then((response) => {
                        this.ability = response.data;
                        this.modal.visible = true;
                        //this.$parent.refresh();
                    })
                    .catch((error) => {
                        window.alert('Ocurrió un problema al cargar la habilidad');
                        console.log(error.response.data);
                    });
            },
            reset() {
            }
        },
        data() {
            return {
                ability: {},
                modal: {
                    visible: false,
                }
            };
        }
    }
</script>

<style scoped>

</style>