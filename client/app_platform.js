//import Turbolinks from 'turbolinks';
import Vue from 'vue';
import Buefy from 'buefy'
import axios from 'axios';
import VueI18n from 'vue-i18n';
import components from './components/platform';
import messages from './i18n-locales.js';
import Snotify from 'vue-snotify';
import Sticky from 'vue-sticky-directive'
import VueCookie from 'vue-cookie';
import apithy_constants from './constants';
import Money from 'vue-money';
import VeeValidate from 'vee-validate';
import es from 'vee-validate/dist/locale/es';
import VLazyload from 'vue-lazyload';
import VueBar from 'vuebar';
import Sortable from 'sortablejs';


//CSS Imports
//import './styles/app_platform.scss';

//Window Addons
const token = document.head.querySelector('meta[name="csrf-token"]');
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
window.moment = require('moment');


//Vue Constants
const options = {
    global: {
        preventDuplicates:true,
    },
    toast: {
        timeout: 10000,
        titleMaxLength: 35,
        bodyMaxLength: 200,
        closeOnClick:true,
        showProgressBar: false,
    }
}

//Turbolinks.start();


//Vue Plugins
//Vue.use(TurbolinksAdapter);
Vue.use(VueI18n);
Vue.use(Buefy);
Vue.use(Snotify, options);
Vue.use(Sticky);
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
Vue.use(VLazyload);
Vue.use(Money, {
    places: 2,
    symbol: '$',
    format: /%money%/,
    directive: 'money-format',
    global: 'moneyFormat'
});
Vue.use(VueBar);


//Vue Filters
Vue.filter('capitalize', function (value) {
    if (!value) return '';
    value = value.toString()
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

Vue.directive('sortable', {
    inserted: function (el, binding) {
        new Sortable(el, binding.value || {})
    }
});


//document.addEventListener('turbolinks:load', () => {


Vue.mixin({
    data: function () {
        return {
            money: {
                decimal: '.',
                thousands: ',',
                prefix: 'R$ ',
                suffix: ' #',
                precision: 2,
            },
            apithy_constants: apithy_constants,
            showNav: false
        }
    },
    methods: {
        sortableUpdate(event) {
            console.log(event);
        },
        route: route,

    },
    mounted() {
        VeeValidate.Validator.localize('es', es);
    },
});

const i18n = new VueI18n({
    locale: 'es',
    messages
});

const bus = new Vue()
export {bus}

const app = new Vue({
    el: '#app',
    data: {},
    mounted() {
        this.$nextTick(() => {
            init();
        });
    },
    methods: {
        updateIframeSize(e) {
            alert("Evento");
        },
        onlyNumber: function (evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                evt.preventDefault();
                ;
            } else {
                return true;
            }
        },
    },
    components,
    i18n
});

window.addEventListener('message', function (e) {
    if (e.data.event == 'apithy-iframe-size') {
        app.$emit('apithy-iframe-size', e.data.height);
    }

    if (e.data.event == 'apithy-iframe-size') {
        app.$emit('apithy-html-content', e.data.height);
    }

    if (e.data.event == 'apithy-video-finished') {
        app.$emit('apithy-video-finished');
    }
});


function init() {
}

//});