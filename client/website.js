import Vue from 'vue';
import axios from 'axios';
import VueI18n from 'vue-i18n';
import VeeValidate from 'vee-validate';
import components from './components/website';
import messages from "./i18n-locales";

const token = document.head.querySelector('meta[name="csrf-token"]');

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;

Vue.use(VueI18n);
Vue.use(VeeValidate, {
    events: 'blur'
});

const i18n = new VueI18n({
    locale: 'es',
    messages
});

const app = new Vue({
    el: '#app',
    data: {},
    mounted(){},
    components,
    i18n,
});