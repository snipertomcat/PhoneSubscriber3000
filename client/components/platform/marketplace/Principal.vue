<template>
    <div style="display: contents">
        <div class="row no-gutters" style="min-height: 75%">
            <div class="col mt-md-0">
                <div class="store-cart-notification" v-if="has_experience_added">
                    <div class="d-none d-md-block desktop">
                        <div class="card">
                            <div class="card-content container">
                                <div class="row ml-0 mr-0 added-experience">
                                    <div class="close">
                                        <b-button type="is-white" icon-left="times" icon-pack="fas" @click="cleanAddedExperience"></b-button>
                                    </div>
                                    <div class="col-auto">
                                        <img class="cover" :src="experience_added.full_path_cover" alt="">
                                    </div>
                                    <div class="col-4 font-18">
                                        <div class="has-text-weight-bold">{{ $t('messages.marketplace.has_added_to_cart') }}:</div>
                                        <div class="">{{ experience_added.title }}</div>
                                    </div>
                                    <div class="col align-self-center">
                                        <div class="row mr-0 ml-0">
                                            <div class="col-6">
                                                <a :href="route('experience.preview', {experience:experience_added.system_id})" class="button is-primary is-fullwidth has-text-weight-bold has-text-white">{{ $t('messages.marketplace.view_details') }}</a>
                                            </div>
                                            <div class="col-6">
                                                <a :href="route('shopping-cart.checkout')" class="button is-primary is-fullwidth has-text-weight-bold has-text-white">{{ $t('messages.marketplace.go_to_checkout') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-block d-md-none mobile">
                        <b-modal :active.sync="has_experience_added">
                            <div class="m-close-background">
                                <div class="text-right has-text-white">
                                <span class="pointer ml-0 mr-2" @click="cleanAddedExperience">
                                  <span class="mr-2">Cerrar</span>
                                  <i class="fa fa-times" aria-hidden="true"></i>
                                </span>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-content">
                                    <div class="row">
                                        <div class="col-auto font-18"><i class="icon-market-cart"></i></div>
                                        <div class="col font-18 has-text-weight-bold">Añadido a carrito</div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col font-14">Tu experiencia ha sido añadida al carrito.</div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-12 text-right font-14">
                                            <a :href="route('shopping-cart.checkout')" class="button is-link is-inverted has-text-weight-bold">{{ $t('messages.marketplace.view_cart') }}</a>
                                        </div>
                                        <div class="col-12 text-right ont-14">
                                            <a class="button is-link is-inverted has-text-weight-bold" @click="cleanAddedExperience">Seguir comprando</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </b-modal>
                    </div>
                </div>
               <div class="" v-if="has_experiences || is_apithy_store">
                   <div class="experiences desktop d-none d-md-block width-100">
                       <div class="row no-gutters">
                           <div class="p-3" :class="{'col-md-12 col-lg-6 col-xl-4':menu.expanded, 'col-md-6 col-lg-4 col-xl-3':!menu.expanded}" v-for="experience in experiences_list.data">
                               <card-element-owner :element="experience" :show-progress="showProgress" :current-enrollment="getCurrentEnrollment(experience.id)" v-if="userData.is_admin"></card-element-owner>
                               <card-element :element="experience" :show-progress="showProgress" :current-enrollment="getCurrentEnrollment(experience.id)" v-else></card-element>
                           </div>
                       </div>
                   </div>
                   <div class="experiences mobile d-block d-md-none">
                       <div class="row no-gutters">
                           <div class="p-3 col-12" v-for="experience in experiences_list.data">
                               <card-element-owner :element="experience" :show-progress="showProgress" :current-enrollment="getCurrentEnrollment(experience.id)" v-if="userData.is_admin"></card-element-owner>
                               <card-element :element="experience" :show-progress="showProgress" :current-enrollment="getCurrentEnrollment(experience.id)" v-else></card-element>
                           </div>
                       </div>
                   </div>
               </div>
                <div class="row mr-0 ml-0 no-gutters justify-content-center align-items-center height-100" v-else>
                    <div class="col-6 col-md-3">
                        <img src="/images/resources/png/vacio.png" />
                        <h1 class="text-center">¡A&uacute;n no tienes experiencias!</h1>
                        <a class="text-center block-centered" href="/home">¡Sal a explorar!</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { Tooltip } from 'element-ui';
    import TabMenu from '../OwnerTabsMenu';
    import CardElement from './CardElement';
    import OwnerCardElement from './OwnerCardElement';
    import { index } from "../../../helpers";

    const JSON_FILE = [
        //{icon: 'icon-clock', label: 'Recientes', amount: 10, link: ''},
        {icon: 'icon-list-clipboard', label: 'Asignadas', amount: 0, route_name:'experience.assigned', link: route('experience.assigned')}, // assigned
        {icon: 'icon-market-cart', label: 'Compradas', amount: 0, route_name:'experience.buyed', link: route('experience.buyed', { use_for: 'personal_use' })}, // buyed-by personal use
        //{icon: 'icon-star', label: 'Favoritas', amount: 2, link: ''},
        //{icon: 'icon-tag', label: 'Mi colección 1', amount: 3, link: ''},
        //{icon: 'icon-tag', label: 'Mi colección 2', amount: 2, link: ''},
        {icon: 'icon-hand-air-balloon', label: 'Todas', amount: 0, route_name:'experience.student', link: route('experience.student')}, // experiences
    ];

    export default {
        name: "Marketplace",
        components: {
            'card-element': CardElement,
            'card-element-owner': OwnerCardElement,
            'tabs-menu': TabMenu,
            'el-tooltip': Tooltip,
        },
        props: {
            experiences: {
                required: true,
                type: Object
            },
            userData: {
                required: false,
                type: Object,
                default: {}
            }
        },
        computed: {
            is_apithy_store() {
                return route().current('experiences.index')
            },
            is_filter_selected: function () {
                return !_.isEmpty(this.menu.selected_filter);
            },
            selected_filter_has_icon: function () {
                return this.is_filter_selected && !_.isEmpty(this.menu.selected_filter.icon);
            },
            selected_filter: function () {
                return this.menu.selected_filter;
            },
            has_experience_added: function () {
                return !_.isEmpty(this.experience_added);
            },
            showProgress: function () {
                let allowed_routes = ['experiences.index', 'experience.student', 'experiences.show'];
                let current_route = route().current();
                return _.includes(allowed_routes, current_route);
            },
            has_experiences: function () {
                return !_.isEmpty(this.experiences_list.data)
            }
        },
        data () {
            return {
                user: null,
                menu: {
                    content: [],
                    selected_filter: null,
                    default_filter: 'Recientes',
                    expanded: false,
                    icon: '',
                },
                experiences_list: this.experiences,
                experience_added: null,
                xl_screen: false,
            }
        },
        created () {
            this.user = _.isEmpty(this.userData) ? this.$root.$refs.adminNav.user : this.userData;
            // TODO: Replace this assignation with the real json
            _.each(JSON_FILE, item => {
                axios.get(item.link + '?this_is_ajax_request=1')
                    .then(response => {
                        if (item.route_name === 'experience.student') {
                            item.amount = response.data.experiences.total
                        } else {
                            item.amount = response.data.total;
                        }
                    })
                    .catch(error => {
                        console.log(error)
                    })
                item.selected = false;
                //if (item.label === this.menu.default_filter) {
                if (item.link.toString() === window.location.href) {
                    item.selected = true;
                    this.menu.selected_filter = item;
                }
                this.menu.content.push(item)
            });

            this.isXLScreen();
            window.addEventListener('resize', event => {
                this.isXLScreen();
            })
        },
        methods: {
            getCurrentEnrollment (experience_id) {
                let enrollment;

                if (!_.isEmpty(this.user)) {
                    enrollment = _.find(this.user.enrollments, { id: experience_id });
                }

                return !_.isEmpty(enrollment) ? enrollment : {};
            },
            isXLScreen () {
                this.xl_screen = (window.innerWidth > 1920);
            },
            filterBy (item) {
                _.each(this.menu.content, mc => {
                    mc.selected = false;
                });
                item.selected = true;
                this.menu.selected_filter = item;
                if (!_.isEmpty(item.link)) {
                    window.location.href = item.link;
                }
            },
            toggleMenu (mobile = false) {
                this.menu.expanded = !this.menu.expanded;
                if (!mobile) {
                    this.menu.icon = this.menu.expanded ? 'angle-left' : 'angle-right';
                } else {
                    this.menu.icon = this.menu.expanded ? 'times' : 'angle-down';
                }
            },
            updateExperiences (url = null) {
                let route_url = _.isEmpty(url) ? window.location.href : url;
                //let loader = index.getLoader();
                this.experiences_list = [];

                setTimeout( () => {
                    axios
                        .get(route_url + '?this_is_ajax_request=1')
                        .then(response => {
                            this.experiences_list = response.data
                            //loader.close()
                        })
                        .catch(error => { console.log(error) })
                }, 200)
            },
            addToCart (experience) {
                this.experience_added = experience;
                this.purchase();
                //setTimeout(() => { this.cleanAddedExperience() }, 5000)
            },
            cleanAddedExperience () {
                this.experience_added = null;
            },
            inCart() {
                let cookie;
                let re = new RegExp('cart_list' + "=([^;]+)");
                let value = re.exec(document.cookie);
                cookie = (value != null) ? JSON.parse(unescape(value[1])) : null;


                if (cookie) {
                    Object.entries(cookie).map((item) => {
                        if (item[1].id == this.experience_added.id) {
                            this.already_in_cart = true;

                            if(item[1].attributes.personal_use){
                                this.already_in_cart_personal_use = true;
                            }
                            if(item[1].attributes.corporative_use){
                                this.already_in_cart_company_use= true;
                            }
                        }
                    });
                }
            },
            purchase() {
                axios({
                    method: 'POST',
                    url: route('shopping-cart.add', {experience: this.experience_added}),
                    params: {
                        user: this.user.id
                    }
                }).then((response) => {
                    //this.inCart();
                    //this.goToCheckout();
                }).catch((error) => {
                    console.log(error);
                });
            }
        }
    }
</script>

<style scoped>
    .menu {
        background-color: #FFFFFF;
        min-width: 50px;
    }
    .menu .header {
        background-color: transparent;
    }
    .menu .content {
        background-color: transparent;
    }
    .menu .content .menu-item.selected {
        background-color: #EEEEEE;
        border-bottom-right-radius: 25px;
        border-top-right-radius: 25px;
    }
    .menu .footer {
        background-color: transparent;
    }
    .menu.desktop {
        height: 100%;
        min-height: calc(100vh - 159px);
        box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.15);
    }
    .menu.desktop.expanded {
        width: calc(100vw * 0.22);
    }
    .menu.desktop.xl.expanded {
        width: calc(100vw * 0.15);
    }
    .menu.desktop .header {
        display: flex;
    }
    .menu.mobile {
        position: fixed;
        width: 100vw;
        height: 50px;
        min-height: 50px;
        box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.15);
        z-index: 1;
    }
    .menu.mobile.expanded {
        min-height: calc(100vh - 48px);
        position: fixed;
        overflow: auto;
        z-index: 2;
    }
    .menu.mobile .header {
        max-height: 50px;
        font-size: 18px;
        font-weight: normal;
        text-transform: capitalize;
    }
    .menu.mobile .header .selected-filter {
        display: flex;
    }
    .experiences {
        max-width: 1920px;
        margin-left: auto;
        margin-right: auto;
    }
    .experiences::-webkit-scrollbar {
        display: none;
    }
    .experiences.mobile {
        overflow-y: auto;
        max-height: 700px;
    }

    @media only screen and (min-width: 812px) and (max-width: 812px) and (orientation: landscape) {
        /*
        .menu.desktop {
            display: none !important;
        }
        .menu.mobile {
            display: block !important;
        }
        */
        .experiences.desktop {
            /*display: none !important;*/
            position: absolute;
            overflow-y: auto;
            max-height: calc(100vh + 170px);
        }
        /*
        .experiences.mobile {
            display: block !important;
        }
        */
    }

    .store-cart-notification .desktop {
        /*
        position: absolute;
        top: 50px;
        left: 65px;
        right: 35px;
         */
        z-index: 1;
        height: 162px;
        box-shadow: -4px 4px 12px rgba(68, 68, 68, 0.3);
    }
    .store-cart-notification .desktop .close {
        position: absolute;
        top: 0;
        right: 0;
    }
    .store-cart-notification .desktop .card {
        height: 100%;
    }
    .store-cart-notification .desktop .card .added-experience .cover {
        width: 222px;
        height: 124px;
        overflow: hidden;
    }
    .container-xl {
        width: 80%;
        margin-left: 10%;
    }
    .pl-75 {
        padding-left: .75rem;
    }
</style>
<style>
    body {
        background-color: #FFFFFF;
    }
    .page-content {
        overflow: unset;
    }
    .modal-close {
        display: none;
    }
    .modal-background {
        background-color: rgba(2, 62, 137, 0.8) !important;
    }
    .m-close-background {
        background-color: transparent !important;
        margin-bottom: 5px;
    }
    .animation-content {
        z-index: 50
    }
    .scrollable {
        overflow: auto;
    }
    @media only screen and (width: 812px) and (orientation: landscape) {
        footer {
            position: relative;
            bottom: 0;
            top: 100vh;
        }
    }
</style>
