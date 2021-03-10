<template>
    <div class="sort-image-container" :id="`sic_${this._uid}`">
        <div v-sortable="sortable_options" v-if="!loading" class="answers grid grid-cols-2 lg:grid-cols-3 gap-2 row-gap-4">
            <div class="image-answer" v-for="(option, index) in component.answers"
                 :class="[option.selected ?  'answer-label-checked': 'answer-label-unchecked']"
                 :key="index"
                 :data="option"
                 :number="index + 1"
                 @click="selectAnswer(option)">
                <div class="answer">
                    <div class="answer-image">
                        <img :src="option.image" alt="option.configurations.imageData.title">
                    </div>
                    <div class="answer-text" v-if="option.configurations.imageText">
                        <span>{{option.configurations.imageText}}</span>
                    </div>
                </div>
                <div class="indicator relative">
                    <div class="absolute left-0"><i class="fas fa-grip-horizontal"></i></div>
                    <div class="num">{{index + 1}}</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {bus} from "../../../app_platform";

    export default {
        name: "apithy-sort",
        inject: ['selectAnswer'],
        components: {},
        computed: {},
        created() {
            this.getCorrectAnswers();
            this.component.answers = _.shuffle(this.component.answers);
            this.updateWeight();
        },
        mounted () {
            setTimeout(() => {
                this.getMinHeight()
            }, 1000)
        },
        methods: {
            getMinHeight () {
                let el = document.getElementById(`sic_${this._uid}`);
                el.style.minHeight = `${el.clientHeight}px`
            },
            checkAnswer(event) {
                let real_answers = [], user_answers = []
                _.forEach(this.correct_answers, answer => {
                real_answers.push({answer_id: answer.answer_id, order: answer.configurations.order.weight})
                })
                _.forEach(this.getUserAnswers(), answer => {
                user_answers.push({answer_id: answer.answer_id, order: answer.configurations.order.weight})
                })
                return _.isEqual(real_answers, user_answers);
            },
            getComponent(component_name) {
                return () => import(`./${component_name}.vue`);
            },
            getUserAnswers() {
                let vm=this;
                this.user_answers=[];
                _.each(this.component.answers, (answer, index) => {
                    let tmp={
                        answer_id:answer.id,
                        configurations: {
                            order: {
                                weight: answer.configurations.order.weight
                            }
                        }
                    }
                    vm.user_answers.push(tmp);
                })

                return vm.user_answers;
            },
            getCorrectAnswers(){
                let vm=this;
                _.each(this.component.answers, (answer, index) => {
                    let tmp={
                        answer_id:answer.id,
                        configurations: {
                            order: {
                                weight: answer.configurations.order.weight
                            }
                        }
                    }
                    vm.correct_answers.push(tmp);
                })
            },
            sortableEnd(event) {
                console.log(event);
                let from = event.oldIndex,
                    to = event.newIndex,
                    array = this.component.answers,
                    item = array[from];
                if (from < to) {
                    array.splice(to + 1, 0, item);
                    array.splice(from, 1);
                } else {
                    array.splice(to, 0, item);
                    array.splice(from + 1, 1);
                }
                this.updateWeight();
                this.reload();
            },
            reload() {
                this.loading = true;
                setTimeout(() => {
                    this.loading = false;
                }, 10);
            },
            updateWeight() {
                _.each(this.component.answers, (answer, index) => {
                    answer.configurations.order.weight = index;
                })
            },
            resetAnswer() {
                this.user_answers = null;
            }
        },
        props: {
            data: {
                required: true,
                type: Object
            },
            user_data: {
                required: false,
                Type: Object
            }
        },
        data() {
            return {
                user_answers : null,
                correct_answers:[],
                component: this.data,
                loading: false,
                sortable_options: {
                    handle: '.image-answer .indicator',
                    onEnd: this.sortableEnd,
                    forceFallback: true
                }
            }
        }
    }
</script>

<style lang="scss" scoped>
    .sort-image-container {

    }
    .answers {
        @apply py-4 text-center;
        @media (min-device-width: 700px) {
            //width: 560px;
        }
        .image-answer {
            @apply mx-auto;
            width: 90%;
            @media (min-device-width: 700px) {
                width: 9rem;
            }
            .indicator {
                @apply w-full h-6 text-center;
                background-color: #F9FAFB;
                cursor: grab;
                .num {
                    @apply w-auto;
                }
            }
            .answer {
                @apply w-full border rounded shadow-md;
                @media (min-device-width: 700px) {
                    width: 9rem;
                }
                .answer-image {
                    @apply flex w-full h-full justify-center items-center;
                    @media (min-device-width: 700px) {
                        height: 9rem;
                    }
                    font-size: 4rem;
                    img {
                        @apply w-full h-full;
                    }
                }
                .answer-text {
                    @apply py-4;
                    .image-text {
                        @apply flex w-full px-2 justify-center items-center;
                    }
                }
            }
        }
    }
    .answer-label span p {
        margin-bottom: unset;
    }

    .img-video {
        width: 274px;
        height: 164px;
    }

    .modal-background {
        background-color: rgba(2, 62, 137, 0.8) !important;
    }

    .m-close-background {
        background-color: transparent !important;
        margin-bottom: 5px;
    }

    .animation-content {
        z-index: 50
    }

    .scrollable {
        overflow: auto;
    }
</style>
