<template>
    <div class="collapse always-show" v-if="!helper.isMobile().any()">
        <div class="d-none d-md-block pt-4 pb-4 pr-0 menu desktop" :class="{'expanded':menu.expanded, 'xl':xl_screen}">
            <div class="header">
                <div class="row justify-content-end no-gutters ml-0 mr-0 width-100" :class="{ 'pl-1':!menu.expanded, 'pl-3':menu.expanded }">
                    <div class="col-auto pr-2" :class="{'pl-1':!menu.expanded}">
                        <b-button class="pointer is-primary" :icon-left="!!menu.icon ? menu.icon : 'angle-right'" icon-pack="fas" @click="toggleMenu(false)" inverted></b-button>
                    </div>
                </div>
            </div>
            <div class="content mt-4 mb-4">
                <div class="" v-for="item in menu.content">
                    <div class="menu-item pt-2 pb-2 pr-0 pl-3" :class="{'selected':item.selected}">
                        <el-tooltip :content="item.label" placement="left" :disabled="menu.expanded">
                            <div class="row ml-0 mr-0 no-gutters width-100 pointer" @click="filterBy(item)">
                                <div class="col-auto pl-75 pr-0" :class="{'selected': item.selected}">
                                    <span v-if="!!item.icon"><i :class="item.icon"></i></span>
                                    <span v-else><div style="width: 13.719px; height: 24px;"></div></span>
                                </div>
                                <div class="col-auto ml-2" v-if="menu.expanded">
                                    <span>{{ item.label }}</span>
                                </div>
                            </div>
                        </el-tooltip>
                    </div>
                    <div class="menu-item child pt-2 pb-2 pr-3 pl-3" :class="{'selected':child.selected}"
                         @click="filterBy(item, child)"
                         v-if="children(item)"
                         v-for="child in children(item)">
                        <el-tooltip :content="child.label" placement="left" :disabled="menu.expanded">
                            <div class="row ml-0 mr-0 no-gutters width-100 pointer"
                                 :class="{'ml-0':!menu.expanded, 'ml-4':menu.expanded}">
                                <div class="col-auto pl-75 pr-0" :class="{'selected': child.selected}">
                                    <span v-if="!!child.icon"><i :class="child.icon"></i></span>
                                    <span v-else><div style="width: 13.719px; height: 24px;"></div></span>
                                </div>
                                <div class="col-auto ml-2" v-if="menu.expanded">
                                    <span>{{ child.label }}</span>
                                </div>
                            </div>
                        </el-tooltip>
                    </div>
                </div>
            </div>
            <div class="footer"></div>
        </div>
        <div class="d-block d-md-none menu mobile" :class="{'expanded':menu.expanded}">
            <div class="header full-height">
                <div class="row justify-content-between no-gutters ml-0 mr-0 pl-3 pr-4 full-width full-height">
                    <div class="selected-filter col align-self-center has-text-black">
                        <span v-if="selected_filter_has_icon"><i :class="menu.selected_filter.icon"></i></span>
                        <span v-else style="display: flex;"><div style="width: 13.719px; height: 24px;"></div><div class="ml-3">Menú</div></span>
                        <span v-if="is_filter_selected" class="ml-3">{{ selected_filter.label }}</span>
                    </div>
                    <div class="col-auto align-self-center">
                        <b-button class="pointer is-primary" :icon-left="!!menu.icon ? menu.icon : 'angle-down'" icon-pack="fas" @click="toggleMenu(true)" inverted></b-button>
                    </div>
                </div>
            </div>
            <div class="content" v-if="menu.expanded">
                <div class="" v-for="item in menu.content">
                    <div class="menu-item pt-2 pb-2 pr-3 pl-3" :class="{'selected':item.selected}">
                        <div class="row ml-0 mr-0 no-gutters width-100 pointer" @click="filterBy(item)">
                            <div class="col-auto pr-0" :class="{'selected': item.selected}">
                                <span v-if="!!item.icon"><i :class="item.icon"></i></span>
                                <span v-else><div style="width: 13.719px; height: 24px;"></div></span>
                            </div>
                            <div class="col-auto ml-2" v-if="menu.expanded">
                                <span>{{ item.label }}</span>
                            </div>
                            <div class="col text-right" v-if="menu.expanded">
                                <span class="has-text-weight-bold">{{ item.amount }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="menu-item child pt-2 pb-2 pr-3 pl-3"
                         :class="{'selected':child.selected}"
                         v-if="children(item)"
                         v-for="child in children(item)">
                        <div class="row ml-0 mt-2 mr-0 pl-4 no-gutters width-100 pointer" @click="filterBy(item, child)">
                            <div class="col-auto pr-0" :class="{'selected': child.selected}">
                                <span v-if="!!child.icon"><i :class="child.icon"></i></span>
                                <span v-else><div style="width: 13.719px; height: 24px;"></div></span>
                            </div>
                            <div class="col-auto ml-2" v-if="menu.expanded">
                                <span>{{ child.label }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { Tooltip } from 'element-ui';
    import { index } from "../../helpers";

    export default {
        name: "OwnerLeftMenu",
        components: {
            'el-tooltip': Tooltip
        },
        computed: {
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
                let allowed_routes = ['experience.student'];
                let current_route = route().current();
                return _.includes(allowed_routes, current_route);
            },
            company: function () {
                return _.head(this.user.company);
            },
            helper () {
                return index;
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
        mounted () {
            this.user = _.isEmpty(this.userData) ? this.$root.$refs.adminNav.user : this.userData;
            // TODO: Replace this assignation with the real json
            let JSON_FILE = [
                {
                    icon: 'icon-dashboard',
                    label: 'Tableros',
                    link: route('dashboard.index'),
                    children: []
                },
                {
                    icon: 'fas fa-building',
                    label: 'Empresa',
                    link: route('companies.show', { company: this.company.system_id }),
                    children: [
                        { icon: 'icon-dollars-stacked', label: 'Presupuesto', link: route('companies.budget.create', { company: this.company.system_id }), children: [] },
                    ]
                },
                { icon: 'icon-tag', label: 'Etiquetas', link: route('taxonomy.index'),
                    children: [
                        { icon: 'icon-folder-tree', label: 'Organizacionales', link: route('taxonomy.index'), children: [] },
                        { icon: 'icon-vector-grow', label: 'Habilidades', link: route('taxonomy.view', {name: 'abilities'}), children: [] },
                        { icon: 'icon-certificate', label: 'Certificaciones', link: route('taxonomy.view', {name: 'certifications'}), children: [] },
                        { icon: 'icon-connected-user', label: 'Equipos', link: route('taxonomy.view', {name: 'teams'}), children: [] },
                        { icon: 'icon-asterisc', label: 'Personalizadas', link: route('taxonomy.view', {name: 'custom'}), children: [] },
                        { icon: 'icon-tag', label: 'Tags', link: route('taxonomy.view', {name: 'tag'}), children: [] },
                    ]
                },
                {
                    icon: 'fas fa-users',
                    label: 'Usuarios',
                    link: route('users.index'),
                    children: []
                },
                { icon: 'icon-blank-clipboard', label: 'Gestión', link: '',
                    children: [
                        {
                            icon: 'icon-book-marked',
                            label: 'Asignaciones',
                            link: route('experience.assignations'),
                            children: []
                        },
                        {
                            icon: 'icon-ribbon-prize',
                            label: 'Licencias',
                            link: route('licenses.index'),
                            children: []
                        },
                    ]
                },
            ];

            _.each(JSON_FILE, item => {
                item.selected = false;
                //if (item.label === this.menu.default_filter) {
                _.each(item.children, child => {
                    if (child.link.toString() === window.location.href) {
                        item.selected = true;
                        child.selected = true;
                        this.menu.selected_filter = child;
                    }
                });
                if (item.link.toString() === window.location.href) {
                    item.selected = true;
                    if(_.isEmpty(this.menu.selected_filter)) {
                        this.menu.selected_filter = item;
                    }
                }
                this.menu.content.push(item)
            });

            this.isXLScreen();
            window.addEventListener('resize', event => {
                this.isXLScreen();
            })
        },
        methods: {
            children (object) {
                if ('children' in object) {
                    if (!_.isEmpty(object.children)) {
                        return object.children
                    }
                }
                return false;
            },
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
            filterBy (item, child = null) {
                if (_.isEmpty(child)) {
                    _.each(this.menu.content, mc => {
                        mc.selected = false;
                    });
                    item.selected = true;
                    this.menu.selected_filter = item;
                    if (!_.isEmpty(item.link)) {
                        window.location.href = item.link;
                    }
                } else {
                    _.each(this.menu.content, mc => {
                        mc.selected = false;
                        if (!_.isEmpty(mc.children)) {
                            _.each(mc.children, child => {
                                child.selected = false;
                            })
                        }
                    });
                    item.selected = true;
                    child.selected = true;

                    this.menu.selected_filter = child;

                    if (!_.isEmpty(child.link)) {
                        window.location.href = child.link;
                    }
                }
            },
            openMenu (mobile = false) {
                this.menu.expanded = true;
                this.menu.icon = this.menu.expanded ? 'angle-left' : 'angle-right';
            },
            closeMenu (mobile = false) {
                this.menu.expanded = false;
                this.menu.icon = this.menu.expanded ? 'angle-left' : 'angle-right';
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
                this.experiences_list = [];
                axios
                    .get(route_url)
                    .then(response => {
                        this.experiences_list = response.data
                    })
                    .catch(error => { console.log(error) })
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
        width: 100% !important;
    }
    .menu .content {
        background-color: transparent;
    }
    .menu .content .menu-item.selected {
        background-color: #C7C7C7;
    }
    .menu .content .menu-item.child i{
        color: #878787;
    }
    .menu .content .menu-item.child.selected {
        background-color: #EEEEEE;
        border-bottom-right-radius: 25px;
        border-top-right-radius: 25px;
    }
    .menu .footer {
        background-color: transparent;
    }
    .menu.desktop {
        position: fixed;
        height: calc(100%);
        min-height: calc(100vh - 25px);
        z-index: 5;
        box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.15);
        overflow-y: auto;
    }
    .menu.desktop.expanded {
        min-width: 300px;
        width: calc(100vw * 0.15);
        animation-duration: 8s;
        overflow-x: hidden;
    }
    .menu.desktop.xl.expanded {
        width: calc(100vw * 0.15);
    }
    .menu.desktop .header {
        display: flex;
    }
    .menu.desktop::-webkit-scrollbar {
        display: none;
    }
    .menu.mobile {
        position: fixed;
        width: 100vw;
        height: 50px;
        min-height: 50px;
        box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.15);
        z-index: 6;
    }
    .menu.mobile.expanded {
        min-height: calc(100vh - 48px);
        position: fixed;
        overflow: auto;
        z-index: 6;
    }
    .menu.mobile .header {
        max-height: 50px;
        font-size: 18px;
        font-weight: normal;
        text-transform: capitalize;
    }
    .menu.mobile .content {
        max-height: calc(100vh - 100px);
        overflow-y: auto;
    }
    .menu.mobile .header .selected-filter {
        display: flex;
    }
</style>
<style>
    .always-show {
        display: block !important;
    }
    @media only screen and (min-width: 768px) {
        .owner-left-menu {
            min-width: 50px;
        }
        .page-content {
            width: calc(100% - 50px);
        }
    }
    @media only screen and (min-width: 768px) and (orientation: landscape) {
        .page-content {
            width: calc(100%);
        }
    }
    @media only screen and (max-width: 768px) {
        .page-content {
            margin-top: 3rem !important;
        }
    }
    @media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
        .page-content>div {
            max-width: 100% !important;
        }
    }
    @media only screen and (min-width: 1024px) and (max-width: 1366px) and (orientation: landscape) {
        .page-content>div {
            max-width: 100% !important;
        }
    }
    @media only screen and (min-width: 812px) and (max-width: 812px) and (orientation: landscape) {
        .page-content {
            margin-top: 3rem !important;
        }
        .menu.mobile {
            display: block !important;
        }
        .menu.desktop {
            display: none !important;
        }
    }
</style>
