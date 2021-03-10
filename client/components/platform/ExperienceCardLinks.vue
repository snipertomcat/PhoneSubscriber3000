<template>
    <div>
        <div class="" v-if="is_super || is_admin">
            <div class="card-links row">
                <div class="col-12">
                    <button v-if="!has_licence && !in_cart_personal_use" class="button is-primary is-outlined is-uppercase width-100" @click.stop="openModal">
                        Obtener licencias
                    </button>
                    <button v-else class="button is-primary is-outlined is-uppercase width-100" @click.stop="buyLicences">
                        Obtener licencias
                    </button>
                </div>
                <div class="col-12" v-if="$parent.enrolled_button && (has_licence || enrolled)">
                    <button class="button is-info is-uppercase width-100"
                       v-bind:href="route('experience.viewer.enroll',{'experience':course.system_id,'enrollment_id': lastEnrollmentId })"
                       v-bind:class="{ loading: $parent.loading }"
                       @click.stop="$parent.continueExperience">
                        Continuar
                    </button>
                </div>
                <div class="col-12" v-if="!$parent.enrolled_button && has_licence">
                    <button class="button is-primary is-outlined is-uppercase width-100" @click="$parent.enroll()">
                        Iniciar
                    </button>
                </div>
                <div class="col-12" v-else>
                    <button class="button is-primary is-uppercase width-100" v-bind:href="route('experience.preview',{'experience':course.system_id})"  @click.stop="$parent.details">
                        Detalles
                    </button>
                </div>
                <div class="col-12" v-if="licences > 0">
                    <div class="button width-100 available-licences">
                       <a  v-bind:href="route('experiences.show',{'experience':course.system_id}).toString()+'/buy-licences'">
                        <span><i class="icon fa fa-users"></i></span>
                        <span>{{licences}} licencias sin asignar</span>
                       </a>
                    </div>
                </div>
                <!--
                <div class="col-12">
                    <div class="button width-100 insufficient-licences">
                        <span><i class="icon fa fa-users"></i></span>
                        <span> Licencias insuficientes</span>
                    </div>
                </div>
                -->
            </div>
        </div>
        <div class="" v-else>
            <div class="card-links row" v-if="assignation">
                <div class="col-12">
                    <button class="button is-primary is-uppercase width-100" v-bind:href="route('experience.preview',{'experience':course.system_id})">
                        <i class="eye icon"></i>
                        Detalles
                    </button>
                </div>
            </div>
            <div class="card-links row" v-else>
                <div class="col-12">
                    <button class="button is-primary is-uppercase width-100" v-bind:href="route('experience.preview',{'experience':course.system_id})"  @click.stop="$parent.details">
                        Detalles
                    </button>
                </div>
                <div class="col-12" v-if="$parent.enrolled_button && has_licence">
                    <button class="button is-info is-uppercase width-100"
                       v-bind:href="route('experience.viewer.enroll',{'experience':course.system_id,'enrollment_id': lastEnrollmentId })"
                       v-bind:class="{ loading: $parent.loading }"
                       @click.stop="$parent.continueExperience">
                        Continuar
                    </button>
                </div>
                <div class="col-12" v-if="!$parent.enrolled_button && (has_licence || course.is_free)">
                    <button class="button is-primary is-outlined is-uppercase width-100" @click="$parent.enroll()">
                        Iniciar
                    </button>
                </div>
                <div class="col-12" v-if="!has_licence && !course.is_free ">
                    <button class="button is-primary is-outlined is-uppercase width-100" @click="$parent.purchase()" :disabled="in_cart_personal_use">
                        {{ (in_cart_personal_use) ? 'En el carrito' : 'Comprar'}}
                    </button>
                </div>
            </div>
        </div>
        <b-modal :active.sync="show_modal" :width="900">
            <div class="card">
                <div class="card-content">
                    <div class="media">
                        <div class="media-content">
                            <p class="title is-4">Tipo de licencia</p>
                        </div>
                    </div>

                    <div class="content">
                        <div class="row">
                            <div class="col-12">
                                <span>¿Quieres adquirir la experiencia </span>
                                <span class="has-text-weight-bold">{{experience_name}}</span>
                                <span> para uso personal o de tu empresa?</span>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <a class="button is-primary width-100 shadow text-white"
                                   @click.stop="closeModal();$parent.purchase();">
                                    Uso personal
                                </a>
                            </div>
                            <div class="col-12 col-md-6">
                                <a class="button is-primary width-100 shadow text-white"
                                   v-bind:href="route('experiences.show',{'experience':course.system_id}).toString()+'/buy-licences'">
                                    Uso empresarial
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </b-modal>
    </div>
</template>

<script>
    export default {
        name: "ExperienceCardLinks",
        props: {
            enrolled: {
                type: Boolean,
                default: false
            },
            course: {
                type: Object,
                required: true
            },
            in_cart_personal_use: {
                type: Boolean,
                required: false,
                default: false
            },
            in_cart_personal_company: {
                type: Boolean,
                required: false,
                default: false
            }
        },
        computed: {
            assignation() {
                if (this.course.assignation && this.course.assignation.type === 'mandatory') {
                    return true;
                }
                return false;
            },
            lastEnrollmentId(){
                let enrollmentId=0;
                if('enrollments_data' in this.course.current_user_progress ){
                    if(this.course.current_user_progress.enrollments_data.length){
                        if(this.course.type == 'journey'){
                            this.course.current_user_progress.enrollments_data.forEach((enrolledExperience) => {
                                enrolledExperience.forEach((progress) => {
                                    if (progress.status == 2) {
                                        enrollmentId=progress.enrollment_id;
                                    }
                                });
                            });

                        }else{
                            enrollmentId=this.course.current_user_progress.enrollments_data[0].enrollment_id;
                        }
                    }
                }

                return enrollmentId;
            },
            is_admin() {
                return this.$parent.user.is_admin;
            },
            is_super() {
                return this.$parent.user.is_super;
            },
            experience_name() {
                return this.$parent.course.title;
            },
        },
        methods: {
            openModal() {
                this.show_modal = true;
            },
            closeModal() {
                this.show_modal = false;
            },
            buyLicences(){
                let got_to=route('experiences.show',{'experience':this.course.system_id}).toString()+'/buy-licences';
                window.location=route('experiences.show',{'experience':this.course.system_id}).toString()+'/buy-licences';
            },
        },
        data() {
            return {
                show_modal: false,
                licences: this.course.admin_available_licences,
                has_licence: this.course.auth_user_has_licence,
                is_cart_added: this.course.cart_added,
            }
        }
    }
</script>

<style scoped>
    .shadow {
        box-shadow: 3px 3px 10px -2px #555;
    }
    .card {
        border-radius: 0px !important;
    }
    .card-content {
        max-height: none;
    }
    .card-content .card-links a:hover {
        color: #fff !important;
    }
    .card-content .card-links>div {
        margin-bottom: 10px;
    }
    .available-licences {
        background-color: #59D694;
        color: #ffffff;
        cursor: default;
    }
    .insufficient-licences {
        background-color: #F5A623;
        color: #ffffff;
        cursor: default;
    }
    .icon {
        margin-right: 2px !important;
    }
</style>