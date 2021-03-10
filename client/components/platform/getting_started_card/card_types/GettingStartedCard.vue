<template>
    <div class="getting-started-card">
        <template v-if="hasImage || true">
            <div class="gs-image">
                <img :src="card.elements.image" alt="" />
            </div>
        </template>
        <div class="row no-gutters mr-0 ml-0">
            <template v-if="hasStep">
                <div class="col-4 text-center">
                    <div class="gs-step">
                        <span>{{ card.elements.step }}</span>
                    </div>
                </div>
            </template>
            <div :class="{ 'col': !hasStep, 'col-8':hasStep }">
                <template v-if="hasTitle">
                    <div class="gs-title">
                        <span>{{ card.elements.title }}</span>
                    </div>
                </template>
                <template v-if="hasDescription">
                    <div class="gs-text">
                        <template v-if="textIsArray">
                            <template v-for="(paragraph, index) in card.elements.text">
                                <p :key="index" v-html="paragraph">{{ paragraph }}</p>
                            </template>
                        </template>
                        <template v-else>
                            <p v-html="card.elements.text">
                                {{ card.elements.description }}
                            </p>
                        </template>
                    </div>
                </template>
                <template v-if="isLast">
                    <div class="gs-next-button">
                        <div class="button" @click="nextCard">
                            <span>Comencemos</span>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
    import bus from '../../../../helpers/bus';
    import {mutations} from "../../../../helpers/store";
    export default {
        name: "GettingStartedCard",
        props: {
            dataCard: {
                required: true,
                default() {
                    return {};
                }
            },
            lastCard: {
                required: false,
                type: Boolean,
                default: false
            }
        },
        computed: {
            card() {
                return this.dataCard;
            },
            hasImage() {
                return !!this.card.elements.image;
            },
            hasStep() {
                return !!this.card.elements.step;
            },
            hasTitle() {
                return !!this.card.elements.title;
            },
            hasDescription() {
                return !!this.card.elements.text;
            },
            textIsArray() {
                return Array.isArray(this.card.elements.text);
            },
            isLast() {
                return this.lastCard;
            }
        },
        methods: {
            nextCard() {
                bus.$emit('nextCard');
            }
        }
    }
</script>

<style scoped>
.getting-started-card {}
.button {
    background-color: unset;
    border-radius: unset;
    border: unset;
    color: unset;
}
.gs-image {
    width: 100%;
    max-height: 285px;
    min-height: 250px;
    display: flex;
    justify-content: center;
}
.gs-step {
    height: 200px;
    font-style: normal;
    font-weight: bold;
    font-size: 200px;
    line-height: 200px;
    color: rgba(26, 107, 247, 0.5);
}
.gs-title {
    margin-top: 14px;
    font-style: normal;
    font-weight: bold;
    font-size: 30px;
    line-height: 37px;
    text-align: center;
    color: #1A6BF7;
}
.gs-text {
    margin-top: 13px;
    font-style: normal;
    font-weight: normal;
    font-size: 20px;
    line-height: 24px;
    text-align: center;
}
.col-8 .gs-title {
    text-align: left;
}
.col-8 .gs-text {
    text-align: left;
}
.gs-next-button {
    width: 100%;
    display: flex;
    margin-top: 45px;
    justify-content: center;
}
.gs-next-button .button {
    width: 249px;
    height: 48px;
    background: #1A6BF7;
    border-radius: 4px;
    box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
}
.gs-next-button .button span {
    font-style: normal;
    font-weight: 600;
    font-size: 16px;
    line-height: 20px;
    text-align: center;
    color: #FFFFFF;
}
@media only screen and (min-device-width: 900px) {
    .gs-step {
        height: 200px;
        font-style: normal;
        font-weight: bold;
        font-size: 150px;
        line-height: 200px;
        color: rgba(26, 107, 247, 0.5);
    }
}
</style>