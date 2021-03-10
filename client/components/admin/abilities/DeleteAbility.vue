<template>
    <button class="button is-outlined is-danger width-100" @click="destroy">
        <i class="fas fa-trash d-block d-lg-none"></i>
        <span class="icon d-none d-lg-block">
            <i class="fas fa-trash"></i>
        </span>
        <span class="d-none d-lg-block">{{ $t('messages.delete') }}</span>
    </button>
</template>

<script>
    export default {
        name: "DeleteAbility",
        props:{
            id: {
                type: Number,
                required: true
            }
        },
        methods: {
            destroy() {
                this.$snotify.async(this.$t('messages.ability'), 'Procesando', () => new Promise((resolve, reject) => {
                    axios({
                        url: route('abilities.destroy',[this.id]),
                        method: 'DELETE'
                    })
                        .then(response => {
                            this.$parent.refresh();
                            resolve({
                                title: 'Petición exitosa',
                                body: 'Guardados los cambios con éxito.',
                                config: {
                                    closeOnClick: true,
                                    html: `
                                        <div class="snotifyToast__title">Petición exitosa</div>
                                        <div class="snotifyToast__body">Guardados los cambios con éxito.</div>
                                        <div class="snotify-icon snotify-icon--success"></div>
                                        `
                                }
                            });
                        })
                        .catch((error) => {
                            console.log(error.response.data);
                            reject({
                                title: 'Error!!',
                                body: 'No se pudo procesar la petición correctamente.',
                                config: {
                                    closeOnClick: true,
                                    html: `
                                         <div class="snotifyToast__title">Error!!</div>
                                        <div class="snotifyToast__body">No se pudo procesar la petición correctamente.</div>
                                        <div class="snotify-icon snotify-icon--error"></div>
                                        `
                                }
                            });
                        })

                }));
            }
        },
    }
</script>

<style scoped>

</style>