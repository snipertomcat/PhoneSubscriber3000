<template>
    <div class="accordion-card">
        <div class="title">
            {{ card.title }}
        </div>
        <div class="tabs">
            <template v-for="(item, index) in card.elements">
                <div class="tab" :key="index">
                    <input
                        type="checkbox"
                        :id="'item-' + index"
                        :name="'item-' + index"
                    />
                    <div class="tab-label" @click="toggleAccordion(index)">
                        <div class="tab-title">
                            <span>{{ item.title }}</span>
                        </div>
                        <div :class="'icon item-' + index">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                    <div class="tab-content">
                        <span>{{ item.text }}</span>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
export default {
    name: 'AccordionCard',
    props: {
        dataCard: {
            required: true
        }
    },
    mounted() {
        this.card = this.dataCard;
    },
    methods: {
        toggleAccordion(index) {
            let target = 'item-' + index;
            let items = document
                .querySelector('.accordion-card')
                .querySelectorAll('.tab input');
            items.forEach(item => {
                let itemIcon = document.querySelector(
                    '.tab .icon.' + item.id + ' i'
                );
                itemIcon.classList.remove('fa-times');
                itemIcon.classList.add('fa-right-arrow');
                if (item.id === target && !item.checked)
                    setTimeout(() => {
                        item.checked = true;
                        itemIcon.classList.remove('fa-right-arrow');
                        itemIcon.classList.add('fa-times');
                    }, 150);
                item.checked = false;
            });
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
.title {
    margin-top: 15px;
    margin-bottom: 15px;
    font-size: 16px;
    font-weight: bold;
    color: #000000;
    text-align: left;
}
input {
    position: absolute;
    opacity: 0;
    z-index: -1;
}
.tabs {
    overflow: hidden;
    max-height: 100%;
}
.tab {
    width: 100%;
    overflow: hidden;
}
.tab-label {
    display: flex;
    justify-content: space-between;
    padding: 1em;
    font-weight: bold;
    cursor: pointer;
    border-bottom: 1px solid #bebebe;
}
.tab-label .icon {
    width: 1em;
    height: 1em;
    color: #fd664a;
    text-align: center;
    transition: all 0.35s;
}
.tab-content {
    max-height: 0;
    padding: 0 1em;
    text-align: left;
    font-size: 14px;
    line-height: 17px;
    transition: all 0.35s;
}
input:checked + .tab-label .icon {
    transform: rotate(90deg);
}
input:checked ~ .tab-content {
    max-height: 100vh;
    padding: 1em;
}
</style>