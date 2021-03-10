<template>
    <div class="card full-height" :class="'element-'+experience.system_id">
        <div class="mobile-card-author d-block d-lg-none">
            <div class="row mr-0 ml-0 mb-2 created-by font-18">
                <div class="author-content col-auto">
                    <img :src="author_company.full_path_logo" alt="" v-if="author_company">
                    <img :src="author.full_path_avatar" alt="" v-else>
                </div>
                <div class="col text-right align-self-center" v-if="author_company">
                    {{ $t('messages.marketplace.created_by') }} {{ author_company.name }}
                </div>
                <div class="col text-right align-self-center" v-else>
                    {{ $t('messages.marketplace.created_by') }} {{ author.full_name }}
                </div>
            </div>
        </div>

        <div class="experience card-content">
            <div class="d-none d-lg-block card-image" @mouseenter="showTooltip" @mouseleave="hideTooltip">
                <div class="image-content" :style="experience_cover"></div>
            </div>
            <div class="d-block d-lg-none card-image" @click="showMobileTooltip">
                <div class="image-content" :style="experience_cover"></div>
            </div>
            <div class="card-author d-none d-lg-block">
                <div class="author-content">
                    <img :src="author_company.full_path_logo" alt="" v-if="author_company">
                    <img :src="author.full_path_avatar" alt="" v-else>
                </div>
            </div>
            <div class="card-body">
                <div class="row created-by font-18 d-none d-lg-block">
                    <div class="col-12" v-if="author_company">
                        {{ $t('messages.marketplace.created_by') }} {{ author_company.name }}
                    </div>
                    <div class="col-12" v-else>
                        {{ $t('messages.marketplace.created_by') }} {{ author.full_name }}
                    </div>
                </div>

                <div class="row element-title">
                    <div class="col-12 font-20 has-text-weight-bold d-none d-lg-block mt-4" @mouseenter="showTooltip" @mouseleave="hideTooltip">
                        {{ experience.title }}
                    </div>
                    <div class="col-12 font-20 has-text-weight-bold d-block d-lg-none" @click="showMobileTooltip">
                        {{ experience.title }}
                    </div>
                </div>

                <div class="row justify-content-between current-progress">
                    <template v-if="showProgress && hasEnrollment">
                        <div class="col-auto">{{ $t('messages.marketplace.progress') }}</div>
                        <div class="col-auto pb-2">{{ progress }}%</div>
                        <div class="col-12">
                            <progress class="progress is-small is-squared is-success" :value="progress" max="100"></progress>
                        </div>
                    </template>
                </div>

                <div class="row mt-3 font-18" :class="{'action-buttons':!showProgress, 'landscape-action-buttons':showProgress}">
                    <div class="col-12 get-licenses-button">
                        <button v-if="!user_has_licence && !already_in_cart_personal_use"
                                class="button is-primary width-100 has-text-weight-bold"
                                @click.stop="openModal">
                            Obtener licencias
                        </button>
                        <button v-else class="button is-primary width-100 has-text-weight-bold"
                                @click.stop="buyLicences">
                            Obtener licencias
                        </button>
                    </div>

                    <div class="col-12 mt-2 available-licences" v-if="available_licences">
                        <button class="button is-link has-text-weight-bold pointer width-100" @click="assignLicences">
                            <span><i class="icon icon-users mr-2"></i></span>
                            <span>{{ available_licences }} licencias sin asignar</span>
                        </button>
                    </div>

                    <div class="col-12 mt-2 actions-dropdown">
                        <b-dropdown aria-role="full-width" :mobile-modal="false" class="full-width" ref="dropdown">
                            <div class="button is-primary is-outlined full-width text-center" type="button" slot="trigger">
                                <span class="has-text-weight-bold text-center">Más opciones</span>
                                <span class="pl-0 pr-0 action-dropdown dropdown-trigger-arrow"><i class="fas fa-sort-down"></i></span>
                            </div>

                            <b-dropdown-item class="pr-2 pl-2" :value="true" paddingless custom>
                                <div class="analytics-button">
                                    <button class="button is-white width-100 justify-left" @click="analytics">
                                        <span class="has-text-link font-20"><i class="icon-analytics mr-2"></i></span>
                                        <span>Datos obtenidos</span>
                                    </button>
                                </div>
                            </b-dropdown-item>

                            <b-dropdown-item class="pr-2 pl-2" :value="false" paddingless custom>
                                <div class="details-button">
                                    <button class="button is-white width-100 justify-left"
                                            @click.stop="preview">
                                        <span class="has-text-link font-20"><i class="icon-details mr-2"></i></span>
                                        <span>Detalles</span>
                                    </button>
                                </div>
                            </b-dropdown-item>

                            <b-dropdown-item class="pr-2 pl-2" :value="false" v-if="user_has_licence || is_enrolled || is_free" paddingless custom>
                                <div class="continue-button">
                                    <button class="button is-white width-100 justify-left" @click="enrollUser()"
                                            v-if="is_free && !is_enrolled">
                                        <span class="has-text-link font-20"><i class="fas fa-caret-right mr-2"></i></span>
                                        <span>Iniciar</span>
                                    </button>
                                    <button class="button is-white width-100 justify-left" @click="enrollUser()"
                                            v-else-if="!is_enrolled && user_has_licence">
                                        <span class="has-text-link font-20"><i class="fas fa-caret-right mr-2"></i></span>
                                        <span>Iniciar</span>
                                    </button>
                                    <button class="button is-white width-100 justify-left" @click.stop="resume" v-else>
                                        <span class="has-text-link font-20"><i class="fas fa-caret-right mr-2"></i></span>
                                        <span>Continuar</span>
                                    </button>
                                </div>
                            </b-dropdown-item>

                        </b-dropdown>
                    </div>
                </div>
            </div>
            <div class="d-block d-lg-none mobile-tooltip" v-if="tooltip.show" @mouseleave="hideMobileTooltip">
                <div class="tooltip-content">
                    <div class="close">
                        <b-button type="is-white" icon-left="times" icon-pack="fas" inverted @click="hideMobileTooltip"></b-button>
                    </div>
                    <div class="description" v-html="experience.summary">
                        {{ experience.description }}
                    </div>
                    <div class="row mt-4">
                        <div class="col-auto">
                            <a :href="route('experience.preview', {experience:experience.system_id})" class="has-text-primary has-text-weight-bold font-20">{{ $t('messages.marketplace.more_details') }}</a>
                        </div>
                    </div>
                    <div class="row justify-content-center mt-4">
                        <div class="col-6" v-if="user_has_licence">
                            <a :href="route('experience.viewer.enroll', {experience:experience.system_id, enrollment_id: enroll.id})" class="button is-primary is-fullwidth has-text-weight-bold has-text-white" v-if="experience_is_started || experience_is_finished">{{ $t('messages.marketplace.continue') }}</a>
                            <a :href="route('experience.viewer.enroll', {experience:experience.system_id, enrollment_id: enroll.id})" class="button is-primary is-fullwidth has-text-weight-bold has-text-white" v-else>{{ $t('messages.marketplace.start') }}</a>
                        </div>
                        <div class="col-auto" v-else-if="experience.price > 0">
                            <a class="button is-primary is-fullwidth has-text-weight-bold has-text-white" @click="addToCart">{{ $t('messages.marketplace.add_to_cart') }}</a>
                        </div>
                        <div class="col-6 align-self-center" v-else>
                            <a class="button is-primary is-fullwidth has-text-weight-bold has-text-white"  @click="enrollUser">{{ $t('messages.marketplace.start') }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-none d-lg-block tooltip" :class="'tooltip-'+experience.system_id" @mouseenter="showTooltip" @mouseleave="hideTooltip">
                <div class="tooltip-direction"></div>
                <div class="tooltip-content p-3">
                    <div class="row">
                        <div class="col-12 font-16 description">
                            <p v-html="experience.summary">{{ experience.summary }}</p>
                        </div>
                    </div>
                    <div class="row justify-content-center mt-4">
                        <div class="col-10 font-14">
                            <ul class="row features-content">
                                <li class="col-sm-12 pb-2">
                                    <i class="padding-r5 fas fa-clock-o"></i>
                                    {{hours_content_value }} {{ $t('messages.'+getPeriod(hours_content_period, hours_content_value)) }} de contenido
                                </li>
                                <li class="col-sm-12 pb-2">
                                    <i class="padding-r5 icon-movie-clipboard"></i>
                                    {{ num_videos }} {{ (num_videos >1) ? 'videos interactivos':'video interactivo'}}
                                </li>
                                <li class="col-sm-12 pb-2">
                                    <i class="padding-r5 fas fa-cloud-download-alt"></i>
                                    {{ num_content_downloads }} archivos para descargar
                                </li>
                                <li class="col-sm-12 pb-2">
                                    <i class="padding-r5 fas fa-basketball-ball mr-1"></i>
                                    {{ num_activities }} actividades
                                </li>
                                <li class="col-sm-12" v-if="experience.type === 'journey'">
                                    <i class="padding-r5 fas fa-trophy"></i>
                                    {{ experience_has_adventures }} experiencias
                                </li>
                                <li class="col-sm-12" v-if="false && (experience.type === 'adventure')">
                                    <i class="padding-r5 fas fa-trophy"></i>
                                    {{ experience_has_sessions }} retos
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row justify-content-center mt-4">
                        <div class="col-6" v-if="user_has_licence">
                            <a :href="route('experience.viewer.enroll', {experience:experience.system_id, enrollment_id: enroll.id})" class="button is-primary is-fullwidth has-text-weight-bold" v-if="experience_is_started || experience_is_finished">{{ $t('messages.marketplace.continue') }}</a>
                            <a :href="route('experience.viewer.enroll', {experience:experience.system_id, enrollment_id: enroll.id})" class="button is-primary is-fullwidth has-text-weight-bold has-text-white" v-else>{{ $t('messages.marketplace.start') }}</a>
                        </div>
                        <div class="col-auto" v-else-if="experience.price > 0">
                            <a class="button is-primary is-fullwidth has-text-weight-bold has-text-white" @click="addToCart">{{ $t('messages.marketplace.add_to_cart') }}</a>
                        </div>
                        <div class="col-6 align-self-center" v-else>
                            <a class="button is-primary is-fullwidth has-text-weight-bold has-text-white"  @click="enrollUser">{{ $t('messages.marketplace.start') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <b-modal :active.sync="modal.show" :width="900">
            <div class="card">
                <div class="card-content">
                    <div class="media">
                        <div class="media-content">
                            <p class="title is-4">Tipo de licencia</p>
                        </div>
                    </div>

                    <div class="content">
                        <div class="row">
                            <div class="col-12">
                                <span>¿Quieres adquirir la experiencia </span>
                                <span class="has-text-weight-bold">{{ experience.title }}</span>
                                <span> para uso personal o de tu empresa?</span>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <a class="button is-primary width-100 shadow text-white"
                                   @click.stop="closeModal();purchase();">
                                    Uso personal
                                </a>
                            </div>
                            <div class="col-12 col-md-6">
                                <a class="button is-primary width-100 shadow text-white"
                                   v-bind:href="route('experiences.show',{'experience':experience.system_id}).toString()+'/buy-licences'">
                                    Uso empresarial
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </b-modal>
    </div>
</template>

<script>
    export default {
        name: "OwnerCardElement",
        props: {
            element: {
                required: true,
                type: Object
            },
            showProgress: {
                required: false,
                type: Boolean,
                default: false
            },
            currentEnrollment: {
                required: false,
                type: Object,
                default: {}
            },
        },
        components: {},
        computed: {
            experience_cover: function () {
                return 'background-image: url(' + this.experience.full_path_cover + ');'
            },
            available_licences: function () {
                return this.experience.admin_available_licences
            },
            is_assigned: function () {
                return window.location.href === route('experience.assigned').toString();
            },
            is_enrolled: function () {
                let exp_enrollments = _.head(this.experience.current_user_progress.enrollments_data);
                if (_.isEmpty(this.currentEnrollment) && _.isEmpty(exp_enrollments))
                    return false;
                return true;
            },
            progress: function () {
                let value = 0;
                if (this.experience_is_finished) {
                    value = 100;
                }
                if (this.experience_is_started) {
                    value = this.experience.current_user_progress.progress_percent;
                }
                return value;
            },
            hasEnrollment () {
                return !_.isEmpty(this.currentEnrollment)
            },
            enroll: function () {
                if (_.isEmpty(this.currentEnrollment))
                    return {id: ''};
                return this.currentEnrollment.pivot;
            },
            author: function () {
                let author = this.experience.author;
                return !_.isEmpty(author) ? author : false ;
            },
            price: function () {
                let price = this.experience.price;
                let formatted_price = price;
                let money = this.money;

                money.suffix = '$';
                money.prefix = 'MXN';

                if (!isNaN(price)) {
                    if (price > 0) {
                        formatted_price = money.suffix + price.toFixed(money.precision).replace(/./g, function(c, i, a) {
                            return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
                        }) + ' ' + money.prefix;
                    } else {
                        formatted_price = this.$t('messages.marketplace.free');
                    }
                }
                return formatted_price;
            },
            is_free: function () {
                return this.experience.price === 0;
            },
            num_videos: function () {
                return (this.experience.features && this.experience.features.num_videos > 0) ? this.experience.features.num_videos : 0;
            },
            author_company: function () {
                let company_array = this.experience.author.company;
                return !_.isEmpty(company_array) ? _.head(company_array) : false ;
            },
            num_activities: function () {
                return (this.experience.features && this.experience.features.num_activities > 0) ? this.experience.features.num_activities : 0;
            },
            hours_content_value: function () {
                return (this.experience.features && this.experience.features.hours_content.time_value > 0) ? this.experience.features.hours_content.time_value : 0;
            },
            hours_content_period: function () {
                return (this.experience.features) ? this.experience.features.hours_content.time_period : '';
            },
            num_content_downloads: function () {
                return (this.experience.features && this.experience.features.num_content_downloads > 0) ? this.experience.features.num_content_downloads : 0;
            },
            experience_has_sessions: function () {
                return (this.experience.sessions && this.experience.sessions.length > 0) ? this.experience.sessions.length : 0;
            },
            experience_has_adventures: function () {
                return (this.experience.adventures && this.experience.adventures.length > 0) ? this.experience.adventures.length : 0;
            },
            experience_is_started: function () {
                return (this.experience.auth_user_has_licence && this.experience.current_user_progress.progress_percent > 0)
            },
            experience_not_started: function () {
                return (this.experience.auth_user_has_licence && this.experience.current_user_progress.progress_percent === 0)
            },
            experience_is_finished: function () {
                return !!this.enroll && this.enroll.status === this.apithy_constants.ENROLLMENT_STATUS_FINISHED;
            },
            user_has_licence: function () {
                return this.experience.auth_user_has_licence;
            },
        },
        data () {
            return {
                initial_width: window.innerWidth,
                experience: this.element,
                tooltip: {
                    show: false,
                    position: 'right',
                },
                modal: {
                    show: false
                },
                already_in_cart: false,
                already_in_cart_personal_use: false,
                already_in_cart_company_use: true,
                user: _.isEmpty(this.$parent.user) ? this.$root.$refs.adminNav.user : this.$parent.user,
            }
        },
        beforeMount () {
            this.experience.features = this.parseFeatures(this.experience.features)
        },
        mounted () {
            this.setTooltipOrientation();
            this.isInCart();
            window.addEventListener('resize', () => {
                this.setTooltipOrientation();
            })
        },
        methods: {
            analytics () {
                window.location.href = route('dashboard.experiences', {exp_item: this.experience.title})
            },
            assignLicences () {
                window.location.href = route('experiences.show',{'experience': this.experience.system_id}).toString() + '/buy-licences'
            },
            preview () {
                window.location.href = route('experience.preview', {'experience': this.experience.system_id})
            },
            resume () {
                window.location.href = route('experience.viewer.enroll',{'experience':this.experience.system_id,'enrollment_id': this.enroll.id })
            },
            openModal () {
                this.modal.show = true;
            },
            closeModal () {
                this.modal.show = false;
            },
            buyLicences () {
                window.location = route('experiences.show',{'experience':this.experience.system_id}).toString()+'/buy-licences';
            },
            purchase() {
                axios({
                    method: 'POST',
                    url: route('shopping-cart.add', {experience: this.experience.system_id}),
                    params: {
                        user: this.user.id
                    }
                }).then((response) => {
                    this.already_in_cart = true;
                    this.isInCart();
                    this.$snotify.confirm('¿Ir al checkout?', '¡Experiencia agregada!', {
                        showProgressBar: true,
                        closeOnClick: false,
                        buttons: [
                            {
                                text: 'Si',
                                action: () => {
                                    window.location.href = window.location.origin + '/shopping-cart/checkout';
                                },
                            },
                            {
                                text: 'Seguir explorando',
                                action: (toast) => {
                                    this.$snotify.remove(toast.id);
                                }
                            }
                        ]
                    });

                }).catch((error) => {
                    console.log(error);
                });
            },
            parseFeatures (string) {
                if(typeof string !== 'object') {
                    let features = JSON.parse(string);

                    if (typeof features.hours_content !== 'object') {
                        features.hours_content = {
                            time_value: parseInt(features.hours_content),
                            time_period: null,
                        }
                    }
                    return features;
                }
                return string;
            },
            getPeriod (period, value_of_period) {
                if (value_of_period < 2 && period) {
                    if(period){
                        return period.substring(0, period.length - 1);
                    }
                } else {
                    return period;
                }
            },
            showMobileTooltip () {
                this.tooltip.show = true;
            },
            hideMobileTooltip () {
                this.tooltip.show = false;
            },
            showTooltip () {
                this.setTooltipOrientation();
                let clases = document.querySelector('.tooltip.tooltip-'+this.experience.system_id).classList;
                clases.add('active');
                this.tooltip.show = true;
            },
            hideTooltip () {
                let clases = document.querySelector('.tooltip.tooltip-'+this.experience.system_id).classList;
                clases.remove('active');
                this.tooltip.show = false;
            },
            setTooltipOrientation () {
                let width = Math.floor(document.querySelector('.experiences').clientWidth);
                let tooltip_position = Math.floor(document.querySelector('.element-'+this.experience.system_id).getBoundingClientRect().right);
                let clases = document.querySelector('.tooltip.tooltip-'+this.experience.system_id).classList;
                // console.log('================================')
                // console.log('current width: ', width);
                // console.log('current right position: ', tooltip_position);
                // console.log('current operation result: ', width - tooltip_position);
                // console.log('================================')
                if ((width - tooltip_position) > 100) {
                    clases.remove('left');
                    clases.add('right');
                } else {
                    clases.remove('right');
                    clases.add('left');
                }
            },
            enrollUser () {
                let has_enrollment = this.experience.current_user_progress.enrollments_data.length > 0;
                let has_licence = this.experience.auth_user_has_licence;
                if (!has_licence || (has_licence && !has_enrollment) ) {
                    this.$snotify.async('Enrolamiento', 'Procesando Enrolamiento', () => new Promise((resolve, reject) => {
                        return axios({
                            method: 'POST',
                            url: route('experience.enroll', {experience: this.experience}),
                            params: {
                                user: this.user.id
                            }
                        }).then((response) => {
                            //this.$parent.updateExperiences();
                            window.location.href = route('experience.viewer', {experience: this.experience.system_id})

                        }).catch((error) => {
                            console.log(error);
                            reject({
                                title: 'Error!!!',
                                body: error.response.data.message,
                                config: {
                                    closeOnClick: true,
                                    html: `
                                        <div class="snotifyToast__title">` + error.data.title + `</div>
                                        <div class="snotifyToast__body">` + error.data.message + `</div>
                                        <div class="snotify-icon snotify-icon--success"></div>
                                        `
                                }
                            })
                        });

                    }));
                }
            },
            addToCart () {
                this.$parent.addToCart(this.experience);
            },
            isInCart() {
                let cookie;
                let re = new RegExp('cart_list' + "=([^;]+)");
                let value = re.exec(document.cookie);
                cookie = (value != null) ? JSON.parse(unescape(value[1])) : null;

                if (cookie) {
                    Object.entries(cookie).map((item) => {
                        if (item[1].id == this.experience.id) {
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
            }
        }
    }
</script>

<style lang="scss" scoped>
    .experiences .card {
        border-radius: 0px;
        box-shadow: none;
    }
    .experiences .card .experience.card-content {
        padding: 1.4rem;
        max-width: 100%;
        max-height: 100%;
        min-height: 550px;
        border-radius: 0px;
        /*overflow: hidden;*/
        box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.214334);
    }
    .experiences.mobile .card .card-content {
        min-height: 480px;
    }

    .experiences.mobile .card .card-content .card-image {
        height: 271px;
    }

    .experiences .card .card-content .card-image {
        height: 262px;
        overflow: hidden;
        top: -24px;
        left: -24px;
        width: calc(100% + 48px);
    }
    .experiences .card .card-content .card-image .image-content {
        width: 125%;
        height: 262px;
        background-size: cover;
        background-repeat: no-repeat;
        background-position-x: center;
        transform: translateX(-15%);
    }
    .experiences .card .card-content .card-author {
        position: absolute;
        right: 20px;
        top: calc(270px - 33px);
        width: 50px;
        height: 50px;
        overflow: hidden;
        border-radius: 10px;
        background-color: white;
        box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.214334);
    }
    .experiences .card .card-content .element-title {
        min-height: 90px;
        max-height: 90px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
    }

    .experiences .card .created-by {
        color: #1A6BF7;
        height: 53.33px;
        > div {
            text-overflow: ellipsis;
            width: 100%;
            overflow: hidden;
        }
    }
    .experiences .card .card-author .author-content {
        width: 50px;
        height: 50px;
    }
    .experiences .card .mobile-card-author .author-content {
        padding-left: 0px;
        padding-right: 0px;
        width: 50px;
        height: 50px;
        overflow: hidden;
        border-radius: 10px;
        background-color: white;
        box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.214334);
    }
    .experiences .card .current-progress {
        height: 45px;
    }
    .experiences .card .action-buttons {
        position: absolute;
        width: 95%;
        bottom: 25px;
    }
    @media only screen and (min-width: 812px) and (max-width: 812px) and (orientation: landscape) {
        .experiences .card .landscape-action-buttons {
            position: absolute;
            width: 95%;
            bottom: 25px;
        }
    }

    .experiences .card .tooltip.active {
        display: block !important;
    }
    .experiences .card .tooltip {
        display: none !important;
        position: absolute;
        top: 0;
        width: 100%;
        height: 80%;
        z-index: 1;
    }
    .experiences .card .tooltip.right {
        right: -100%;
    }
    .experiences .card .tooltip.left {
        left: -100%;
    }
    .experiences .card .tooltip .tooltip-direction {
        position: absolute;
        width: 40px;
        height: 30px;
        top: 10px;
    }
    .experiences .card .tooltip.right .tooltip-direction {
        left: 0;
        border-right: solid 30px rgba(255,255,255,1);
        border-top: solid 30px transparent;
        border-bottom: solid 30px transparent;
    }
    .experiences .card .tooltip.left .tooltip-direction {
        right: 0;
        border-left: solid 30px rgba(255,255,255,1);
        border-top: solid 30px transparent;
        border-bottom: solid 30px transparent;
    }
    .experiences .card .tooltip.left .tooltip-content {
        margin-left: -2rem;
        box-shadow: 8px 8px 25px rgba(0, 0, 0, 0.25);
    }
    .experiences .card .tooltip.right .tooltip-content {
        margin-left: 2rem;
        box-shadow: -8px 8px 25px rgba(0, 0, 0, 0.25);
    }
    .experiences .card .tooltip .tooltip-content {
        width: 100%;
        height: 100%;
        background-color: #fff;
    }
    .experiences .card .tooltip-content .description {
        max-height: 120px;
        height: 120px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 5;
        -webkit-box-orient: vertical;
    }
    .experiences .card .tooltip .tooltip-content .features-content i {
        width: 20px;
        padding-right: 5px;
    }

    .experiences .card .mobile-tooltip {
        position: absolute;
        top: 58px;
        left: 0;
        width: 100%;
        height: calc(100% - 58px);;
        background-color: rgba(0,0,0,0.8);
    }
    @media only screen and (orientation: landscape) {
        .experiences .card .mobile-tooltip {
            top: 58px;
            height: 90%;
        }
        .experiences .card .mobile-tooltip .tooltip-content .description {
            margin-top: 12% !important;
        }
        .experiences .card .card-content .card-image .image-content {
            width: 100%;
            transform: none;
        }
    }
    @media only screen and (min-width: 768px) and (max-width: 768px) and (orientation: portrait) {
        .experiences .card .card-content {
            min-height: 525px;
        }
        .experiences .card .card-content .price {
            font-size: 17px;
        }
        .experiences .card .mobile-tooltip .tooltip-content .description {
            margin-top: 18% !important;
        }
        .experiences .card .card-content .card-image {
            height: 200px;
        }
    }
    .experiences .card .mobile-tooltip .tooltip-content .close {
        position: absolute;
        top: 0;
        right: 0;
    }
    .experiences .card .mobile-tooltip .tooltip-content {
        padding: 16px;
    }
    .experiences .card .mobile-tooltip .tooltip-content .description {
        margin-top: 25%;
        color: #ffffff;
    }

    .is-squared {
        border-radius: 0px;
    }
    a.has-text-primary:hover {
        color: orange !important;
    }
    a.button.is-primary:hover {
        text-decoration: none !important;
    }
</style>
<style>
    .actions-dropdown .dropdown .dropdown-trigger {
        width: 100% !important;
    }
    .actions-dropdown .dropdown .dropdown-trigger .dropdown-trigger-arrow {
        position: absolute;
        right: 16px;
    }
    .actions-dropdown .dropdown .dropdown-menu {
        width: 100% !important;
    }
    .actions-dropdown .dropdown .dropdown-menu .dropdown-content .dropdown-item:hover {
        text-decoration: none;
    }
    .actions-dropdown .dropdown .dropdown-menu .dropdown-content button.button.justify-left {
        justify-content: left;
        text-align: left;
    }
    .actions-dropdown .dropdown .dropdown-menu .dropdown-content button.button.justify-left i {
        width: 20px;
        text-align: center;
    }
</style>
