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

        <div class="card-content">
            <div class="d-none d-lg-block card-image" @mouseenter="showTooltip" @mouseleave="hideTooltip">
                <div class="image-content">
                    <img :src="experience.full_path_cover" alt="">
                </div>
            </div>
            <div class="d-block d-lg-none card-image" @click="showMobileTooltip">
                <div class="image-content">
                    <img :src="experience.full_path_cover" alt="">
                </div>
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
                <div class="row details font-18 has-text-weight-bold d-none d-lg-block" :class="{'mt-1':showProgress, 'mt-3':!showProgress}">
                    <div class="col-12">
                        <a :href="route('experience.preview', {experience:experience.system_id})" class="has-text-primary pl-0 pr-0 font-16">{{ $t('messages.marketplace.more_details') }}</a>
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
                    <div class="col-6 align-self-center has-text-weight-bold price" v-if="!experience.auth_user_has_licence">
                        {{ price }}
                    </div>
                    <div class="col-6 align-self-center" v-if="experience_is_started || experience_is_finished">
                        <a :href="route('experience.viewer.enroll', {experience:experience.system_id, enrollment_id: enroll.id})" class="button is-primary is-fullwidth has-text-weight-bold">{{ $t('messages.marketplace.continue') }}</a>
                    </div>
                    <div class="col-6 align-self-center" v-else-if="experience_not_started">
                        <a :href="route('experience.viewer.enroll', {experience:experience.system_id, enrollment_id: enroll.id})" class="button is-primary is-fullwidth has-text-weight-bold">{{ $t('messages.marketplace.start') }}</a>
                    </div>
                    <div class="col-6 align-self-center" v-else-if="experience.price == 0 || is_assigned || user_has_licence">
                        <a class="button is-primary is-fullwidth has-text-weight-bold has-text-white"  @click="enrollUser">{{ $t('messages.marketplace.start') }}</a>
                    </div>
                    <div class="col-6 align-self-center" v-else>
                        <a :href="route('experience.preview', {experience:experience.system_id})" class="button is-primary is-fullwidth has-text-weight-bold">{{ $t('messages.marketplace.test') }}</a>
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
    </div>
</template>

<script>
    export default {
        name: "CardElement",
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
            }
        },
        components: {},
        computed: {
            is_assigned: function () {
                return window.location.href === route('experience.assigned').toString();
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
                return (this.experience.auth_user_has_licence && !_.isEmpty(this.currentEnrollment) && (this.currentEnrollment.pivot.status === 7 || this.currentEnrollment.pivot.status === 8))
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
                user: _.isEmpty(this.$parent.user) ? this.$root.$refs.adminNav.user : this.$parent.user,
                blockEnrollButton: false
            }
        },
        beforeMount () {
            this.experience.features = this.parseFeatures(this.experience.features)
        },
        mounted () {
            this.setTooltipOrientation();
            window.addEventListener('resize', () => {
                this.setTooltipOrientation();
            })
        },
        methods: {
            parseFeatures(string) {
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
            getPeriod(period, value_of_period) {
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
            addToCart () {
                this.$parent.addToCart(this.experience);
            },
            enrollUser () {
                let has_enrollment = this.experience.current_user_progress.enrollments_data.length > 0;
                let has_licence = this.experience.auth_user_has_licence;
                if (this.blockEnrollButton)
                    return 0;
                this.blockEnrollButton = true;
                if (!has_licence || (has_licence && !has_enrollment) ) {
                    this.$snotify.async('Enrolamiento', 'Procesando Enrolamiento', () => new Promise((resolve, reject) => {
                        return axios({
                            method: 'POST',
                            url: route('experience.enroll', {experience: this.experience}),
                            params: {
                                user: this.$parent.user.id
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
            }
        }
    }
</script>

<style lang="scss" scoped>
    .experiences .card {
        border-radius: 0px;
        box-shadow: none;
    }
    .experiences .card .card-content {
        padding: 1.4rem;
        max-width: 100%;
        max-height: 100%;
        min-height: 550px;
        border-radius: 0px;
        overflow: hidden;
        box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.214334);
    }
    .experiences.mobile .card .card-content {
        min-height: 480px;
    }

    .experiences .card .card-content .card-image {
        overflow: hidden;
        top: -24px;
        left: -24px;
        width: calc(100% + 48px);
    }
    .experiences .card .card-content .card-image .image-content {
        width: 125%;
        transform: translateX(-15%);
        height: 262px;
    }
    .experiences .card .card-content .card-author {
        position: absolute;
        right: 20px;
        top: calc(270px - 24px);
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
            height: 300px;
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
