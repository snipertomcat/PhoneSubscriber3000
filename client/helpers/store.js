import Vue from 'vue';
import bus from "./bus";

export const store = Vue.observable({
    isSideMenuOpen: false,
    selectedSession: null,
    showDialog: false,
    currentCard: {},
    navigation: {
        currentCard: null,
        previousCard: null,
        nextCard: null,
        total: null
    },
});

export const mutations = {
    toogleSideMenu() {
        store.isSideMenuOpen = !store.isSideMenuOpen;
    },
    closeSideMenu() {
        store.isSideMenuOpen = false;
    },
    openSideMenu() {
        store.isSideMenuOpen = true;
    },
    openDialog() {
        store.showDialog = true;
    },
    closeDialog(query = null) {
        store.showDialog = false;
        mutations.processQuery(query);
    },
    processQuery(query) {
        if (query === 'closeSession') {
            mutations.closeSession();
        }
    },
    setSession(session) {
        store.selectedSession = session;
    },
    getSession() {
        return store.selectedSession;
    },
    startSession() {
        if (
            store.selectedSession !== null &&
            store.selectedSession !== 'undefined'
        ) {
            if (!store.selectedSession.started) {
                store.selectedSession.started = true;
                // do something
            }
            bus.$emit('updateCard');
        }
    },
    closeSession() {
        if (
            store.selectedSession !== null &&
            store.selectedSession !== 'undefined'
        ) {
            store.selectedSession.started = false;
            store.selectedSession = null;
        }
    },
    openSession() {
        mutations.closeDialog();
        // router.push({ name: 'session' });
    },
    leaveSession() {
        // save the current session
        mutations.setCurrentCard({});
        mutations.closeDialog();
        mutations.closeSession();
        // router.push({ name: 'home' });
    },
    getCards() {
        if (store.selectedSession.type === 'activity' && 'questions' in store.selectedSession)
            return store.selectedSession.questions;
        return store.selectedSession.cards;
    },
    setCurrentCard(card) {
        store.currentCard = card;
        bus.$emit('updateCard');
    },
    getCurrentCard() {
        return store.currentCard;
    },
    setNavigation(current = 0) {
        if (store.selectedSession === null)
            return console.error('not session stabilised');
        if (current === 0)
            store.navigation.total = store.selectedSession.cards.length;
        store.navigation.nextCard = current < (store.navigation.total - 1) ? current + 1 : null;
        store.navigation.previousCard = current > 0 ? current - 1 : null;
        store.navigation.currentCard = current;
    },
    getNavigation() {
        return store.navigation;
    },
    evaluate(answer, evaluationType) {
        // methods.evaluate();
        return {
            evaluationType: evaluationType,
            correctAnswer: answer.is_correct,
            selectedAnswer: answer
        };
    }
};