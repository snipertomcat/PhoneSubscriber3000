<template>
    <div class="">
        <template v-for="(option, index) in shuffled_answers">

            <label
                class="answer-label"
                :key="index"
                :class="[option.checked ?  'answer-label-checked': 'answer-label-unchecked']"
            >
                <!-- Checkbox -->
                <input
                    type="checkbox"
                    class="form-checkbox h-8 w-8 ml-4 text-blue-lighter"
                    :value="option.id"
                    v-model="user_answers"
                />
                <span v-html="option.configurations.html">{{option.configurations.html}}</span>
            </label>

            <div v-if="false" class="multiple-item" :key="index" :data="option" :number="index + 1">
                <div class="row mr-0 ml-0">
                    <div class="col-auto align-self-center">
                        <input :id="`mo-${uid}-${index}`" type="checkbox" :value="option.id" v-model="user_answers">
                    </div>
                    <div class="col align-self-center">
                        <label :for="`mo-${uid}-${index}`" v-html="option.configurations.html">{{
                            option.configurations.html }}</label>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    import {bus} from "../../../app_platform";

    export default {
        name: "apithy-multiple",
        components: {},
        computed: {
            uid() {
                return this._uid
            },
            shuffled_answers() { return _.shuffle(this.component.answers) }
        },
        watch: {
        },
        methods: {
            checkAnswer(event) {
                console.log(this.user_answers,'User');
                console.log(this.getCorrectAnwers(),'Correct');

                return _.isEqual(this.user_answers.sort(), this.getCorrectAnwers().sort());
            },
            setAnswer(data) {
                bus.$emit('apithy-user-answer', this.user_answers);
            },
            getUserAnswers() {
                let vm = this;
                _.each(this.user_answers, (answer) => {
                    let tmp = {
                        answer_id: answer,
                    }
                    vm.users_answers_object.push(tmp);
                })

                return this.users_answers_object;
            },
            getCorrectAnwers(){
                let correct=[];
                _.each(this.component.answers, (answer) => {
                    if(answer.is_correct){
                        correct.push(answer.id);
                    }
                })

                return correct;
            },
            resetAnswer() {
                this.user_answers = [];
                this.users_answers_object = [];
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
                component: this.data,
                user_answers: [],
                users_answers_object: []
            }
        }
    }
</script>

<style>
    .answer-label span p {
        margin-bottom: unset;
    }

    .form-checkbox:after {
        left: 9px;
        top: 5px;
        width: 5px;
        height: 10px;
        border: solid white;
        border-width: 0 3px 3px 0;
        -webkit-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        transform: rotate(45deg);
    }

    .form-checkbox:checked {
        background-color: #2196F3;
        border-color: transparent;
        background-size: 100% 100%;
        background-position: center;
        background-repeat: no-repeat;
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
