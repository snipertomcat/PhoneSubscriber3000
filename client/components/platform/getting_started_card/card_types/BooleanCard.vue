<template>
    <div class="boolean-card">
        <template v-if="hasTitle">
            <div class="title">
                <span>{{ card.title }}</span>
            </div>
        </template>
        <template v-if="hasDescription">
            <div class="description">
                <span>{{ card.description }}</span>
            </div>
        </template>
        <template v-if="hasQuestion">
            <div class="question">
                <span>{{ card.question }}</span>
            </div>
        </template>
        <template v-if="hasAnswers">
            <div class="answers">
                <template v-for="(answer, index) in card.answers">
                    <div
                        :class="'answer answer-' + index"
                        :key="index"
                        @click="select(answer, index)"
                    >
                        <span>{{ answer.title }}</span>
                    </div>
                </template>
            </div>
        </template>
    </div>
</template>

<script>
import bus from "../../../../helpers/bus";

export default {
    name: 'BooleanCard',
    props: {
        dataCard: {
            required: true,
            default() {
                return {};
            }
        }
    },
    computed: {
        card() {
            return this.dataCard;
        },
        hasTitle() {
            return !!this.card.title;
        },
        hasDescription() {
            return !!this.card.description;
        },
        hasQuestion() {
            return !!this.card.question;
        },
        hasAnswers() {
            return !!this.card.answers && this.card.answers.length > 0;
        }
    },
    mounted() {
        bus.$on('resetCard', () => {
            this.resetCardClases();
        });
    },
    methods: {
        select(answer, index) {
            // let result = mutations.evaluate(answer, this.card.type);
            this.displayEffects(answer, index);
        },
        displayEffects(result, index) {
            let query = '.card-' + this.$parent.index + ' .card .card-content';
            let cardContent = document.querySelector(query);
            let isCorrectAnswer = result.is_correct;
            let item = cardContent.querySelector('.answer.answer-' + index);
            this.resetCardClases();
            if (isCorrectAnswer == 1 || isCorrectAnswer == true) {
                item.classList.add('correct-answer');
                cardContent.classList.remove('wrong');
                cardContent.classList.add('success');
            } else {
                item.classList.add('wrong-answer');
                cardContent.classList.remove('success');
                cardContent.classList.add('wrong');
            }
            bus.$emit('nextCard');
        },
        resetCardClases() {
            document.querySelectorAll('.answer').forEach(item => {
                item.classList.remove('correct-answer');
                item.classList.remove('wrong-answer');
            });
        }
    }
};
</script>

<style scoped>
.boolean-card {
    padding-top: 10%;
    padding-bottom: 10%;
    height: 90%;
}
.title {
    display: flex;
    height: 75px;
    font-weight: bold;
    font-size: 16px;
    line-height: 20px;
    text-align: left;
    align-items: center;
}
.description {
    height: 100px;
    margin-top: 20px;
    font-weight: bold;
    font-size: 16px;
    line-height: 20px;
    text-align: left;
}
.question {
    display: flex;
    min-height: 100px;
    margin-top: 20px;
    font-size: 16px;
    line-height: 20px;
    text-align: left;
    align-items: start;
    justify-content: start;
}
.answers {
    margin-top: 20px;
    height: 75px;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
}
.answer {
    display: flex;
    width: 100px;
    height: 50px;
    left: 168px;
    top: 310px;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    background: #fd664a;
    box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
    border-radius: 4px;
}
.answer.correct-answer {
    background: #18d679;
    color: #fff2ef;
}
.answer.wrong-answer {
    background: rgba(238, 18, 18, 1);
    color: #fff2ef;
}
</style>