<template>
    <div class="">
        <div class="d-inline-flex align-items-center justify-content-between landing-bar">
            <div class="left d-flex align-items-center justify-content-center">
                <a href="/">
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
            <div class="center align-items-center justify-content-end d-flex">
                <div class="social d-inline-flex">
                    <div class="btn-instagram mr-5">
                        <a target="_blank" href="https://www.instagram.com/apithylearn/">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                    <div class="btn-facebook mr-4">
                        <a target="_blank" href="https://www.facebook.com/apithylearn/">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="right d-flex align-items-center justify-content-center">
                <div class="contact" v-if="mobileView">
                    <a href="#four">
                        <div class="landing-button-mobile">
                            <span>{{ 'Solicita un demo' }}</span>
                        </div>
                    </a>
                </div>
                <div class="contact" v-else>
                    <div class="landing-button" @click="contactUs">
                        <span>{{ 'Contacto' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "LandingPageNavbar",
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
    .landing-bar .social a {
        text-decoration: none;
        color: #FFFFFF;
    }
    .landing-bar .social {
        color: #FFFFFF;
        font-size: 32px;
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