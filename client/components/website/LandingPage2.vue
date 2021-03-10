<template>
    <div class="">
        <div class="desktop" id="fullpage">
            <div class="bg-two-three">
                <img src="/images/landing/bg-two-three.png" alt="">
            </div>
            <section class="full-screen" id="one">
                <div class="content">
                    <apithy-landingpage-navbar :host="source"></apithy-landingpage-navbar>
                    <div class="row mr-0 ml-0 no-gutters justify-content-center" style="margin-top: 6vh">
                        <div class="col-12 apithy-logo text-center">
                            <img src="/apihty_logo_blanco.png" alt="">
                        </div>
                    </div>
                    <div class="row mr-0 ml-0 no-gutters justify-content-center" style="margin-top: -2vh">
                        <div class="col-12 apithy-slogan text-center">
                            <p>
                                <i>Live the <b>learning</b> digital <br>experience</i>
                            </p>
                        </div>
                    </div>
                    <div class="row mr-0 ml-0 no-gutters justify-content-center" style="margin-top: 2vh">
                        <div class="col-12 apithy-slogan-two text-center">
                            <p>
                                <b>Formamos</b> personas a través de un <br>ambiente digital <b>adaptativo</b>
                            </p>
                        </div>
                    </div>
                    <div class="row mr-0 ml-0 no-gutters justify-content-center" style="margin-top: 6vh">
                        <div class="col-auto apithy-request-demo text-center">

                                <div class="btn-request" @click="contactUs">
                                    {{ 'Solicita un demo' }}
                                </div>

                        </div>
                    </div>
                    <div class="row mr-0 ml-0 no-gutters justify-content-center" style="margin-top: 4vh">
                        <div class="col-12 know-us text-center">

                                <span @click="knowUs">{{ 'Conócenos' }}</span>

                        </div>
                    </div>
                    <div class="row mr-0 ml-0 no-gutters justify-content-center" style="margin-top: 3vh">
                        <div class="col-12 slider">
                            <div id="wrapper">
                                <div id="carousel">
                                    <div class="slider-item" width="600" height="100">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        <img src="/images/landing/platform.png" alt="" class="img-fluid">
                                                    </div>
                                                    <div class="col">
                                                        <p class="text-center">
                                                            <i>Plataforma <br>intuitiva e inclusiva</i>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="slider-item" width="600" height="100">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        <img src="/images/landing/hifive.png" alt="" class="img-fluid">
                                                    </div>
                                                    <div class="col">
                                                        <p class="text-center">
                                                            <i>Formación de <br>talento sin parar <br>operaciones</i>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="slider-item" width="600" height="100">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        <img src="/images/landing/map.png" alt="" class="img-fluid">
                                                    </div>
                                                    <div class="col">
                                                        <p class="text-center">
                                                            <i>Gestiona tu <br>estrategia de <br>formación en 3 clics</i>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="slider-item" width="600" height="100">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        <img src="/images/landing/filter.png" alt="" class="img-fluid">
                                                    </div>
                                                    <div class="col">
                                                        <p class="text-center">
                                                            <i>Mayor retención del <br>contenido versus la <br>capacitación presencial</i>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="slider-item" width="600" height="100">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        <img src="/images/landing/clock.png" alt="" class="img-fluid">
                                                    </div>
                                                    <div class="col">
                                                        <p class="text-center">
                                                            <i>El colaborador invierte <br>un 60% menos tiempo y <br>consume hasta 5 veces
                                                                <br>más contenido</i>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center" v-if="false">
                                <div class="card" style="width: 30%; height: 100px;">
                                    <div class="card-body"></div>
                                </div>
                                <div class="card" style="width: 30%; height: 100px;">
                                    <div class="card-body"></div>
                                </div>
                                <div class="card" style="width: 30%; height: 100px;">
                                    <div class="card-body"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mr-0 ml-0 no-gutters justify-content-center" style="margin-top: 1vh">
                        <div class="col-auto">
                            <div class="" style="">
                                <div class="mouse"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="full-screen" id="five">
                <div class="content">
                    <div class="row justify-content-center apithy-slogan">
                        <div class="col-auto">
                            <p>¿Cómo <b>transformamos</b> tu <br>estrategia de formación?</p>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-12">
                            <div class="video">
                                <video controls
                                       id='video-apithy'
                                       class='video-js'
                                       preload='auto'
                                       poster='https://s3-us-west-2.amazonaws.com/cdn.apithy.com/video-contents/welcome_2.png'
                                       data-setup='{}'>
                                    <source src='https://s3-us-west-2.amazonaws.com/cdn.apithy.com/video-contents/Flyer_5.mp4' type='video/mp4'>
                                    <p class='vjs-no-js'>
                                        To view this video please enable JavaScript, and consider upgrading to a web browser that
                                        <a href='https://videojs.com/html5-video-support/' target='_blank'>
                                            supports HTML5 video
                                        </a>
                                    </p>
                                </video>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center" style="margin-top: 30px">
                        <div class="col-auto">
                            <div class="">
                                <div class="mouse"></div>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <div class="leaf-one">
                            <img src="/images/landing/leaf.png" alt="">
                        </div>
                        <div class="plant-one">
                            <img src="/images/landing/plant.png" alt="">
                        </div>
                    </div>
                </div>
            </section>
            <section class="full-screen" id="two">
                <div class="content">
                    <div class="">
                        <div class="cloud-one">
                            <img src="/images/landing/cloud.png" alt="">
                        </div>
                        <div class="cloud-two">
                            <img src="/images/landing/cloud.png" alt="">
                        </div>
                    </div>
                    <div class="" style="margin-top: 4%">
                        <div class="row justify-content-center d-md-flex d-none">
                            <div class="col-auto">
                                <img src="/images/landing/leaf.png" class="card-leaf" alt="">
                                <div class="card-one">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="text-center">
                                                <p><b>Transformamos</b> la experiencia y conocimiento de tu empresa</p>
                                            </div>
                                            <div class="image-container">
                                                <div class="image">
                                                    <div class="d-flex justify-content-center">
                                                        <img src="/images/landing/laptop-work.png" alt="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="leaf-one">
                                    <img src="/images/landing/leaf.png" alt="">
                                </div>
                            </div>
                            <div class="col-auto">
                                <img src="/images/landing/leaf.png" class="card-leaf" alt="">
                                <div class="card-two">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="text-center">
                                                <p>Construimos verdaderas <b>experiencias</b> de aprendizaje</p>
                                            </div>
                                            <div class="image-container">
                                                <div class="image">
                                                    <div class="d-flex justify-content-center">
                                                        <img src="/images/landing/learning.png" alt="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto  mt-portrait">
                                <img src="/images/landing/leaf.png" class="card-leaf" alt="">
                                <div class="card-three">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="text-center">
                                                <p>Implementamos un <b>ambiente</b> digital adaptativo</p>
                                            </div>
                                            <div class="image-container">
                                                <div class="image">
                                                    <div class="d-flex justify-content-center">
                                                        <img src="/images/landing/peoples.png" alt="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-md-none cards">
                            <div class="">
                                <div class="card-one">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="">
                                                    <img src="/images/landing/laptop-work.png" alt="">
                                                </div>
                                                <div class="text-center text-container">
                                                    <p><b>Transformamos</b> la experiencia y conocimiento de tu empresa</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <div class="card-two">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="">
                                                    <img src="/images/landing/learning.png" alt="">
                                                </div>
                                                <div class="text-center text-container">
                                                    <p>Construimos verdaderas <b>experiencias</b> de aprendizaje</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <div class="card-three">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="">
                                                    <img src="/images/landing/peoples.png" alt="">
                                                </div>
                                                <div class="text-center text-container">
                                                    <p>Implementamos un <b>ambiente</b> digital adaptativo</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center" style="margin-top: 50px">
                        <div class="col-12 text-center apithy-slogan">
                            <p>
                                Te acompañamos en la implementación de tu <br><b>estrategia de formación</b>
                            </p>
                        </div>
                    </div>
                    <div class="row justify-content-center" style="margin-top: 25px">
                        <div class="" style="position:relative; width: 100%">
                            <div class="leaf-two">
                                <img src="/images/landing/leaf.png" alt="">
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="btn-know-us" @click="contactUs">
                                <span>Contáctanos</span>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center" style="margin-top: 50px">
                        <div class="col-auto">
                            <div class="">
                                <div class="mouse"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="full-screen" id="three">
                <div class="content">
                    <div class="">
                        <div class="bg-three">
                            <img src="/images/landing/bg-three.png" alt="">
                        </div>
                        <div class="plant-one">
                            <img src="/images/landing/plant.png" alt="">
                        </div>
                    </div>
                    <div class="">
                        <div class="cloud-one">
                            <img src="/images/landing/cloud.png" alt="">
                        </div>
                        <div class="cloud-two">
                            <img src="/images/landing/cloud.png" alt="">
                        </div>
                    </div>
                    <div class="d-flex" style="margin-top: 4vh">
                        <div class="apithy-slogan">
                            <p>
                                <i>
                                    Haz que tus colaboradores aprendan como en <br>
                                    la vida.. con <b>experiencias y retos</b>
                                </i>
                            </p>
                        </div>
                    </div>
                    <div class="row features feature-one align-items-center" style="margin-top: 2vh">
                        <div class="col-auto feature-image">
                            <img src="/images/landing/laptop.png" alt="">
                        </div>
                        <div class="col feature-text">
                            <p>Contenidos a la medida de las <br>necesidades de tu empresa</p>
                        </div>
                    </div>
                    <div class="row features feature-two align-items-center" style="margin-top: 2vh">
                        <div class="col-auto feature-image">
                            <img src="/images/landing/ipad.png" alt="">
                        </div>
                        <div class="col feature-text">
                            <div class="leaf-one">
                                <img src="/images/landing/leaf.png" alt="">
                            </div>
                            <p>Microlearning y contenidos
                                <br>adaptativos, en respuesta a las
                                <br>necesidades de formación
                                <br>actuales</p>
                        </div>
                    </div>
                    <div class="row features feature-three align-items-center" style="margin-top: 2vh">
                        <div class="col-auto feature-image">
                            <img src="/images/landing/gaming.png" alt="">
                        </div>
                        <div class="col feature-text">
                            <p>Gamificación y <br>contenidos inmersivos</p>
                        </div>
                    </div>
                    <div class="row justify-content-center" style="margin-top: 40px">
                        <div class="col-auto">
                            <div class="btn-know-us" @click="contactUs">
                                <span>Contáctanos</span>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center" style="margin-top: 40px">
                        <div class="col-auto">
                            <div class="">
                                <div class="mouse"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="full-screen" id="four">
                <div class="content">
                    <div class="">
                        <div class="bg-four">
                            <img src="/images/landing/bg-four.png" alt="">
                        </div>
                    </div>
                    <div class="row" style="margin-top: 3vh" v-show="show_form">
                        <div class="col-12 text-center apithy-slogan">
                            <p>
                                <b>Contacta</b> a tu asesor <b>apithy</b> <br>
                                y conoce las nuevas tendencias en contenidos <br>
                                <b>digitales formativos</b>
                            </p>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 3vh" v-show="!show_form">
                        <div class="col-12 text-center apithy-slogan">
                            <p>
                                Felicidades! un asesor <b>apithy</b> <br>
                                se contactará contigo muy pronto
                            </p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center contact-form">
                        <div class="form" v-show="show_form">
                            <div class="row" style="margin-top: 16px">
                                <div class="col-12">
                                    <label for="name">Nombre completo*</label><br>
                                    <input id="name" name="name" type="text" v-model="form.name" v-validate="validators.name">
                                    <span class="text-danger" v-show="errors.has('name')">{{ errors.first('name') }}</span>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 16px">
                                <div class="col-12">
                                    <label for="company_name">Nombre de tu empresa*</label><br>
                                    <input id="company_name" name="company" type="text" v-model="form.company" v-validate="validators.company">
                                    <span class="text-danger" v-show="errors.has('company')">{{ errors.first('company') }}</span>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 16px">
                                <div class="col-12">
                                    <label for="email">Correo electrónico*</label><br>
                                    <input id="email" name="email" type="text" v-model="form.email" v-validate="validators.email">
                                    <span class="text-danger" v-show="errors.has('email')">{{ errors.first('email') }}</span>
                                    <div class="leaf-one">
                                        <img src="/images/landing/leaf.png" alt="">
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 16px">
                                <div class="col-12">
                                    <label for="phone">Número de celular</label><br>
                                    <input id="phone" name="phone" type="text" placeholder="(444) 345 0981" v-model="form.phone" v-validate="validators.phone">
                                    <span class="text-danger" v-show="errors.has('phone')">{{ errors.first('phone') }}</span>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 16px">
                                <div class="col-12">
                                    <label for="employes_num">Número de empleados en tu empresa</label><br>
                                    <select id="employes_num" name="number_employees" v-model="form.number_employees" v-validate="validators.employees">
                                        <option :value="null" class="default">Selecciona un tamaño</option>
                                        <option value="1-100">1 a 100</option>
                                        <option value="101-500">101 a 500</option>
                                        <option value="501-1000">501 a 1,000</option>
                                        <option value="1001-5000">1,001 a 5,000</option>
                                        <option value="5001-999999">Más de 5,000</option>
                                    </select>
                                    <span class="text-danger" v-show="errors.has('employees')">{{ errors.first('employees') }}</span>
                                    <div class="cloud-plant">
                                        <img src="/images/landing/cloud-plant.png" alt="">
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center" style="margin-top: 32px">
                                <button class="col-auto talking-us text-center" type="button" @click="send">
                                    {{ 'Platiquemos' }}
                                </button>
                            </div>
                        </div>
                        <div class="" v-show="!show_form" style="height: 524px">
                            <div id="anim"></div>
                        </div>
                    </div>
                    <div class="bg-four-bottom">
                        <img src="/images/landing/bg-four-bottom.png" alt="">
                    </div>
                    <div class="row mr-0 ml-0 no-gutters justify-content-between align-items-center footer">
                        <div class="col-auto d-none d-md-block">
                            <img src="/apihty_logo_blanco.png" alt="" height="60">
                        </div>
                        <div class="col-auto footer-text">
                            <p>
                                Apithy® CDMX 2019 <br>TODOS LOS DERECHOS RESERVADOS <br>Tabasco, CDMX, Querétaro
                            </p>
                        </div>
                        <div class="col-auto d-none d-md-block">
                            <a target="_blank" href="/aviso-de-privacidad">
                                <p>
                                    Aviso de privacidad
                                </p>
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
    import LandingPageNavbar from './LandingPageNavbar.vue'
    import VeeValidate from 'vee-validate'
    import es from 'vee-validate/dist/locale/es'
    import { email } from 'vee-validate/dist/rules.esm'

    export default {
        name: "LandingPage",
        props: {
            source: {
                type: String,
                required: true
            }
        },
        components: {
            'apithy-landingpage-navbar': LandingPageNavbar
        },
        mounted: function () {

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
            }

            VeeValidate.Validator.localize(dictionary);
            VeeValidate.Validator.localize('es', es)

            let innerWidth = window.innerWidth
            // Carousel
            $(function() {
                var _center = {
                    width: 400,
                    height: 100,
                    marginLeft: 0,
                    marginTop: 0,
                    marginRight: 0,
                    opacity: 1
                };
                var _left = {
                    width: 400,
                    height: 100,
                    marginLeft: 0,
                    marginTop: 50,
                    marginRight: -150,
                    opacity: 0.70
                };
                var _right = {
                    width: 400,
                    height: 100,
                    marginLeft: -150,
                    marginTop: 50,
                    marginRight: 0,
                    opacity: 0.70
                };
                var _outLeft = {
                    width: 200,
                    height: 100,
                    marginLeft: 150,
                    marginTop: 75,
                    marginRight: -200,
                    opacity: 0
                };
                var _outRight = {
                    width: 200,
                    height: 100,
                    marginLeft: -200,
                    marginTop: 75,
                    marginRight: 50,
                    opacity: 0
                };
                $('#carousel').carouFredSel({
                    width: 900,
                    height: 150,
                    align: false,
                    items: {
                        visible: 3,
                        width: 150
                    },
                    scroll: {
                        items: 1,
                        duration: 400,
                        onBefore: function( data ) {
                            data.items.old.eq( 0 ).animate(_outLeft);
                            data.items.visible.eq( 0 ).animate(_left);
                            data.items.visible.eq( 1 ).animate(_center);
                            data.items.visible.eq( 2 ).animate(_right).css({ zIndex: 1 });
                            data.items.visible.eq( 2 ).next().css(_outRight).css({ zIndex: 0 });

                            setTimeout(function() {
                                data.items.old.eq( 0 ).css({ zIndex: 1 });
                                data.items.visible.eq( 0 ).css({ zIndex: 2 });
                                data.items.visible.eq( 1 ).css({ zIndex: 3 });
                                data.items.visible.eq( 2 ).css({ zIndex: 2 });
                            }, 400);
                        }
                    }
                });

                $('#carousel').children().eq( 0 ).css(_left).css({ zIndex: 2 });
                $('#carousel').children().eq( 1 ).css(_center).css({ zIndex: 3 });
                $('#carousel').children().eq( 2 ).css(_right).css({ zIndex: 2 });
                $('#carousel').children().eq( 3 ).css(_outRight).css({ zIndex: 1 });
            });

            // Fullpage
            $(document).ready(function () {
                enableFullpageLibrary()
            })

            function enableFullpageLibrary() {
                $('#fullpage').fullpage({
                    autoScrolling: true,
                    sectionSelector: 'section',
                    onLeave: function (event) {
                        let el = event.item
                        ga('send', 'pageview', { 'page': "landing-presentation-card#section["+el.id+"]", 'title': "#section["+el.id+"]" })
                    }
                })
            }
        },
        methods: {
            contactUs () {
                $('#fullpage').fullpage.moveTo(5)
            },
            knowUs () {
                $('#fullpage').fullpage.moveTo(3)
            },
            success(){
                this.show_form = false
                let animation = bodymovin.loadAnimation({
                    container: document.getElementById('anim'),
                    rederer: 'svg',
                    loop: true,
                    autoplay: true,
                    path: '/js/data.json'
                });

                setTimeout(() => {
                    let paths = document.querySelectorAll("path"),i;
                    for (i = 0; i < paths.length; ++i) {
                        if(paths[i].getAttribute("fill")=="rgb(3,41,86)") {
                            paths[i].setAttribute('style', 'fill:transparent');
                        }
                    }
                }, 10)
            },
            send: function () {
                this.$validator.validateAll().then(result => {
                    if (result) {
                        axios({
                            method: 'POST',
                            url: '/contacts',
                            data: this.form
                        })
                            .then(response => {
                                this.show_form = false
                                let animation = bodymovin.loadAnimation({
                                    container: document.getElementById('anim'),
                                    rederer: 'svg',
                                    loop: true,
                                    autoplay: true,
                                    path: '/js/data.json'
                                });

                                setTimeout(() => {
                                    let paths = document.querySelectorAll("path"),i;
                                    for (i = 0; i < paths.length; ++i) {
                                        if(paths[i].getAttribute("fill")=="rgb(3,41,86)") {
                                            paths[i].setAttribute('style', 'fill:transparent');
                                        }
                                    }
                                }, 100)
                            }).catch(error => { console.error(error) })
                    }else{
                        console.log("Hola");
                    }
                });
            }
        },
        data: function () {
            return  {
                show_form: true,
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
                        regex: '^[a-z0-9_+]([a-z0-9_+.]+[a-z0-9_+])?\\@[a-z0-9.-]+$'
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

    html, body {
        height: 100%;
        padding: 0;
        margin: 0;
    }
    body {
        min-height: 500px;
        background-color: #f6f6e6;
    }
    .slider * {
        font-size: 14px;
        color: #333;
        line-height: 22px;
    }
    #wrapper {
        width: 900px;
        height: 200px;
        position: relative;
        left: 50%;
        transform: translateX(-50%);
    }
    #carousel .slider-item {
        display: block;
        float: left;
        border: none;
        position: relative;
    }
    #carousel .slider-item .card {
        height: 100px;
        box-shadow: #606060 0 5px 10px -4px;
    }
    #carousel .slider-item .card .card-body {
        padding: 0px 16px;
    }
    #carousel .slider-item .card .card-body .row {
        height: 100%;
    }
    #carousel .slider-item .card img {
        max-height: 70px;
    }
    #carousel .slider-item .card p {
        padding: 4px 0px;
        font-size: 20px;
    }


    section a {
        text-decoration: none;
    }
    section.full-screen {
        width: 100vw;
        height: 100vh;
        overflow: hidden;
    }
    section .content {
        height: 100%;
    }

    section#one {
        background-image: url("/images/landing/bg-one.png");
        background-repeat: no-repeat;
        background-position-x: center;
        background-size: cover;
    }
    section#one .content .apithy-logo {
        width: 345px;
        height: 125px;
    }
    section#one .content .apithy-logo img {
        height: 45%;
    }
    section#one .content .apithy-slogan {
        width: 503px;
        height: 70px;
        font-size: 25px;
        color: #FFFFFF;
    }
    section#one .content .apithy-slogan p {
        padding: 0 75px;
    }
    section#one .content .apithy-slogan-two {
        width: 503px;
        height: 70px;
        font-size: 27px;
        color: #FFFFFF;
    }
    section#one .content .apithy-request-demo .btn-request {
        width: 300px;
        height: 60px;
        padding: 12px 0;
        color: #444;
        text-align: center;
        font-size: 20px;
        font-weight: bold;
        background-color: #FEE857;
        border-radius: 38px;
        cursor: pointer;
    }
    section#one .content .know-us {
        text-align: center;
        font-size: 20px;
        color: #FFFFFF;
    }
    section#one .content .know-us span {
        cursor: pointer;
        color: #FFFFFF;
    }

    section#two .card-leaf {
        position: absolute;
        z-index: 1;
        left: 50%;
        transform: translateX(-50%);
        top: -50px;
        width: 64px;
        height: 89px;
    }
    section#two .card-one {
        background: #fff;
        box-shadow: 0px 4px 4px #D7E1F1;
    }
    section#two .card-one .card {
        background: linear-gradient(157.76deg, rgba(43, 149, 255, 0.2) -23.82%, rgba(43, 149, 255, 0) 30.57%);
    }
    section#two .card-two {
        background: #fff;
        box-shadow: 0px 4px 4px #D7E1F1;
    }
    section#two .card-two .card {
        background: linear-gradient(300.79deg, rgba(43, 149, 255, 0.2) -26.51%, rgba(43, 149, 255, 0) 26.49%);
    }
    section#two .card-three {
        background: #fff;
        box-shadow: 0px 4px 4px #D7E1F1;
    }
    section#two .card-three .card {
        background: linear-gradient(230.68deg, rgba(43, 149, 255, 0.2) -59.3%, rgba(43, 149, 255, 0) 30.93%);
    }
    section#two .card {
        width: 329px;
        height: 445px;
    }
    section#two .card .card-body {
        padding: 0px;
    }
    section#two .card .card-body p {
        padding: 40px;
        font-size: 20px;
    }
    section#two .card .card-body .image-container {
        height: 299px;
    }
    section#two .card .card-body .image-container .image {
        position: absolute;
        bottom: 0;
        width: 100%;
    }
    section#two .content .apithy-slogan {
        font-size: 28px;
    }
    section#two .content .btn-know-us {
        width: 300px;
        height: 60px;
        padding: 12px 0;
        text-align: center;
        font-size: 20px;
        font-weight: bold;
        color: #FFFFFF;
        background-color: #1A6BF7;
        border-radius: 38px;
        cursor: pointer;
    }
    section#two .content .btn-know-us a span {
        color: #FFFFFF;
    }
    section#two .content .leaf-one img {
        position: absolute;
        top: 5%;
        left: -85px;
    }
    section#two .content .leaf-two img {
        position: absolute;
        top: -30px;
        left: 80%;
    }
    section#two .content .cloud-one img {
        position: absolute;
        top: 150vh;
        width: 900px;
        height: 600px;
        z-index: -1;
    }
    section#two .content .cloud-two img {
        position: absolute;
        z-index: -1;
        top: 160vh;
        right: 0;
        width: 580px;
        height: 450px;
    }
    section#two .content .mouse {
        border: 2px solid #1A6BF7;
    }
    section#two .content .mouse:before {
        background: #1A6BF7;
    }

    section#three .content .apithy-slogan {
        font-size: 25px;
        padding-left: 200px;
    }
    section#three .content .features .feature-image img {
        width: 150px;
        height: 150px;
    }
    section#three .content .features .feature-text {
        margin-left: 50px;
        font-size: 25px;
    }
    section#three .content .features.feature-one {
        margin-left: 100px;
    }
    section#three .content .features.feature-two {
        margin-left: 200px;
    }
    section#three .content .features.feature-three {
        margin-left: 300px;
    }
    section#three .content .leaf-one img {
        position: absolute;
        top: 5%;
        left: -425px;
    }
    section#three .content .plant-one img {
        position: absolute;
        top: 300vh;
        right: 5vw;
        width: 270px;
        z-index: -1;
    }
    section#three .content .btn-know-us {
        width: 300px;
        height: 60px;
        padding: 12px 0;
        text-align: center;
        font-size: 20px;
        font-weight: bold;
        color: #FFFFFF;
        background-color: #1A6BF7;
        border-radius: 38px;
        cursor: pointer;
    }
    section#three .content .btn-know-us a span {
        color: #FFFFFF;
    }
    section#three .content .cloud-one img {
        position: absolute;
        top: 200vh;
        width: 900px;
        height: 600px;
        z-index: -1;
    }
    section#three .content .cloud-two img {
        position: absolute;
        top: 260vh;
        right: 0;
        width: 580px;
        height: 450px;
        z-index: -1;
    }
    section#three .content .mouse {
        border: 2px solid #1A6BF7;
    }
    section#three .content .mouse:before {
        background: #1A6BF7;
    }

    section#four .content .apithy-slogan {
        font-size: 30px;
        color: #FFFFFF;
    }
    section#four .content .contact-form .form {
        width: 40vw;
    }
    section#four .content .contact-form .form label {
        color: #FFFFFF;
        font-size: 16px;
        font-weight: 600;
    }
    section#four .content .contact-form .form input {
        width: 100%;
        border-radius: 5px !important;
        background-color: #FEFEFE;
        color: #0f0f10 !important;
        font-size: 12px
    }
    section#four .content .contact-form .form select {
        width: 100%;
        border-radius: 5px !important;
        font-size: 12px;
        color: #0f0f10 !important;
        padding: 10px 20px !important;
        background: #FEFEFE url('/images/landing/select_indicator.png') no-repeat;
        background-size: 20px;
        background-position:  right 10px center;
        -webkit-appearance: none;
        -moz-appearance: none;
    }
    section#four .content .leaf-one img {
        position: absolute;
        top: -5%;
        left: -300px;
    }
    section#four .content .cloud-plant img {
        position: absolute;
        top: -212%;
        right: -270px;
    }
    section#four .content .talking-us {
        width: 300px;
        height: 60px;
        padding: 12px 0;
        text-align: center;
        font-size: 22px;
        font-weight: bold;
        background-color: #FEE857;
        border-radius: 38px;
        border: none;
        cursor: pointer;
    }
    section#four .content .footer {
        margin-top: 175px;
        padding: 0 50px;
    }
    section#four .content .footer p {
        font-size: 20px;
        font-weight: bold;
        text-align: center;
        color: #FFFFFF;
    }

    section#five .content .apithy-slogan {
        margin-top: 50px;
    }
    section#five .content .apithy-slogan p {
        font-size: 30px;
    }

    section#five .content .video-js {
        width: 100%;
        height: 100%;
    }

    section#five .content .video {
        width: 1000px;
        height: 600px;
        margin-top: 30px;
        margin-left: auto;
        margin-right: auto;
    }

    section#five .content .video video {
        width: 100%;
        height: 100%;
    }
    section#five .content .mouse {
        border: 2px solid #1A6BF7;
    }
    section#five .content .mouse:before {
        background: #1A6BF7;
    }
    section#five .content .plant-one {
        position: absolute;
        z-index: -1;
        top: 140vh;
        left: 15%;
        transform: translateX(-50%);
    }
    section#five .content .plant-one img {
        width: 280px;
        height: auto;
    }
    section#five .content .leaf-one {
        position: absolute;
        z-index: -1;
        top: 105vh;
        right: 15%;
        transform: translateX(50%);
    }

    .bg-two-three img {
        position: absolute;
        z-index: -1;
        top: 100vh;
        height: 300vh;
        width: 90vw;
    }
    .bg-three img {
        position: absolute;
        height: 100vh;
        top: 300vh;
        right: 0;
        z-index: -1;
    }
    .bg-four img {
        position: absolute;
        height: 100vh;
        width: 100vw;
        top: 400vh;
        right: 0;
        z-index: -1;
    }

    .bg-four-bottom{
        position: relative;
    }
    .bg-four-bottom img {
        position: absolute;
        width: 100vw;
        height: 170px !important;
        bottom: -295px;
    }
    .content .mouse {
        width: 20px;
        height: 40px;
        border: 2px solid #fff;
        border-radius: 60px;
    }
    .mouse:before {
        content: '';
        width: 5px;
        height: 5px;
        position: absolute;
        top: 10px;
        background: #fff;
        left: 50%;
        transform: translateX(-50%);
        border-radius: 50%;
        opacity: 1;
        animation: mouse 2s infinite;
    }
    @keyframes mouse {
        from {
            opacity: 1;
            top: 10px;
        }
        to {
            opacity: 0;
            top: 30px;
        }
    }

    /** Mobile **/
    @media only screen and (orientation: portrait) and (max-width: 767px) {
        .content .row {
            margin-top: 0 !important;
        }

        section#one .apithy-logo {
            padding-top: 50px;
            height: 100px !important;
        }
        section#one .apithy-slogan {
            font-size: 24px !important;
            height: auto !important;
        }
        section#one .apithy-slogan p {
            padding: 0 !important;
        }
        section#one .apithy-slogan-two {
            font-size: 20px !important;
            height: auto !important;
        }
        section#one .apithy-request-demo {
            padding-top: 40px;
            height: auto !important;
        }
        section#one .know-us {
            padding-top: 40px;
            height: auto !important;
        }
        section#one .slider {
            padding-top: 40px;
            height: auto !important;
        }
        section#one .mouse {
            display: none !important;
        }

        section#two .cards .card {
            height: auto;
            margin: 16px auto 0 auto;
            background-color: #ffffff;
        }
        section#two .cards .card .card-body {
            height: auto;
            background: linear-gradient(157.76deg, rgba(43, 149, 255, 0.2) -23.82%, rgba(43, 149, 255, 0) 30.57%);
        }
        section#two .cards .card .card-body img {
            height: 150px;
        }
        section#two .cards .card .card-body .text-container p {
            padding: 0 !important;
            font-size: 16px;
        }
        section#two .cards .card-one {
            background-color: transparent;
            box-shadow: none;
        }
        section#two .cards .card-two {
            background-color: transparent;
            box-shadow: none;
        }
        section#two .cards .card-three {
            background-color: transparent;
            box-shadow: none;
        }
        section#two .apithy-slogan {
            margin-top: 16px;
            padding: 0 40px;
            font-size: 16px !important;
        }
        section#two .btn-know-us {
            margin-top: 16px;
        }
        section#two .mouse {
            display: none !important;
        }

        section#three .btn-know-us {
            margin-top: 40px;
        }
        section#three .mouse {
            display: none !important;
        }

        section#four .apithy-slogan {
            font-size: 20px !important;
            padding: 40px 40px 0 40px;
        }
        section#four .form {
            width: 100vw !important;
            padding: 40px;
        }
        section#four .form label {
            margin-top: 10px;
        }
        section#four .content .talking-us {
            margin-top: 16px;
            height: 50px;
            padding: 8px 0;
            font-size: 20px;
        }
        section#four .content .footer {
            padding: 0px 75px;
        }
        section#four .content .footer p {
            font-size: 15px !important;
        }

        section#five .content .apithy-slogan {
            margin-top: 30px !important;
        }
        section#five .content .apithy-slogan p {
            font-size: 18px;
        }
        section#five .content .video {
            width: 350px;
            height: 300px;
            margin-top: 30px;
            margin-left: auto;
            margin-right: auto;
        }
        section#five .content .video video {
            width: 100%;
            height: 100%;
        }
        section#five .content .plant-one {
            display: none;
            position: absolute;
            z-index: 1;
            top: 160vh;
            left: 50%;
            transform: translateX(-50%);
        }
        section#five .content .plant-one img {
            width: 130px;
            height: auto;
        }
        section#five .content .leaf-one {
            display: none;
        }

        section#five .mouse {
            display: none !important;
        }

        .bg-four-bottom img {
            display: none;
        }
    }
    @media only screen and (orientation: portrait) and (width: 414px) and (height: 896px) {
        section#five .content .plant-one {
            display: none;
            position: absolute;
            z-index: 1;
            top: 160%;
            left: 50%;
            transform: translateX(-50%);
        }
    }

    /** iPad **/
    @media only screen and (orientation: portrait) and (width: 768px) and (height: 1024px) {
        section#two .mt-portrait {
            margin-top: 50px;
        }
        section#two .card {
            width: 290px;
            height: 340px;
        }
        section#two .card .card-body {
            padding: 0px;
        }
        section#two .card .card-body p {
            padding: 40px;
            font-size: 20px;
        }
        section#two .card .card-body .image-container {
            height: auto;
        }
        section#two .card .card-body .image-container .image {
            position: relative;
            top: -60px;
            width: 100%;
        }
        section#two .card .card-body .image-container .image img {
            height: 180px;
            width: auto;
        }

        section#four .content .footer {
            margin-top: 247px;
        }
        section#four .content .footer p {
            font-size: 16px;
        }
        .bg-four-bottom img {
            bottom: -345px;
        }

        section#five .content .apithy-slogan p {
            font-size: 30px;
        }
        section#five .content .video {
            width: 650px;
            height: 435px;
            margin-top: 30px;
            margin-left: auto;
            margin-right: auto;
        }
        section#five .content .video video {
            width: 100%;
            height: 100%;
        }
        section#five .content .plant-one {
            position: absolute;
            z-index: -1;
            top: 150vh;
            left: 25%;
            transform: translateX(-50%);
        }
        section#five .content .plant-one img {
            width: 280px;
            height: auto;
        }
        section#five .content .leaf-one {
            position: absolute;
            z-index: -1;
            top: 105vh;
            right: 15%;
            transform: translateX(50%);
        }
    }

    /** iPad landscape **/
    @media only screen and (orientation: landscape) and (width: 1024px) and (height: 768px) {
        section#two .card {
            width: 290px;
            height: 340px;
        }
        section#two .card .card-body {
            padding: 0px;
        }
        section#two .card .card-body p {
            padding: 40px;
            font-size: 20px;
        }
        section#two .card .card-body .image-container {
            height: auto;
        }
        section#two .card .card-body .image-container .image {
            position: relative;
            top: -60px;
            width: 100%;
        }
        section#two .card .card-body .image-container .image img {
            height: 180px;
            width: auto;
        }

        section#four .content .footer {
            margin-top: 20px;
        }

        section#four .content .footer p {
            font-size: 16px;
        }
        .bg-four-bottom img {
            bottom: -175px;
        }

        section#five .content .apithy-slogan p {
            font-size: 30px;
        }
        section#five .content .video {
            width: 800px;
            height: 450px;
            margin-top: 30px;
            margin-left: auto;
            margin-right: auto;
        }
        section#five .content .video video {
            width: 100%;
            height: 100%;
        }
        section#five .content .plant-one {
            position: absolute;
            z-index: -1;
            top: 135vh;
            left: 20%;
            transform: translateX(-50%);
        }
        section#five .content .plant-one img {
            width: 280px;
            height: auto;
        }
        section#five .content .leaf-one {
            position: absolute;
            z-index: -1;
            top: 105vh;
            right: 15%;
            transform: translateX(50%);
        }
    }

    /** iPad pro **/
    @media only screen and (orientation: portrait) and (width: 1024px) and (height: 1366px) {
        section#four .content .footer {
            margin-top: 560px;
        }
        .bg-four-bottom img {
            bottom: -680px;
        }

        section#five .content .apithy-slogan {
            margin-top: 100px !important;
        }
        section#five .content .apithy-slogan p {
            font-size: 30px;
        }
        section#five .content .video {
            width: 800px;
            height: 600px;
            margin-top: 50px;
            margin-left: auto;
            margin-right: auto;
        }
        section#five .content .video video {
            width: 100%;
            height: 100%;
        }
        section#five .content .plant-one {
            position: absolute;
            z-index: -1;
            top: 160vh;
            left: 25%;
            transform: translateX(-50%);
        }
        section#five .content .plant-one img {
            width: 280px;
            height: auto;
        }
        section#five .content .leaf-one {
            position: absolute;
            z-index: -1;
            top: 105vh;
            right: 15%;
            transform: translateX(50%);
        }
    }

    /** iPad pro landscape **/
    @media only screen and (orientation: landscape) and (width: 1366px) and (height: 1024px) {
        section#four .content .footer {
            margin-top: 230px;
        }
        .bg-four-bottom img {
            bottom: -345px;
        }

        section#five .content .apithy-slogan {
            margin-top: 100px !important;
        }
        section#five .content .apithy-slogan p {
            font-size: 30px;
        }
        section#five .content .video {
            width: 1000px;
            height: 600px;
            margin-top: 30px;
            margin-left: auto;
            margin-right: auto;
        }
        section#five .content .video video {
            width: 100%;
            height: 100%;
        }
        section#five .content .plant-one {
            position: absolute;
            z-index: -1;
            top: 150vh;
            left: 20%;
            transform: translateX(-50%);
        }
        section#five .content .plant-one img {
            width: 280px;
            height: auto;
        }
        section#five .content .leaf-one {
            position: absolute;
            z-index: -1;
            top: 105vh;
            right: 15%;
            transform: translateX(50%);
        }
    }

    @media (max-width: 640px) {

        section#two .apithy-slogan{
            font-size: 14px !important;
        }
        section#three .content .apithy-slogan {
            padding-left: 18px;
            z-index: 1;
            font-size: 15px !important;
        }

        section#three .content .features.feature-one,
        section#three .content .features.feature-two,
        section#three .content .features.feature-three{
            margin-left: 0px;
        }

        section#three .content .features .feature-text{
            margin-left: 10px;
            font-size: 16px;
        }

        section#three .content .plant-one{
            display: none !important;
        }

        section#four .content {
            margin-top: 27px;
        }
        section#four .content .apithy-slogan{
            font-size: 14px !important;
        }

        section#four .form{
            padding: 10px 30px !important;
        }

        section#four .bg-four{
            position: relative;
        }

        section#four .bg-four img{
            position: absolute;
            height: 100vh;
            width: 100vw;
            top: 0px !important;
            right: 0;
            z-index: -1;
        }

        .bg-four-bottom{
            position: absolute;
        }

        .bg-four-bottom img{
            position: absolute;
            width: 100vw;
            bottom: -155px;
            height: 110px !important;
        }

        .footer-text{
            margin-top: 40px;
        }

        section#five .content .video-js {
            width: 340px !important;
            height: 280px !important;
            max-width: 360px !important;
        }

        section#five .content .video {
            width: 350px !important;
            height: 300px !important;
        }
        section#five .content .video video {
            width: 100% !important;
            height: 100% !important;
        }

        section#five .vjs-poster {
            background-color: #000000 !important;
        }

        section#five .video-apithy-dimensions {
            width: 1920px;
            height: 1080px;
        }

    }

    /** iPhone X **/
    @media only screen
    and (device-width : 375px){
        section#two .card {
            margin-top: 16px !important;
        }
        section#two .cards .card .card-body img {
            height: 120px;
        }
        section#two .cards .card .card-body .text-container p {
            font-size: 14px !important;
        }
        section#three .content .apithy-slogan {
            font-size: 14px;
        }
        section#four .content .apithy-slogan {
            font-size: 14px!important;
        }
        section#three .content .features {
            padding: 0 10px;
        }
        section#three .content .features .feature-text {
            font-size: 14px !important;
        }
        section#three .content .features .feature-image img {
            width: 70px !important;
            height: 70px !important;
        }
        section#three .features {
            margin-top: 25px !important;
        }
        section#four .footer {
            padding: 75px 50px !important;
        }
        section#four .content .bg-four-bottom img {
            height: 120px !important;
            bottom: -214px !important;
        }
    }

</style>
<style>
    body {
        margin: 0;
    }
    footer {
        display: none;
    }
    section#four .fp-tableCell {
        margin-top: -27px;
    }
    section#five .content .video-js .vjs-big-play-button {
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
    }
</style>