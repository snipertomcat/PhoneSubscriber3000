<template>
    <div class="section-switcher-card">
        <div class="title">
            {{ card.title }}
        </div>
        <div class="section-switcher">
            <template v-for="(item, index) in card.elements">
                <div class="tab" :key="index">
                    <input type="checkbox" :id="item.id" :name="item.id" />
                    <div
                        class="tab-label"
                        @click="toggleSectionSwitcher(item.id)"
                    >
                        <div class="tab-title">
                            <span>{{ item.title }}</span>
                        </div>
                        <div :class="'icon ' + item.id">
                            <i
                                :class="{
                                    fas: true,
                                    'fa-arrow-left': item.open,
                                    'fa-arrow-right': !item.open
                                }"
                            ></i>
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
    name: 'SectionSwitcherCard',
    props: {
        dataCard: {
            required: true
        }
    },
    mounted() {
        this.card = this.dataCard;
        this.card.elements.forEach((item, index) => {
            item.open = false;
            item.id = 'item-' + index;
        });
    },
    methods: {
        toggleSectionSwitcher(target) {
            let items = this.card.elements;
            let anyChecked = false;
            items.forEach(item => {
                let el = document.querySelector('.tab input#' + item.id);
                el.checked = item.id === target && !el.checked;
                item.open = el.checked;
                if (item.open) anyChecked = true;
            });

            let docItems = document.querySelectorAll('.section-switcher .tab');
            docItems.forEach(item => {
                let input = item.querySelector('input');
                if (anyChecked) {
                    if (input.id !== target) item.classList.add('hide');
                } else {
                    item.classList.remove('hide');
                }
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
.subsection-title {
    display: flex;
    font-size: 16px;
    text-align: left;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 20px;
    padding-top: 20px;
    border-bottom: 1px solid #bebebe;
    cursor: pointer;
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
.tab.hide {
    display: none;
}
.tab-label {
    display: flex;
    justify-content: space-between;
    padding-top: 20px;
    padding-bottom: 20px;
    font-weight: bold;
    cursor: pointer;
    border-bottom: 1px solid #bebebe;
    text-align: left;
}
.tab-label .icon {
    width: 1em;
    height: 1em;
    color: #fd664a;
    font-size: 36px;
    text-align: center;
    transition: all 0.35s;
}
.tab-content {
    max-height: 0;
    font-size: 16px;
    text-align: left;
    padding-top: 16px;
    line-height: 20px;
    transition: all 0.35s;
}
input:checked + .tab-label .icon {
    transform: rotate(180deg);
}
input:checked ~ .tab-content {
    max-height: 100vh;
}
</style>