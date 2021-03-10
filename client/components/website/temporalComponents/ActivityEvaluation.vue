<template>
    <div class="d-flex align-items-center justify-content-center height-100">
        <div class="width-75">
            <div class="card">
                <template v-if="showMessage">
                    <div class="card-content" style="height: 300px;">
                        <div class="row justify-content-center align-items-center height-100">
                            <div class="col-auto">
                                <div class="title">
                                    <span>Se ha establecido la calificación</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <template v-else>
                    <div class="card-content">
                        <div class="row width-100 justify-content-center">
                            <div class="col-12 col-lg-8">
                                <div class="user-response">
                                    <div class="response">
                                        <div class="row mb-3">
                                            <div class="col"><span class="label">Nombre del alumno:</span></div>
                                            <div class="col-auto"><span>{{ dataTable.user_name }}</span></div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col"><span class="label">Experiencia:</span></div>
                                            <div class="col-auto"><span>{{ dataTable.experience_name }}</span></div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col"><span class="label">Actividad:</span></div>
                                            <div class="col-auto"><span>{{ dataTable.activity_name }}</span></div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col"><span class="label">Respondido el:</span></div>
                                            <div class="col-auto"><span>{{ helper.parse(dataTable.finished_at, 'date') }}</span></div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col"><span class="label">Texto enviado:</span></div>
                                            <div class="col-auto"><span>{{ dataTable.user_response_pattern }}</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row width-100 justify-content-center">
                            <div class="col-12 col-lg-8">
                                <div class="form">
                                    <form :action="dataUrl" method="post" @submit.prevent="send">
                                        <div class="" style="display: none">
                                            <slot name="token"></slot>
                                        </div>
                                        <input type="numeric" name="activity_id" v-model="dataTable.activity_id" hidden>
                                        <input type="numeric" name="enrollment_progress_id" v-model="dataTable.enrollment_progress_id" hidden>
                                        <input type="numeric" name="score" v-model="scaledScore" :disabled="hasScore" hidden>
                                        <div class="row mb-3 justify-content-between">
                                            <div class="col"><span class="label">Calificacion (0-100):</span></div>
                                            <div class="col-auto">
                                                <input class="text-right"
                                                       type="number"
                                                       min="0"
                                                       max="100"
                                                       v-model="displayed_score"
                                                       :disabled="hasScore || disable_field" required>
                                            </div>
                                        </div>
                                        <div class="row justify-content-center">
                                            <div class="col-12 col-lg-3">
                                                <button class="button is-primary width-100"
                                                        type="submit"
                                                        :disabled="hasScore || disable_field">
                                                    {{ $t('messages.send') }}
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
    import { index } from "../../../helpers";

    export default {
        name: "ActivityEvaluation",
        props: {
            dataUrl: '',
            dataTable: ''
        },
        computed: {
            helper: function () {
                return index;
            },
            hasScore: function () {
                // this is temporal open form
                return false;
                return !_.isEmpty(this.dataTable.score);
            },
            scaledScore: function () {
                if (this.hasScore)
                    return this.dataTable.score;
                return this.displayed_score / 100;
            },
            showMessage: function () {
                return this.disable_field;
            }
        },
        mounted: function () {
            if (this.hasScore)
                this.displayed_score = parseFloat(this.dataTable.score) * 100
        },
        methods: {
            send: function () {
                axios({
                    method: 'POST',
                    url: this.dataUrl,
                    data: {
                        activity_id: parseInt(this.dataTable.activity_id),
                        enrollment_progress_id: parseInt(this.dataTable.enrollment_progress_id),
                        score: this.scaledScore
                    }
                })
                    .then(response => {
                        this.disable_field = true;
                    })
                    .catch(error => {
                        console.error(error)
                    })
            }
        },
        data () {
            return {
                displayed_score: 0,
                disable_field: false
            };
        }
    }
</script>

<style scoped>
form .label {
    font-size: 1rem !important;
}
</style>