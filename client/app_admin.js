import Vue from 'vue';
import axios from 'axios';
import Buefy from 'buefy';
import VueI18n from 'vue-i18n';
import components from './components/admin/index';
import messages from './i18n-locales.js';
import Sortable from 'sortablejs'
import VeeValidate from 'vee-validate';
import es from 'vee-validate/dist/locale/es';
import Snotify, { SnotifyPosition } from 'vue-snotify'
import apithy_constants from './constants';
import VLazyload from 'vue-lazyload';

//import './styles/app_admin.scss'

//import Turbolinks from 'turbolinks';
//import TurbolinksAdapter from 'vue-turbolinks';
//Turbolinks.start();

const token = document.head.querySelector('meta[name="csrf-token"]');

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;

Vue.use(VueI18n);
//Vue.use(TurbolinksAdapter);

// Bulma
Vue.use(Buefy);

Vue.use(VeeValidate, {
    locale: 'es',
    dictionary: {
        es: {
            attributes: messages.es.validation.vee_validate.attributes,
            custom: messages.es.validation.vee_validate.custom
        }
    }
});

const options = {
    global: {
        preventDuplicates:true
    },
    toast: {
        timeout: 6000,
        titleMaxLength: 30,
        bodyMaxLength: 200,
        closeOnClick:true,
        showProgressBar: true,
    }
}

Vue.use(Snotify, options);

// Lazyload
Vue.use(VLazyload);

Vue.directive('sortable', {
    inserted: function (el, binding) {
        new Sortable(el, binding.value || {})
    }
});

Vue.component('apithy-adventures-tree', require('./components/admin/AdventuresTree'));


const i18n = new VueI18n({
    locale: 'es',
    messages
});

//document.addEventListener('turbolinks:load', () => {

Vue.filter('capitalize', function (value) {
    if (!value) return '';
    value = value.toString()
    return value.charAt(0).toUpperCase() + value.slice(1)
});

Vue.mixin({
    data: function () {
        return {
            apithyModal: {
                open: false,
                header: 'Modal Header',
                content: 'Modal Content',
                botton_ok_text: 'Ok',
                ok_method: 'closeModal'
            },
            messageData: {visible: false, content: '', header: '', icon: '', messageClass: {visible: false}},
            route: route,
            tree_info:tree_info,
            apithy_constants: apithy_constants,
        }
    },
    mounted() {
        VeeValidate.Validator.localize('es', es);
    },
    methods: {
        sortableUpdate(event) {
            console.log(event);
        },
    }
});

const app_admin = new Vue({
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
    data: {},
    components,
    i18n
});
