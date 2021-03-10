<template>
    <div class="apithy-home">
        <template v-if="helper.isMobile().any()">
            <div class="device-not-supported">
                <div class="row mr-0 ml-0 justify-content-center align-items-center height-100">
                    <div class="col-auto">
                        <div class="message">
                            <span>Esta sección debe visualizarse en un equipo de escritorio o equipo portatil</span>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <template v-else>
            <!-- Primera sección del home -->
            <template v-if="main">
                <div class="main">
                    <div class="decoration">
                        <div class="background"></div>
                        <div class="corner-cloud">
                            <img src="https://s3-us-west-2.amazonaws.com/cdn.apithy.com/home_page/angle_home_nube.png" alt="">
                        </div>
                        <div class="floating-cloud">
                            <img src="https://s3-us-west-2.amazonaws.com/cdn.apithy.com/home_page/angle_home_planta_nube.png" alt="">
                        </div>
                    </div>
                    <div class="container">
                        <div class="head d-flex align-items-center">
                            <div class="ml-5">
                                <div class="d-flex width-100 align-self-center">
                                    <div class="decoration">
                                        <div class="leaf"
                                             :style="generateStyle({
                                 'background-color': '#ffffff',
                                 top: '45px',
                                 left: '-50px',
                                 width: '35px'
                                 })"></div>
                                    </div>
                                    <div class="greeting">
                                        <span>{{ translate('hi') }}, </span><span>{{ user.name }}</span>
                                    </div>
                                </div>
                                <div class="d-flex width-100 align-self-start">
                                    <div class="question">
                                        <span>{{ translate('what_do_you_want') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="content">
                            <div class="row justify-content-center">
                                <template v-for="(card, index) in cards">
                                    <div class="col-auto mb-4">
                                        <div class="card">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="">
                                                    <div class="step">
                                                        <span>{{ card.step }}</span>
                                                    </div>
                                                </div>
                                                <div class="">
                                                    <div class="image">
                                                        <img :src="card.image" alt="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex" style="margin: 16px 0">
                                                <div class="width-100">
                                                    <div class="title" :style="generateStyle(card.styles.title)">
                                                        <div class="decoration">
                                                            <div class="leaf" :style="generateStyle(card.styles.title_decoration)"></div>
                                                        </div>
                                                        <span>{{ card.title }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-center" style="margin: 16px 0">
                                                <div class="width-100">
                                                    <div class="text">
                                                        <p>{{ card.paragraph }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-center">
                                                <div class="button width-100" @click="selectCard(card)">
                                                    <span>{{ translate('next') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <!-- End primera sección del home -->

            <!-- Segunda sección del home -->
            <template v-if="isCardSelected">
                <div class="section">
                    <!-- Breadcrumb -->
                    <div class="breadcrumb">
                        <div class="row align-items-center ml-5 mr-5">
                            <div class="col-auto item home">
                                <span class="ico mr-4"><i class="fa fa-angle-left"></i></span>
                                <span class="text" @click="goMain">Home</span>
                            </div>
                            <div class="col-auto item">
                                <span class="ico mr-4"><i class="fa fa-angle-left"></i></span>
                                <span class="text">{{ translate(selectedCard.breadcrumbName) }}</span>
                            </div>
                        </div>
                    </div>
                    <!-- End breadcrumb -->
                    <!-- Cards -->
                    <div class="container d-flex justify-content-center">
                        <div class="content ml-0 mr-0 ml-lg-5 mr-lg-5" style="height: 100%;">
                            <div class="row justify-content-center">
                                <template v-for="(card, index) in subCards">
                                    <div class="col-auto mb-4">
                                        <div class="card" :class="{'short': isShortCard(card)}">
                                            <div class="d-flex align-items-center justify-content-end">
                                                <div class="">
                                                    <div class="image">
                                                        <img :src="card.image" alt="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex" style="margin: 16px 0">
                                                <div class="width-100">
                                                    <div class="title" :style="generateStyle(card.styles.title)">
                                                        <div class="decoration">
                                                            <div class="leaf" :style="generateStyle(card.styles.title_decoration)"></div>
                                                        </div>
                                                        <span>{{ card.title }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-center" style="margin: 16px 0">
                                                <div class="width-100">
                                                    <div class="text">
                                                        <p>{{ card.paragraph }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-center">
                                                <a :href="card.buttonLink" class="width-100">
                                                    <div class="button width-100">
                                                        <span>{{ translate('next') }}</span>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="overlay"></div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                    <!-- End cards -->
                </div>
            </template>
            <!-- End segunda sección del home -->
        </template>
    </div>
</template>

<script>
    import { cards } from "../../../static_json/home/cards";
    import { index } from "../../../helpers";

    export default {
        name: "Home",
        props: {
            user: {
                type: Object,
                required: true
            }
        },
        computed: {
            main () {
                return this.selectedCard === null;
            },
            isCardSelected () {
                return this.selectedCard !== null;
            },
            subCards () {
                if (!this.isCardSelected || _.isEmpty(this.selectedCard.subCards))
                    return [];
                return this.selectedCard.subCards;
            },
            helper () {
                return index;
            }
        },
        created () {
            this.cards = cards;
        },
        methods: {
            translate (string) {
                return this.$t('messages.' + string);
            },
            generateStyle (styleObject) {
                let style = '';
                _.each(styleObject, (value, index) => {
                    style = style + index + ': ' + value + '; ';
                });
                return style;
            },
            selectCard (card) {
                this.selectedCard = card;
            },
            goMain () {
                this.selectedCard = null;
            },
            isShortCard (card) {
                if (!('shortCard' in card))
                    return false
                return card.shortCard;
            }
        },
        data() {
            return {
                selectedCard: null,
                cards: []
            };
        }
    }
</script>

<style scoped>
    a {
        text-decoration: none;
        color: unset;
    }
    .content .col-auto {
        padding: 7.5px;
    }
    .section {
        padding: 0;
    }
    .decoration {
        position: relative;
    }
    .background {
        position: absolute;
        width: 100%;
        height: 42vh;
        overflow: hidden;
        background: url(https://s3-us-west-2.amazonaws.com/cdn.apithy.com/home_page/angle_home_background.png);
        background-size: cover;
    }
    .corner-cloud {
        position: absolute;
        right: 0;
    }
    .floating-cloud {
        position: absolute;
        right: 20%;
        top: 60px;

    }
    .content {
        min-height: 450px;
    }
    .card {
        position: relative;
        width: 325px;
        height: 400px;
        padding: 20px;
        box-shadow: 0px 10px 25px rgba(0, 0, 0, 0.25);
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.5s;
    }
    .card:hover {
        width: 345px;
        height: 435px;
        padding: 20px;
        box-shadow: 0px 15px 40px rgba(0, 0, 0, 0.25);
        border-radius: 20px;
        overflow: hidden;
        transform: translateY(-22px);
    }
    .card:hover .image {
        width: 114px;
        height: 114px;
        opacity: 1;
    }
    .card:hover .text {
        height: 115px;
    }
    .head {
        height: 27vh;
    }
    .greeting {
        margin-top: 20px;
        font-style: normal;
        font-weight: 800;
        font-size: 60px;
        line-height: 82px;
        color: #FFFFFF;
    }
    .question {
        font-style: normal;
        font-weight: 500;
        font-size: 30px;
        line-height: 41px;
        color: #FFFFFF;
    }
    .step {
        font-style: normal;
        font-weight: bold;
        font-size: 80px;
        line-height: 96px;
        color: rgba(0, 178, 255, 0.5);
    }
    .image {
        width: 95px;
        height: 95px;
        transition: all 0.5s;
        opacity: 0;
    }
    .leaf {
        position: absolute;
        top: -25px;
        width: 30px;
        height: 25px;
        background-color: #1A6BF7;
        -webkit-mask: url(https://s3-us-west-2.amazonaws.com/cdn.apithy.com/home_page/angle_home_hoja_azul.png) no-repeat center;
        mask: url(https://s3-us-west-2.amazonaws.com/cdn.apithy.com/home_page/angle_home_hoja_azul.png) no-repeat center;
    }
    .card .leaf {
        opacity: 0;
        transition: opacity 0.5s;
    }
    .card:hover .leaf {
        opacity: 1;
    }
    .title {
        font-style: normal;
        font-weight: bold;
        font-size: 30px;
        line-height: 37px;
    }
    .text {
        max-width: 249px;
        height: 90px;
        font-style: normal;
        font-weight: normal;
        font-size: 17px;
        line-height: 24px;
        color: #444444;
        transition: all 0.5s;
    }
    .button {
        height: 48px;
        border: unset;
        text-align: center;
        background: #1A6BF7;
        box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
        border-radius: 4px;
        font-style: normal;
        font-weight: 600;
        font-size: 18px;
        line-height: 22px;
        color: #FFFFFF;
    }
    .breadcrumb .row {
        height: 75px;
        border-bottom: 1px solid #BEBEBE;
    }
    .breadcrumb .item span {
        font-style: normal;
        font-weight: 600;
        font-size: 20px;
        line-height: 30px;
    }
    .breadcrumb .item.home span {
        color: #BEBEBE;
        cursor: pointer;
        transition: color 0.3s;
    }
    .breadcrumb .item.home:hover span {
        color: #1A6BF7;
    }
    .section .content {
        max-width: 1100px;
    }
    .section .card .image img {
        opacity: 0;
        transition: opacity 0.5s;
    }
    .section .card:hover .image img {
        opacity: 1;
    }
    .section .card.short {
        height: 270px;
        transition: all 0.5s;
    }
    .section .card.short:hover {
        height: 400px;
    }
    .section .card.short .image {
        height: 0px;
        transition: height 0.5s;
    }
    .section .card.short:hover .image {
        height: 114px;
    }
    .device-not-supported {
        height: 300px;
    }
    .device-not-supported .message {
        font-size: 24px;
        background-color: unset;
    }
</style>
<style>
    .page-content {
        min-height: calc(100vh - 200px);
    }
</style>