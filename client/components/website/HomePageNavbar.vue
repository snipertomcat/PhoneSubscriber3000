<template>
    <div class="">
        <div class="d-inline-flex align-items-center justify-content-between landing-bar">
            <div class="left d-flex align-items-center justify-content-center">
                <div class="" v-if="mobileView">
                    <span class="slideout-trigger">
                        <i class="fa fa-bars"></i>
                    </span>
                </div>
                <a href="/" v-else>
                    <div class="logo">
                        <template v-if="isDemo">
                            <img class="" height="60" src="/logo_demo_blanco.png">
                        </template>
                        <template v-else>
                            <img class="" height="60" src="/apihty_logo_blanco.png">
                        </template>
                    </div>
                </a>
            </div>
            <div class="align-items-center justify-content-end d-flex" :class="{'center': !mobileView}" v-if="!mobileView">
                <div class="link"><a href="#"><span>Producto</span></a></div>
                <div class="link"><a href="#"><span>Precios</span></a></div>
                <div class="link"><a href="/login"><span>Ingresa</span></a></div>
                <a href="/signup"><div class="link button"><span>Regístrate</span></div></a>
            </div>
            <div class="d-flex align-items-center justify-content-center" v-else>
                <div class="contact" v-if="mobileView">
                    <a href="#four">
                        <div class="landing-button-mobile">
                            <span>{{ 'Solicita un demo' }}</span>
                        </div>
                    </a>
                </div>
                <div class="contact d-none d-lg-flex">
                    <div class="landing-button" @click="contactUs">
                        <span>{{ 'Contacto' }}</span>
                    </div>
                </div>
            </div>
            <div class="position-relative" :class="{'right': !ipad&&!mobileView}">
                <div class="d-block d-lg-none position-absolute social">
                    <div class="d-inline-flex justify-content-center">
                        <div class="social-btn btn-instagram">
                            <a target="_blank" href="https://www.instagram.com/apithylearning/">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                        <div class="social-btn btn-facebook">
                            <a target="_blank" href="https://www.facebook.com/apithylearning/">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="d-none d-lg-block social">
                    <div class="d-inline-flex justify-content-center">
                        <div class="social-btn btn-instagram">
                            <a target="_blank" href="https://www.instagram.com/apithylearning/">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                        <div class="social-btn btn-facebook">
                            <a target="_blank" href="https://www.facebook.com/apithylearning/">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "HomePageNavbar",
        props: {
            host: {
                type: String,
                required: true
            },
            mobileView: {
                type: Boolean,
                required: false,
                default: false
            }
        },
        computed: {
            isDemo: function () {
                return this.host === 'demo'
            },
            ipad: function () {
                return (window.screen.width > 720 && window.screen.width < 1366)
            }
        },
        methods: {
            contactUs () {
                fullpage_api.moveTo(4)
            },
        }
    }
</script>

<style scoped>
    .landing-bar {
        margin: 0 0px;
        width: 100vw;
        height: 90px;
        border-bottom: #FFFFFF 1px solid;
    }
    .landing-bar .left {
        width: 335px;
        height: 60px;
        padding: 0 25px;
    }
    .landing-bar .left {
        width: 335px;
        height: 60px;
        padding: 0 25px;
    }
    .landing-bar .logo img{
        cursor: pointer;
        height: 80%;
    }
    .landing-bar .center {
        width: 1245px;
        height: 60px;
        padding: 0 25px;
    }
    .landing-bar .link {
        color: #ffffff;
        font-weight: bold;
        margin-left: 20px;
        cursor: pointer;
    }
    .landing-bar .link:hover {
        border-bottom: 1px solid #ffffff;
    }
    .landing-bar .link a {
        color: unset;
        text-decoration: none;
    }
    .landing-bar .link.button {
        width: 164px;
        height: 40px;
        padding: 8px;
        font-size: 16px;
        text-align: center;
        color: #FFFFFF;
        border: #FFFFFF 1px solid;
        border-radius: 20px;
        cursor: pointer;
    }
    .landing-bar .social a {
        text-decoration: none;
        color: #FFFFFF;
    }
    .landing-bar .social.d-lg-none {
        top: unset;
        right: unset;
        width: unset;
    }
    .landing-bar .social {
        top: 30px;
        right: 60px;
        width: 100%;
        color: #FFFFFF;
        font-size: 32px;
    }
    .landing-bar .social .d-inline-flex {
        width: 100%;
    }
    .landing-bar .social .social-btn {
        padding: 16px;
    }
    .landing-bar .social i {
        cursor: pointer;
    }
    .landing-bar .right {
        width: 340px;
        height: 60px;
        padding: 0 25px;
    }
    .landing-bar .contact a {
        text-decoration: none;
        color: unset;
    }
    .landing-bar .contact .landing-button {
        width: 164px;
        height: 40px;
        padding: 8px;
        font-size: 16px;
        text-align: center;
        color: #FFFFFF;
        border: #FFFFFF 1px solid;
        border-radius: 20px;
        cursor: pointer;
    }
    .landing-bar .contact .landing-button-mobile {
        width: 100%;
        height: 30px;
        padding: 4px;
        color: #FEE857;
        font-size: 13px;
        font-weight: bold;
        text-align: center;
    }
    .landing-bar .contact .landing-button:hover {
        box-shadow: #000000 0px 3px 11px -6px;;
    }


    @media only screen and (max-width: 767px) {
        .landing-bar {
            height: 50px;
        }
        .landing-bar .left {
            width: calc(100vw * 0.5);
            height: 50px;
        }
        .landing-bar .center {
            display: none !important;
        }
        .landing-bar .right {
            width: calc(100vw * 0.5);
            height: 50px;
        }
        .landing-bar .logo img {
            height: 40px;
        }
        .landing-bar .contact  {
            width: 100%;
        }
        .landing-bar .contact .landing-button {
            width: 100%;
            height: 30px;
            font-size: 12px;
            text-align: center;
            padding: 4px;
        }
    }

    @media only screen and (min-device-width: 767px) and (max-device-width: 896px ) {
        .landing-bar {
            height: 60px;
        }
    }
</style>
<style>
    .position-absolute.trigger {
        display: none;
    }
</style>