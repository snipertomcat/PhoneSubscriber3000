<template>
    <div class="full-height">
        <div class="mobile-nav" :class="{'d-block':is_mobile, 'd-none':!is_mobile}">
            <main id="panel" :class="{'slideout-open-left':menu_left.is_open, 'slideout-open-right':menu_right.is_open}">
                <header class="apithy-header fixed-top padding-9">
                    <div class="row align-items-center justify-content-between height-100">
                        <div class="col-3 text-center">
                            <div class="open-left">
                                <span class="icon"><i :class="{'pointer fa':true, 'fa-bars':!menu_left.is_open, 'fa-angle-left':menu_left.is_open}"></i></span>
                            </div>
                        </div>
                        <div class="col-3 user-menu">
                            <figure class="image is-48x48 image-center pointer" @click="goto('/home')">
                                <img src="https://s3-us-west-2.amazonaws.com/cdn.apithy.com/logo/new_apithy_logo_Icon.png" alt="">
                            </figure>
                        </div>
                        <div class="col-3">
                            <div class="user-menu-button open-right pointer">
                                <div class="row no-gutters">
                                    <div class="col">
                                        <figure class="image is-48x48">
                                            <img class="is-rounded mini-profile-avatar" :src="user.full_path_avatar" alt="">
                                        </figure>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
            </main>
            <Slideout menu="#menu_left"
                      panel="#app"
                      ref="slideout_left"
                      :touch="false"
                      :toggleSelectors="['.open-left']"
                      @on-open="open(menu_left)"
                      @on-close="close(menu_left)">
                <nav id="menu_left" v-if="!helper.isMobile().any()">
                    <br>
                    <div class="width-100 mt-4 mb-5">
                        <figure class="image-center pointer width-50" @click="goto('/home')">
                            <img src="https://s3-us-west-2.amazonaws.com/cdn.apithy.com/logo/new_apithy_logo_blue.svg" alt="">
                        </figure>
                    </div>
                    <div class="menu-left">
                        <div class="row align-items-center no-gutters padding-lr-10 menu-item cursor" @click="goto('/my-experiences')">
                            <div class="col-3 text-center align-self-center">
                                <span class="ico"><i class="icon-hand-air-balloon"></i></span>
                            </div>
                            <div class="col-auto align-self-center">Mis experiencias</div>
                        </div>
                        <div class="row align-items-center no-gutters padding-lr-10 menu-item cursor" @click="goto('/experiences/company')">
                            <div class="col-3 text-center align-self-center">
                                <span class="ico"><i class="icon-store-build"></i></span>
                            </div>
                            <div class="col-auto align-self-center">Experiencias</div>
                        </div>
                        <div class="row align-items-center no-gutters padding-lr-10 menu-item cursor" @click="goto('/profile')">
                            <div class="col-3 text-center align-self-center">
                                <span class="ico"><i class="icon-grow-up"></i></span>
                            </div>
                            <div class="col-auto align-self-center">Progreso</div>
                        </div>
                        <div class="row align-items-center no-gutters padding-lr-10 menu-item cursor" @click="goto('/shopping-cart/checkout')">
                            <div class="col-3 text-center align-self-center">
                                <span class="ico"><i class="fa fa-shopping-cart"></i></span>
                            </div>
                            <div class="col-auto align-self-center">Carrito</div>
                        </div>
                    </div>
                </nav>
            </Slideout>
            <Slideout menu="#menu_right"
                      panel="#app"
                      ref="slideout_right"
                      :touch="false"
                      :toggleSelectors="['.open-right']"
                      @on-open="open(menu_right)"
                      @on-close="close(menu_right)"
                      side="right">
                <nav id="menu_right">
                    <br>
                    <br>
                    <div class="width-100">
                        <figure class="image is-96x96 image-center pointer profile-avatar" @click="goto('/my-experiences')" v-if="!helper.isMobile().any()">
                            <img :src="user.full_path_avatar" alt="">
                        </figure>
                        <figure class="image is-96x96 image-center pointer profile-avatar" v-else>
                            <img :src="user.full_path_avatar" alt="">
                        </figure>
                    </div>
                    <br><br>
                    <div class="menu-right padding-lr-15" v-if="!helper.isMobile().any()">
                        <div class="row">
                            <div class="col-12">
                                <span class="has-text-weight-bold font-20">{{ user.name }}</span>
                                <span class="has-text-weight-bold font-20">{{ user.surname }}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <span class="font-18">{{ user.email }}</span>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-12">
                        <span class="has-text-weight-bold font-18" @click="goto('/profile/edit')">
                            <a href="/profile/edit">Ir a perfil y preferencias</a>
                        </span>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-11 separator"></div>
                        </div>
                        <div class="row">
                            <div class="col-12 pointer" @click="goto('/my-experiences')">
                        <span class="font-18">
                            Mis experiencias
                        </span>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-11 separator"></div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <span class="has-text-weight-bold font-18 pointer" @click="goto('/getting-started')">
                                    Guia de inicio Apithy
                                </span>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-11 separator"></div>
                        </div>
                        <div class="row" v-if="user.is_impersonated">
                            <div class="col-12 pointer" @click="goto(route('impersonate.leave'))">
                        <span class="font-18">
                            Regresar a mi cuenta
                        </span>
                            </div>
                        </div>
                        <div class="row justify-content-center" v-if="user.is_impersonated">
                            <div class="col-11 separator"></div>
                        </div>
                        <br>
                        <div class="row align-items-center pointer menu-item" @click="goto('/logout')">
                            <div class="col-3 text-center align-self-center has-text-primary">
                                <span class="ico"><i class="fa fa-sign-out"></i></span>
                            </div>
                            <div class="col-9 align-self-center">
                        <span class="has-text-weight-bold font-18 has-text-primary">
                            Cerrar sesión
                        </span>
                            </div>
                        </div>
                    </div>
                    <div class="menu-right padding-lr-15" v-else>
                        <div class="row">
                            <div class="col-12">
                                <span class="has-text-weight-bold font-20">{{ user.name }}</span>
                                <span class="has-text-weight-bold font-20">{{ user.surname }}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <span class="font-18">{{ user.email }}</span>
                            </div>
                        </div>
                        <br>
                        <div class="row justify-content-center">
                            <div class="col-11 separator"></div>
                        </div>
                        <div class="row" v-if="user.is_impersonated">
                            <div class="col-12 pointer" @click="goto(route('impersonate.leave'))">
                        <span class="font-18">
                            Regresar a mi cuenta
                        </span>
                            </div>
                        </div>
                        <div class="row justify-content-center" v-if="user.is_impersonated">
                            <div class="col-11 separator"></div>
                        </div>
                        <br>
                        <div class="row align-items-center pointer menu-item" @click="goto('/logout')">
                            <div class="col-3 text-center align-self-center has-text-primary">
                                <span class="ico"><i class="fa fa-sign-out-alt"></i></span>
                            </div>
                            <div class="col-9 align-self-center">
                        <span class="has-text-weight-bold font-18 has-text-primary">
                            Cerrar sesión
                        </span>
                            </div>
                        </div>
                    </div>
                </nav>
            </Slideout>
        </div>
        <div class="desktop-nav" :class="{'d-block':!is_mobile, 'd-none':is_mobile}">
            <header class="apithy-header fixed-top padding-9">
                <div class="row align-items-center justify-content-between">
                    <div class="col-3">
                        <figure class="text-center pointer" @click="goto('/home')">
                            <img src="https://s3-us-west-2.amazonaws.com/cdn.apithy.com/logo/new_apithy_logo_blue.png" alt="">
                        </figure>
                    </div>
                    <div class="col-6">
                        <div class="row justify-content-center">
                            <!-- Store / Empresa / Carrito / Ayuda -->
                            <template v-if="!helper.isMobile().any()">
                            <div class="col-auto">
                                <div :class="{
                                        'menu-item pointer':true,
                                        'selected':isThisPlace(['/experiences/company'])
                                    }"
                                     @click="goto('/experiences/company')">
                                    <div class="text-center"><span class="ico"><i class="icon-store-build"></i></span></div>
                                    <div class=""><span class="padding-lr-10">Experiencias</span></div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div :class="{
                                   'menu-item pointer':true,
                                   'selected':isThisPlace('companies.show', true)
                                 }"
                                     @click="goto('companies.show', { company: company.system_id }, true)">
                                    <div class="text-center"><span class="ico"><i class="fas fa-building"></i></span></div>
                                    <div class=""><span class="padding-lr-10">{{ $t('messages.company') }}</span></div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div :class="{
                                        'menu-item pointer':true,
                                        'selected':isThisPlace('/shopping-cart/checkout')
                                    }"
                                     @click="goto('/shopping-cart/checkout')">
                                    <div class="text-center"><span class="ico"><i class="fa fa-shopping-cart"></i></span></div>
                                    <div class=""><span class="padding-lr-10">Carrito</span></div>
                                </div>
                            </div>
                            <div class="col-auto" v-if="false">
                                <div :class="{
                                   'menu-item pointer':true,
                                   'selected':isThisPlace('/help')
                                 }"
                                     @click="goto('/help')">
                                    <div class="text-center"><span class="ico"><i class="fas fa-exclamation"></i></span></div>
                                    <div class=""><span class="padding-lr-10">{{ $t('messages.help') }}</span></div>
                                </div>
                            </div>
                            <div class="col-auto" v-if="false">
                                <div :class="{
                                   'menu-item pointer':true,
                                   'selected':isThisPlace('/creator')
                                 }"
                                     @click="goto('/creator')">
                                    <div class="text-center"><span class="ico"><i class="fas fa-palette"></i></span></div>
                                    <div class=""><span class="padding-lr-10">{{ $t('messages.creator') }}</span></div>
                                </div>
                            </div>
                            </template>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="user-menu-profile-overlay" v-if="menu_profile.is_open"  @click.stop="toogleMenuProfile"></div>
                        <div class="user-menu-profile pointer">
                            <div class="row no-gutters" @click.stop="toogleMenuProfile">
                                <div class="col">
                                    <figure class="image is-64x64">
                                        <img class="is-rounded mini-profile-avatar" :src="user.full_path_avatar" alt="">
                                    </figure>
                                </div>
                                <div class="col-8 align-self-center">
                                    <div class="name"><span>{{ firstName(user.name) }}</span></div>
                                    <div class="position"><span>{{ 'Owner' }}</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="user-menu-profile-container" v-if="menu_profile.is_open">
                            <div class="user-menu-profile-content-triangle"></div>
                            <div class="user-menu-profile-content p-3">
                                <div class="row no-gutters">
                                    <div class="col-4 align-self-center">
                                        <template v-if="!helper.isMobile().any()">
                                            <figure class="image is-64x64 image-center pointer profile-avatar" @click="goto('/home')">
                                                <img :src="user.full_path_avatar" alt="">
                                            </figure>
                                        </template>
                                        <template v-else>
                                            <figure class="image is-64x64 image-center pointer profile-avatar">
                                                <img :src="user.full_path_avatar" alt="">
                                            </figure>
                                        </template>
                                    </div>
                                    <div class="col-8 align-self-center">
                                        <span class="has-text-weight-bold ">{{ user.name }}</span>
                                        <span class="has-text-weight-bold ">{{ user.surname }}</span>
                                        <div class="long-email">{{ user.email }}</div>

                                        <div class="pt-2 has-text-weight-bold font-15" v-if="!helper.isMobile().any()">
                                            <span @click="goto('/profile/edit')">
                                                <a href="/profile/edit" >Ir a perfil y preferencias</a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <template v-if="!helper.isMobile().any()">
                                <div class="row justify-content-center">
                                    <div class="col-11 separator"></div>
                                </div>
                                <div class="row">
                                    <div class="col-12 pointer" @click="goto('/my-experiences')">
                                    <span class="">
                                        Mis experiencias
                                    </span>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-11 separator"></div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                    <span class="has-text-weight-bold pointer" @click="goto('/getting-started')">
                                        Guia de inicio Apithy
                                    </span>
                                    </div>
                                </div>
                                </template>
                                <div class="row justify-content-center">
                                    <div class="col-11 separator"></div>
                                </div>
                                <div class="row" v-if="user.is_impersonated">
                                    <div class="col-12 pointer" @click="goto(route('impersonate.leave'))">
                                    <span class="">
                                        Regresar a mi cuenta
                                    </span>
                                    </div>
                                </div>
                                <div class="row justify-content-center" v-if="user.is_impersonated">
                                    <div class="col-11 separator"></div>
                                </div>
                                <div class="row align-items-center pointer pt-3" @click="goto('/logout')">
                                    <div class="col-3 text-center align-self-center has-text-primary">
                                        <span class="ico font-20"><i class="fa fa-sign-out-alt"></i></span>
                                    </div>
                                    <div class="col-9 align-self-center">
                                    <span class="has-text-weight-bold has-text-primary font-18">
                                        Cerrar sesión
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
        </div>
        <br><br>
    </div>
