<template>
    <div class="" v-if="selected_end">
        <template v-for="(option, index) in data.answers">
            <div class="answer-label rounded"
                 :class="[option.selected ?  'answer-label-checked': 'answer-label-unchecked']"
                 :key="index"
                 :data="option"
                 :number="index + 1"
                 @click="setAnswer(index)">
                <template v-if="!!option.configurations.html">
                    <span v-html="option.configurations.html">{{ option.configurations.html}}</span></template>
                <template v-else>
                    <span v-html="option.title">{{ option.title }}</span>
                </template>
            </div>
        </template>
    </div>
</template>

<script>
    import {bus} from "../../../app_platform";

    export default {
        name: "apithy-bool",
        inject: ['selectAnswer'],
        components: {},
        computed: {},
        mounted () {
            this.resetAnswer()
        },
        methods: {
            checkAnswer(event) {
                return this.correct_answer;
            },
            setAnswer (answer_index) {
                this.selected_end=false;
                let vm=this;
                _.each(this.data.answers, (answer, index) => {
                    if (index === answer_index) {
                        answer.selected = true;
                        this.selectAnswer(answer);
                        this.user_answers = answer.id;
                        vm.user_answers = answer.id;

                        if(answer.is_correct){
                            this.correct_answer=true;
                        }else{
                            this.correct_answer=false;
                        }
                    }
                    else {
                        answer.selected = false;
                    }

                })
                this.selected_end=true;
            },
            getUserAnswers(){
                let tmp={
                    answer_id:this.user_answers
                }
                return [tmp];
            },
            resetAnswer() {
                this.user_answers = null;
                _.each(this.data.answers, answer => {
                    answer.selected = false;
                })
                this.selected_end = false;
                setTimeout(() => { this.selected_end = true }, 500)
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
                selected_end:true,
                correct_answer:false
            }
        }
    }
</script>

<style scoped>
    .option {
        margin-top: 1rem;
        margin-bottom: 1rem;
    }
    .option button {
        min-width: 10rem;
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
