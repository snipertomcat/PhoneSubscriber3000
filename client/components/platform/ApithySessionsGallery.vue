<template>
    <div class="row ml-0 mr-0 experience-sessions-gallery" :class="{'has-teaser': !video_teaser}">
        <!-- Desktop view -->
        <div class="offset-1 col-sm-10" v-if="isDesktop()">
            <div class="col-sm-12 row session-container">
                <div v-if="!video_teaser" class="offset-sm-3 col-md-10 session-image">
                    <img :src="sessions[this.session_selected].full_path_cover" alt="default_img">
                </div>

                <div v-else class="offset-sm-3 col-md-10 video_teaser">
                    <video controls>
                        <source :src="video_teaser" type="video/mp4">
                    </video>
                </div>

                <div class="col-8 session-info">
                    <div class="row">
                        <div class="col-12">
                            <div class="row justify-content-between ml-0 mr-0">
                                <div class="col-1 text-left">
                                    <div class="pointer session-prev font-48" @click="prevSession">
                                        <i class="fas fa-angle-left"></i>
                                    </div>
                                </div>
                                <div class="col-10">
                                    <div>
                                        <span class="index"><b>{{ this.session_selected+1 }}.</b></span><br>
                                        <span class="title">{{this.sessions[this.session_selected].title }}</span>
                                    </div>

                                    <div class="">
                                        <div class="session-description">
                                            <p v-html="sessions[this.session_selected].summary">
                                                {{ sessions[this.session_selected].summary }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-1 text-right">
                                    <div class="pointer session-next font-48" @click="nextSession">
                                        <i class="fas fa-angle-right"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12" v-if="abilities.length || sessions[this.session_selected].taxonomy_ability.length">
                            <span><b>Aprenderás</b></span>
                            <br>
                            <div class="row abilities-container" v-if="sessions[this.session_selected].taxonomy_ability.length">
                                <div class="ability" v-for="(ability, index) in sessions[this.session_selected].taxonomy_ability">{{ ability.title }}</div>
                            </div>
                            <div class="row abilities-container" v-else>
                                <div class="ability" v-for="(ability, index) in abilities">{{ ability.title }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="session-selector offset-md-2 row" v-if="false">
                <div class="col-sm-12 session-selector-header">
                    <span><b>Retos por conquistar</b></span>
                </div>
                <div class="section-selector-content col-sm-12 row">
                    <div class="col-sm-2 pointer" v-for="(session, index) in sessions" @click="sessionSelect(index)">
                        <div :class="{'session-selector-image':true, 'active': isActive(index)}">
                            <img :src="session.full_path_cover" alt="default_img" class="img-responsive">
                        </div>
                        <div class="session-selector-number">
                            <span><b>{{ index+1 }}</b></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tablet view -->
        <div class="col-sm-12 row" v-if="isTablet()">
            <div class="col-sm-12 row session-container">
                <div class="offset-sm-3 col-md-10 session-image">
                    <img :src="sessions[this.session_selected].full_path_cover" alt="default_img">
                </div>
                <div class="col-12 col-md-9 session-info">
                    <div class="card-info">
                        <div class="row ml-0 mr-0">
                            <div class="col-1 tex-center">
                                <div class="pointer session-prev font-48" @click="prevSession">
                                    <i class="fas fa-angle-left"></i>
                                </div>
                            </div>
                            <div class="col-10">
                                <div>
                                    <span class="index"><b>{{ this.session_selected+1 }}.</b></span><br>
                                    <span class="title">{{this.sessions[this.session_selected].title }}</span>
                                </div>

                                <div class="">
                                    <div class="session-description">
                                        <p v-html="sessions[this.session_selected].summary">
                                            {{ sessions[this.session_selected].summary }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-1 text-center">
                                <div class="pointer session-next font-48" @click="nextSession">
                                    <i class="fas fa-angle-right"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12" v-if="abilities.length || sessions[this.session_selected].taxonomy_ability.length">
                            <span><b>Aprenderás</b></span>
                            <br>
                            <div class="row abilities-container" v-if="sessions[this.session_selected].taxonomy_ability.length">
                                <div class="ability" v-for="(ability, index) in sessions[this.session_selected].taxonomy_ability">{{ ability.title }}</div>
                            </div>
                            <div class="row abilities-container" v-else>
                                <div class="ability" v-for="(ability, index) in abilities">{{ ability.title }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="session-selector col-sm-12 row" v-if="false">
                <div class="col-sm-12 session-selector-header">
                    <span><b>Retos por conquistar</b></span>
                </div>
                <div class="section-selector-content col-sm-12 row">
                    <div class="col-sm-2 pointer" v-for="(session, index) in sessions" @click="sessionSelect(index)">
                        <div :class="{'session-selector-image':true, 'active': isActive(index)}">
                            <img :src="session.full_path_cover" alt="default_img">
                        </div>
                        <div class="session-selector-number">
                            <span><b>{{ index+1 }}</b></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Phone view -->
        <div class="col-12" v-if="isPhone()">
            <div class="">
                <div class="row">
                    <div class="col-12"><b>Retos:</b></div>
                </div>
                <div class="row justify-content-between">
                    <div class="col-auto">
                        <a class="button is-primary is-inverted pl-0 pr-0" @click="prevSession">
                            <span class="icon">
                                <i class="fas fa-angle-left"></i>
                            </span>
                            <span>Anterior</span>
                        </a>
                    </div>
                    <div class="col-auto align-self-center">
                        <span>{{this.session_selected+1}}/{{this.sessions.length}}</span>
                    </div>
                    <div class="col-auto">
                        <a class="button is-primary is-inverted pl-0 pr-0" @click="nextSession">
                            <span class="icon">
                                <i class="fas fa-angle-right"></i>
                            </span>
                            <span>Siguiente</span>
                        </a>
                    </div>
                </div>
                <div class="row height-500">
                    <div class="session-image">
                        <img :src="sessions[this.session_selected].full_path_cover" alt="default_img">
                    </div>
                    <div class="session-info">
                        <div class="card-info">
                            <div>
                                <span class="index"><b>{{ this.session_selected+1 }}.</b></span><br>
                                <span class="title">{{this.sessions[this.session_selected].title }}</span>
                            </div>

                            <div class="">
                                <div class="session-description ml-0 mr-0 pl-0 pr-0">
                                    <p v-html="sessions[this.session_selected].summary">
                                        {{ sessions[this.session_selected].summary }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-4" v-if="abilities.length || sessions[this.session_selected].taxonomy_ability.length">
                                <div class="row" v-if="sessions[this.session_selected].taxonomy_ability.length">
                                    <div class="col-12"><b>Aprenderás</b></div>
                                </div>
                                <div class="row abilities-container" v-if="sessions[this.session_selected].taxonomy_ability.length">
                                    <div class="col-12">
                                        <span class="ability p-1" v-for="(ability, index) in sessions[this.session_selected].taxonomy_ability">{{ ability.title }}</span>
                                    </div>
                                </div>
                                <div class="row abilities-container" v-else>
                                    <div class="col-12">
                                        <span class="ability p-1" v-for="(ability, index) in abilities">{{ ability.title }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br><br><br><br>
                </div>
                <div class="row justify-content-between mt-2 mb-4">
                    <div class="col-auto">
                        <a class="button is-primary is-inverted pl-0 pr-0" @click="prevSession">
                            <span class="icon">
                                <i class="fas fa-angle-left"></i>
                            </span>
                            <span>Anterior</span>
                        </a>
                    </div>
                    <div class="col-auto">
                        <a class="button is-primary is-inverted pl-0 pr-0" @click="nextSession">
                            <span class="icon">
                                <i class="fas fa-angle-right"></i>
                            </span>
                            <span>Siguiente</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ApithySessionsGallery",
        components: {
            'Slick': () => import('vue-slick'),
        },
        props: {
            title: {
                type: String,
                default: 'El reto de hoy'
            },
            sessions: {
                type: Array,
                required: true
            },
            abilities: {
                type: Array,
                required: true
            },
            video_teaser:null
        },
        mounted() {
            this.setDevice(window.innerWidth);
            window.addEventListener('resize', () => {
                this.setDevice(window.innerWidth);
            });
            if((this.isPortraitPhone() || this.isLandscapePhone()) && this.$parent.$refs.adventure_content) {
                this.$parent.$refs.adventure_content.setAttribute('hidden',true);
            }
        },
        methods: {
            setDevice(device_width) {
                this.devices.forEach((device)=>{
                    if(device_width >= device.min_width && device_width <= device.max_width) {
                        device.active = true;
                    }
                    else {
                        device.active = false;
                    }
                });
            },
            sessionSelect(index) {
                if(index < this.sessions.length)
                    this.session_selected = index;
            },
            nextSession() {
                if(this.session_selected < this.sessions.length)
                    this.sessionSelect(this.session_selected+1);
            },
            prevSession() {
                if (this.session_selected > 0)
                    this.sessionSelect(this.session_selected-1);
            },
            isActive(index) {
                return (index === this.session_selected);
            },
            isDesktop(){
                return (this.devices[0].active);
            },
            isTablet(){
                return this.devices[1].active;
            },
            isPhone() {
                if(this.isPortraitPhone() || this.isLandscapePhone()) {
                    return true;
                }
                else {
                    return false;
                }
            },
            isLandscapePhone(){
                return this.devices[2].active;
            },
            isPortraitPhone(){
                return this.devices[3].active;
            },

            //slick
            reInit() {
                // Helpful if you have to deal with v-for to update dynamic lists
                this.$nextTick(() => {
                    this.$refs.slick.reSlick();
                });
            },
        },
        data() {
            return {
                session_selected: 0,
                devices:[
                    {
                        label: 'desktop',
                        max_width: 4096,
                        min_width: 1366,
                        active: false
                    },
                    {
                        label: 'tablet',
                        max_width: 1365,
                        min_width: 768,
                        active: false
                    },
                    {
                        label: 'phone_landscape',
                        max_width: 767,
                        min_width: 576,
                        active: false
                    },
                    {
                        label: 'phone_portrait',
                        max_width: 576,
                        min_width: 320,
                        active: false
                    }
                ],
                slickOptions: {
                    slidesToShow: 1,
                    dots: true,
                    customPaging: function(slider, i) {
                        return '<a href="#" class="slick-dot-custom"><i class="far fa-dot-circle stand-by"></i></a>';
                    },
                    infinite: true,
                    speed: 300,
                    adaptiveHeight: true
                },
            }
        }
    }
</script>

<style scoped>
    .session-info .index {
        font-size: 1.3rem;
        font-weight: bold;
    }
    .session-info {
        box-shadow: -2px 2px 10px -5px rgba(0,0,0,0.75);
    }
    .session-info .title {
        font-size: 1rem;
        font-weight: bold;
    }
    .session-info .description {
        margin-top: 10px;
    }
    .session-description p {
        font-size: 14px;
    }
    .session-description {
        margin-bottom: 1rem;
        max-height: 150px;
        overflow-y: auto;
    }
    .abilities-container .ability {
        padding: 0 5px;
        margin: 2px;
        background-color: #0d71bb33;
        color: #0d71bb;
        text-align: center;
        border-radius: 2px;
    }
    .session-gallery .image img{
        width: 100%;
    }
    .session-text {
        position: absolute;
        padding: 10px;
        margin: 34px;
        width: 82vw;
        height: 71%;
        top: 0;
        background-color: white;
        margin-top: 40px;
    }
    .session-text .index {
        font-size: 12px;
        font-weight: bold;
    }
    .session-text .title {
        font-size:10px;
        font-weight: bold;
    }
    .session-text .description {
        font-size: 10px;
        max-height: 40px;
        overflow-y: hidden;
    }
    .session-info .ability {
        display: inline-block;
        padding: 0 3px;
        margin: 1px;
        font-size: 14px;
        text-align: center;
        border-radius: 5px;
        color: #0d71bb;
        background-color: #0d71bb33;
    }

    .section-selector-content .session-selector-image {
        width: 100%;
        height: 100px;
        overflow: hidden;
        -webkit-filter: grayscale(100%);
    }
    .section-selector-content .session-selector-image img{
        width: 100%;
        transform: translate(0%, -27%);
    }
    .section-selector-content .session-selector-number {
        position: absolute;
        width: 25px;
        height: 25px;
        background-color: #0f0f1080;
        color: #FFF;
        top: 33%;
        left: 44%;
        border-radius: 50%;
        padding: 3px 7px;
    }
    .active {
        border: #FFA900 solid 1px;
        -webkit-filter: grayscale(0%) !important;
    }
    .pointer {
        cursor: pointer;
    }
    .height-500 {
        min-height: 500px;
    }

    @media only screen and (min-width: 1024px) {
        .session-container {
            position: relative;
            display: inline-block;
        }
        .session-container .session-image {
            height: 56vh;
            overflow: hidden;
        }
        .session-container .session-image img{
            width: 100%;
            height: 100%;
        }
        .session-container .session-info {
            position: absolute;
            padding: 2rem;
            top: 20%;
            left: 0%;
            display: block;
            background-color: #FFF;
        }
        .session-selector-header {
            margin-top: 30px;
        }
    }
    @media only screen and (min-width: 700px) and (max-width: 1023px) {
        .session-container {
            height: 30vh;
            overflow: hidden;
        }
        .session-image {
            position: absolute;
            right: -3vw;
        }
        .session-container .session-info {
            position: absolute;
            top: calc(50% - 100px);
            background-color: #fff;
        }
        .session-info .card-info {
            margin: 10px 0px;
        }
        .session-description {
            max-height: 100px;
            overflow-y: auto;
        }
        .abilities-container {
            padding-top: 12px;
        }
        .section-selector-content .session-selector-image {
            width: 100%;
            height: 75px;
            overflow: hidden;
        }
        .section-selector-content .session-selector-image img{
            width: 100%;
            transform: translate(0%, -5%);
        }
        .section-selector-content .session-selector-number {
            position: absolute;
            width: 25px;
            height: 25px;
            background-color: #0f0f1080;
            color: #FFF;
            top: 30%;
            left: 40%;
            border-radius: 50%;
            padding: 3px 7px;
        }
    }

    @media only screen and (max-width: 414px) and (orientation: portrait){
        .session-image {
            transform: translate(-5vw, 0px);
        }
        .session-image img {
            max-width: 100vw;
        }
        .session-info {
            box-shadow: none;
        }
        .card-info {
            position: absolute;
            top: 35%;
            width: 90vw;
            padding: 15px;
            background-color: #fff;
        }
    }

    @media only screen and (max-width: 736px) and (orientation: landscape){
        .session-image {
            position: relative;
            right: 0;
        }
        .session-image img {
            max-width: 100%;
        }
        .session-info {
            box-shadow: none;
            z-index: 0;
        }
        .card-info {
            position: absolute;
            bottom: 20%;
            left: calc(50% - 35vw);
            width: 70vw;
            padding: 15px;
            background-color: #fff;
        }
    }

    /* ======= iphone X landscape worst resolution ======= */
    @media only screen and (width: 812px) and (orientation: landscape) {
        .session-container {
            height: 100vh;
        }
    }
</style>
