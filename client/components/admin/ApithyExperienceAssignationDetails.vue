<template>
    <div class="ui segment container">
        <div class="iframe-experience-header ui segment no-borders no-shadows">
            <img src="/images/apithy_small.png" width="30">
            <span class="iframe-experience-header-title">Estado de la asignación</span>
            <button class="ui red icon button right floated close-modal"
                    @click="closeModal('apithy-experience-assignation-details')">
                <i class="ui cancel icon"></i>
            </button>
        </div>
        <div class="ui segment no-borders no-shadows">
            <sui-table class="ui segment very basic table tablet stackable striped">
                <sui-table-header>
                    <sui-table-row>
                        <sui-table-header-cell>Correo electronico</sui-table-header-cell>
                        <sui-table-header-cell>Estado</sui-table-header-cell>
                        <sui-table-header-cell>Fecha de Enrolamiento</sui-table-header-cell>
                        <sui-table-header-cell></sui-table-header-cell>
                    </sui-table-row>
                </sui-table-header>
                <sui-table-body>
                    <sui-table-row v-for="(assignation, index) in assignation_list.data" :key="index">
                        <sui-table-cell><span>{{ assignation.email }}</span></sui-table-cell>
                        <sui-table-cell><span class="ui label" :class="customClass(getEnrollStatus(assignation))">{{ enrolled(getEnrollStatus(assignation)) }}</span></sui-table-cell>
                        <sui-table-cell><span>{{ assignation.updated_at }}</span></sui-table-cell>
                    </sui-table-row>
                </sui-table-body>
                <sui-table-footer>
                    <pagination
                            :current="assignation_list.current_page"
                            :total="assignation_list.total"
                            :per-page="assignation_list.per_page"
                            prev-text="Prev"
                            next-text="Next"
                            @page-changed="fetchData"
                    ></pagination>
                </sui-table-footer>
            </sui-table>
        </div>

    </div>
</template>

<script>
    import Pagination from '../Pagination';
    export default {
        name: "ApithyExperienceAssignationList",
        components: { Pagination },
        created() {
            this.getData();
        },
        props: ['id_assignation'],
        methods: {
            customClass(status) {
                switch (status) {
                    case 1:
                        return 'green';
                    case 0:
                    case 5:
                        return 'red';
                    default:
                        return 'blue';
                }
            },
            getValue(item,key) {
                let enrollment = item.enrollments.find((el) => {
                    return el.pivot.experience_assignation_id === this.id_assignation
                });
                if(enrollment) {
                    return enrollment.pivot[key];
                }
                return null;
            },
            enrolled(value) {
                switch (value) {
                    case 1:
                        return 'Enrolado';
                    case 2:
                        return 'Terminado';
                    case 3:
                        return 'Suspendido';
                    case 4:
                        return 'Expirado';
                    case 5:
                        return 'Expulsado';
                    default:
                        return 'No enrolado';
                }
            },
            getEnrollStatus(item) {
                if (item.enrollments.length) {
                    let enrollment = item.enrollments.find((el) => {
                        return el.pivot.experience_assignation_id === this.id_assignation
                    });
                    if(enrollment) {
                        return enrollment.pivot.status;
                    }
                    return null;
                }
                return null;
            },
            newAssignation() {
                this.closeModal('apithy-experience-assignation-details');
                this.showModal('apithy-experience-assignation-form');
            },
            fetchData(page) {
                this.params.page = page || 1;
                this.getData();
            },
            getData() {
                axios({
                    method: 'GET',
                    url: this.url,
                    params: this.params
                })
                    .then((response) => {
                        this.assignation_list = response.data;
                    })
                    .catch((error)=>{
                        console.log(error);
                    });
            }
        },
        data(){
            return {
                assignation_list: '',
                url: document.location.pathname+'/enrollments/detail',
                params: {
                    page: 1,
                    assignation_id: this.id_assignation
                }
            }
        }
    }
</script>

<style scoped>
    .container {
        padding: 1rem;
    }
    .new-assignation {
        right: 0px;
    }
    .close-button {
        z-index: 1;
    }
</style>