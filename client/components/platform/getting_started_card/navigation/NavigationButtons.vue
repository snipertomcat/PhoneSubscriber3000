<template>
    <div class="navigation-buttons">
        <template v-if="!session.started">
            <div class="button start-button" @click="startSession">
                <span>Iniciar</span>
                <span><i class="fas fa-play"></i></span>
            </div>
        </template>
        <template v-else>
            <div class="button next-button" @click="nextCard">
                <span>Siguiente</span>
                <span><i class="fas fa-step-forward"></i></span>
            </div>
            <div
                class="button prev-button"
                v-if="navigation.previousCard !== null"
                @click="previousCard"
            >
                <span>Anterior</span>
                <span><i class="fas fa-step-backward"></i></span>
            </div>
        </template>
    </div>
</template>

<script>
import { mutations } from '../../../../helpers/store';
import bus from '../../../../helpers/bus';
export default {
    name: 'NavigationButtons',
    computed: {
        session() {
            return mutations.getSession();
        },
        cards() {
            return mutations.getCards();
        },
        navigation() {
            return mutations.getNavigation();
        },
        lastCard() {
            let stackAmount = this.navigation.total;
            let currentCard = this.navigation.currentCard;
            return (stackAmount -1) === currentCard;
        }
    },
    mounted() {
        bus.$on('nextCard', this.nextCard);
        bus.$on('previousCard', this.previousCard);
        bus.$emit('updateNavigationBar');
    },
    methods: {
        startSession() {
            mutations.startSession();
            mutations.setNavigation();
            bus.$emit('updateNavigationBar');
        },
        previousCard() {
            let prev = this.navigation.currentCard - 1;
            mutations.setNavigation(prev);
            bus.$emit('resetCard');
            bus.$emit('updateNavigationBar');
            bus.$emit('sortStack');
        },
        nextCard() {
            if (!this.lastCard) {
                let next = this.navigation.currentCard + 1;
                mutations.setNavigation(next);
                bus.$emit('resetCard');
                bus.$emit('updateNavigationBar');
                bus.$emit('sortStack');
            } else {
                mutations.openDialog('closeSession');
                bus.$emit('resetCard');
                window.location.href = '/home';
            }
        }
    },
    data() {
        return {
            skip: false
        };
    }
};
</script>

<style scoped>
.button {
    background-color: unset;
    border-color: unset;
    border: unset;
}
.navigation-buttons {
    display: flex;
    height: 50px;
    width: 90vw;
    max-width: 454px;
    margin-right: auto;
    margin-left: auto;
    align-items: center;
    flex-direction: row-reverse;
    justify-content: space-between;
}
.navigation-buttons .button {
    color: #ffffff;
    font-size: 12px;
    cursor: pointer;
    display: flex;
    align-items: center;
}
.navigation-buttons .button i {
    font-size: 30px;
}
.button.start-button {
    font-size: 18px;
    position: relative;
    right: 0;
}
.button span:last-child {
    margin-left: 16px;
}
</style>