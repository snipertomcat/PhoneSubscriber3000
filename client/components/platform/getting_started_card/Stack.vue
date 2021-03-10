<template>
    <div class="stack">
        <template v-for="(card, index) in stack">
            <div :class="'card-container animated card-' + index" v-bind:key="index">
                <Card :card-data="card" :index="index"></Card>
            </div>
        </template>
    </div>
</template>

<script>
import Card from './Card';
import bus from "../../../helpers/bus";
import { mutations } from '../../../helpers/store';
export default {
    name: 'Stack',
    components: { Card },
    created() {
        this.stack = this.cards;
        bus.$on('sortStack', () => {
            this.sortStack();
        });
    },
    mounted() {
        this.sortStack();
    },
    beforeDestroy() {
        bus.$off('sortStack');
    },
    computed: {
        cards() {
            return mutations.getCards();
        },
        currentCard() {
            return mutations.getNavigation().currentCard;
        }
    },
    methods: {
        onTop(index) {
            return index === this.currentCard;
        },
        underTop(index) {
            return index > this.currentCard;
        },
        overTop(index) {
            return index < this.currentCard;
        },
        sortStack() {
            let length = document.querySelectorAll('.stack .card-container').length;
            for (let i = 0; i < length; i++) {
                let card = document.querySelector('.card-' + i);
                let y = 10 * (i - this.currentCard);
                let w = 4 * (i - this.currentCard);
                if (card) {
                    card.style.zIndex = length - i;
                    card.style.transform = null;
                    if (this.overTop(i)) {
                        card.classList.remove('stacked');
                        card.classList.add('fadeOutLeft');
                        setTimeout(() => {
                            card.classList.add('slided-card');
                            card.classList.remove('fadeOutLeft');
                            card.classList.add('fadeInLeft');
                        }, 500);
                    }
                    if (this.onTop(i)) {
                        card.classList.remove('stacked');
                        card.classList.remove('slided-card');
                        card.querySelector('.card').style.width = 100 - w + '%';
                        setTimeout(() => {
                            card.classList.remove('fadeInLeft');
                        }, 500);
                    }
                    if (this.underTop(i)) {
                        card.classList.remove('rotateInDownLeft');
                        card.classList.remove('slided-card');
                        card.classList.add('stacked');
                        card.style.transform = 'translate(-50%, ' + y + 'px)';
                        card.querySelector('.card').style.width = 100 - w + '%';
                    }
                }
            }
        },
        isCurrentCard(index = null) {
            if (index === null) return false;
            return this.currentCard === index;
        }
    },
    data() {
        return {
            stack: []
        };
    }
};
</script>

<style scoped>
.stack {
    position: relative;
    display: inline-flex;
    min-height: 625px;
    width: 90vw;
    max-width: 454px;
    margin-left: auto;
    margin-right: auto;
}
.card-container {
    position: absolute;
    top: 0;
    width: 100%;
    height: 90%;
}
.card-container.stacked {
    left: 50%;
    transform: translateX(-50%);
}
.slided-card {
    display: none;
}
</style>