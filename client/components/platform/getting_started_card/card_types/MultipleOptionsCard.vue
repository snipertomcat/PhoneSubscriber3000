<template>
    <div class="multiple-options-card">
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
                        :class="getClass(index)"
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
    name: 'MultipleOptionsCard',
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
    methods: {
        getClass(index) {
            return 'answer answer-' + index;
        },
        select(answer, index) {
            let items = document.querySelectorAll('.answers .answer');
            for (let i = 0; i < items.length; i++) {
                items[i].classList.remove('selected-answer');
            }
            //let result = mutations.evaluate(answer, this.card.type);
            this.displayEffects(answer, index);
        },
        displayEffects(result, index) {
            let query = '.card-' + this.$parent.index + ' .card .card-content';
            let cardContent = document.querySelector(query);
            let isCorrectAnswer = result.is_correct;
            let item = cardContent.querySelector('.answer.answer-' + index);
            item.classList.add('selected-answer');
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
    height: 50px;
    font-weight: bold;
    font-size: 16px;
    line-height: 20px;
    text-align: left;
    align-items: center;
}
.description {
    margin-top: 10px;
    font-size: 16px;
    line-height: 20px;
    text-align: left;
}
.question {
    display: flex;
    font-weight: bold;
    margin-top: 10px;
    font-size: 16px;
    line-height: 20px;
    text-align: left;
    align-items: start;
    justify-content: start;
}
.answers {
    margin-top: 10px;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.answer {
    margin-top: 12px;
    padding: 8px;
    display: flex;
    width: 230px;
    height: 35px;
    left: 51px;
    top: 250px;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    line-height: 20px;
    color: #000000;
    background: #fff2ef;
    box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
    border-radius: 4px;
}
.answer.selected-answer {
    background: #fd664a;
    color: #fff2ef;
}
</style>