<template>
    <div id="experience-enrollments" class="ui segments no-borders no-shadows">
        <div class="iframe-experience-header ui segment no-borders no-shadows">
            <img src="/images/apithy_small.png" width="30">
            <span class="iframe-experience-header-title">Enrolamiento({{ paginatedResults.length }})</span>
            <button class="ui red icon button right floated close-modal"
                    @click="closeModal('experience-enrollment')"><i
                    class="ui cancel icon"></i>
            </button>
        </div>
        <div class="experience-enrollment-list segment no-borders no-shadows">
            <sui-table class="ui very basic table tablet stackable striped segment no-borders no-shadows">
                <sui-table-header>
                    <sui-table-row>
                        <sui-table-header-cell>Nombre</sui-table-header-cell>
                        <sui-table-header-cell>Enrolado</sui-table-header-cell>
                        <sui-table-header-cell>Estado</sui-table-header-cell>
                        <sui-table-header-cell></sui-table-header-cell>
                    </sui-table-row>
                </sui-table-header>
                <sui-table-body>
                    <sui-table-row v-for="(enroll,index) in paginatedResults" :id="'enroll_'+enroll.pivot.id"
                                   v-bind:enroll="enroll" :key="index">
                        <sui-table-cell>{{ enroll.full_name}}</sui-table-cell>
                        <sui-table-cell>{{ enroll.pivot.created_at }}</sui-table-cell>
                        <sui-table-cell class="is-narrow">
                                                <span class="ui label" v-bind:class="[enroll.enrollment_status_color]">
                                                    {{enroll.enrollment_status_text}}
                                                </span>
                        </sui-table-cell>
                        <sui-table-cell>
                            <sui-button v-if="enroll.pivot.status==1" class="red"
                                        v-bind:class="{ loading: loading }" @click="ejectUser(enroll)">
                                Expulsar
                            </sui-button>
                        </sui-table-cell>
                    </sui-table-row>
                </sui-table-body>
            </sui-table>
            <div class="ui segment no-borders no-shadows">
                <button class="ui button labeled icon" @click="resultPrevPage">
                    Anterior
                    <i class="arrow circle left icon"></i>
                </button>
                <button class="ui button labeled icon" @click="resultNextPage">
                    Siguiente
                    <i class="arrow circle right icon"></i>
                </button>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        name: 'apithy-experience-enrollment-list',
        props: {
            experience: "",
            enrollments_data: "",
        },
        computed: {
            paginatedResults: function () {
                return this.enrollments.filter((row, index) => {
                    let start = (this.currentPage - 1) * this.pageSize;
                    let end = this.currentPage * this.pageSize;
                    if (index >= start && index < end) return true;
                });
            }
        },
        methods: {
            ejectUser(enroll) {
                this.loading = true;
                axios({
                    method: 'PUT',
                    url: route('experience.enroll', {experience: this.experience.system_id}),
                    params: {
                        user: enroll.id,
                        status: 5
                    }
                }).then((response) => {
                    enroll.pivot.status = 5;
                    enroll.enrollment_status_text = "Expulsado";
                    enroll.enrollment_status_color = "red";
                    this.loading = false;
                }).catch((error) => {
                    this.loading = false;
                    console.log(error);
                });
            },
            resultNextPage: function () {
                if ((this.currentPage * this.pageSize) < this.enrollments.length) this.currentPage++;
            },
            resultPrevPage: function () {
                if (this.currentPage > 1) this.currentPage--;
            }
        },
        data() {
            return {
                loading: false,
                enrollments: this.enrollments_data,
                pageSize: 10,
                currentPage: 1,
            }
        }
    }
</script>
