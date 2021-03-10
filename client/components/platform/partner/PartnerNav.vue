<template>
    <div class="apithy-nav-wrapper">
        <div class="row align-items-center apithy-student-nav" :style="expanded ? 'height: 68px;' : 'height: 50px;'">
            <div class="col-2 apithy-menu has-text-left">
                <img class="apithy-logo pointer" src="/images/apithy_small.png" alt="Apithy.logo" @click="goTo()">
                <span class="apithy-icon nav-dropdown" @click="toggle" v-if="user.id">
                <i :class="{'fa':true, 'fa-sort-down':!expanded, 'fa-sort-up':expanded}"></i>
            </span>
            </div>
            <div class="col-8 row apithy-menu has-text-centered justify-content-center no-gutters">
                <!-- Dashboard -->
                <span class="col-3 col-lg-1">
                    <div :class="{'apithy-icon':true, 'font-21':!expanded, 'font-25':expanded}"
                         @click="goTo('dashboard')"><i class="icon-dashboard"></i></div>
                    <div class="pointer menu-text" v-show="expanded" @click="goTo('dashboard')">Dashboard</div>
                </span>
                <!-- Experiences -->
                <span class="col-3 col-lg-1">
                    <div :class="{'apithy-icon':true, 'font-21':!expanded, 'font-25':expanded}"
                         @click="goTo('experiences')"><i class="icon-air-balloon"></i></div>
                    <div class="pointer menu-text" v-show="expanded" @click="goTo('experiences')">Experiencias</div>
                </span>
                <!-- ingress -->
                <span class="col-3 col-lg-1">
                    <div :class="{'apithy-icon':true, 'font-21':!expanded, 'font-25':expanded}"
                         @click="goTo('ingress')"><i class="fa fa-money-bill"></i></div>
                    <div class="pointer menu-text" v-show="expanded" @click="goTo('ingress')">Ingresos</div>
                </span>
                <!-- Conciliation -->
                <span class="col-3 col-lg-1">
                    <div :class="{'apithy-icon':true, 'font-21':!expanded, 'font-25':expanded}"
                         @click="goTo('conciliation')"><i class="icon-conciliation"></i></div>
                    <div class="pointer menu-text" v-show="expanded" @click="goTo('conciliation')">Conciliación</div>
                </span>
            </div>
            <div class="col-2 apithy-profile " v-if="user.id">
                <b-dropdown :position="'is-top-right'">
                    <figure class="image is-32x32" slot="trigger">
                        <img class="pointer is-rounded" :src="user.full_path_avatar" @click="switchProfileMenu">
                    </figure>
                    <b-dropdown-item>
                        <a class="dropdown-item-text" href="/profile">Perfil</a>
                    </b-dropdown-item>
                    <b-dropdown-item>
                        <a class="dropdown-item-text" href="/profile/security">Seguridad</a>
                    </b-dropdown-item>
                    <b-dropdown-item v-if="user.is_impersonated">
                        <a class="dropdown-item-text" :href="route('impersonate.leave')">Regresar a mi cuenta</a>
                    </b-dropdown-item>
                    <b-dropdown-item>
                        <a class="dropdown-item-text" href="/logout">Cerrar Sesión</a>
                    </b-dropdown-item>
                </b-dropdown>
            </div>
        </div>
        <div class="row align-items-center apithy-admin-sub-menu" v-if="submenu.open">
            <div class="col-11 row">
                <div class="col col-md-2 has-text-centered item"
                     v-if="submenu.module_active.value !== 'companies'"
                     v-for="(item, index) in submenu.module_active.items">
                    <span :class="{'pointer': true, 'active':isActive(item)}"
                          @click="goTo(item.url)">{{item.label}}</span>
                </div>
                <div class="col col-md-2 has-text-centered item"
                     v-if="submenu.module_active.value === 'companies'"
                     v-for="(item, index) in submenu.module_active.items_admin">
                    <span :class="{'pointer': true, 'active':isActive(item)}"
                          @click="goTo(item.url)">{{item.label}}</span>
                </div>
            </div>
            <span class="col-1">
                <span class="pointer" @click="submenu.open = false"><i class="fa fa-sort-up"></i></span>
            </span>
        </div>
    </div>
</template>

