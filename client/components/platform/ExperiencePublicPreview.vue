<template>
    <div class="experience-preview-component">
        <div class="header-container">
            <div class="d-none d-md-block desktop-header">
                <img v-if="!experience.cover_top" class="header-image" :src="experience.full_path_cover" alt="default_img">
                <img v-if="experience.cover_top" class="header-image-top" :src="experience.full_path_cover_top" alt="default_img">
                <div class="header-text">
                    <div class="row ml-0 mr-0 full-height">
                        <div class="col-12 align-self-end">
                            <span class="experience-title">{{experience.title}}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-block d-md-none mobile-header">
                <div class="image-container">
                    <img class="cover landscape" :src="experience.full_path_cover_top" alt="default_img">
                    <img class="cover portrait" :src="experience.full_path_cover" alt="default_img">
                </div>
                <div class="title-container">
                    <div class="row full-height ml-0 mr-0">
                        <div class="col-12 align-self-center text-left">
                            <span class="has-text-white has-text-weight-bold font-20">
                                {{ experience.title }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mobile-actions action-buttons d-block d-md-none d-lg-none hidden" v-sticky>
            <div class="sticky-actions-title">
                <h3>{{experience.title}}</h3>
            </div>
            <div class="row">
                <!--
                <div class="col-5 text-center offset-1">
                    <button class="btn pointer" @click="goToExperience"><b>Probar</b></button>
                </div>
                -->
                <div class="col-5 text-center" @click="purchase" v-if="!!experience.price_default">
                    <button class="btn pointer">
                        <i class="fa fa-shopping-cart" v-if="false"></i> <b>Añadir al carrito</b>
                    </button>
                </div>
                <div class="col-5 text-center" @click="isAdventureFinished" v-else>
                    <button class="btn pointer">
                        <i class="fa fa-leanpub" v-if="false"></i> <b>Comenzar</b>
                    </button>
                </div>
            </div>
        </div>
        <div class="content-container">
            <div class="row mr-0 ml-0">
                <div class="col-sm-3 d-none d-md-block mt-4">
                    <div class="row mb-4">
                        <div class="col-12 has-text-centered">
                            <span class="price is-bold" v-if="!!experience.price_default">
                                <!-- {{ money.suffix + experience.price_default + ' ' + money.prefix }} -->
                                {{ '$' + experience.price_default + ' ' + 'MXN' }}
                            </span>
                            <span class="price is-bold" v-else> Gratis</span>
                        </div>
                    </div>
                    <div class="action-buttons">
                        <div class="row justify-content-center">
                            <!--
                            <div class="col-8 text-center">
                                <button class="btn pointer" @click="goToExperience">
                                    <b>Probar</b>
                                </button>
                            </div>
                            -->
                            <div class="col-8 text-center" @click="purchase" v-if="!!experience.price_default">
                                <button class="btn pointer">
                                    <i class="fa fa-shopping-cart" v-if="false"></i> <b>Añadir al carrito</b>
                                </button>
                            </div>
                            <div class="col-8 text-center" @click="isAdventureFinished" v-else>
                                <button class="btn pointer">
                                    <i class="fa fa-leanpub" v-if="false"></i> <b>Comenzar</b>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-9 ml-0 mr-0">
                    <div class="">
                        <div class="pt-4 pb-4">
                            <span class="one-half">¿De qu&eacute; se trata?</span>
                        </div>
                        <div class="d-none d-md-block experience-description desktop">
                            <p v-html="experience.description">{{experience.description}}</p>
                        </div>
                        <div class="d-block d-md-none experience-description mobile">
                            <div class="description-container">
                                <p v-html="experience.description">{{experience.description}}</p>
                            </div>
                            <a class="button is-primary is-inverted pl-0 pr-0" @click="keepReading">
                                <span>Seguir leyendo</span>
                                <span class="icon">
                                    <i class="fas fa-angle-down"></i>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" v-if="is_experience">
                <div class="col-md-3 experience-summary-content d-none d-md-block mb-4" ref="adventure_content"
                     v-if="has_instructors">
                    <p class="" v-if="has_features"><b>Esta experiencia tiene:</b></p>
                    <ul class="row adventure-content mt-5">
                        <li class="col-sm-12" v-if="experience_data.features.hours_content"><i class="padding-r5 fas fa-clock-o"></i> {{
                            hours_content_value }} {{ $t('messages.'+getPeriod(hours_content_period, hours_content_value)) }} de contenido
                        </li>
                        <li class="col-sm-12" v-if="num_videos"><i class="padding-r5 icon-movie-clipboard"></i> {{ num_videos }}
                            {{ (num_videos >1) ? 'videos interactivos':'video interactivo'}}
                        </li>
                        <li class="col-sm-12" v-if="num_content_downloads"><i
                                class="padding-r5 fas fa-cloud-download-alt"></i> {{ num_content_downloads }} archivo{{ (num_content_downloads > 1) ? 's': '' }}
                            para descargar
                        </li>
                        <li class="col-sm-12" v-if="num_activities"><i class="padding-r5 fas fa-basketball-ball"></i> {{
                            num_activities }} actividad{{(num_activities > 1) ? 'es' : ''}}
                        </li>
                        <li class="col-sm-12" v-if="(experience.type === 'journey' && experience_has_adventures)"><i
                                class="padding-r5 fas fa-trophy"></i> {{ experience_has_adventures }} experiencia{{ (experience_has_adventures>1)?'s':'' }}
                        </li>
                        <li class="col-sm-12" v-if="false && (experience.type === 'adventure' && experience_has_sessions)"><i
                                class="padding-r5 fas fa-trophy"></i> {{ experience_has_sessions }} reto{{(experience_has_sessions>1)?'s':''}}
                        </li>
                        <li class="col-12" v-if="false">
                            <i class="padding-r5 icon-user-card-stack"></i> {{ 57 }} estudiantes inscritos
                        </li>
                    </ul>
                </div>
                <div :class="{'col-12 col-lg-9':true, 'offset-lg-3':!has_instructors}">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-12 col-sm-6 made-by mt-5" v-if="experience.company_author">
                                <div class="has-text-weight-bold mb-4"><b>Creado por:</b></div>
                                <div class="d-block d-md-none">
                                    <div class="row">
                                        <div class="col-auto">
                                            <figure class="image is-64x64">
                                                <img class="image-profile" :src="experience.company_author.full_path_logo" alt="image_default">
                                            </figure>
                                        </div>
                                        <div class="col">
                                            <span class="text pt-0">{{ experience.company_author.name }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-none d-md-block">
                                    <div class="row">
                                        <div class="col-auto col-lg-4">
                                            <img class="image-profile" :src="experience.company_author.full_path_logo"
                                                 alt="image_default">
                                        </div>
                                        <div class="col align-self-center no-pl">
                                            <span class=""><b>{{ experience.company_author.name }}</b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 instructors mt-5" :class="{'col-sm-6':!many_instructors}" v-if="has_instructors">
                                <div class="mb-4 no-pl"><b>Instructores:</b></div>
                                <div class="instructors-list">
                                    <div class="mobile-list d-block d-md-none">
                                        <div class="row mb-4" v-for="(instructor, index) in experience.instructors">
                                            <div class="col-4">
                                                <figure class="image is-64x64">
                                                    <img class="image-profile" :src="instructor.full_path_avatar" alt="image_default">
                                                </figure>
                                            </div>
                                            <div class="col-8 align-self-center">
                                                <span class="">{{ instructor.full_name }}</span>
                                                <span class="career_text">{{ instructor.career_title }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="desktop-list d-none d-md-block">
                                        <div class="clasic-list" v-if="!many_instructors">
                                            <div class="row"  v-for="(instructor, index) in experience.instructors">
                                                <div class="col-sm-4">
                                                    <img class="image-profile" :src="instructor.full_path_avatar"
                                                         alt="image_default">
                                                </div>
                                                <div class="col-sm-8 align-self-center no-pl">
                                                    <span class="">{{ instructor.full_name }}</span>
                                                    <span class="career_text">{{ instructor.career_title }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="enlarge-list" v-else>
                                            <div class="row mb-4"  v-for="(instructor, index) in enlarge_instructors_list">
                                                <div class="col-sm-2">
                                                    <img class="image-profile" :src="instructor[0].full_path_avatar"
                                                         alt="image_default">
                                                </div>
                                                <div class="col-sm-4 align-self-center no-pl">
                                                    <span class="">{{ instructor[0].full_name }}</span>
                                                    <span class="career_text">{{ instructor[0].career_title }}</span>
                                                </div>
                                                <div class="col-sm-2" v-if="!!instructor[1]">
                                                    <img class="image-profile" :src="instructor[1].full_path_avatar"
                                                         alt="image_default">
                                                </div>
                                                <div class="col-sm-4 align-self-center no-pl" v-if="!!instructor[1]">
                                                    <span class="">{{ instructor[1].full_name }}</span>
                                                    <span class="career_text">{{ instructor[1].career_title }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 mt-5" v-else>
                                <p class="mb-4" v-if="has_features"><b>Esta experiencia tiene:</b></p>
                                <ul class="row adventure-content">
                                    <li class="col-sm-12" v-if="hours_content_value"><i class="pr-3 fas fa-clock-o"></i> {{
                                        hours_content_value }} {{ $t('messages.'+getPeriod(hours_content_period, hours_content_value)) }} de contenido
                                    </li>
                                    <li class="col-sm-12" v-if="num_videos"><i class="pr-3 icon-movie-clipboard"></i> {{
                                        num_videos }} {{ (num_videos >1) ? 'videos interactivos':'video interactivo'}}

                                    </li>
                                    <li class="col-sm-12" v-if="num_content_downloads"><i
                                            class="padding-r5 fas fa-cloud-download-alt"></i> {{ num_content_downloads }} archivo{{ (num_content_downloads > 1) ? 's': '' }}
                                        para descargar
                                    </li>
                                    <li class="col-sm-12" v-if="num_activities"><i class="padding-r5 fas fa-basketball-ball"></i> {{
                                        num_activities }} actividad{{(num_activities > 1) ? 'es' : ''}}
                                    </li>
                                    <li class="col-sm-12" v-if="(experience.type === 'journey' && experience_has_adventures)"><i
                                            class="padding-r5 fas fa-trophy"></i> {{ experience_has_adventures }} experiencia{{ (experience_has_adventures>1)?'s':'' }}
                                    </li>
                                    <li class="col-sm-12" v-if="false && (experience.type === 'adventure' && experience_has_sessions)"><i
                                            class="padding-r5 fas fa-trophy"></i> {{ experience_has_sessions }} reto{{(experience_has_sessions>1)?'s':''}}
                                    </li>
                                    <li class="col-12" v-if="false">
                                        <i class="padding-r5 icon-user-card-stack"></i> {{ 57 }} estudiantes inscritos
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" v-if="is_journey">
                <div class="offset-md-3 col-md-9 experience-summary-content mt-3">
                    <div class="row">
                        <div class="col-md-6 col-lg-4 order-1 order-md-0">
                            <p class="padding-b10" v-if="has_features"><b>Esta experiencia tiene:</b></p>
                            <ul class="row adventure-content">
                                <li class="col-sm-12" v-if="experience_data.features.hours_content"><i class="padding-r5 fas fa-clock-o"></i> {{
                                    hours_content_value }} {{ $t('messages.'+getPeriod(hours_content_period, hours_content_value)) }} de contenido
                                </li>
                                <li class="col-sm-12" v-if="num_videos"><i class="padding-r5 icon-movie-clipboard"></i> {{ num_videos }}
                                    {{ (num_videos >1) ? 'videos interactivos':'video interactivo'}}
                                </li>
                                <li class="col-sm-12" v-if="num_content_downloads"><i
                                        class="padding-r5 fas fa-cloud-download-alt"></i> {{ num_content_downloads }} archivo{{ (num_content_downloads > 1) ? 's': '' }}
                                    para descargar
                                </li>
                                <li class="col-sm-12" v-if="num_activities"><i class="padding-r5 fas fa-basketball-ball"></i> {{
                                    num_activities }} actividad{{(num_activities > 1) ? 'es' : ''}}
                                </li>
                                <li class="col-sm-12" v-if="(experience.type === 'journey' && experience_has_adventures)"><i
                                        class="padding-r5 fas fa-trophy"></i> {{ experience_has_adventures }} experiencia{{ (experience_has_adventures>1)?'s':'' }}
                                </li>
                                <li class="col-sm-12" v-if="false && (experience.type === 'adventure' && experience_has_sessions)"><i
                                        class="padding-r5 fas fa-trophy"></i> {{ experience_has_sessions }} reto{{(experience_has_sessions>1)?'s':''}}
                                </li>
                                <li class="col-12" v-if="false">
                                    <i class="padding-r5 icon-user-card-stack"></i> {{ 57 }} estudiantes inscritos
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6 col-lg-8 order-0 order-md-1 mb-4 mb-md-0">
                            <div class="made-by" v-if="experience.company_author">
                                <div class="has-text-weight-bold mb-2"><b>Creado por:</b></div>
                                <div class="row">
                                    <div class="col-auto">
                                        <img class="image-profile" :src="experience.company_author.full_path_logo"
                                             alt="image_default">
                                    </div>
                                    <div class="col align-self-center no-pl">
                                        <span class=""><b>{{ experience.company_author.name }}</b></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="spacer"></div>
                </div>
            </div>
            <hr width="4">
            <div class="experience-info-gallery">
                <div class="col-sm-12 d-none d-sm-block" v-if="(is_experience)">
                    <span class="font-24 has-text-weight-bold">{{ experience_has_sessions }} reto{{(experience_has_sessions>1)?'s':'' }} en esta experiencia</span>
                </div>
                <div class="col-sm-12 d-none d-sm-block" v-if="(is_journey)">
                    <span class="font-24 has-text-weight-bold">{{ experience_has_adventures }} experiencia{{(experience_has_adventures>1)?'s':'' }} en este viaje</span>
                </div>
                <br><br><br>
                <apithy-journey-gallery v-if="experience_has_adventures"
                                        title="El reto de hoy"
                                        :adventures="experience.adventures"
                                        :abilities="experience.taxonomy_ability">
                </apithy-journey-gallery>
                <apithy-session-gallery v-if="experience_has_sessions"
                                        title="El reto de hoy"
                                        :sessions="experience.sessions"
                                        :abilities="experience.taxonomy_ability">
                </apithy-session-gallery>
                <br><br>
            </div>
        </div>
    </div>
</template>
<script>
    import ApithySessionGallery from './ApithySessionsGallery';
    import ApithyJourneyGallery from './ApithyJourneyGallery';

    export default {
        name: 'apithy-experience-public-preview',
        props: {
            experience: {
                required: true,
                type: Object
            },
        },
        components: {
            'apithy-session-gallery': ApithySessionGallery,
            'apithy-journey-gallery': ApithyJourneyGallery,
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
            keepReading() {
                let el = document.querySelector('.experience-description.mobile .description-container');
                let icon_button = document.querySelector('.experience-description.mobile .button.is-inverted .icon .fas');

                if (el.classList.contains('expanded')) {
                    el.classList.remove('expanded');
                    //icon_button.classList.remove('fa-angle-up');
                    //icon_button.classList.add('fa-angle-down');
                } else {
                    el.classList.add('expanded');
                    //icon_button.classList.remove('fa-angle-down');
                    //icon_button.classList.add('fa-angle-up');
                    el.parentNode.removeChild(el.parentElement.querySelector('.button'));
                }
            },
            getPeriod(period, value_of_period) {
                if (value_of_period < 2 && period) {
                    return period.substring(0, period.length - 1);
                } else {
                    return period;
                }
            },
            goToExperience() {
                window.location.href = route('experience.viewer', this.experience.system_id);
            },
            isAdventureFinished() {
                let message = 'Necesitas ingresar o crear una cuenta para continuar.';
                let title = '¡Reto bloqueado!';
                let cookie = {
                    id: this.experience.id,
                    experience: this.experience.system_id,
                    event: (!!this.experience.price_default) ? 'append_to_cart' : 'enroll_user',
                    redirect_to: (!!this.experience.price_default) ? 'experience.preview' : 'experience.viewer',
                };
                this.$cookie.set('public_experience', JSON.stringify(cookie));

                window.location.href = route('signup');

                //this.$cookie.set('public_experience', JSON.stringify(cookie));
                /*
                this.$snotify.confirm(message, title, {
                    showProgressBar: true,
                    closeOnClick: false,
                    buttons: [
                        {
                            text: 'Ingresar',
                            action: (toast) => {
                                this.$snotify.remove(toast.id);
                                window.location.href = route('login');
                            },
                            bold: false
                        },
                        {
                            text: 'Crear cuenta', action: (toast) => {
                                this.$snotify.remove(toast.id);
                                window.location.href = route('signup');
                            }
                        }
                    ]
                });
                 */
            },
            purchase() {
                let cookie = {
                    id: this.experience.id,
                    experience: this.experience.system_id,
                    event: (!!this.experience.price_default) ? 'append_to_cart' : 'enroll_user',
                    redirect_to: (!!this.experience.price_default) ? 'experience.preview' : 'experience.viewer',
                };
                this.$cookie.set('public_experience', JSON.stringify(cookie));
                if (!!this.experience.price_default) {
                    window.location.href = route('signup');
                    //window.location.href = route('login');
                } else {
                    window.location.href = route('signup');
                }
            },

        },
        beforeMount() {
            for (let key in this.experience) {
                if (this.experience[key] !== null) {
                    if (key === 'features') {
                        if(typeof this.experience[key] !== 'object') {
                            let features = JSON.parse(this.experience[key]);

                            if (typeof features.hours_content !== 'object') {
                                features.hours_content = {
                                    time_value: parseInt(features.hours_content),
                                    time_period: null,
                                }
                            }

                            if (this.experience.type === this.apithy_constants.EXPERIENCE_TYPE_JOURNEY) {
                                console.log(features)
                                features.num_activities = features.num_content_downloads = features.num_videos = 0;
                                _.each(this.experience.adventures, adventure => {
                                    adventure.features = this.parseFeatures(adventure.features);
                                    _.each(adventure.features, (f,k) => {
                                        if (k !== 'hours_content') {
                                            features[k] += parseInt(f)
                                        }
                                    })
                                });
                            }

                            this.experience_data[key] = features;
                        }
                    } else {
                        this.experience_data[key] = this.experience[key];
                    }
                }
            }
        },
        computed: {
            is_experience: function () {
                return this.experience.type === this.apithy_constants.EXPERIENCE_TYPE_ADVENTURE;
            },
            is_journey: function () {
                return this.experience.type === this.apithy_constants.EXPERIENCE_TYPE_JOURNEY;
            },
            hours_content_period() {
                return (this.experience_data.features) ? this.experience_data.features.hours_content.time_period : '';
            },
            hours_content_value() {
                return (this.experience_data.features && this.experience_data.features.hours_content.time_value > 0) ? this.experience_data.features.hours_content.time_value : 0;
            },
            num_videos() {
                return (this.experience.features && this.experience.features.num_videos > 0) ? this.experience.features.num_videos : 0;
            },
            num_content_downloads() {
                return (this.experience.features && this.experience.features.num_content_downloads > 0) ? this.experience.features.num_content_downloads : 0;
            },
            num_activities() {
                return (this.experience.features && this.experience.features.num_activities > 0) ? this.experience.features.num_activities : 0;
            },
            experience_has_sessions() {
                return (this.experience.sessions && this.experience.sessions.length > 0) ? this.experience.sessions.length : 0;
            },
            experience_has_adventures() {
                return (this.experience.adventures && this.experience.adventures.length > 0) ? this.experience.adventures.length : 0;
            },
            has_features() {
                return (this.hours_content || this.num_videos || this.num_content_downloads || this.num_activities || this.experience_has_adventures || this.experience_has_sessions);
            },
            isUserEnrolled() {
                if (this.experience_data.current_user_enrollment !== null) {
                    return true;
                }
            }
        },
        data() {
            return {
                show_more: false,
                show_modal: false,
                has_instructors: this.experience.instructors.length,
                enrolled_button: false,
                is_purchased: this.experience.is_current_user_purchased,
                is_cart_added: this.experience.cart_added,
                loading: true,
                experience_data: this.experience
            }
        }
    }
</script>
<style>
@media (device-width: 375px) and (device-height: 812px) {
    body {
        height: 100%;
        overflow-y: unset;
    }
}
</style>
<style scoped>
    .experience-info-gallery {
        max-width: 1700px;
        margin: 0 auto;
        display: block;
    }

    .header-container {
        position: relative;
        display: inline-block;
        width: 100%;
        overflow: hidden;
        background-color: white;
    }
    .header-container .desktop-header {
        display: block;
        height: 100%;
        overflow: hidden;
    }
    .header-container .desktop-header .experience-title {
        padding-left: calc(100vw - 75%);
        display: inline-block;
    }
    .header-container .mobile-header {
        display: none;
        height: 270px;
        overflow: hidden;
    }
    .header-container .mobile-header .title-container {
        position: absolute;
        bottom: 0;
        width: 100%;
        height: 40%;
        background-color: rgba(0, 0, 0, 0.5);
    }
    .header-container .mobile-header .image-container {
        position: absolute;
        bottom: 0;
    }
    .header-container .header-image {
        width: 100vw;
        position: absolute;
        right: 0;
        top: -150%;
    }
    .header-container .header-image-top {
        width: 100vw;
        min-height: 200px;
        right: 0;
    }
    .header-container .header-text {
        width: 100%;
        position: absolute;
        bottom: 0;
        color: #FFF;
        font-size: 1.8rem;
        font-weight: bold;
        background: linear-gradient(rgba(0,0,0,0), rgba(0,0,0,.8));
        height: 40%;
    }

    .mobile-actions {
        background-color: #979797;
        padding: 20px;
    }
    .mobile-actions h3 {
        padding-left: 20px;
        margin-bottom: 10px;
    }
    .action-buttons .text-center {
        padding: 5px;
        text-align: center;
        height: 50%;
    }
    .action-buttons button.btn {
        padding: 5px 10px;
        width: 100%;
        height: 90%;
        color: #FFF;
        background-color: #FFA900;
        border-radius: 4px;
        border: none;
    }

    .one-half {
        font-size: 1.5rem;
    }
    .padding-r5 {
        padding-right: 5px;
    }
    .padding-b10 {
        padding-bottom: 10px;
    }
    .padding-b16 {
        padding-bottom: 16px;
    }
    .pointer {
        cursor: pointer;
    }

    .adventure-content li {
        margin-bottom: 10px;
        text-transform: lowercase;
    }

    .experience-description.mobile .description-container {
        max-height: 125px;
        overflow-y: hidden;
    }
    .experience-description.mobile .description-container.expanded {
        max-height: 100%;
        overflow-y: visible;
    }

    .image-profile {
        /*margin: 5px 12px 5px 5px;*/
        width: 100%;
        height: 100%;
        max-width: 75px;
        /*height: 100px;*/
        border-radius: 10px;
        box-shadow: 2px 2px 4px 0px rgba(76, 79, 82, 0.5);
    }

    .content-container {
        margin-top: -7px;
        padding: 0 10%;
    }

    .text {
        padding-top: 15px;
    }

    .text, .career_text {
        display: block;
    }

    .adventure-select select{
        background-color: transparent !important;
    }

    .select.adventure-select select{
        background-color: transparent !important;
    }

    span.price {
        font-size: 1.5rem;
    }

    .spacer {
        border-bottom: 1px solid #BEBEBE;
    }

    .mobile-actions {
        margin-top: -7px;
    }

    .content-container {
        background-color: #FFFFFF;
    }

    @media only screen and (min-width: 768px) and (max-width: 1024px) {

        .header-container .desktop-header .experience-title {
            font-size: 22px;
        }
    }

    @media only screen and (max-width: 768px) and (orientation: portrait) {
        /*
        @media only screen and (max-width: 1200px){
            .instructors .instructors-list {
                max-height: auto;
            }

            .header-container .header-text {
                transform: translate(2vw, -100%);
            }

            .experience-summary-content {
                font-size: 12px;
            }
        }

        @media only screen and (max-width: 768px) {
            .header-container .header-text {
                font-size: 1.4rem;
            }

            .made-by .text {
                font-size: 14px;
            }

            .teacher .text {
                font-size: 14px;
                padding-top: 10px;

            }

            .experience-summary-content li {
                font-size: 10px;
            }
        }

        @media only screen and (max-width: 992px){
            .instructors .instructors-list {
                max-height: auto;
            }

            .header-container .header-text span {
                display: inline-block;
                text-align: left;
            }

            .header-container .header-text {
                transform: translate(2vw, -100%);
            }

            .experience-description {
                font-size: 12px;
            }

            .experience-summary-content {
                font-size: 12px;
            }

            .image-profile {
                width: 70px;
                height: 70px;
            }
        }

        @media only screen and (max-width: 576px) {
            .header-container .header-image {
                top: 0;
            }

            .top-sticky {
                display: block !important;
            }

            .header-container .header-text {
                font-size: 1rem;
                bottom: 30%;
            }

            .teacher {
                margin-top: 20px;
            }

            .experience-description p {
                font-size: 16px;
            }
        }
        */
        .header-container .desktop-header {
            display: none;
        }
        .header-container .mobile-header {
            display: block;
        }
        .header-container .mobile-header .cover.landscape {
            display: none;
        }
        .header-container .mobile-header .cover.portrait {
            display: block;
        }
        .content-container {
            margin-top: 0px;
            padding: 0 5%;
        }
    }

    @media only screen and (orientation: landscape) {
        .header-container .desktop-header {
            display: none;
        }
        .header-container .mobile-header {
            display: block;
            height: 100px;
        }
        .header-container .mobile-header .cover.landscape {
            display: block;
        }
        .header-container .mobile-header .cover.portrait {
            display: none;
        }
        .content-container {
            padding: 0 10%;
            background-color: #FFFFFF;
        }
    }
</style>
