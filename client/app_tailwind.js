import Vue from 'vue';
import axios from 'axios';
import VueI18n from 'vue-i18n';
import Sortable from 'sortablejs';
import VueCookie from 'vue-cookie';
import VeeValidate from 'vee-validate';
import es from 'vee-validate/dist/locale/es';
import components from './components/tailwind';
import messages from './i18n-locales.js';
import bus from './helpers/bus';

//Window Addons
const token = document.head.querySelector('meta[name="csrf-token"]');
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
window.moment = require('moment');


//Vue Constants

//Vue Plugins
Vue.use(VueI18n);
Vue.use(VueCookie);
Vue.use(VeeValidate, {
    locale: 'es',
    dictionary: {
        es: {
            attributes: messages.es.validation.vee_validate.attributes,
            custom: messages.es.validation.vee_validate.custom
        }
    }
});


Vue.directive('sortable', {
    inserted: function (el, binding) {
        new Sortable(el, binding.value || {})
    }
});

//Vue Filters
Vue.filter('capitalize', function (value) {
    if (!value) return '';
    value = value.toString();
    return value.charAt(0).toUpperCase() + value.slice(1)
});

Vue.filter('lowercase', function (value) {
    if (!value) return '';
    return value.toLowerCase();
});

Vue.filter('uppercase', function (value) {
    if (!value) return '';
    return value.toUpper();
});

Vue.mixin({
    computed: {
        bus: () => { return bus; }
    },
    mounted() {
        VeeValidate.Validator.localize('es', es);
    },
});

const i18n = new VueI18n({
    locale: 'es',
    messages
});

const app = new Vue({
    el: '#app',
    methods: {
        onlyNumber: function (evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                evt.preventDefault();
            } else {
                return true;
            }
        },
    },
    components,
    i18n
});