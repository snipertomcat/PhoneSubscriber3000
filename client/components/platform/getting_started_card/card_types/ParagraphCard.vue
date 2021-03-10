<template>
    <div class="paragraph">
        <template v-if="hasTitle">
            <div class="paragraph-title">
                <span>{{ card.title }}</span>
            </div>
        </template>
        <template v-if="hasImage && positionTop">
            <div class="paragraph-image">
                <img :src="card.elements.image" alt="" />
            </div>
        </template>
        <template v-if="hasSecondTitle">
            <div class="paragraph-second-title">
                <span>{{ card.elements.title }}</span>
            </div>
        </template>
        <template v-if="hasImage && positionCenter">
            <div class="paragraph-image">
                <img :src="card.elements.image" alt="" />
            </div>
        </template>
        <template v-if="hasDescription">
            <div class="paragraph-text">
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
        <template v-if="hasImage && positionBottom">
            <div class="paragraph-image">
                <img :src="card.elements.image" alt="" />
            </div>
        </template>
    </div>
</template>

<script>
export default {
    name: 'ParagraphCard',
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
        positionTop() {
            return (
                !this.card.elements.image_position ||
                this.card.elements.image_position.toLowerCase() === 'top'
            );
        },
        positionCenter() {
            return this.card.elements.image_position.toLowerCase() === 'center';
        },
        positionBottom() {
            return this.card.elements.image_position.toLowerCase() === 'bottom';
        },
        hasImage() {
            return !!this.card.elements.image;
        },
        hasTitle() {
            return !!this.card.title;
        },
        hasSecondTitle() {
            return !!this.card.elements.title;
        },
        hasDescription() {
            return !!this.card.elements.text;
        },
        textIsArray() {
            return Array.isArray(this.card.elements.text);
        }
    }
};
</script>

<style scoped>
.paragraph-title {
    height: 50px;
    font-size: 16px;
    font-weight: bold;
    margin-left: auto;
    margin-right: auto;
}
.paragraph-second-title {
    height: 50px;
    font-size: 16px;
    font-weight: bold;
    margin-left: auto;
    margin-right: auto;
    display: flex;
    align-items: center;
}
.paragraph-image {
    width: 112px;
    height: auto;
    margin-left: auto;
    margin-right: auto;
}
.paragraph-image img {
    width: 100%;
    height: 100%;
}
.paragraph-text {
    text-align: left;
    line-height: 20px;
    font-size: 16px;
}
</style>