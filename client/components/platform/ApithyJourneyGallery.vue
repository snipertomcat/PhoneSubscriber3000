<template>
    <div class="">
            <div class="row justify-content-center">
                <div class="col-md-10 pl-0 pr-0 pl-md-4 pr-md-4">
                    <div class="experiences_accordion" v-if="has_adventures">
                        <b-collapse class="accordion-item"
                                    animation="none"
                                    :key="key"
                                    :open.sync="experience.show"
                                    v-for="(experience, key) in adventures_list"
                                    @open="selectAdventure(key)">
                            <div class="item-header pl-4 pl-md-0" slot="trigger" :id="'experience_'+experience.id">
                                <div class="row align-items-center full-height">
                                    <div class="offset-md-2 col">
                                        <span class="mr-2">
                                            <i :class="{'fa': true, 'fa-angle-down':!experience.show, 'fa-angle-up':experience.show}"></i>
                                        </span>
                                        <span class="has-text-weight-bold">{{ experience.title }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="content item-content container">
                                <div class="row justify-content-center">
                                    <div class="col-md-10">
                                        <div class="pt-4 pb-4">
                                            <span class="one-half has-text-weight-bold">¿De qu&eacute; se trata?</span>
                                        </div>
                                        <div class="d-none d-md-block experience-description desktop">
                                            <p v-html="experience.description">{{experience.description}}</p>
                                        </div>
                                        <div class="d-block d-md-none experience-description mobile">
                                            <div class="description-container">
                                                <p v-html="experience.description">{{experience.description}}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-10 instructors mt-5" v-if="experience.has_instructors">
                                        <div class="mb-4 no-pl"><b>Instructores:</b></div>
                                        <div class="instructors-list">
                                            <div class="desktop-list">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3" v-for="(instructor, index) in experience.instructors">
                                                        <div class="row">
                                                            <div class="col-auto">
                                                                <figure class="image is-48x48 ml-0 mr-0">
                                                                    <img :src="instructor.full_path_avatar">
                                                                </figure>
                                                            </div>
                                                            <div class="col align-self-center no-pl">
                                                                <span class="">{{ instructor.full_name }}</span>
                                                                <span class="career_text">{{ instructor.career_title }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-4 mb-4">
                                        <div class="spacer"></div>
                                    </div>
                                    <div class="col-12">
                                        <div class="experience-info-gallery">
                                            <div class="col-sm-12 d-none d-sm-block">
                                                <span class="font-24 has-text-weight-bold">Retos en esta experiencia</span>
                                            </div>
                                            <div class="d-none d-md-block desktop-view">
                                                <div class="row justify-content-end session-container">
                                                    <div class="col-md-12 col-lg-8 session-image">
                                                        <img :src="session.full_path_cover" alt="">
                                                    </div>
                                                </div>
                                                <div class="session-container">
                                                    <div class="session-info width-75">
                                                        <div class="row justify-content-between">
                                                            <div class="col-md-2 col-lg-1 text-left">
                                                                <div class="pointer session-prev font-48" @click="prevSession">
                                                                    <i class="fas fa-angle-left"></i>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-8 col-lg-10">
                                                                <div>
                                                                    <span class="font-18 has-text-weight-bold">{{ session_selected+1 }}.</span><br>
                                                                    <span class="font-18 has-text-weight-bold">{{session.title }}</span>
                                                                </div>

                                                                <div class="">
                                                                    <div class="session-description font-14">
                                                                        <p v-html="session.summary">
                                                                            {{ session.summary }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2 col-lg-1 text-right">
                                                                <div class="pointer session-next font-48" @click="nextSession">
                                                                    <i class="fas fa-angle-right"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-4" v-if="abilities.length || sessions[this.session_selected].taxonomy_ability.length">
                                                            <div class="col-12 text-left">
                                                                <span class="font-16"><b>Aprenderás</b></span>
                                                            </div>
                                                            <div class="col-12 mt-2">
                                                                <div class="abilities-container" v-if="session.taxonomy_ability.length">
                                                                    <div class="ability" v-for="(ability, index) in session.taxonomy_ability">{{ ability.title }}</div>
                                                                </div>
                                                                <div class="abilities-container" v-else>
                                                                    <div class="ability" v-for="(ability, index) in abilities">{{ ability.title }}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-block d-md-none mobile-view">
                                                <div class="row justify-content-between">
                                                    <div class="col-auto">
                                                        <a class="button is-primary is-inverted pl-0 pr-0" @click="prevSession">
                            <span class="icon">
                                <i class="fas fa-angle-left"></i>
                            </span>
                                                            <span>Anterior</span>
                                                        </a>
                                                    </div>
                                                    <div class="col-auto">
                                                        <span>{{session_selected+1}}/{{sessions.length}}</span>
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

                                                <div class="row justify-content-end session-container">
                                                    <div class="col-12 session-image">
                                                        <img :src="session.full_path_cover" alt="">
                                                    </div>
                                                </div>
                                                <div class="session-container">
                                                    <div class="session-info">
                                                        <div class="mobile-info">
                                                            <div class="row justify-content-between">
                                                                <div class="col-12">
                                                                    <div>
                                                                        <span class="font-18 has-text-weight-bold">{{ session_selected+1 }}.</span><br>
                                                                        <span class="font-18 has-text-weight-bold">{{session.title }}</span>
                                                                    </div>

                                                                    <div class="">
                                                                        <div class="session-description">
                                                                            <p v-html="session.summary">
                                                                                {{ session.summary }}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row mt-4" v-if="abilities.length || sessions[this.session_selected].taxonomy_ability.length">
                                                                <div class="col-12 text-left">
                                                                    <span class="font-18"><b>Aprenderás</b></span>
                                                                </div>
                                                                <div class="col-12 mt-2">
                                                                    <div class="abilities-container" v-if="session.taxonomy_ability.length">
                                                                        <div class="ability" v-for="(ability, index) in session.taxonomy_ability">{{ ability.title }}</div>
                                                                    </div>
                                                                    <div class="abilities-container" v-else>
                                                                        <div class="ability" v-for="(ability, index) in abilities">{{ ability.title }}</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
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
                                            <br><br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </b-collapse>
                    </div>
                </div>
            </div>
    </div>
</template>

<script>
    import ApithySessionGallery from './ApithySessionsGallery';
    export default {
        name: "ApithyJourneyGallery",
        components: { 'apithy-session-gallery': ApithySessionGallery },
        computed: {
            has_adventures:  function () {
                return !_.isEmpty(this.adventures_list)
            },
            session: function () {
                return this.adventures_list[this.adventure_selected].sessions[this.session_selected]
            },
            sessions: function () {
                return this.adventures_list[this.adventure_selected].sessions
            }
        },
        props: {
            title: {
                type: String,
                default: ''
            },
            adventures: {
                type: Array,
                required: true,
            },
            abilities: {
                type: Array,
                required: true,
            }
        },
        data() {
            return {
                adventures_list: [],
                adventure_selected: 0,
                session_selected:0,
                session_abilities: [],
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
            }
        },
        mounted() {
            this.mountAdventures();
            this.setDevice(window.innerWidth);
            window.addEventListener('resize', () => {
                this.setDevice(window.innerWidth);
            });
            this.session_abilities = this.adventures[this.adventure_selected].sessions[this.session_selected].taxonomy_ability;
        },
        methods: {
            selectAdventure(key) {
                let adventure = _.find(this.adventures_list, (adventure, index) => {
                    return key === index;
                });
                this.adventure_selected = key;
                this.session_selected = 0;
                this.closeOther(adventure.id);
            },
            closeOther(id) {
                _.each(this.adventures_list, experience => {
                    if (experience.id !== id) {
                        experience.show = false;
                    }
                })
            },
            mountAdventures() {
                let temp_array = _.each(this.adventures, experience => {
                    experience.show = false;
                    experience.has_instructors = !_.isEmpty(experience.instructors);
                    experience.has_sessions = !_.isEmpty(experience.sessions);
                });
                this.adventures_list = temp_array;
            },
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
                if(index < this.adventures[this.adventure_selected].sessions.length)
                    this.session_selected = index;
            },
            nextSession() {
                if(this.session_selected < this.adventures[this.adventure_selected].sessions.length)
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
            changeAdventure(selected = false){
                this.session_selected = 0;
                //this.$refs.slick.goTo(0);
                this.session_abilities = this.adventures[this.adventure_selected].sessions[this.session_selected].taxonomy_ability;
            }
        },
    }
</script>

<style scoped>
    .experiences_accordion .accordion-item .item-header{
        height: 40px;
        color: #fff;
        background-color: #152B53;
        border-bottom: solid 1px #fff;
    }

    .experience-info-gallery .ability {
        font-size: 12px !important;
    }
    .experience-info-gallery .session-image {
        transform: none !important;
    }
    .experience-info-gallery .session-image img {
        max-width: 100% !important;
    }
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
        height: 200px;
        overflow: hidden;
    }
    .header-container .desktop-header .experience-title {
        padding-left: calc(100vw - 72%);
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
    .pointer {
        cursor: pointer;
    }

    .adventure-content li {
        margin-bottom: 10px;
        text-transform: lowercase;
    }

    .experience-description.mobile .description-container {
        max-height: 100%;
        overflow-y: visible;
    }

    .desktop-view .session-container .session-info {
        position: absolute;
        top: 20%;
        padding: 1rem;
        background-color: #fff;
        box-shadow: -2px 2px 10px -5px rgba(0,0,0,0.75);
    }
    .mobile-view .session-container .session-info {
        height: 200px;
    }
    .mobile-view .session-container .session-info .mobile-info {
        position: absolute;
        bottom: 20%;
        width: 80%;
        margin: 0 5%;
        padding: 1rem;
        background-color: #fff;
    }
    .abilities-container {
        display: flex;
    }
    .mobile-view .abilities-container .ability {
        padding: 1px;
    }
    .desktop-view .abilities-container .ability {
        padding: 0 3px;
    }
    .abilities-container .ability {
        display: inline-block;;
        margin: 0 1px;
        font-size: 10px;
        text-align: center;
        border-radius: 5px;
        color: #0d71bb;
        background-color: #0d71bb33;
    }

    .image-profile {
        /*margin: 5px 12px 5px 5px;*/
        width: 100px;
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

    @media only screen and (max-width: 768px) and (orientation: portrait) {
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

    @media only screen and (max-width: 800px) and (min-width: 700px) and (orientation: portrait) {
        .desktop-view .session-container .session-info {
            left: 70px;
        }
    }

    @media only screen and (width: 812px) and (orientation: landscape) {
        .desktop-view .session-container .session-info {
            top: 20%;
            left: 70px;
        }
    }

    @media only screen and (min-width: 1080px) {
        .desktop-view .session-container .session-info {
            top: 33%;
        }
    }

</style>