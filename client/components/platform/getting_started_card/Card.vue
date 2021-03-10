<template>
    <div class="card" v-if="!!card">
        <div class="card-content">
            <template v-if="isActivity">
                <template v-if="isBooleanCard">
                    <BooleanCard :data-card.sync="card"></BooleanCard>
                </template>
                <template v-if="isMultipleOptionsCard">
                    <MultipleOptionsCard
                        :data-card.sync="card"
                    ></MultipleOptionsCard>
                </template>
                <template v-if="isVideoCard">
                    <VideoCard :data-card.sync="card"></VideoCard>
                </template>
            </template>
            <template v-else>
                <template v-if="isDefaultCard">
                    <ParagraphCard :data-card.sync="card"></ParagraphCard>
                </template>
                <template v-if="isSubsectionsCard">
                    <SectionSwitcherCard
                        :data-card.sync="card"
                    ></SectionSwitcherCard>
                </template>
                <template v-if="isAccordionCard">
                    <AccordionCard :data-card.sync="card"></AccordionCard>
                </template>
                <template v-if="isVideoCard">
                    <VideoCard :data-card.sync="card"></VideoCard>
                </template>
                <template v-if="isGettingStartedCard">
                    <GettingStartedCard :data-card.sync="card" :last-card="isLastCard"></GettingStartedCard>
                </template>
            </template>
        </div>
    </div>
</template>

<script>
import bus from "../../../helpers/bus";
import { mutations } from "../../../helpers/store";
import ParagraphCard from './card_types/ParagraphCard';
import SectionSwitcherCard from './card_types/SectionSwitcherCard';
import AccordionCard from './card_types/AccordionCard';
import BooleanCard from './card_types/BooleanCard';
import MultipleOptionsCard from './card_types/MultipleOptionsCard';
import VideoCard from './card_types/VideoCard';
import GettingStartedCard from './card_types/GettingStartedCard';

export default {
    name: 'Card',
    components: {
        SectionSwitcherCard,
        AccordionCard,
        ParagraphCard,
        BooleanCard,
        MultipleOptionsCard,
        VideoCard,
        GettingStartedCard
    },
    props: {
        cardData: {
            default() {
                return {};
            }
        },
        index: {
            required: true
        }
    },
    computed: {
        isDefaultCard() {
            return (
                this.card.component === 'default' ||
                this.card.component === 'paragraph'
            );
        },
        isSubsectionsCard() {
            return this.card.component === 'section-switcher';
        },
        isAccordionCard() {
            return this.card.component === 'accordion';
        },
        isActivity() {
            return mutations.getSession().type === 'activity';
        },
        isBooleanCard() {
            return this.card.type === 'bool';
        },
        isMultipleOptionsCard() {
            return this.card.type === 'multiple';
        },
        isVideoCard() {
            return this.isActivity
                ? this.card.type === 'video'
                : this.card.component === 'video';
        },
        isGettingStartedCard() {
            return this.card.component === 'getting-started';
        },
        isLastCard() {
            let last = mutations.getNavigation().total - 1;
            let current = this.index;
            return current === last;
        }
    },
    mounted() {
        this.updateCard();
        bus.$on('updateCard', () => {
            this.updateCard();
        });
    },
    methods: {
        updateCard() {
            this.card = this.cardData;
        }
    },
    data() {
        return {
            card: {}
        };
    }
};
</script>

<style scoped>
.card {
    background-color: #ffffff;
    border-radius: 10px;
    width: 100%;
    height: 100%;
    max-width: 454px;
    max-height: 585px;
    margin-left: auto;
    margin-right: auto;
    overflow: hidden;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.15);
    transition: width 0.5s;
}
.card-content {
    padding: 20px 6px;
    height: 100%;
}
.card-content.wrong {
    background: rgb(255, 255, 255);
    background: linear-gradient(
        0deg,
        rgb(255, 255, 255) 0%,
        rgba(238, 18, 18, 0.5) 50%,
        rgb(255, 255, 255) 100%,
        rgba(255, 0, 0, 0.5) 99%
    );
}
.card-content.success {
    background: rgb(255, 255, 255);
    background: linear-gradient(
        0deg,
        rgba(255, 255, 255, 1) 0%,
        rgba(18, 238, 151, 0.5) 50%,
        rgba(255, 255, 255, 1) 99%,
        rgba(18, 238, 151, 1) 99%
    );
}
</style>