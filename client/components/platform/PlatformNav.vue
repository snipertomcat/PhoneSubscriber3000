<template>
    <div class="apithy-nav-wrapper">
        <div class="user-navbar" v-if="user.id">
            <div class="row align-items-center justify-content-between apithy-student-nav" :style="expanded ? 'height: 68px;' : 'height: 50px;'">
                <div class="col-2 apithy-menu has-text-left">
                    <img v-if="apithy_constants.ENV=='demo'" class="apithy-logo pointer" src="/logo_hoja_demo_blanco.png" alt="Apithy.logo" @click="goTo()">
                    <img v-else class="apithy-logo pointer" src="/logo_hoja_beta_blanco.png" alt="Apithy.logo" @click="goTo('/home')">
                    <span class="apithy-icon nav-dropdown" @click="toggle" v-if="user.id">
                        <i :class="{'fa':true, 'fa-sort-down':!expanded, 'fa-sort-up':expanded}"></i>
                    </span>
                </div>
                <div class="col-8 row apithy-menu has-text-centered justify-content-center">
                <span class="col-auto col-lg-1" v-if="user.is_admin">
                    <div :class="{'apithy-icon':true, 'font-21':!expanded, 'font-25':expanded}"
                         @click="goTo('dashboard')"><i class="icon-dashboard"></i></div>
                    <div class="pointer menu-text" v-show="expanded" @click="goTo('dashboard')">Dashboard</div>
                </span>
                    <span class="col-auto col-lg-1" v-if="user.is_admin">
                    <div :class="{'apithy-icon':true, 'font-21':!expanded, 'font-25':expanded}"
                         @click="setModule('companies')"><i class="fa fa-building"></i></div>
                    <div class="pointer menu-text" v-show="expanded" @click="setModule('companies')">Compañia</div>
                </span>
                    <span class="col-auto col-lg-1" v-if="user.is_admin">
                    <div :class="{'apithy-icon':true, 'font-21':!expanded, 'font-25':expanded}"
                         @click="setModule('users')"><i class="fa fa-users"></i></div>
                    <div class="pointer menu-text" v-show="expanded" @click="setModule('users')">Usuarios</div>
                </span>
                    <span class="col-auto col-lg-1">
                    <div :class="{'apithy-icon':true, 'font-21':!expanded, 'font-25':expanded}"
                         @click="setModule('experiences')"><i class="icon-air-balloon"></i></div>
                    <div class="pointer menu-text" v-show="expanded"
                         @click="setModule('experiences')">Experiencias</div>
                </span>
                    <span class="col-auto col-lg-1">
                    <div :class="{'apithy-icon':true, 'font-21':!expanded, 'font-25':expanded}"
                         @click="goTo('shopping-cart/checkout')"><i class="fa fa-shopping-cart"></i></div>
                    <div class="pointer menu-text" v-show="expanded" @click="goTo('dashboard')">Carrito</div>
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
            <div class="row align-items-center no-gutters apithy-admin-sub-menu" v-if="submenu.open">
                <div class="col-11">
                    <div class="row m-l-15">
                        <div class="col-auto col-lg-2 has-text-centered item"
                             v-if="submenu.module_active.value !== 'companies'"
                             v-for="(item, index) in submenu.module_active.items">
                        <span :class="{'pointer': true, 'active':isActive(item)}"
                              @click="goTo(item.url)">
                            {{item.label}}
                        </span>
                        </div>
                        <div class="col-auto col-lg-2 has-text-centered item"
                             v-if="submenu.module_active.value === 'companies'"
                             v-for="(item, index) in submenu.module_active.items_admin">
                        <span :class="{'pointer': true, 'active':isActive(item)}"
                              @click="goTo(item.url)">
                            {{item.label}}
                        </span>
                        </div>
                    </div>
                </div>
                <span class="col-1 text-center">
                <span class="pointer" @click="submenu.open = false"><i class="fa fa-sort-up"></i></span>
            </span>
            </div>
        </div>
        <div class="guest-navbar" v-else>
            <nav :class="{'navbar is-fixed-top':true, 'stuck':stuck, 'no-stuck':!stuck}" role="navigation" aria-label="main navigation">
                <div class="container">
                    <div class="navbar-brand">
                        <a class="navbar-item" href="/" v-if="apithy_constants.ENV === 'demo'">
                            <img :class="{'apithy-logo':true, 'stuck':stuck, 'no-stuck':!stuck}" src="/logo_demo_azul.png">
                        </a>
                        <a class="navbar-item" href="/" v-else>
                            <img :class="{'apithy-logo':true, 'stuck':stuck, 'no-stuck':!stuck}"  src="/logo_beta_azul.png">
                        </a>
                        <a role="button" class="navbar-burger burger" @click="toggleMenu">
                            <span aria-hidden="true"></span>
                            <span aria-hidden="true"></span>
                            <span aria-hidden="true"></span>
                        </a>
                    </div>
                    <div class="navbar-menu" ref="navbar_menu">
                        <div class="navbar-end">
                            <div class="navbar-item" v-if="!user.id">
                                <button class="button is-white login-button" @click="goTo('login')">{{ 'Ingresa' }}</button>
                            </div>
                            <div class="navbar-item" v-if="!user.id">
                                <button class="button is-primary is-rounded register-button" @click="goTo('signup')">{{ 'Regístrate' }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
            <div class="margin-b-50"></div>
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
        created() {
            this.stuck = (window.scrollY > 50);
            document.addEventListener('scroll', event => {
                this.stuck = (window.scrollY > 50);
                return;
            });
        },
        mounted() {
            this.$i18n.locale = 'es';

            let current_path = window.location.pathname.split('/')[1];
            let full_path = window.location.pathname.split('/');
            let user_company = _.head(this.user.company);

            this.setModule(current_path);

            if (full_path[3] == 'preview' || full_path[3] =='view') {
                this.submenu.open = false;
            }

            if (!_.isEmpty(this.user)) {
                if (this.user.is_admin) {
                    this.submenu.modules[0].items.unshift(
                        {
                            label: 'ADQUIRIDAS',
                            url: 'experiences/buyed/company_use',
                        });

                }
                else if(!this.user.is_super && user_company.account_name !== 'apithy') {
                    this.submenu.modules[0].items.push(
                        {
                            label: 'ASIGNADAS',
                            url: 'experiences/assigned',
                        });
                }
            }
        },
        methods: {
            toggleMenu() {
                let el = this.$refs.navbar_menu;
                if (el.style.display !== 'block') {
                    el.style.display = 'block';
                } else {
                    el.style.display = '';
                }
            },
            toggle() {
                this.expanded = !this.expanded;
            },
            goTo(url = '') {
                let origin = window.location.origin;
                window.location.href = origin + '/' + url;
            },
            setModule(module = '') {
                this.submenu.open = false;
                this.submenu.modules.find((item) => {
                    item.active = false;
                    if (item.value === module) {
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
                            items: [
                                {
                                    label: 'MARKETPLACE',
                                    url: 'experiences'
                                },
                                {
                                    label: 'MIS EXPERIENCIAS',
                                    url: 'experiences/buyed/personal_use'
                                },
                                {
                                    label: 'LICENCIAS',
                                    url: 'licenses'
                                }
                            ]
                        },
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
                                    url: 'companies/' + this.user.company_system,
                                },
                                {
                                    label: 'Áreas',
                                    url: 'companies/' + this.user.company_system + '/areas',
                                },
                                {
                                    label: 'Presupuesto',
                                    url: 'companies/' + this.user.company_system + '/budget/create',
                                },
                            ],
                        },
                    ]
                },
                profile: {
                    show: false
                },
                stuck: false,
            };
        },
    }
