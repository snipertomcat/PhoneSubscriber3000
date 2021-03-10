<template>
    <div class="progress-bar">
        <div class="bar-background"></div>
        <div class="bar" :style="{ width: currentPercent + '%' }"></div>
    </div>
</template>

<script>
import bus from '../../../../helpers/bus';
import { mutations } from '../../../../helpers/store';

export default {
    name: 'NavigationProgressBar',
    computed: {
        navigation() {
            return mutations.getNavigation();
        }
    },
    created() {
        bus.$on('updateNavigationBar', () => {
            this.update();
        });
    },
    methods: {
        update() {
            let total = this.navigation.total;
            let current = this.navigation.currentCard + 1;
            let aux = (current / total) * 100;
            aux = aux > 100 ? 100 : aux;
            this.currentPercent = Math.round(aux);
        }
    },
    data() {
        return {
            currentPercent: 0
        };
    }
};
</script>

<style scoped>
.progress-bar {
    width: 100%;
    max-width: 450px;
    margin-left: auto;
    margin-right: auto;
    padding-top: 10px;
    padding-bottom: 10px;
}
.bar-background {
    /*background-color: #fd664a;*/
    background-color: transparent;
    height: 5px;
    border-radius: 0;
    width: 100%;
}
.bar {
    background-color: #ffffff;
    height: 5px;
    border-radius: 5px;
    transform: translateY(-100%);
    transition: width 1s;
}
</style>