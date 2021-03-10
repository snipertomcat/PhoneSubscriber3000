<template>
    <div class="">
        <div v-sortable="sortable_options" v-if="!loading">
            <div class="answer-label rounded" v-for="(option, index) in component.answers"
                 :class="[option.selected ?  'answer-label-checked': 'answer-label-unchecked']"
                 :key="index"
                 :data="option"
                 :number="index + 1"
                 @click="selectAnswer(option)">
                <span class="inline-flex ml-2 text-2xl fas fa-grip-vertical handle"></span>
                <span v-html="option.configurations.html">{{ option.configurations.html }}</span>
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
        methods: {
            checkAnswer(event) {
                console.log(this.user_answers);
                console.log(this.correct_answers);

                return _.isEqual(this.getUserAnswers(), this.correct_answers);
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
                    handle: '.handle',
                    onEnd: this.sortableEnd
                }
            }
        }
    }
</script>

<style>
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
