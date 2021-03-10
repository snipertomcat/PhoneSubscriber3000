<template>
    <div class="apithy-navbar px-3 lg:px-5">
        <div class="apithy-navbar-section left">
            <div class="apithy-navbar-logo">
                <img src="/logo_beta_azul.png" alt="apithy_logo" @click="goTo()">
            </div>
        </div>
        <div class="apithy-navbar-section center menu-items">
            <template v-for="(item, index) in menu.items">
                <div class="apithy-navbar-item" :class="{'open': false}" @click="goTo(item.value)">
                    <div class="item-icon"><i :class="item.icon"></i></div>
                    <div class="item-name">{{item.label}}</div>
                </div>
            </template>
        </div>
        <div class="apithy-navbar-section right">
            <div class="user-menu" :class="{'open':userMenu.opened}" @click="toggleUserMenu">
                <div class="user-image">
                    <img :src="user.full_path_avatar" alt="">
                </div>
                <div class="user-name">
                    <span>{{user.name}}</span>
                </div>
                <div class="icon">
                    <i :class="{'fas':true, 'fa-angle-down':!userMenu.opened, 'fa-angle-up':userMenu.opened, }"></i>
                </div>
            </div>
            <div class="user-menu-collapse" :class="{'open': userMenu.opened}">
                <div class="user-menu-collapse-item" @click="goTo('profile')"><span>Perfil</span></div>
                <div class="user-menu-collapse-item" @click="goTo('profile/security')"><span>Seguridad</span></div>
                <div class="user-menu-collapse-item" @click="goTo('logout')"><span>Cerrar sesión</span></div>
            </div>
        </div>
    </div>
    <!--
    <div class="apithy-nav-wrapper">
        <div class="row align-items-center apithy-admin-nav" :style="expanded ? 'height: 68px;' : 'height: 50px;'">
            <div class="col-2 apithy-menu has-text-left">
                <img v-if="apithy_constants.ENV=='demo'" class="apithy-logo pointer" src="/logo_hoja_demo_blanco.png" alt="Apithy.logo" @click="goTo()">
                <img v-else class="apithy-logo pointer" src="/logo_hoja_beta_blanco.png" alt="Apithy.logo" @click="goTo()">
                <span class="apithy-icon nav-dropdown" @click="toggle">
                <i :class="{'fa':true, 'fa-sort-down':!expanded, 'fa-sort-up':expanded}"></i>
            </span>
            </div>
            <div class="col-8 row apithy-menu has-text-centered justify-content-center no-gutters">
                <span class="col-2 col-lg-1">
                    <div :class="{'apithy-icon':true, 'font-21':!expanded, 'font-25':expanded}" @click="goTo('dashboard')"><i class="icon-dashboard"></i></div>
                    <div class="pointer" v-show="expanded" @click="goTo('dashboard')">Dashboard</div>
                </span>
                    <span class="col-2 col-lg-1">
                    <div :class="{'apithy-icon':true, 'font-21':!expanded, 'font-25':expanded}" @click="setModule('users')"><i class="fa fa-users"></i></div>
                    <div class="pointer" v-show="expanded" @click="setModule('users')">Usuarios</div>
                </span>
                    <span class="col-2 col-lg-1">
                    <div :class="{'apithy-icon':true, 'font-21':!expanded, 'font-25':expanded}" @click="setModule('companies')"><i class="fa fa-building"></i></div>
                    <div class="pointer" v-show="expanded" @click="setModule('companies')">Compañia</div>
                </span>
                    <span class="col-2 col-lg-1">
                    <div :class="{'apithy-icon':true, 'font-21':!expanded, 'font-25':expanded}" @click="setModule('experiences')"><i class="icon-air-balloon"></i></div>
                    <div class="pointer" v-show="expanded" @click="setModule('experiences')">Experiencias</div>
                </span>
            </div>
            <div class="col-2 apithy-profile ">

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
            <div class="col-11 row sub-menu-container" v-if="user.is_super">
                <div class="col-4 col-md-2 has-text-centered item" v-for="(item, index) in submenu.module_active.items">
                    <span :class="{'pointer': true, 'active':isActive(item)}" @click="goTo(item.url)">{{item.label}}</span>
                </div>
            </div>
            <div class="col-11 row" v-if="user.is_admin">
                <div class="col col-md-2 has-text-centered item"
                     v-if="submenu.module_active.value !== 'companies'"
                     v-for="(item, index) in submenu.module_active.items">
                    <span :class="{'pointer': true, 'active':isActive(item)}" @click="goTo(item.url)">{{item.label}}</span>
                </div>
                <div class="col col-md-2 has-text-centered item"
                     v-if="submenu.module_active.value === 'companies'"
                     v-for="(item, index) in submenu.module_active.items_admin">
                    <span :class="{'pointer': true, 'active':isActive(item)}" @click="goTo(item.url)">{{item.label}}</span>
                </div>
            </div>
            <span class="col-1">
                <span class="pointer" @click="submenu.open = false"><i class="fa fa-sort-up"></i></span>
            </span>
        </div>
    </div>
    -->