</script>

<style scoped>
    .margin-b-50 {
        position: relative;
        margin-bottom: 50px;
        height: 25px;
        width: 100%;
    }

    .guest-navbar .navbar {
        padding-bottom: unset;
        margin-bottom: unset;
        border-bottom: solid 1px lightgray !important;
    }

    .guest-navbar .navbar.no-stuck {
        height: 75px;
    }

    .guest-navbar .navbar.stuck {
        height: 66.55px;
    }

    .guest-navbar .navbar-brand {
        margin-right: 100px;
    }

    .guest-navbar .navbar {
        transition: 0.8s;
        -webkit-transition:  0.8s;
    }

    .guest-navbar .apithy-logo {
        transition: 0.8s;
        -webkit-transition:  0.8s;
    }

    .guest-navbar .margin-b-50 {
        transition: 0.8s;
        -webkit-transition:  0.8s;
    }

    @media screen and (max-width: 1087px) {
        .guest-navbar .navbar-brand {
            margin-right: 0px;
        }

        .guest-navbar .navbar-burger.burger {
            margin-top: 8px;
        }

        .guest-navbar .navbar-start .navbar-item {
            text-align: right;
            margin: 10px 0;
        }

        .guest-navbar .navbar-end .navbar-item {
            text-align: center;
        }
    }

    .guest-navbar .navbar-brand .navbar-item {
        padding: 0;
    }

    .guest-navbar .navbar-start .navbar-item {
        border-bottom: transparent !important;
        margin-right: 50px !important;
        font-size: 0.8rem;
    }

    .guest-navbar .navbar-end .navbar-item {
        padding-top: 0;
        padding-bottom: 0;
    }

    .guest-navbar .navbar-end .navbar-item .register-button {
        padding: 10px 20px;
        width: 154px;
        height: 43px;
        background-color: #FFA81E;
        color: white !important;
        border: none;
        box-shadow: 2px 2px 6px 0 rgba(88, 88, 88, 0.41);
        border-top-left-radius: 40px !important;
        border-bottom-left-radius: 40px !important;
        border-radius: 40px !important;
        font-size: 1rem;
        font-weight: bold;
    }

    .guest-navbar .navbar-end .navbar-item .login-button {
        font-weight: bold;
    }

    .guest-navbar .apithy-logo.stuck {
        height: 30px;
        max-height: 100%
    }

    .guest-navbar .apithy-logo.no-stuck {
        max-height: 100%
    }

    .user-navbar {
        position: relative;
        z-index: 0;
    }

    .apithy-nav-wrapper{
        z-index: 1;
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
        height: 40px;
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

    .apithy-profile .dropdown-content{
        z-index: 10;
        position:relative;
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

    .m-l-15 {
        margin-left: 15px;
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