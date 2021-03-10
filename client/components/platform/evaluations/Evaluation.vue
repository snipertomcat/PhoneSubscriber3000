<template>
    <div class="">
        <template v-if="evaluation.notStarted">
            <div class="evaluation-start eva-modal">
                <div class="background"></div>
                <div class="card">
                    <div class="card-header hidden"></div>

                    <div class="card-content flex flex-wrap content-around justify-center lg:px-64">

                        <h1 class="block w-full text-3xl font-bold text-center">Evaluación</h1>

                        <div class="w-10 h-10 md:w-32 md:h-32 image">
                            <img :src="evaluation_logo" alt="">
                        </div>

                        <p
                            class="mt-2 text-center w-full"
                        >
                            En este reto podrás obtener las evidencias de tu aprendizaje. Recuerda que puedes navegar por los retos cuantas veces lo necesites, ¡éxito!
                        </p>
                    </div>

                    <div class="card-footer flex flex-row align-center justify-end">
                        <button class="block text-blue-medium text-2xl font-bold flex items-center"
                                @click="startEvaluation">
              <span>
                Entendido
              </span>
                            <i class="material-icons">keyboard_arrow_right</i>
                        </button>
                    </div>
                </div>
            </div>
        </template>
        <template v-if="evaluation.started">
            <div class="evaluation">
                <div class="w-11/12 lg:w-3/5 mx-auto">
                    <template v-for="(reactive, index) in component.questions">
                        <div class="reactive" :key="index" :class="{'active':isCurrent(reactive.configurations.index)}">
                            <apithy-reactive :key="index"
                                             :data="reactive"
                                             :number="index + 1"
                                             ref="reactive"
                            >
                            </apithy-reactive>
                        </div>
                    </template>
                </div>
            </div>
        </template>
        <template v-if="evaluation.checked">
            <div class="evaluation-checked eva-modal">
                <div class="background"></div>
                <div class="card">
                    <div class="card-header flex justify-end items-center p-2">
                        <!-- Close button -->

                    </div>

                    <div class="card-content flex flex-wrap content-around justify-center lg:px-64">
                        <!-- Instructor image -->
                        <!--img
                          src="https://i.pinimg.com/564x/1e/62/88/1e6288fed858b3ed34e1e4fe574995f8.jpg"
                          alt="Evan Peters"
                          class="w-16 h-16 rounded-full object-cover"
                        /-->

                        <div class="w-full">
                            <div class="w-10 h-10 md:w-24 md:h-24 mx-auto rounded-full object-cover">
                                <img :src="evaluation_logo" alt="">
                            </div>
                        </div>

                        <div v-if="correct_answer">
                            <!-- Answer feedback title -->
                            <h1 class="block w-full mt-2 text-3xl font-bold text-center">¡Correcto!</h1>

                            <!-- Answer feedback text -->
                            <p
                                class="mt-2 text-center w-full"
                                :class="evaluation.configuration.feedbackText ? '' : 'invisible'"
                            >Cada respuesta acertada suma a tus competencias, ¡sigue así!</p>

                            <!-- Answer score
                            <div class="w-full" :class="evaluation.configuration.score ? '' : 'invisible'">
                                <span class="block text-5xl text-center font-black text-yellow-dark">10</span>
                                <span class="block -mt-3 text-center">Puntos</span>
                            </div>
                            -->
                        </div>
                        <div v-else>
                            <!-- Answer feedback title -->
                            <h1 class="block w-full mt-2 text-3xl font-bold text-center">¡No!</h1>

                            <!-- Answer feedback text -->
                            <p
                                class="mt-2 text-center w-full"
                                :class="evaluation.configuration.feedbackText ? '' : 'invisible'"
                            >Recuerda que puedes revisar los retos cuantas veces sea necesario.</p>

                            <!-- Answer score
                            <div class="w-full" :class="evaluation.configuration.score ? '' : 'invisible'">
                                <span class="block text-5xl text-center font-black text-yellow-dark">10</span>
                                <span class="block -mt-3 text-center">Puntos</span>
                            </div>
                            -->
                        </div>
                    </div>

                    <div
                        class="card-footer bg-yellow-lighter border-t-4 border-yellow-dark flex items-center justify-between px-4">
                        <!-- Retry button -->
                        <button
                            class="block icon-retry text-4xl text-blue-medium"
                            :class="evaluation.configuration.retryButton ? '' : 'invisible'"
                        ></button>

                        <!-- Next question button -->
                        <button class="block text-2xl text-blue-medium" @click="nextReactive">
                            <span>Continuar</span>
                            <i class="material-icons">keyboard_arrow_right</i>
                        </button>
                    </div>
                </div>
            </div>
        </template>
        <template v-if="evaluation.finished">
            <div class="evaluation-end eva-modal">
                <div class="background"></div>
                <div class="card bg-yellow-lighter">
                    <div class="card-header">
                        <!-- Progress bar -->
                        <div class="h-1 w-full mb-1">
                            <div class="h-full bg-yellow-dark" :style="{ width: evaluation.current.progress }"></div>
                        </div>
                    </div>

                    <div class="card-content flex flex-wrap content-around justify-center lg:px-64">
                        <div class="w-10 h-10 md:w-24 md:h-24 image">
                            <img :src="evaluation_logo" alt="">
                        </div>

                        <h1 class="block w-full mt-2 text-3xl font-bold text-center">Tu puntaje es:</h1>

                        <span class="block w-full text-3xl md:text-5xl text-center font-black text-yellow-dark">{{ Math.round((score * 100)) }}%</span>

                        <div class="w-full">
                            <!--<span class="block text-center text-xs">Texto Faltante</span>-->
                            <template v-if="evaluation_passed">
                                <span class="block -mt-1 text-center text-lg">¡Felicidades! Desbloqueaste el siguiente módulo y puedes seguir con tu formación dando clic al botón Siguiente.</span>
                            </template>
                            <template v-else>
                                <span class="block -mt-1 text-center text-lg">El porcentaje mínimo para desbloquear el siguiente módulo es de {{parseInt(evaluation_minimun_required*100)}}%; repasa la información y reintenta la evaluación, ¡mucho éxito!</span>
                            </template>
                        </div>
                    </div>

                    <div class="card-footer border-t-4 border-yellow-dark flex items-center px-4" 
                        :class="{'justify-between': evaluation.configuration.retryButton, 'justify-end': !evaluation.configuration.retryButton}"
                    >
                        <button class="block icon-retry text-4xl text-blue-medium" v-if="evaluation.configuration.retryButton"
                                @click="retryEvaluation"
                        >
                            <i class="fas fa-undo" aria-label="Reintentar"></i>
                        </button>
                        <button
                            type="button"
                            class="btn lg:w-64 h-12 text-white bg-blue-medium hover:bg-blue-darker"
                            :class="{'disabled': !evaluation_passed, 'w-full':!evaluation.configuration.retryButton, 'w-6/12':evaluation.configuration.retryButton}"
                            @click="validateEvaluation"
                        >
                            <template v-if="$parent.lastSession">Terminar</template>
                            <template v-else>Siguiente</template>
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    import {bus} from "../../../app_platform";
    import qs from "qs";
    //import evaluation_json from './evaluations_data.json';
    export default {
        name: "apithy-evaluation",
        props: {
            componentData: {
                required: true,
                type: Object
            },
            minScore:{
                default:-1
            }
        },
        inject: ['getAuthUser', 'getSession', 'getExperience'],
        components: {
            'apithy-reactive': () => import('./Reactive.vue')
        },
        computed: {
            component() {
                return this.componentData
            },
            auth_user() {
                return this.getAuthUser()
            },
            session() {
                return this.getSession()
            },
            evaluation_logo() {
                let company = this.getExperience().company_author;
                return _.get(company, ['full_path_logo'], '/images/grow.svg')
            },
            evaluation_minimun_required() {
                return this.evaluation.configuration.minimun_score;
            },
            evaluation_passed() {
                return this.score >= this.evaluation_minimun_required;
            },
        },
        beforeMount() {
            bus.$on('next-reactive', () => this.nextReactive())
            bus.$on('check-reactive', () => this.checkEvaluation())
            this.initReactives();
        },
        mounted() {
            let firstReactive = _.first(this.componentData.questions);
            this.setCurrent(['firstReactive'], firstReactive);
            let lastReactive = _.last(this.componentData.questions);
            this.setCurrent(['lastReactive'], lastReactive);

            this.setCurrent(['reactive'], firstReactive)
        },
        methods: {
            getCompanyLogo() {
                let copmpany = _.fisrt(this.auth_user.company);
                return _.get(company, ['full_path_logo'], )
            },
            updateUserData(data) {
                this.user_data = data
            },
            initReactives() {
                _.each(this.component.questions, (reactive, index) => {
                    reactive.configurations.index = index;
                })
            },
            startEvaluation() {
                this.evaluation.notStarted = false;
                this.evaluation.started = true;
                this.evaluation.finished = false;
                this.started_at = Math.round(+new Date() / 1000);
            },
            checkEvaluation(value = true) {
                this.evaluation.checked = value;
                let current_reactive = this.getCurrent();

                console.log(current_reactive);
                console.log(this.$refs);

                if(typeof this.$refs.reactive != 'undefined'){
                    this.correct_answer = this.$refs.reactive[current_reactive].checkAnswer();
                }

            },
            validateEvaluation () {
                if (this.score < this.evaluation_minimun_required) {
                    // El porcentaje mínimo para desbloquear el siguiente módulo es de 80%; repasa la información y reintenta la evaluación, ¡mucho éxito!
                    return console.log('El usuario no aprobó la evaluación');
                } else {
                    //¡Felicidades! Desbloqueaste el siguiente módulo y puedes seguir con tu formación dando clic al botón Siguiente.
                    //this.finishEvaluation(false);
                    this.$parent.nextItem();
                }
            },
            finishEvaluation(value = true) {
                this.evaluation.notStarted = false;
                //this.evaluation.started = false;
                this.sendAnswers();
                this.evaluation.finished = value;
            },
            nextItem() {
                if (!this.evaluation.finished) {
                    this.finishEvaluation(false);
                    //this.validateEvaluation();
                }
                this.$parent.nextItem();
            },
            nextReactive() {
                this.checkEvaluation(false);
                let flag = _.get(this.evaluation.current, ['reactive', 'configurations', 'index'], null);
                if (_.isNull(flag)) return console.error('flag is null.');
                let nextReactive = _.get(this.component.questions, [flag + 1], null)
                if (flag + 1 >= _.size(this.component.questions)) {
                    return this.finishEvaluation();
                }
                if (_.isEmpty(nextReactive)) {
                    return console.error('next reactive is empty.')
                }
                ;
                this.setCurrent(['reactive'], nextReactive);

                /*
              this.checkEvaluation(false);
              let current_reactive = this.evaluation.current.reactive;
              let current_index = current_reactive.configurations.index;
              let next_index = current_index + 1;
              let next_reactive = _.get(this.componentData.questions, [next_index], null);

              if (!_.isEmpty(next_reactive) && !_.isNull(next_reactive)) {
                this.setCurrent(['reactive'], next_reactive)
              } else {
                this.nextItem();
              }
              */
            },
            setCurrent(path = [], value = null) {
                if (!_.isEmpty(value))
                    _.set(this.evaluation.current, path, value)
            },
            setAnswer(answer) {
                //console.log(answer)
            },
            isCurrent(reactiveIndex) {
                return _.get(this.evaluation.current, ['reactive', 'configurations', 'index'], -1) === reactiveIndex
            },
            getCurrent() {
                return _.get(this.evaluation.current, ['reactive', 'configurations', 'index'], -1)
            },
            getAnswers() {
                let user_answers = [];
                let vm = this;

                _.each(this.$refs.reactive, (reactive, index) => {
                    user_answers = user_answers.concat(reactive.getUserAnswers());
                })

                return user_answers;
            },
            sendAnswers() {
                let vm = this;
                let eval_params = {
                    enrollment_id: this.session.current_enrollment_progress.enrollment_id,
                    experience_id: this.session.experience_id,
                    session_id: this.session.id,
                    evaluation: this.componentData.id,
                    answers: this.getAnswers(),
                    started_at:this.started_at
                }
                axios({
                    method: 'POST',
                    url: '/user-answer',
                    //params: eval_params,
                    /*paramsSerializer: function (params) {
                        return qs.stringify(eval_params, {encode: false});
                    },*/
                    data: eval_params
                }).then((response) => {
                    vm.score = response.data.data.score;
                    vm.success = response.data.data.sucess;
                    if (response.data.data.score < vm.evaluation.configuration.minimun_score) {
                        this.enableRetry();
                    }
                }).catch((error) => {
                    console.error(error);
                });
            },
            clearAnswers() {
                for( let i = 0; i < _.size(this.$refs.reactive); i++) {
                    let reactive = this.$refs.reactive[i];
                    reactive.resetAnswer();
                }
            },
            enableRetry() {
                this.evaluation.configuration.retryButton = true;
            },
            async retryEvaluation() {
                await this.clearAnswers();
                this.evaluation.current.reactive = this.evaluation.current.firstReactive;
                this.startEvaluation();
            }
        },
        data() {
            return {
                isOpen: false,
                user_data: {},
                user_answers: {},
                score: 0,
                success: false,
                correct_answer: false,
                started_at:null,
                evaluation: {
                    current: {
                        firstReactive: null,
                        lastReactive: null,
                        reactive: null,
                        progress: "100%",
                    },
                    notStarted: true,
                    started: false,
                    finished: false,
                    checked: false,
                    configuration: {
                        feedbackText: true,
                        score: true,
                        minimun_score: this.getExperience().min_evaluation_score,
                        retryButton: false
                    },
                }
            }
        }
    }
</script>

<style lang="scss">
    .render > .card {
        overflow: visible;
    }
</style>
