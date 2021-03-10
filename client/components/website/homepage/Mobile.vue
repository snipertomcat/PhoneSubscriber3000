<template>
    <div class="mobile">
        <Slideout menu="#menu" panel="#panel" :toggleSelectors="['.slideout-trigger']" ref="slideout">
            <nav id="menu">
                <div class="row ml-0 mr-0 no-gutters justify-content-end align-items-center so-menu-header">
                    <div class="col-auto">
                        <div class="so-close">
                            <span class="slideout-trigger"><i class="fa fa-times"></i></span>
                        </div>
                    </div>
                </div>
                <div class="so-body">
                    <div class="row ml-0 mr-0 no-gutters justify-content-center align-items-center so-item">
                        <div class="col-auto"><a href="#"><span>Productos</span></a></div>
                    </div>
                    <div class="row ml-0 mr-0 no-gutters justify-content-center align-items-center so-item">
                        <div class="col-auto"><a href="#"><span>Precios</span></a></div>
                    </div>
                    <div class="row ml-0 mr-0 no-gutters justify-content-center align-items-center so-item">
                        <div class="col-auto">
                            <div class="contact">
                                <!--a href="#four" -->
                                    <div class="landing-button" @click="goToContact">
                                        <span>{{ 'Contacto' }}</span>
                                    </div>
                                <!--/a-->
                            </div>
                        </div>
                    </div>
                    <div class="row ml-0 mr-0 justify-content-center align-items-center so-item b-bottom">
                        <div class="col-auto">
                            <div class="social-btn btn-instagram">
                                <a target="_blank" href="https://www.instagram.com/apithylearning/">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="social-btn btn-facebook">
                                <a target="_blank" href="https://www.facebook.com/apithylearning/">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="row ml-0 mr-0 no-gutters justify-content-center align-items-center so-item">
                        <div class="col-auto">
                            <a href="/login">
                                <span class="login">Ingresa</span>
                            </a>
                        </div>
                    </div>
                    <div class="row ml-0 mr-0 no-gutters justify-content-center align-items-center so-item">
                        <div class="col-auto">
                            <a href="/signup"><div class="link button"><span>Regístrate</span></div></a>
                        </div>
                    </div>
                </div>
            </nav>
            <main id="panel">
                <div id="">
                <section class="section" id="one">
                    <apithy-homepage-navbar :host="source" :mobile-view="true"></apithy-homepage-navbar>
                    <div class="apithy-logo text-center">
                        <img src="/apihty_logo_blanco.png" alt="">
                    </div>
                    <div class="apithy-slogan text-center">
                        <p>
                            <i>Live the <b>learning</b> digital <br>experience</i>
                        </p>
                    </div>
                    <div class="apithy-slogan-two text-center">
                        <p>
                            Es <b>formación</b> de personas a través de un ambiente digital <b>adaptativo</b>
                        </p>
                    </div>
                    <div class="apithy-video text-center">
                        <a href="#video-popup" class="open-popup-link">
                            <div class="btn-video">
                                <span>
                                    {{ 'Ver video' }}
                                </span>
                                <span class="icon-play">
                                    <i class="fa fa-play"></i>
                                </span>
                            </div>
                        </a>
                    </div>
                </section>
                <section class="section" id="two">
                    <div class="apithy-slogan">
                        <p>¿Aún capacitas <br>empleados?</p>
                    </div>
                    <div class="section-static-with-background">
                        <div class="apithy-slogan-two">
                            <p>Te proponemos <b>formar <br>al mejor <br>talento</b> y <b>liberar su potencial</b></p>
                        </div>
                    </div>
                    <div class="apithy-features swiper-container">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide active" data-id="one">
                                <div class="card feature">
                                    <div class="feature-image">
                                        <img src="/images/landing/hifive-h.png" alt="">
                                    </div>
                                    <div class="card-body">
                                        <p>
                                            Construimos <br>
                                            <b>ambientes adaptativos</b> <br>
                                            digitales para <b>formar talento</b>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide" data-id="two">
                                <div class="card feature">
                                    <div class="feature-image">
                                        <img src="/images/landing/map-h.png" alt="">
                                    </div>
                                    <div class="card-body">
                                        <p>
                                            Implementamos <br>
                                            <b>contigo el proceso</b> <br>
                                            de formación
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide" data-id="three">
                                <div class="card feature">
                                    <div class="feature-image">
                                        <img src="/images/landing/clock-h.png" alt="">
                                    </div>
                                    <div class="card-body">
                                        <p>
                                            Diseñamos <b>estrategias de</b> <br>
                                            <b>formación</b> adaptadas a la <br>
                                            forma de aprender de <b>tu</b> <br>
                                            <b>talento</b> con inteligencia <br>
                                            artificial
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide" data-id="four">
                                <div class="card feature">
                                    <div class="feature-image">
                                        <img src="/images/landing/filter-h.png" alt="">
                                    </div>
                                    <div class="card-body">
                                        <p>
                                            Desarrollamos junto a tu <br>
                                            equipo los contenidos en <br>
                                            formato digital para <br>
                                            capitalizar el conocimiento <br>
                                            de tu empresa
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide" data-id="five">
                                <div class="card feature">
                                    <div class="feature-image">
                                        <img src="/images/landing/platform-h.png" alt="">
                                    </div>
                                    <div class="card-body">
                                        <p>
                                            Evidenciamos la <br>
                                            formación y aprendizaje <br>
                                            de tu talento
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="apithy-features-dots">
                        <div class="row ml-0 mr-0 justify-content-center">
                            <div class="col-auto"><div class="dot active" data-id="one"></div></div>
                            <div class="col-auto"><div class="dot" data-id="two"></div></div>
                            <div class="col-auto"><div class="dot" data-id="three"></div></div>
                            <div class="col-auto"><div class="dot" data-id="four"></div></div>
                            <div class="col-auto"><div class="dot" data-id="five"></div></div>
                        </div>
                    </div>
                    <div class="apithy-request-demo">
                        <a href="#four">
                            <div class="btn-request">
                                <span>Solicita un demo</span>
                            </div>
                        </a>
                    </div>
                </section>
                <section class="section" id="three">
                    <template v-if="isPortrait">
                        <div class="apithy-slogan">
                            <p>Digital <br>vs Presencial</p>
                        </div>
                        <div class="section-static-with-background"></div>
                    </template>
                    <template v-if="isLandscape">
                        <div class="section-static-without-background">
                            <div class="apithy-slogan">
                                <p>Digital <br>vs Presencial</p>
                            </div>
                        </div>
                    </template>
                    <div class="apithy-features swiper-container">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide active" data-id="one">
                                <div class="card feature row align-items-center">
                                    <div class="feature-image col-auto">
                                        <img src="/images/landing/peoples3.png" alt="">
                                    </div>
                                    <div class="card-body col-auto">
                                        <p>
                                            <b>El aprendizaje</b> digital incrementa la retención entre <b>25% y 60%</b> mientras que el presencial es entre el <b>8% y 10%</b>.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide" data-id="two">
                                <div class="card feature row align-items-center">
                                    <div class="feature-image col-auto">
                                        <img src="/images/landing/laptop2.png" alt="">
                                    </div>
                                    <div class="card-body col-auto">
                                        <p>
                                            Con aprendizaje digital, <b>los participantes aprenden 5 veces más</b> material sin incrementar el tiempo de entrenamiento.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide" data-id="three">
                                <div class="card feature row align-items-center">
                                    <div class="feature-image col-auto">
                                        <img src="/images/landing/hifive2.png" alt="">
                                    </div>
                                    <div class="card-body col-auto">
                                        <p>
                                            Para <b>2020, el 50%</b> de <b>la fuerza laboral será millennials</b>, nativamente digitales.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide" data-id="four">
                                <div class="card feature row align-items-center">
                                    <div class="feature-image col-auto">
                                        <img src="/images/landing/ipad2.png" alt="">
                                    </div>
                                    <div class="card-body col-auto">
                                        <p>
                                            <b>Micro-learning</b> hace <b>17%</b> más efectivo el aprendizaje.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide" data-id="five">
                                <div class="card feature row align-items-center">
                                    <div class="feature-image col-auto">
                                        <img src="/images/landing/filter2.png" alt="">
                                    </div>
                                    <div class="card-body col-auto">
                                        <p>
                                            <b>Ingresos</b> generados por empleado son <b>26% más alto para compañías</b> que ofrecen entrenamiento utilizando tecnología, incluyendo aprendizaje digital.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide" data-id="six">
                                <div class="card feature row align-items-center">
                                    <div class="feature-image col-auto">
                                        <img src="/images/landing/laptop-work2.png" alt="">
                                    </div>
                                    <div class="card-body col-auto">
                                        <p>
                                            <b>65%</b> de los millennials escogen su trabajo por las <b>oportunidades de crecimiento personales y profesionales</b>.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="apithy-features-dots">
                        <div class="row ml-0 mr-0 justify-content-center">
                            <div class="col-auto"><div class="dot active" data-id="one"></div></div>
                            <div class="col-auto"><div class="dot" data-id="two"></div></div>
                            <div class="col-auto"><div class="dot" data-id="three"></div></div>
                            <div class="col-auto"><div class="dot" data-id="four"></div></div>
                            <div class="col-auto"><div class="dot" data-id="five"></div></div>
                            <div class="col-auto"><div class="dot" data-id="six"></div></div>
                        </div>
                    </div>
                    <div class="apithy-request-demo">
                        <a href="#four">
                            <div class="btn-request" @click="fullPage.reference.moveTo(4)">
                                <span>Solicita un demo</span>
                            </div>
                        </a>
                    </div>
                </section>
                <section class="section" id="four">
                    <div class="footer" v-if="isPortrait">
                        <div class="footer-container">
                            <div class="row ml-0 mr-0 justify-content-between">
                                <div class="col-8 footer-text">
                                    <p>
                                        Apithy® CDMX 2019 <br>TODOS LOS DERECHOS RESERVADOS <br>Tabasco, CDMX, Querétaro
                                    </p>
                                </div>
                                <div class="col-auto footer-text text-right">
                                    <p>
                                        <a target="_blank" href="/aviso-de-privacidad">Aviso de <br>privacidad</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="decoration">
                        <!--div class="rectangle-one"></div-->
                    </div>
                    <div class="apithy-slogan">
                        <p>
                            <b>Queremos</b> ser parte de tu <b>éxito</b> como empresa.
                        </p>
                    </div>
                    <div class="apithy-contact-form portrait" v-show="isPortrait">
                        <div class="contact-form">
                            <div class="field field-one">
                                <label>Nombre completo*</label><br>
                                <input name="name" type="text" v-model="form.name" ref="form_name" v-validate="validators.name">
                                <div class="text-danger" v-show="errors.has('name')">{{ errors.first('name') }}</div>
                            </div>
                            <div class="field field-two">
                                <label>Nombre de tu empresa*</label><br>
                                <input name="company" type="text" v-model="form.company" ref="form_company" v-validate="validators.company">
                                <div class="text-danger" v-show="errors.has('company')">{{ errors.first('company') }}</div>
                            </div>
                            <div class="field field-three">
                                <label>Correo electrónico*</label><br>
                                <input name="email" type="email" v-model="form.email" ref="form_email" v-validate="validators.email">
                                <div class="text-danger" v-show="errors.has('email')">{{ errors.first('email') }}</div>
                            </div>
                            <div class="field field-four">
                                <label>Número de celular</label><br>
                                <input name="phone" type="text" placeholder="(444) 345 0981" v-model="form.phone" ref="form_phone" v-validate="validators.phone" data-last="true">
                                <div class="text-danger" v-show="errors.has('phone')">{{ errors.first('phone') }}</div>
                            </div>
                        </div>
                        <div class="talking-us" @click="send">
                            {{ 'Platiquemos' }}
                        </div>
                    </div>
                    <div class="apithy-contact-form landscape" v-show="isLandscape">
                        <div class="contact-form swiper-container">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <div class="field field-one">
                                        <label>Nombre completo*</label><br>
                                        <input name="name" type="text" v-model="form.name" ref="form_name" v-validate="validators.name">
                                        <span class="next-btn">
                                            <i class="fas fa-arrow-circle-right" @click="nextField" data-field="one" data-x="100"></i>
                                        </span>
                                        <div class="text-danger" v-show="errors.has('name')">{{ errors.first('name') }}</div>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="field field-two">
                                        <label>Nombre de tu empresa*</label><br>
                                        <input name="company" type="text" v-model="form.company" ref="form_company" v-validate="validators.company">
                                        <span class="next-btn">
                                        <i class="fas fa-arrow-circle-right" @click="nextField" data-field="two" data-x="200"></i>
                                    </span>
                                        <div class="text-danger" v-show="errors.has('company')">{{ errors.first('company') }}</div>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="field field-three">
                                        <label>Correo electrónico*</label><br>
                                        <input name="email" type="email" v-model="form.email" ref="form_email" v-validate="validators.email">
                                        <span class="next-btn">
                                        <i class="fas fa-arrow-circle-right" @click="nextField" data-field="three" data-x="300"></i>
                                    </span>
                                        <div class="text-danger" v-show="errors.has('email')">{{ errors.first('email') }}</div>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="field field-four">
                                        <label>Número de celular</label><br>
                                        <input name="phone" type="text" placeholder="(444) 345 0981" v-model="form.phone" ref="form_phone" v-validate="validators.phone" data-last="true">
                                        <span class="next-btn">
                                        <i class="fas fa-arrow-circle-right" @click="nextField" data-field="four" data-x="400"></i>
                                    </span>
                                        <div class="text-danger" v-show="errors.has('phone')">{{ errors.first('phone') }}</div>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="talking-us" @click="send">
                                        {{ 'Platiquemos' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="we-contact-u" v-show="!showForm">
                        <img src="/images/landing/plant2.png" alt="" height="100">
                        <p>En breve nos <br>comunicaremos contigo</p>
                    </div>
                </section>
                <section class="section" id="five" v-show="isLandscape">
                    <div class="footer-logo">
                        <img src="/apihty_logo_blanco.png" alt="" height="60">
                    </div>
                    <div class="footer-social-network">
                        <div class="row ml-0 mr-0 justify-content-center align-items-center">
                            <template v-for="network in socialsNetwork">
                                <div class="col-auto">
                                    <div class="network">
                                        <a target="_blank" :href="network.link">
                                            <i :class="network.iconClass"></i>
                                        </a>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                    <div class="footer-privacy-notification">
                        <p>
                            <a target="_blank" href="/aviso-de-privacidad">Aviso de privacidad</a>
                        </p>
                    </div>
                    <div class="footer-copyright">
                        <p>
                            Apithy® CDMX 2019 <br>TODOS LOS DERECHOS RESERVADOS <br>Tabasco, CDMX, Querétaro
                        </p>
                    </div>
                </section>

                <!-- video -->
                <div class="video-container position-relative">
                    <div id="video-popup" class="white-popup mfp-hide">
                        <video id='video-apithy' class='video-js' controls preload='auto' width='800' height='600'
                               poster='MY_VIDEO_POSTER.jpg' data-setup='{}'>
                            <source src='https://s3-us-west-2.amazonaws.com/cdn.apithy.com/video-contents/Flyer_5.mp4' type='video/mp4'>
                            <p class='vjs-no-js'>
                                To view this video please enable JavaScript, and consider upgrading to a web browser that
                                <a href='https://videojs.com/html5-video-support/' target='_blank'>supports HTML5 video</a>
                            </p>
                        </video>
                    </div>
                </div>
            </div>
            </main>
        </Slideout>
    </div>
</template>

<script>
    import HomePageNavbar from '../HomePageNavbar.vue'
    import VeeValidate from 'vee-validate'
    import es from 'vee-validate/dist/locale/es'
    import { email } from 'vee-validate/dist/rules.esm'
    import Swiper from 'swiper'
    import Slideout from 'vue-slideout'

    export default {
        name: "mobile",
        props: {
            source: {
                type: String,
                required: true
            }
        },
        components: {
            'apithy-homepage-navbar': HomePageNavbar,
            Slideout
        },
        mounted () {
            this.doOnOrientationChange()
            window.addEventListener('orientationchange', e => {
                this.doOnOrientationChange(window.orientation)
            }, true)
            this.initValidations()
            this.initCarousels()
            this.initFormulary()
        },
        computed: {
            isPortrait () {
                return this.orientation === 'portrait' && this.showForm
            },
            isLandscape () {
                return this.orientation === 'landscape' && this.showForm
            }
        },
        methods: {
            goToContact () {
                let el = this.$refs.slideout.slideout
                el.close()
                window.location.href = '/#four'
            },
            doOnOrientationChange (orientation = null) {
                orientation = (orientation !== null) ? orientation : window.orientation
                switch(orientation) {
                    case -90:
                    case 90:
                        //alert('landscape');
                        this.orientation = 'landscape'
                        break;
                    default:
                        //alert('portrait');
                        this.orientation = 'portrait'
                        break;
                }
            },
            initValidations () {
                const dictionary = {
                    es: {
                        messages: {
                            company_phone: () => !this.badPhone ? `El número de teléfono sólo debe contener 10 dígitos.` : `El número de teléfono debe contener solo dígitos`,
                        },
                        attributes: {
                            name: 'nombre',
                            email: 'correo electrónico',
                            phone: 'teléfono',
                            company: 'nombre de la empresa',
                        },
                        custom: {
                            name: {
                                required: 'El nombre es obligatorio.',
                                alpha_spaces: 'El nombre sólo puede contener letras y espacios.',
                                min: 'La longitud mínima del nombre es de 2 caracteres.',
                                max: 'La longitud máxima del nombre es de 50 caracteres.',
                            },
                            email: {
                                required: 'La dirección de correo electrónico es obligatoria.',
                                email: 'No es una dirección de correo electrónico válida.',
                                regex: 'No es una dirección de correo electrónico válida.'
                            },
                            phone: {
                                required: 'El número de teléfono es obligatorio.',
                                digits: 'El número de teléfono sólo debe contener 10 dígitos.',
                                numeric: 'El número de teléfono sólo debe contener números.',
                            },
                            company: {
                                required: 'El nombre de la empresa es obligatorio.',
                                max: 'La longitud máxima del nombre de la empresa son 25 caracteres.'
                            },
                        }
                    },
                    en: {
                        messages: {
                            company_phone: () => !this.badPhone ? `The phone number must only contain 10 digits.` : `The phone number must only digits.`,
                        }
                    }
                };

                VeeValidate.Validator.localize(dictionary);
                VeeValidate.Validator.localize('es', es);
            },
            initCarousels () {
                let carousels = document.querySelectorAll('.apithy-features.swiper-container')
                _.each(carousels, carousel => {
                    new Swiper(carousel, this.swiper.options)
                })
            },
            initFormulary () {
                let formulary = new Swiper('section#four .apithy-contact-form.landscape .swiper-container', this.swiper.formOptions)
                this.swiper.formReference = formulary
            },
            nextField (e) {
                let t = e.target
                let target = '.field-'+ t.dataset.field
                let el = document.querySelector('section#four '+target+' input')
                this.$validator.validate(el.name).then((r) => {
                    let formulary = document.querySelector('section#four .apithy-contact-form.landscape .swiper-container .swiper-wrapper')
                    if (!this.errors.any()) {
                        formulary.style.transform = 'translateX(-'+t.dataset.x+'vw)'
                    }
                })
            },
            send () {
                this.$validator.validateAll().then(result => {
                    if (result) {
                        axios({
                            method: 'POST',
                            url: '/contacts',
                            data: this.form
                        })
                            .then(response => {
                                this.showForm = false
                            })
                            .catch(error => { console.error(error) })
                    }
                });
            }
        },
        data () {
            return {
                orientation: 'portrait',
                showForm: true,
                swiper: {
                    reference: null,
                    formReference: null,
                    options: {
                        direction: 'horizontal',
                        loop: true,
                        autoplay: {
                            delay: 5000
                        },
                        on: {
                            slideChange: function () {
                                let parent = this.el.parentElement
                                let current = this.realIndex
                                _.each(parent.querySelectorAll('.apithy-features-dots .dot'), (item, index) => {
                                    item.classList.remove('active')
                                    if (index === current) {
                                        item.classList.add('active')
                                    }
                                })
                            }
                        }
                    },
                    formOptions: {
                        direction: 'horizontal',
                        loop: false,
                        allowSlidePrev: false,
                        allowSlideNext: false,
                        on: {
                            init: function () {
                                let swiper = this
                                if (swiper.originalParams.loop && swiper.loopedSlides < swiper.originalParams.slidesPerView) {
                                    swiper.params.slidesPerView = swiper.loopedSlides;
                                    swiper.destroy(false, false);
                                    swiper.init();
                                }
                            }
                        }
                    }
                },
                socialsNetwork: [
                    {iconClass: 'fab fa-facebook-f', link: 'https://www.facebook.com/apithylearning/'},
                    {iconClass: 'fab fa-instagram', link: 'https://www.instagram.com/apithylearning/'},
                ],
                form: {
                    name: null,
                    company: null,
                    email: null,
                    phone: null,
                    number_employees: null
                },
                validators: {
                    name: {
                        required: true,
                        alpha_spaces: true,
                        min: 2,
                        max: 50
                    },
                    company: {
                        required: true,
                        alpha_spaces: true,
                        min: 2,
                        max: 50
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    phone: {
                        required: false,
                        digits: 10,
                        numeric: true,
                    },
                    employees: {
                        required: true,
                    },
                }
            }
        }
    }
</script>

<style scoped>
    /**-- Default styles --**/
    section.section {
        height: 100vh;
    }
    section.section .apithy-features-dots .dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: rgba(127.5, 127.5, 127.5, 0.5);
    }
    section.section .apithy-features-dots .dot.active {
        background-color: rgba(127.5, 127.5, 127.5, 1);
    }
    section .swiper-container .swiper-wrapper {
        display: inline-flex;
    }
    section.section .apithy-request-demo a {
        text-decoration: none;
        color: unset;
    }
    section.section .card .card-body p {
        color: #444444;
    }

    /* section_one */
    @media only screen {
        section#one {
            background-image: url("/images/landing/bg-one1.png");
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
        }
        section#one .apithy-logo img {
            height: 45%;
        }
        section#one .apithy-slogan {
            color: #FFFFFF;
        }
        section#one .apithy-slogan-two {
            color: #FFFFFF;
        }
        section#one .apithy-request-demo .btn-request {
            width: 300px;
            height: 60px;
            padding: 12px 0;
            color: #444;
            text-align: center;
            font-weight: bold;
            background-color: #FEE857;
            border-radius: 38px;
            cursor: pointer;
        }
        section#one .apithy-video {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            top: 55vh;
        }
        section#one .apithy-video a {
            text-decoration: none;
            color: #FFFFFF;
        }
        section#one .apithy-video .btn-video {
            width: 300px;
            height: 60px;
            padding: 12px 0;
            color: #444444;
            text-align: center;
            font-weight: bold;
            background-color: #FEE857;
            border-radius: 38px;
            cursor: pointer;
        }
        section#one .apithy-video .btn-video .icon-play {
            color: #444444;
        }
    }

    /* section_two */
    @media only screen {
        section#two .apithy-slogan {
            color: #444444;
            font-weight: bold;
        }
        section#two .section-static-with-background {
            position: relative;
            background-size: auto;
            background-position: right;
            background-repeat: no-repeat;
            background-image: url("/images/landing/image1-2.png");
        }
        section#two .apithy-slogan-two {
            position: absolute;
            right: 0;
            top: 50%;
            max-width: 60%;
            color: #ffffff;
            font-weight: bold;
            transform: translateY(-50%);
        }
        section#two .swiper-container {
            width: 100vw;
            height: 150px;
        }
        section#two .apithy-features .feature {
            width: 90%;
            margin: 0 auto;
            border: transparent;
            border-radius: 0;
        }
        section#two .apithy-features .feature .feature-image img {
            position: absolute;
            transform: translate(-50%, -50%);
            left: 50%;
            width: 100px;
            height: 100px;
        }
        section#two .apithy-features .feature .card-body p {
            margin: 0;
            padding: 0;
            width: 100%;
            text-align: center;
            max-height: 200px;
            overflow: hidden;
        }
        section#two .apithy-request-demo {
            margin-top: 10%;
        }
        section#two .apithy-request-demo .btn-request {
            width: 300px;
            height: 60px;
            padding: 12px 0;
            color: #ffffff;
            text-align: center;
            font-weight: bold;
            background-color: #1A6BF7;
            border-radius: 38px;
            cursor: pointer;
        }
    }

    /* section_three */
    @media only screen {
        section#three .apithy-slogan {
            color: #444444;
            font-weight: bold;
            text-align: center;
        }
        section#three .section-static-with-background {
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-image: url("/images/landing/image3-2.png");
        }
        section#three .swiper-container {
            width: 100vw;
            height: 210px;
        }
        section#three .apithy-features .feature {
            width: 90%;
            margin: 0 auto;
            border: transparent;
            border-radius: 0;
        }
        section#three .apithy-features .feature .feature-image img {
            left: 50%;
            width: 80px;
            height: 80px;
        }
        section#three .apithy-features .feature .card-body {
            margin-top: 0;
        }
        section#three .apithy-features .feature .card-body p {
            margin: 0;
            padding: 0;
            width: 100%;
            text-align: center;
            max-height: 200px;
            overflow: hidden;
        }
        section#three .apithy-request-demo {
            margin-top: 10%;
        }
        section#three .apithy-request-demo .btn-request {
            width: 300px;
            height: 60px;
            padding: 12px 0;
            color: #ffffff;
            text-align: center;
            font-weight: bold;
            background-color: #1A6BF7;
            border-radius: 38px;
            cursor: pointer;
        }
    }

    /* section_four */
    @media only screen {
        section#four {
            background: linear-gradient(175.69deg, #0072E4 4.22%, #0050AD 79.56%);
        }
        section#four .decoration .rectangle-one {
            position: absolute;
            width: 603.50px;
            height: 900px;
            left: -5%;
            background: linear-gradient(180.84deg, rgba(43, 149, 255, 0.3) -13.01%, rgba(43, 149, 255, 0) 67.73%);
            border-radius: 67px;
            transform: rotate(10deg);
        }
        section#four .apithy-slogan {
            width: 100%;
            color: #FFFFFF;
        }
        section#four .apithy-contact-form.portrait .contact-form {
            width: 90vw;
            margin-left: auto;
            margin-right: auto;
        }
        section#four .apithy-contact-form.landscape .contact-form .swiper-wrapper {
            transition-duration: 300ms !important;
        }
        section#four .apithy-contact-form.landscape .contact-form .swiper-slide {
            width: 100vw;
        }
        section#four .apithy-contact-form .contact-form label {
            margin-top: 16px;
            margin-bottom: 0;
            color: #FFFFFF;
            font-size: 16px;
            font-weight: 600;
        }
        section#four .apithy-contact-form .contact-form input {
            border-radius: 5px !important;
            background-color: #FEFEFE;
            color: #0f0f10 !important;
        }
        section#four .apithy-contact-form .talking-us {
            padding: 12px 0;
            width: 300px;
            height: 60px;
            font-weight: bold;
            text-align: center;
            color: #444444;
            background-color: #FEE857;
            border-radius: 38px;
            border: none;
            cursor: pointer;
        }
        section#four .we-contact-u {
            display: flex;
            margin-left: auto;
            margin-right: auto;
            margin-top: 33vh;
            padding-top: 25px;
            padding-left: 25px;
            padding-right: 25px;
            width: fit-content;
            width: -moz-fit-content;
            align-items: center;
            font-weight: bold;
            color: #fff;
        }
        section#four .footer {
            position: relative;
        }
        section#four .footer .footer-container {
            position: absolute;
            width: 100%;
            color: #FFFFFF;
            font-weight: bold;
            background-image: url("/images/landing/bg-four-bottom.png");
            background-repeat: no-repeat;
            background-position: top;
            background-size: cover;
        }
        section#four .footer .footer-container .footer-text a {
            color: unset;
            text-decoration: none;
        }
    }

    /* section_five */
    @media only screen {
        section#five {
            background: linear-gradient(0deg, #0072E4 4.22%, #0050AD 79.56%);
            position: relative;
        }
        section#five .footer-logo {
            margin-left: auto;
            margin-right: auto;
            width: fit-content;
            height: 80px;
        }
        section#five .footer-logo img {
            height: 100%;
        }
        section#five .footer-social-network {
            color: #ffffff;
        }
        section#five .footer-privacy-notification {
            color: #ffffff;
        }
        section#five .footer-privacy-notification a {
            color: unset;
            text-decoration: none;
        }
        section#five .footer-copyright {
            color: #ffffff;
            font-weight: bold;
            background-image: url("/images/landing/bg-four-bottom.png");
            background-repeat: no-repeat;
            background-position: top;
            background-size: cover;
        }
    }

    /* Devices with 375px or less landscape */
    @media only screen
    and (max-device-width: 680px)
    and (max-device-height: 375px)
    and (orientation: landscape) {
        section#one {
            background-image: url("/images/landing/bg-one.png");
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
        }
        section#two .section-static-with-background {
            background-size: auto;
            background-position: bottom;
        }
        section#one .apithy-logo {
            margin-top: 10px;
            height: 90px;
        }
        section#one .apithy-slogan {
            display: none;
        }
        section#one .apithy-slogan-two {
            margin-top: 0;
            font-size: 16px;
            height: auto;
        }
        section#one .apithy-slogan-two p {
            padding: 0 150px;
        }
        section#one .apithy-video {
            margin-top: 50px;
            width: 100%;
        }
        section#one .apithy-video .btn-video {
            margin-left: auto;
            margin-right: auto;
            padding: 8px 0;
            font-size: 16px;
            width: 200px;
            height: 40px;
        }
        section#one .apithy-video .btn-video .icon-play {
            position: relative;
            left: 40px;
            color: #444444;
        }
        section#one .apithy-request-demo {
            margin-top: 0;
            width: 50%;
        }
        section#one .apithy-request-demo .btn-request {
            margin-left: 15px;
            margin-right: auto;
            padding: 8px 0;
            font-size: 16px;
            width: 200px;
            height: 40px;
        }

        section#two .section-static-with-background {
            height: 100vh;
            width: 100vw;
            background-size: cover;
            background-position: right;
            background-repeat: no-repeat;
        }
        section#two .apithy-slogan {
            position: relative;
            font-size: 20px;
            color: #ffffff;
        }
        section#two .apithy-slogan p {
            position: absolute;
            top: 10vh;
            right: 10vw;
            z-index: 2;
        }
        section#two .apithy-slogan-two {
            right: 10vw;
            width: 130px;
            font-size: 16px;
            border-bottom: 1px solid #ffffff;
        }
        section#two .apithy-features {
            position: relative;
            height: 0;
        }
        section#two .apithy-features .swiper-wrapper {
            position: absolute;
            top: -65vh;
            left: -25%;
        }
        section#two .apithy-features .feature {
            width: 40%;
            font-size: 14px;
            background-color: rgba(255, 255, 255, 0.7)
        }
        section#two .apithy-features .feature .card-body {
            padding-top: 30px;
            min-height: 145px;
        }
        section#two .apithy-features-dots {
            position: relative;
        }
        section#two .apithy-features-dots > .row {
            position: absolute;
            top: -23vh;
            left: 9vw;
        }
        section#two .apithy-features-dots .dot {
            background-color: rgba(227.5, 227.5, 227.5, 0.5);
        }
        section#two .apithy-features-dots .dot.active {
            background-color: rgba(227.5, 227.5, 227.5, 1);
        }
        section#two .apithy-request-demo {
            margin-top: 0;
        }
        section#two .apithy-request-demo .btn-request {
            font-size: 16px;
            width: 200px;
            height: 40px;
            padding: 10px 0;
            position: absolute;
            transform: translate(-50%, -165%);
            left: 50%;
            margin-left: auto;
            margin-right: auto;
        }

        section#three {
            background-size: cover;
            background-position: right;
            background-repeat: no-repeat;
            background-image: url('/images/landing/image3-2.png')
        }
        section#three .section-static-without-background {
            height: 75px;
        }
        section#three .apithy-slogan {
            padding-left: 40px;
            padding-top: 40px;
            font-size: 20px;
            color: #ffffff;
        }
        section#three .apithy-slogan p {
            text-align: center;
        }
        section#three .apithy-slogan p br {
            display: none;
        }
        section#three .apithy-features.swiper-container {
            width: 100vw;
            padding-top: 45px;
            height: 165px;
            background-color: transparent;
        }
        section#three .apithy-features img {
            text-align: left;
            margin: 0;
        }
        section#three .apithy-features .feature {
            -webkit-flex-direction: block;
            -ms-flex-direction: block;
            flex-direction: block;
            width: 70%;
            background-color: rgba(255, 255, 255, 0.7);
        }
        section#three .apithy-features .feature .feature-image {
            width: 100%;
            text-align: center;
        }
        section#three .apithy-features .feature .feature-image img {
            margin-left: auto;
            margin-right: auto;
            position: absolute;
            transform: translate(-50%, -65%);
            left: 50%;
            width: 70px;
            height: 70px;
        }
        section#three .apithy-features .feature .card-body {
            width: 100%;
            margin-top: unset;
            min-height: 112px;
        }
        section#three .apithy-features-dots .dot {
            background-color: rgba(255, 255, 255, 0.5);
        }
        section#three .apithy-features-dots .dot.active {
            background-color: rgba(255, 255, 255, 1);
        }
        section#three .apithy-request-demo {
            margin-top: 30px;
        }
        section#three .apithy-request-demo .btn-request {
            margin-left: auto;
            margin-right: auto;
            width: 200px;
            height: 40px;
            padding: 10px 0;
            font-size: 16px;
        }

        section#four .apithy-slogan {
            padding-top: 25px;
            text-align: center;
            color: #FFFFFF;
        }
        section#four .apithy-slogan p {
            width: 80%;
            margin-left: auto;
            margin-right: auto;
            border-bottom: 1px solid #ffffff;
        }
        section#four .apithy-contact-form.landscape {
            margin-top: 20vh;
        }
        section#four .apithy-contact-form.landscape .contact-form .field {
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }
        section#four .apithy-contact-form.landscape .contact-form input {
            width: 75%;
            height: 30px;
            font-size: 14px;
        }
        section#four .apithy-contact-form.landscape .contact-form .next-btn {
            margin-left: 50px;
            font-size: 25px;
            color: #fff;
        }
        section#four .apithy-contact-form.landscape .contact-form .text-danger {
            position: absolute;
            font-size: 12px;
        }
        section#four .apithy-contact-form.landscape .talking-us {
            margin-left: auto;
            margin-right: auto;
            margin-top: 20px;
            font-size: 18px;
            height: 50px;
        }
        section#four .we-contact-u {
            width: fit-content;
            display: flex;
            margin-left: auto;
            margin-right: auto;
            padding-top: 25px;
            padding-left: 25px;
            padding-right: 25px;
            align-items: center;
            font-weight: bold;
            color: #fff;
        }
        section#four .footer .footer-container {
            height: 100px;
            top: 85vh;
        }
        section#four .footer .footer-text {
            margin-top: 22px;
            font-size: 12px;
        }

        section#five .footer-logo {
            padding-top: 16px;
            height: 100px;
        }
        section#five .footer-social-network {
            margin-top: 16px;
            color: #ffffff;
        }
        section#five .footer-social-network .network {
            padding: 12px;
            width: 50px;
            height: 50px;
            color: #0050AD;
            cursor: pointer;
            font-size: 26px;
            border-radius: 50%;
            text-align: center;
            background-color: #ffffff;
        }
        section#five .footer-social-network a {
            text-decoration: none;
            color: unset;
        }
        section#five .footer-social-network i {
            text-decoration: none;
            color: unset;
        }
        section#five .footer-privacy-notification {
            margin-top: 25px;
            font-weight: bold;
        }
        section#five .footer-privacy-notification p {
            margin-left: auto;
            margin-right: auto;
            width: fit-content;
        }
        section#five .footer-privacy-notification a {
            color: unset;
            text-decoration: none;
        }
        section#five .footer-copyright {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 110px;
            padding-top: 25px;
            text-align: center
        }
    }

    /* Devices between 680px and 900px landscape*/
    @media only screen
    and (min-device-width: 680px)
    and (max-device-width: 900px)
    and (min-device-height: 375px)
    and (max-device-height: 480px)
    and (orientation: landscape) {
        section#one {
            background-image: url("/images/landing/bg-one.png");
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
        }
        section#two .section-static-with-background {
            background-size: auto;
            background-position: bottom;
        }
        section#one .apithy-logo {
            margin-top: 10px;
            height: 90px;
        }
        section#one .apithy-slogan {
            display: none;
        }
        section#one .apithy-slogan-two {
            margin-top: 0;
            font-size: 16px;
            height: auto;
        }
        section#one .apithy-slogan-two p {
            padding: 0 150px;
        }
        section#one .apithy-video {
            margin-top: 50px;
            width: 100%;
        }
        section#one .apithy-video .btn-video {
            margin-left: auto;
            margin-right: auto;
            padding: 8px 0;
            font-size: 16px;
            width: 200px;
            height: 40px;
        }
        section#one .apithy-video .btn-video .icon-play {
            position: relative;
            left: 40px;
            color: #444444;
        }
        section#one .apithy-request-demo {
            margin-top: 0;
            width: 50%;
        }
        section#one .apithy-request-demo .btn-request {
            margin-left: 15px;
            margin-right: auto;
            padding: 8px 0;
            font-size: 16px;
            width: 200px;
            height: 40px;
        }

        section#two .section-static-with-background {
            height: 100vh;
            width: 100vw;
            background-size: cover;
            background-position: right;
            background-repeat: no-repeat;
        }
        section#two .apithy-slogan {
            position: relative;
            font-size: 20px;
            color: #ffffff;
        }
        section#two .apithy-slogan p {
            position: absolute;
            top: 10vh;
            right: 10vw;
            z-index: 2;
        }
        section#two .apithy-slogan-two {
            right: 10vw;
            width: 130px;
            font-size: 16px;
            border-bottom: 1px solid #ffffff;
        }
        section#two .apithy-features {
            position: relative;
            height: 0;
        }
        section#two .apithy-features .swiper-wrapper {
            position: absolute;
            top: -75vh;
            left: -25%;
        }
        section#two .apithy-features .feature {
            width: 40%;
            font-size: 14px;
            background-color: rgba(255, 255, 255, 0.7)
        }
        section#two .apithy-features .feature .card-body {
            padding-top: 30px;
            min-height: 145px;
        }
        section#two .apithy-features-dots {
            position: relative;
        }
        section#two .apithy-features-dots > .row {
            position: absolute;
            top: -35vh;
            left: 9vw;
        }
        section#two .apithy-features-dots .dot {
            background-color: rgba(227.5, 227.5, 227.5, 0.5);
        }
        section#two .apithy-features-dots .dot.active {
            background-color: rgba(227.5, 227.5, 227.5, 1);
        }
        section#two .apithy-request-demo {
            margin-top: 0;
        }
        section#two .apithy-request-demo .btn-request {
            font-size: 16px;
            width: 200px;
            height: 40px;
            padding: 10px 0;
            position: absolute;
            transform: translate(-50%, -25vh);
            left: 50%;
            margin-left: auto;
            margin-right: auto;
        }

        section#three {
            background-size: cover;
            background-position: right;
            background-repeat: no-repeat;
            background-image: url('/images/landing/image3-2.png')
        }
        section#three .section-static-without-background {
            height: 75px;
        }
        section#three .apithy-slogan {
            padding-left: 40px;
            padding-top: 40px;
            font-size: 20px;
            color: #ffffff;
        }
        section#three .apithy-slogan p {
            text-align: center;
        }
        section#three .apithy-slogan p br {
            display: none;
        }
        section#three .apithy-features.swiper-container {
            width: 100vw;
            padding-top: 45px;
            height: 165px;
            background-color: transparent;
        }
        section#three .apithy-features img {
            text-align: left;
            margin: 0;
        }
        section#three .apithy-features .feature {
            -webkit-flex-direction: block;
            -ms-flex-direction: block;
            flex-direction: block;
            width: 70%;
            background-color: rgba(255, 255, 255, 0.7);
        }
        section#three .apithy-features .feature .feature-image {
            width: 100%;
            text-align: center;
        }
        section#three .apithy-features .feature .feature-image img {
            margin-left: auto;
            margin-right: auto;
            position: absolute;
            transform: translate(-50%, -65%);
            left: 50%;
            width: 70px;
            height: 70px;
        }
        section#three .apithy-features .feature .card-body {
            width: 100%;
            margin-top: unset;
            min-height: 112px;
        }
        section#three .apithy-features-dots .dot {
            background-color: rgba(255, 255, 255, 0.5);
        }
        section#three .apithy-features-dots .dot.active {
            background-color: rgba(255, 255, 255, 1);
        }
        section#three .apithy-request-demo {
            margin-top: 30px;
        }
        section#three .apithy-request-demo .btn-request {
            margin-left: auto;
            margin-right: auto;
            width: 200px;
            height: 40px;
            padding: 10px 0;
            font-size: 16px;
        }

        section#four .apithy-slogan {
            padding-top: 25px;
            text-align: center;
            color: #FFFFFF;
        }
        section#four .apithy-slogan p {
            width: 80%;
            margin-left: auto;
            margin-right: auto;
            border-bottom: 1px solid #ffffff;
        }
        section#four .apithy-contact-form.landscape {
            margin-top: 24vh;
        }
        section#four .apithy-contact-form.landscape .contact-form .field {
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }
        section#four .apithy-contact-form.landscape .contact-form input {
            width: 75%;
            height: 30px;
            font-size: 14px;
        }
        section#four .apithy-contact-form.landscape .contact-form .next-btn {
            margin-left: 50px;
            font-size: 25px;
            color: #fff;
        }
        section#four .apithy-contact-form.landscape .contact-form .text-danger {
            position: absolute;
            font-size: 12px;
        }
        section#four .apithy-contact-form.landscape .talking-us {
            margin-left: auto;
            margin-right: auto;
            margin-top: 20px;
            font-size: 18px;
            height: 50px;
        }
        section#four .we-contact-u {
            width: fit-content;
            display: flex;
            margin-left: auto;
            margin-right: auto;
            padding-top: 25px;
            padding-left: 25px;
            padding-right: 25px;
            align-items: center;
            font-weight: bold;
            color: #fff;
        }
        section#four .footer .footer-container {
            height: 100px;
            top: 85vh;
        }
        section#four .footer .footer-text {
            margin-top: 22px;
            font-size: 12px;
        }

        section#five .footer-logo {
            padding-top: 16px;
            height: 100px;
        }
        section#five .footer-social-network {
            margin-top: 16px;
            color: #ffffff;
        }
        section#five .footer-social-network .network {
            padding: 12px;
            width: 50px;
            height: 50px;
            color: #0050AD;
            cursor: pointer;
            font-size: 26px;
            border-radius: 50%;
            text-align: center;
            background-color: #ffffff;
        }
        section#five .footer-social-network a {
            text-decoration: none;
            color: unset;
        }
        section#five .footer-social-network i {
            text-decoration: none;
            color: unset;
        }
        section#five .footer-privacy-notification {
            margin-top: 25px;
            font-weight: bold;
        }
        section#five .footer-privacy-notification p {
            margin-left: auto;
            margin-right: auto;
            width: fit-content;
        }
        section#five .footer-privacy-notification a {
            color: unset;
            text-decoration: none;
        }
        section#five .footer-copyright {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 110px;
            padding-top: 25px;
            text-align: center
        }
    }

    /* Devices iphone xs xr landscape*/
    @media only screen
    and (min-device-width : 375px)
    and (max-device-width : 812px)
    and (-webkit-device-pixel-ratio : 3)
    and (orientation : landscape) {
        section#one {
            background-image: url("/images/landing/bg-one.png");
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
        }
        section#two .section-static-with-background {
            background-size: auto;
            background-position: bottom;
        }
        section#one .apithy-logo {
            margin-top: 10px;
            height: 90px;
        }
        section#one .apithy-slogan {
            display: none;
        }
        section#one .apithy-slogan-two {
            margin-top: 0;
            font-size: 16px;
            height: auto;
        }
        section#one .apithy-slogan-two p {
            padding: 0 150px;
        }
        section#one .apithy-video {
            margin-top: 50px;
            width: 100%;
        }
        section#one .apithy-video .btn-video {
            margin-left: auto;
            margin-right: auto;
            padding: 8px 0;
            font-size: 16px;
            width: 200px;
            height: 40px;
        }
        section#one .apithy-video .btn-video .icon-play {
            position: relative;
            left: 40px;
            color: #444444;
        }
        section#one .apithy-request-demo {
            margin-top: 0;
            width: 50%;
        }
        section#one .apithy-request-demo .btn-request {
            margin-left: 15px;
            margin-right: auto;
            padding: 8px 0;
            font-size: 16px;
            width: 200px;
            height: 40px;
        }

        section#two .section-static-with-background {
            height: 100vh;
            width: 100vw;
            background-size: cover;
            background-position: right;
            background-repeat: no-repeat;
        }
        section#two .apithy-slogan {
            position: relative;
            font-size: 20px;
            color: #ffffff;
        }
        section#two .apithy-slogan p {
            position: absolute;
            top: 10vh;
            right: 10vw;
            z-index: 2;
        }
        section#two .apithy-slogan-two {
            right: 10vw;
            width: 130px;
            font-size: 16px;
            border-bottom: 1px solid #ffffff;
        }
        section#two .apithy-features {
            position: relative;
            height: 0;
        }
        section#two .apithy-features .swiper-wrapper {
            position: absolute;
            top: -75vh;
            left: -25%;
        }
        section#two .apithy-features .feature {
            width: 40%;
            font-size: 14px;
            background-color: rgba(255, 255, 255, 0.7)
        }
        section#two .apithy-features .feature .card-body {
            padding-top: 30px;
            min-height: 145px;
        }
        section#two .apithy-features-dots {
            position: relative;
        }
        section#two .apithy-features-dots > .row {
            position: absolute;
            top: -35vh;
            left: 9vw;
        }
        section#two .apithy-features-dots .dot {
            background-color: rgba(227.5, 227.5, 227.5, 0.5);
        }
        section#two .apithy-features-dots .dot.active {
            background-color: rgba(227.5, 227.5, 227.5, 1);
        }
        section#two .apithy-request-demo {
            margin-top: 0;
        }
        section#two .apithy-request-demo .btn-request {
            font-size: 16px;
            width: 200px;
            height: 40px;
            padding: 10px 0;
            position: absolute;
            transform: translate(-50%, -25vh);
            left: 50%;
            margin-left: auto;
            margin-right: auto;
        }

        section#three {
            background-size: cover;
            background-position: right;
            background-repeat: no-repeat;
            background-image: url('/images/landing/image3-2.png')
        }
        section#three .section-static-without-background {
            height: 75px;
        }
        section#three .apithy-slogan {
            padding-left: 40px;
            padding-top: 40px;
            font-size: 20px;
            color: #ffffff;
        }
        section#three .apithy-slogan p {
            text-align: center;
        }
        section#three .apithy-slogan p br {
            display: none;
        }
        section#three .apithy-features.swiper-container {
            width: 100vw;
            padding-top: 45px;
            height: 165px;
            background-color: transparent;
        }
        section#three .apithy-features img {
            text-align: left;
            margin: 0;
        }
        section#three .apithy-features .feature {
            -webkit-flex-direction: block;
            -ms-flex-direction: block;
            flex-direction: block;
            width: 70%;
            background-color: rgba(255, 255, 255, 0.7);
        }
        section#three .apithy-features .feature .feature-image {
            width: 100%;
            text-align: center;
        }
        section#three .apithy-features .feature .feature-image img {
            margin-left: auto;
            margin-right: auto;
            position: absolute;
            transform: translate(-50%, -65%);
            left: 50%;
            width: 70px;
            height: 70px;
        }
        section#three .apithy-features .feature .card-body {
            width: 100%;
            margin-top: unset;
            min-height: 112px;
        }
        section#three .apithy-features-dots .dot {
            background-color: rgba(255, 255, 255, 0.5);
        }
        section#three .apithy-features-dots .dot.active {
            background-color: rgba(255, 255, 255, 1);
        }
        section#three .apithy-request-demo {
            margin-top: 30px;
        }
        section#three .apithy-request-demo .btn-request {
            margin-left: auto;
            margin-right: auto;
            width: 200px;
            height: 40px;
            padding: 10px 0;
            font-size: 16px;
        }

        section#four .apithy-slogan {
            padding-top: 25px;
            text-align: center;
            color: #FFFFFF;
        }
        section#four .apithy-slogan p {
            width: 80%;
            margin-left: auto;
            margin-right: auto;
            border-bottom: 1px solid #ffffff;
        }
        section#four .apithy-contact-form.landscape {
            margin-top: 24vh;
        }
        section#four .apithy-contact-form.landscape .contact-form .field {
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }
        section#four .apithy-contact-form.landscape .contact-form input {
            width: 75%;
            height: 30px;
            font-size: 14px;
        }
        section#four .apithy-contact-form.landscape .contact-form .next-btn {
            margin-left: 50px;
            font-size: 25px;
            color: #fff;
        }
        section#four .apithy-contact-form.landscape .contact-form .text-danger {
            position: absolute;
            font-size: 12px;
        }
        section#four .apithy-contact-form.landscape .talking-us {
            margin-left: auto;
            margin-right: auto;
            margin-top: 20px;
            font-size: 18px;
            height: 50px;
        }
        section#four .we-contact-u {
            width: fit-content;
            display: flex;
            margin-left: auto;
            margin-right: auto;
            padding-top: 25px;
            padding-left: 25px;
            padding-right: 25px;
            align-items: center;
            font-weight: bold;
            color: #fff;
        }
        section#four .footer .footer-container {
            height: 100px;
            top: 85vh;
        }
        section#four .footer .footer-text {
            margin-top: 22px;
            font-size: 12px;
        }

        section#five .footer-logo {
            padding-top: 16px;
            height: 100px;
        }
        section#five .footer-social-network {
            margin-top: 16px;
            color: #ffffff;
        }
        section#five .footer-social-network .network {
            padding: 12px;
            width: 50px;
            height: 50px;
            color: #0050AD;
            cursor: pointer;
            font-size: 26px;
            border-radius: 50%;
            text-align: center;
            background-color: #ffffff;
        }
        section#five .footer-social-network a {
            text-decoration: none;
            color: unset;
        }
        section#five .footer-social-network i {
            text-decoration: none;
            color: unset;
        }
        section#five .footer-privacy-notification {
            margin-top: 25px;
            font-weight: bold;
        }
        section#five .footer-privacy-notification p {
            margin-left: auto;
            margin-right: auto;
            width: fit-content;
        }
        section#five .footer-privacy-notification a {
            color: unset;
            text-decoration: none;
        }
        section#five .footer-copyright {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 110px;
            padding-top: 25px;
            text-align: center
        }
    }

    /* Devices iphone 8 landscape*/
    @media only screen
    and (min-device-width : 375px)
    and (max-device-width : 812px)
    and (-webkit-device-pixel-ratio : 2)
    and (orientation : landscape) {
        section#one {
            background-image: url("/images/landing/bg-one.png");
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
        }
        section#two .section-static-with-background {
            background-size: auto;
            background-position: bottom;
        }
        section#one .apithy-logo {
            margin-top: 10px;
            height: 90px;
        }
        section#one .apithy-slogan {
            display: none;
        }
        section#one .apithy-slogan-two {
            margin-top: 0;
            font-size: 16px;
            height: auto;
        }
        section#one .apithy-slogan-two p {
            padding: 0 150px;
        }
        section#one .apithy-video {
            margin-top: 50px;
            width: 100%;
        }
        section#one .apithy-video .btn-video {
            margin-left: auto;
            margin-right: auto;
            padding: 8px 0;
            font-size: 16px;
            width: 200px;
            height: 40px;
        }
        section#one .apithy-video .btn-video .icon-play {
            position: relative;
            left: 40px;
            color: #444444;
        }
        section#one .apithy-request-demo {
            margin-top: 0;
            width: 50%;
        }
        section#one .apithy-request-demo .btn-request {
            margin-left: 15px;
            margin-right: auto;
            padding: 8px 0;
            font-size: 16px;
            width: 200px;
            height: 40px;
        }

        section#two .section-static-with-background {
            height: 100vh;
            width: 100vw;
            background-size: cover;
            background-position: right;
            background-repeat: no-repeat;
        }
        section#two .apithy-slogan {
            position: relative;
            font-size: 20px;
            color: #ffffff;
        }
        section#two .apithy-slogan p {
            position: absolute;
            top: 10vh;
            right: 10vw;
            z-index: 2;
        }
        section#two .apithy-slogan-two {
            right: 10vw;
            width: 130px;
            font-size: 16px;
            border-bottom: 1px solid #ffffff;
        }
        section#two .apithy-features {
            position: relative;
            height: 0;
        }
        section#two .apithy-features .swiper-wrapper {
            position: absolute;
            top: -75vh;
            left: -25%;
        }
        section#two .apithy-features .feature {
            width: 40%;
            font-size: 14px;
            background-color: rgba(255, 255, 255, 0.7)
        }
        section#two .apithy-features .feature .card-body {
            padding-top: 30px;
            min-height: 145px;
        }
        section#two .apithy-features-dots {
            position: relative;
        }
        section#two .apithy-features-dots > .row {
            position: absolute;
            top: -35vh;
            left: 9vw;
        }
        section#two .apithy-features-dots .dot {
            background-color: rgba(227.5, 227.5, 227.5, 0.5);
        }
        section#two .apithy-features-dots .dot.active {
            background-color: rgba(227.5, 227.5, 227.5, 1);
        }
        section#two .apithy-request-demo {
            margin-top: 0;
        }
        section#two .apithy-request-demo .btn-request {
            font-size: 16px;
            width: 200px;
            height: 40px;
            padding: 10px 0;
            position: absolute;
            transform: translate(-50%, -25vh);
            left: 50%;
            margin-left: auto;
            margin-right: auto;
        }

        section#three {
            background-size: cover;
            background-position: right;
            background-repeat: no-repeat;
            background-image: url('/images/landing/image3-2.png')
        }
        section#three .section-static-without-background {
            height: 75px;
        }
        section#three .apithy-slogan {
            padding-left: 40px;
            padding-top: 40px;
            font-size: 20px;
            color: #ffffff;
        }
        section#three .apithy-slogan p {
            text-align: center;
        }
        section#three .apithy-slogan p br {
            display: none;
        }
        section#three .apithy-features.swiper-container {
            width: 100vw;
            padding-top: 45px;
            height: 165px;
            background-color: transparent;
        }
        section#three .apithy-features img {
            text-align: left;
            margin: 0;
        }
        section#three .apithy-features .feature {
            -webkit-flex-direction: block;
            -ms-flex-direction: block;
            flex-direction: block;
            width: 70%;
            background-color: rgba(255, 255, 255, 0.7);
        }
        section#three .apithy-features .feature .feature-image {
            width: 100%;
            text-align: center;
        }
        section#three .apithy-features .feature .feature-image img {
            margin-left: auto;
            margin-right: auto;
            position: absolute;
            transform: translate(-50%, -65%);
            left: 50%;
            width: 70px;
            height: 70px;
        }
        section#three .apithy-features .feature .card-body {
            width: 100%;
            margin-top: unset;
            min-height: 112px;
        }
        section#three .apithy-features-dots .dot {
            background-color: rgba(255, 255, 255, 0.5);
        }
        section#three .apithy-features-dots .dot.active {
            background-color: rgba(255, 255, 255, 1);
        }
        section#three .apithy-request-demo {
            margin-top: 30px;
        }
        section#three .apithy-request-demo .btn-request {
            margin-left: auto;
            margin-right: auto;
            width: 200px;
            height: 40px;
            padding: 10px 0;
            font-size: 16px;
        }

        section#four .apithy-slogan {
            padding-top: 25px;
            text-align: center;
            color: #FFFFFF;
        }
        section#four .apithy-slogan p {
            width: 80%;
            margin-left: auto;
            margin-right: auto;
            border-bottom: 1px solid #ffffff;
        }
        section#four .apithy-contact-form.landscape {
            margin-top: 24vh;
        }
        section#four .apithy-contact-form.landscape .contact-form .field {
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }
        section#four .apithy-contact-form.landscape .contact-form input {
            width: 75%;
            height: 30px;
            font-size: 14px;
        }
        section#four .apithy-contact-form.landscape .contact-form .next-btn {
            margin-left: 50px;
            font-size: 25px;
            color: #fff;
        }
        section#four .apithy-contact-form.landscape .contact-form .text-danger {
            position: absolute;
            font-size: 12px;
        }
        section#four .apithy-contact-form.landscape .talking-us {
            margin-left: auto;
            margin-right: auto;
            margin-top: 20px;
            font-size: 18px;
            height: 50px;
        }
        section#four .we-contact-u {
            width: fit-content;
            display: flex;
            margin-left: auto;
            margin-right: auto;
            padding-top: 25px;
            padding-left: 25px;
            padding-right: 25px;
            align-items: center;
            font-weight: bold;
            color: #fff;
        }
        section#four .footer .footer-container {
            height: 100px;
            top: 85vh;
        }
        section#four .footer .footer-text {
            margin-top: 22px;
            font-size: 12px;
        }

        section#five .footer-logo {
            padding-top: 16px;
            height: 100px;
        }
        section#five .footer-social-network {
            margin-top: 16px;
            color: #ffffff;
        }
        section#five .footer-social-network .network {
            padding: 12px;
            width: 50px;
            height: 50px;
            color: #0050AD;
            cursor: pointer;
            font-size: 26px;
            border-radius: 50%;
            text-align: center;
            background-color: #ffffff;
        }
        section#five .footer-social-network a {
            text-decoration: none;
            color: unset;
        }
        section#five .footer-social-network i {
            text-decoration: none;
            color: unset;
        }
        section#five .footer-privacy-notification {
            margin-top: 25px;
            font-weight: bold;
        }
        section#five .footer-privacy-notification p {
            margin-left: auto;
            margin-right: auto;
            width: fit-content;
        }
        section#five .footer-privacy-notification a {
            color: unset;
            text-decoration: none;
        }
        section#five .footer-copyright {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 110px;
            padding-top: 25px;
            text-align: center
        }
    }

    /* Devices between 375px and 480px */
    @media only screen
    and (min-device-width: 375px)
    and (max-device-width: 480px)
    and (min-device-height: 688px)
    and (max-device-height: 900px)
    and (orientation: portrait) {
        section#one .apithy-logo {
            margin-top: 10px;
            height: 125px;
        }
        section#one .apithy-slogan {
            display: none;
        }
        section#one .apithy-slogan-two {
            margin-top: 5%;
            font-size: 18px;
            height: auto;
        }
        section#one .apithy-slogan-two p {
            padding: 0 50px;
        }
        section#one .apithy-video {
            margin-top: 30%;
        }
        section#one .apithy-video .btn-video {
            margin-left: auto;
            margin-right: auto;
            font-size: 18px;
            height: 50px;
        }
        section#one .apithy-video .btn-video .icon-play {
            position: relative;
            left: 50px;
        }
        section#one .apithy-request-demo {
            margin-top: 10%;
        }
        section#one .apithy-request-demo .btn-request {
            margin-left: auto;
            margin-right: auto;
            font-size: 18px;
            height: 50px;
        }

        section#two .section-static-with-background {
            height: 40%;
        }
        section#two .apithy-slogan {
            font-size: 24px;
            text-align: center;
        }
        section#two .apithy-slogan p {
            padding-top: 16px;
            padding-bottom: 16px;
            margin-bottom: 0;
        }
        section#two .apithy-slogan-two {
            font-size: 20px;
            padding-right: 16px;
        }
        section#two .apithy-features .feature {
            font-size: 14px;
        }
        section#two .apithy-features .feature .feature-image {
            margin-bottom: 20px;
        }
        section#two .apithy-request-demo .btn-request {
            font-size: 18px;
            height: 50px;
            margin-left: auto;
            margin-right: auto;
        }

        section#three .section-static-with-background {
            height: 25%;
        }
        section#three .apithy-slogan {
            font-size: 20px;
        }
        section#three .apithy-slogan p {
            padding-top: 16px;
            padding-bottom: 16px;
            margin-bottom: 0;
        }
        section#three .apithy-features .feature .feature-image {
            width: 100%;
        }
        section#three .apithy-request-demo .btn-request {
            font-size: 18px;
            height: 50px;
            margin-left: auto;
            margin-right: auto;
        }

        section#four .apithy-slogan {
            padding-top: 25px;
            text-align: center;
            color: #FFFFFF;
        }
        section#four .apithy-contact-form.portrait .contact-form input {
            width: 100%;
            height: 30px;
            font-size: 14px;
        }
        section#four .apithy-contact-form.portrait .contact-form .text-danger {
            position: absolute;
            font-size: 12px;
        }
        section#four .apithy-contact-form.portrait .talking-us {
            margin-left: auto;
            margin-right: auto;
            margin-top: 20px;
            font-size: 18px;
            height: 50px;
        }
        section#four .footer .footer-container {
            height: 112px;
            top: calc(100vh - 112px);
        }
        section#four .footer .footer-text {
            margin-top: 22px;
            font-size: 12px;
        }
    }

    /* Devices with 375px or less */
    @media only screen
    and (max-device-width: 375px)
    and (orientation: portrait) {
        section#one .apithy-logo {
            margin-top: 10px;
            height: 125px;
        }
        section#one .apithy-slogan {
            display: none;
        }
        section#one .apithy-slogan-two {
            margin-top: 5%;
            font-size: 18px;
            height: auto;
        }
        section#one .apithy-slogan-two p {
            padding: 0 50px;
        }
        section#one .apithy-video {
            margin-top: 40%;
        }
        section#one .apithy-video .btn-video {
            margin-left: auto;
            margin-right: auto;
            font-size: 18px;
            height: 50px;
        }
        section#one .apithy-video .btn-video .icon-play {
            position: relative;
            left: 50px;
        }
        section#one .apithy-request-demo {
            margin-top: 10%;
        }
        section#one .apithy-request-demo .btn-request {
            margin-left: auto;
            margin-right: auto;
            font-size: 18px;
            height: 50px;
        }

        section#two .section-static-with-background {
            height: 30%;
        }
        section#two .apithy-slogan {
            font-size: 20px;
            text-align: center;
        }
        section#two .apithy-slogan p {
            padding-top: 16px;
            padding-bottom: 16px;
            margin-bottom: 0;
        }
        section#two .apithy-slogan-two {
            font-size: 16px;
            padding-right: 16px;
        }
        section#two .apithy-features .feature {
            font-size: 14px;
        }
        section#two .apithy-features .feature .feature-image {
            margin-bottom: 20px;
        }
        section#two .apithy-features-dots {
            margin-top: 25px;
        }
        section#two .apithy-request-demo .btn-request {
            font-size: 18px;
            height: 50px;
            margin-left: auto;
            margin-right: auto;
        }

        section#three .section-static-with-background {
            height: 25%;
        }
        section#three .apithy-slogan {
            font-size: 20px;
        }
        section#three .apithy-slogan p {
            padding-top: 16px;
            padding-bottom: 16px;
            margin-bottom: 0;
        }
        section#three .apithy-features .feature {
            font-size: 14px;
        }
        section#three .apithy-features .feature .feature-image {
            width: 100%;
        }
        section#three .apithy-features-dots {
            margin-top: 25px;
        }
        section#three .apithy-request-demo .btn-request {
            font-size: 18px;
            height: 50px;
            margin-left: auto;
            margin-right: auto;
        }

        section#four .apithy-slogan {
            padding-top: 25px;
            text-align: center;
            color: #FFFFFF;
        }
        section#four .apithy-contact-form.portrait .contact-form input {
            width: 100%;
            height: 30px;
            font-size: 14px;
        }
        section#four .apithy-contact-form.portrait .contact-form .text-danger {
            position: absolute;
            font-size: 12px;
        }
        section#four .apithy-contact-form.portrait .talking-us {
            margin-left: auto;
            margin-right: auto;
            margin-top: 20px;
            font-size: 18px;
            height: 50px;
        }
        section#four .footer .footer-container {
            height: 100px;
            top: calc(100vh - 100px);
        }
        section#four .footer .footer-text {
            margin-top: 22px;
            font-size: 10px;
        }
    }