</template>

<script>
    import Slideout from 'vue-slideout'
    import { isMobile } from 'mobile-device-detect'
    import {bus} from "../../app_platform";
    import { index } from "../../helpers";

    export default {
        name: "OwnerMenu",
        components: { Slideout },
        props: { user: { type: Object, required: true } },
        computed: {
            company: function () {
                return _.head(this.user.company);
            },
            helper () {
                return index;
            },
        },
        data () {
            return {
                menu_left: {
                    is_open: false,
                },
                menu_right: {
                    is_open: false,
                },
                menu_profile: {
                    is_open: false,
                },
                user_data: {},
                is_mobile: false,
            };
        },
        beforeMount () {
            this.getUserData()
            bus.$on('update-user-getting', data => {
                this.user_data = data
            })
        },
        mounted() {
            /*
            let cookie = this.$cookie.get('open_user_menu')

            this.menu_profile.is_open = !_.isEmpty(cookie);
            if (!_.isEmpty(cookie)) {
                this.$cookie.delete('open_user_menu')
            }
            */


            this.is_mobile = document.querySelector('#app').clientWidth < 955;
            window.addEventListener('resize', () => {
                this.is_mobile = document.querySelector('#app').clientWidth < 955;

                if(!this.is_mobile) {
                    let slide_left = this.$refs.slideout_left;
                    let slide_right = this.$refs.slideout_right;

                    slide_left.slideout.close();
                    slide_right.slideout.close();

                    this.menu_left.is_open = false;
                    this.menu_right.is_open = false;
                }
            });
            window.addEventListener('orientationchange', event => {
                let slide_left = this.$refs.slideout_left;
                let slide_right = this.$refs.slideout_right;

                slide_left.slideout.close();
                slide_right.slideout.close();

                this.menu_profile.is_open = false;
                this.menu_left.is_open = false;
                this.menu_right.is_open = false;
            })
        },
        methods: {
            getUserData () {
                axios.get(route('getting_started.user'))
                    .then(response => {
                        this.user_data = response.data.data
                    })
            },
            firstName (name) {
                let first_name = name.split(' ')[0];
                return first_name;
            },
            open (menu) {
                menu.is_open = true;
            },
            close (menu) {
                menu.is_open = false;
            },
            goto (route_name = '/', params = {}, use_route_name = false) {
                let url = '';
                if (use_route_name) {
                    url = route(route_name, params).toString();
                } else {
                    url = route_name;
                }
                window.location.href = url;
            },
            isThisPlace (url, use_route_name = false) {
                if (!_.isArray(url)) {
                    if (use_route_name) {
                        return route().current() === url;
                    } else {
                        return url === window.location.pathname;
                    }
                } else {
                    let result = false;
                    _.each(url, item => {
                        if (use_route_name) {
                            result = route().current() === item;
                        } else {
                            if (item === window.location.pathname) {
                                result = true;
                            }
                        }
                    });
                    return result;
                }
            },
            toogleMenuProfile () {
                this.menu_profile.is_open = !this.menu_profile.is_open;
            },
        },
    }