</template>

<script>
    export default {
        name: "apithy-admin-nav",
        props: {
            user: {
                type: Object,
                required: true
            }
        },
        mounted() {
            this.$i18n.locale = 'es';
            this.setModule(window.location.pathname.split('/',2)[1]);
            this.submenu.open = (this.submenu.module_active.open) ? this.submenu.module_active.open : false;
        },
        methods: {
            toggleUserMenu() {
                this.userMenu.opened = !this.userMenu.opened
            },
            toggle() {
                this.expanded = !this.expanded;
            },
            goTo(url = '') {
                let origin = window.location.origin;
                window.location.href = origin+'/'+url;
            },
            setModule(module = '') {
                this.submenu. open = false;
                this.submenu.modules.find((item) => {
                    item.active = false;
                    if (item.value === module) {
                        this.submenu.open = true;
                        this.submenu.module_active =  item;
                    }
                });

            },
            isActive(item) {
                let location = window.location.pathname;
                let item_url = '/'+item.url;
                return (location == item_url);
            },
            switchProfileMenu() {
                this.profile.show = !this.profile.show;
            },
        },
        data() {
            return {
                expanded: false,
                menu: {
                    open: false,
                    item_active: null,
                    items: [
                        {
                            label: 'Store',
                            value: 'experiences',
                            icon: 'navbar-icon-store',
                            open: true,
                            items: []
                        },
                        {
                            label: 'Empresa',
                            value: 'companies',
                            icon: 'navbar-icon-office',
                            open: true,
                            items: []
                        },
                        {
                            label: 'Carrito',
                            value: 'shopping-cart/checkout',
                            icon: 'navbar-icon-cart',
                            open: true,
                            items: []
                        },
                    ]
                },
                userMenu: {
                    opened: false
                },
                submenu: {
                    open: false,
                    module_active: {},
                    modules: [
                        {
                            label: 'Usuarios',
                            value: 'users',
                            open: true,
                            items: [
                                {
                                    label: 'Listado',
                                    url: 'users'
                                },
                                {
                                    label: 'Nuevo',
                                    url: 'users/create'
                                },
                                {
                                    label: 'Importar',
                                    url: 'users/import'
                                },
                                {
                                    label: 'Invitaciones',
                                    url: 'users/invitations'
                                }
                            ]
                        },
                        {
                            label: 'Compañia',
                            value: 'companies',
                            open: true,
                            items: [
                                {
                                    label: 'Listado',
                                    url: 'companies'
                                },
                                {
                                    label: 'Nueva',
                                    url: 'companies/create'
                                }
                            ],
                            items_admin: [
                                {
                                    label: 'Detalles',
                                    url: 'companies/'+this.user.company_system,
                                },
                                {
                                    label: 'Áreas y Puestos',
                                    url: 'companies/'+this.user.company_system+'/areas',
                                },
                            ],
                        },
                        {
                            label: 'Experiencia',
                            value: 'experiences',
                            open: true,
                            items: [
                                {
                                    label: 'Listado',
                                    url: 'experiences'
                                },
                                {
                                    label: 'Nueva',
                                    url: 'experiences/create'
                                }
                            ]
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

    .apithy-admin-nav {
        padding: 0 10px;
        background-color: #2D7EFC;
        color: #FFFFFF;
    }
    .apithy-admin-sub-menu {
        height: 50px;
        background-color: #FFFFFF;
        color: #000000;
        box-shadow:  0px 10px 30px -20px black;
        position:relative;
        z-index:-2;
    }
    .sub-menu-container {
        height: 30px;
        overflow-x: auto;
        display: -moz-inline-box;
        display: -webkit-inline-box;
    }
    .apithy-logo {
        margin-right: 12px;
        height: 26px;
        filter: hue-rotate(0deg) saturate(0%) brightness(500%);
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
</style>