</style>
<style>
    .path-home .mfp-close {
        top: 0 !important;
        right: 0 !important;
        color: white !important;
        position: absolute !important;
    }
    .mfp-content {
        vertical-align: top;
        margin-top: 40px;
        margin-left: auto;
        margin-right: auto;
        text-align: left;
        z-index: 1045;
    }
    .video-apithy-dimensions {
        width: 100vw;
        height: 70vh;
        min-height: 270px;
        margin: 0 auto;
    }
    .mobile .fp-slides .fp-slidesContainer {
        display: flex;
    }
    section#two .apithy-features .slide .fp-tableCell {
        height: auto !important;
    }
    section#three .apithy-features .slide .fp-tableCell {
        height: auto !important;
    }
    section#four .apithy-contact-form.landscape .slide .fp-tableCell {
        height: auto !important;
    }
    section#five .fp-tableCell {
        position: relative;
    }
    @media only screen
    and (max-device-width: 680px)
    and (max-device-height: 375px)
    and (orientation: landscape) {
        section#two .fp-slides {
            position: absolute;
            transform: translate(150px, -200%);
        }
    }
    @media only screen
    and (min-device-width: 680px)
    and (max-device-width: 812px)
    and (min-device-height: 375px)
    and (max-device-height: 480px)
    and (orientation: landscape) {
        section#two .fp-slides {
            position: absolute;
            transform: translate(150px, -225%);
        }
    }

    .so-menu-header {
        padding-left: 16px;
        padding-right: 16px;
        height: 50px;
        color: #1A6BF7;
        border-bottom: 1px solid #1A6BF7;
    }
    .so-menu-header .so-close {
        font-size: 26px;
    }
    .so-body .so-item {
        height: 75px;
        color: #1A6BF7;
    }
    @media only screen and (orientation: landscape) {
        .so-body .so-item {
            height: 50px;
        }
    }
    .so-item.b-bottom {
        border-bottom: 1px solid #C4C4C4;
    }
    .so-body .so-item a {
        color: unset;
        text-decoration: none;
    }
    .so-body .contact .landing-button {
        width: 164px;
        height: 40px;
        padding: 8px;
        font-size: 16px;
        text-align: center;
        color: #1A6BF7;
        border: #1A6BF7 1px solid;
        border-radius: 20px;
        cursor: pointer;
    }
    .so-body .social-btn {
        padding: 16px;
        font-size: 32px;
        cursor: pointer;
    }
    .so-body .login {
        font-weight: bold;
        color: #1A6BF7;
        border-bottom: 1px solid #1A6BF7;
    }
    .so-body .link.button {
        width: 164px;
        height: 40px;
        padding: 8px;
        font-size: 16px;
        font-weight: bold;
        text-align: center;
        color: #444444;
        background-color: #FEE857;
        border-radius: 20px;
        cursor: pointer;
    }

    /* Slideout styles */

    .slideout-menu {
        position: fixed;
        top: 0;
        bottom: 0;
        width: 256px;
        height: 100vh;
        overflow-y: scroll;
        -webkit-overflow-scrolling: touch;
        z-index: 0;
        display: none;
        background-color: #FFFFFF;
        color: #444444;
    }

    .slideout-menu-left {
        left: 0;
    }

    .slideout-menu-right {
        right: 0;
    }

    .slideout-panel {
        background-color: transparent;
        color: white;
        position: relative;
        z-index: 1;
        will-change: transform;
        min-height: 100vh;
    }

    .slideout-open,
    .slideout-open body,
    .slideout-open .slideout-panel {
        overflow: hidden;
    }

    .slideout-open .slideout-menu {
        display: block;
    }
</style>