</script>

<style scoped>
    .apithy-header {
        /*background-color: #FFA81E;
        height: 50px;*/
        height: 100px;
        background-color: #ffffff;
        box-shadow: 0px 3px 15px -3px rgba(0,0,0,.5);
    }
    .user-menu-profile {
        background-color: rgba(255,255,255,0.6);
        border-radius: 15px;
    }
    .user-menu-profile .name {
        color: #1A6BF7;
        font-weight: bold;
    }
    .user-menu-profile .image img {
        box-shadow: 1px 1px 5px -3px black;
    }
    .user-menu-button .image img {
        box-shadow: 1px 1px 5px -3px black;
    }
    .mobile-nav .open-left {
        color: #1A6BF7;
        font-size: 32px;
    }
    @media (min-width: 1401px) {
        .user-menu-profile {
            width: 80% ;
        }
        .user-menu-profile-container {
            right: 42% ;
        }
    }
    @media (max-width: 1400px) {
        .user-menu-profile {
            width: 80% ;
        }
        .user-menu-profile-container {
            right: 30% ;
        }
    }
    @media (max-width: 1200px) {
        .user-menu-profile {
            width: 100%;
        }
        .user-menu-profile-container {
            right: 9%;
        }
    }
    .user-menu-profile-overlay {
        position: absolute;
        top: -10px;
        right: 0;
        width: 100vw;
        height: 100vh;
        background-color: transparent;
    }
    .user-menu-profile-container {
        position: absolute;
        top: 100px;
    }
    .user-menu-profile-content-triangle {
        position: absolute;
        width: 20px;
        height: 20px;
        top: -20px;
        right: 15px;
        border-bottom: solid 20px rgba(255,255,255,1);
        border-left: solid 20px transparent;
        border-right: solid 20px transparent;
    }
    .user-menu-profile-content {
        overflow-y: auto;
        max-height: 81vh;
        width: 316px;
        border-radius: 3px;
        background-color: rgba(255,255,255,1);
        box-shadow: 0px 3px 10px -3px rgba(0,0,0,.5);
    }
    .long-email {
        max-width: 190px;
        overflow-x: hidden;
        text-overflow: ellipsis;
    }
    .user-menu-button {
        width: 70px;
        background-color: rgba(255,255,255,0.6);
        border-radius: 15px;
    }
    .image-center {
        margin: 0 auto;
    }
    .profile-avatar {
        border-radius: 50%;
        overflow: hidden;
        box-shadow: 1px 1px 10px -3px rgba(0,0,0,.5);
    }
    .profile-avatar img {
        height: 100%;
    }
    .mini-profile-avatar {
        height: 100%;
    }
    .menu-right .ico {
        font-size: 26px;
    }
    .menu-left .menu-item .ico {
        font-size: 26px;
    }
    .menu-left .menu-item {
        margin: 10px 0;
        cursor: pointer;
    }
    .menu-left .menu-item:hover {
        background-color: rgba(0,0,0,.1);
    }
    .menu-right .menu-item:hover {
        background-color: rgba(0,0,0,.1);
    }
    .separator {
        margin: 10px 0;
        border-bottom: #C4C4C4 solid 2px;
    }
    .progress-bar {
        margin-top: 10px;
    }
    .has-text-brown {
        color: #8C5D0A;
    }
    .has-right-border-brown {
        border-right: #8C5D0A 1px solid;
    }
    .font-15 {
        font-size: 15px;
    }

    /* new styles */
    .desktop-nav .apithy-header .apithy-logo img {
        height: 33px;
    }
    .desktop-nav .apithy-header .menu-item .ico {
        font-size: 32px;
        color: #888888;
    }
    .desktop-nav .apithy-header .menu-item {
        font-weight: bold;
        color: #444444;
    }
    .desktop-nav .apithy-header .menu-item.selected {
        color: #1A6BF7;
        border-bottom: 3px solid #1A6BF7;
    }
    .desktop-nav .apithy-header .menu-item.selected .ico {
        color: #1A6BF7;
    }

    /**  sideout clases  **/
    html .slideout-menu {
        position: fixed;
        top:0;
        bottom: 0;
        width: 256px;
        height: 100vh;
        overflow-y: scroll;
        -webkit-overflow-scrolling: touch;
        z-index: 0;
        display: none;
    }

    html .slideout-menu-left {
        left: -256px;
        background-color: #FFFFFF;
    }

    html .slideout-menu-right {
        right: -256px;
        background-color: #FFFFFF;
    }

    html .slideout-panel {
        position: relative;
        z-index: 1;
        will-change: transform;
        min-height: 100vh;
    }

    html.slideout-open .slideout-menu {
        display: block;
    }

    .progress-bar {
        background-color: transparent !important;
    }
</style>
