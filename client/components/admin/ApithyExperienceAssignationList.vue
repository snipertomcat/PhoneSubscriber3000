<template>
    <div class="ui segment no-borders no-shadows">
        <div class="iframe-experience-header ui segment no-borders no-shadows">
            <img src="/images/apithy_small.png" width="30">
            <span class="iframe-experience-header-title">Asignaciones</span>
            <sui-button class="green ten" @click="newAssignation">Nueva asignación</sui-button>
            <button class="ui red icon button right floated close-modal"
                    @click="closeModal('apithy-experience-assignation-list')">
                <i class="ui cancel icon"></i>
            </button>
        </div>
        <div class="ui segment no-borders no-shadows">
            <div class="">
                <sui-table class="ui segment very basic table tablet stackable striped no no-borders no-shadows">
                    <sui-table-header>
                        <sui-table-row>
                            <sui-table-header-cell class="column four">Descripción</sui-table-header-cell>
                            <sui-table-header-cell>Tipo</sui-table-header-cell>
                            <sui-table-header-cell>Fecha de creación</sui-table-header-cell>
                            <sui-table-header-cell></sui-table-header-cell>
                        </sui-table-row>

                    </sui-table-header>
                    <sui-table-body>
                        <sui-table-row v-for="(assignation, index) in assignations" :key="index">
                            <sui-table-cell><span>{{ assignation.description }}</span></sui-table-cell>
                            <sui-table-cell><span class="ui label" :class="customClass(assignation.type)">{{ assignation.type }}</span></sui-table-cell>
                            <sui-table-cell><span>{{ assignation.created_at }}</span></sui-table-cell>
                            <sui-table-cell>
                                <sui-button class="ui blue labeled icon" @click="detailAssignation(assignation.id)">
                                    <i class="ui icon eye"></i>
                                    Detalles
                                </sui-button>
                            </sui-table-cell>
                        </sui-table-row>
                    </sui-table-body>
                </sui-table>
            </div>
        </div>

    </div>
</template>

<script>
    import Vue from 'vue';
    window.eventbus = new Vue;
    export default {
        name: "ApithyExperienceAssignationDetails",
        created() {
            this.getData();
            window.eventbus.$on('reloadTableAssignationList',() => {
                this.getData();
            });
        },
        methods: {
            getData() {
                axios({
                    method: 'GET',
                    url: document.location.pathname+'/enrollments'
                }).then((response) => {
                    this.assignations = response.data;
                }).catch((error) => {
                    console.log(error);
                })
            },
            detailAssignation(id) {
                this.$parent.$parent.$data.assignation_id = id;
                this.showModal('apithy-experience-assignation-details');
            },
            newAssignation() {
                this.closeModal('apithy-experience-assignation-details');
                this.showModal('apithy-experience-assignation-form');
            },
            customClass(type) {
                return (type === 'mandatory') ? 'yellow' : 'blue';
            }
        },
        data() {
            return {
                assignations: this.dataAssignations
            };
        }
    }
</script>

<style scoped>

</style>