<script>
    export default {
        name: "PlatformNav",
        props: {
            user: {
                type: Object,
                required: true
            }
        },
        mounted() {
            this.$i18n.locale = 'es';

            let current_path = window.location.pathname.split('/')[1];
            let full_path = window.location.pathname.split('/');

            this.setModule(current_path);

            if (full_path[3] == 'preview' || full_path[3] =='view') {
                this.submenu.open = false;
            }

            if (this.user.is_admin) {
                this.submenu.modules[0].items.unshift(
                    {
                        label: 'ADQUIRIDAS',
                        url: 'experiences/buyed/company_use',
                    });
                this.submenu.modules[0].items.unshift(
                    {
                        label: 'ASIGNADAS',
                        url: 'experiences/assigned',
                    });

                this.submenu.modules[0].items[2].url = "experiences/buyed/personal_use";

            }
        },
        methods: {
            toggle() {
                this.expanded = !this.expanded;
            },
            goTo(url = '') {
                let origin = window.location.origin;
                window.location.href = (url !== 'dashboard') ? origin + '/partner/' + url : origin + '/' + url;
            },
            setModule(module = '') {
                this.submenu.open = false;
                this.submenu.modules.find((item) => {
                    item.active = false;
                    if (item.value === module && item.items.length > 0) {
                        this.submenu.open = true;
                        this.submenu.module_active = item;
                    }
                });

            },
            isActive(item) {
                let location = window.location.pathname;
                let item_url = '/' + item.url;
                return (location == item_url);
            },
            switchProfileMenu() {
                this.profile.show = !this.profile.show;
            },
        },
        data() {
            return {
                expanded: false,
                submenu: {
                    open: true,
                    module_active: {},
                    modules: [
                        {
                            label: 'Experiencia',
                            value: 'experiences',
                            open: true,
                            items: []
                        },
                        {
                            label: 'Ingresos',
                            value: 'ingress',
                            open: true,
                            items: []
                        },
                        {
                            label: 'Conciliación',
                            value: 'conciliation',
                            open: true,
                            items: [],
                        },
                    ]
                },
                profile: {
                    show: false
                }
            };
        },
    }
</script>

<style scoped>

    .apithy-nav-wrapper{
        z-index: 0;
        position: relative;
    }

    .apithy-profile .dropdown-content{
        z-index: 10;
        position:relative;
    }

    .apithy-student-nav {
        padding: 0 10px;
        background-color: orange;
        color: #FFFFFF;
        -webkit-box-shadow: 0px 1px 5px 0px rgba(0, 0, 0, 0.75);
        -moz-box-shadow: 0px 1px 5px 0px rgba(0, 0, 0, 0.75);
        box-shadow: 0px 1px 10px 0px rgba(0, 0, 0, 0.30);
    }

    .apithy-student-nav .menu-text {
        font-size: .9rem;
    }

    .apithy-admin-sub-menu {
        height: 50px;
        background-color: #FFFFFF;
        color: #000000;
        box-shadow: 0px 10px 30px -20px black;
        text-transform: uppercase;
        position:relative;
        z-index:-2;
    }

    .apithy-logo {
        margin-right: 12px;
        height: 26px;
        filter: hue-rotate(0deg) saturate(0%) brightness(500%);
    }

    .apithy-nav-item .pointer {
        display: block;
        color: white;
        font-weight: bold;
    }

    a.apithy-icon {
        color: white;
        text-decoration: none;
    }

    .apithy-icon {
        cursor: pointer;

    }

    .apithy-icon.nav-dropdown {
        margin-right: 0;
        color: #EC9203;
    }

    .apithy-icon.profile {
        margin-right: 0;
        color: #EC9203;
    }

    .apithy-icon:hover {
        text-shadow: 0px 0px 20px white;
    }

    .apithy-profile .dropdown {
        margin-left: 40%;
    }

    .apithy-profile-dropdown {
        position: absolute;
        top: 50px;
        right: 0;
        height: 100px;
        box-shadow: -4px 4px 15px -7px #000;
    }

    .dropdown-item-text {
        color: #000;
    }

    .active {
        border-bottom: 2px solid #106CFB;
        padding-bottom: 5px;
        font-weight: bold;
    }

    .dropdown-menu {
        left: -70px;
        min-width: 12rem;
        padding-top: 4px;
        position: absolute;
        top: 38px !important;
    }

</style>