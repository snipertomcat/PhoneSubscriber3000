<template>
    <div class="experience-sessions-list-wrapper col-sm-12 col-md-6 col-lg-3">
        <div class="experience-sessions-list-launcher hidden">
            <span class="icon is-medium"><i class="fa fa-2x fa-youtube-play"></i></span>
        </div>
        <div class="experience-sessions-list row">
            <div class="col-sm-12 experience-sessions-list-container">
                <div class="experience-title-wrapper">
                    <div class="experience-back" v-if="!showAdventuresTitles" @click="showAdventures">
                        <span class="icon is-medium">
                            <i class="fas fa-arrow-left"></i>
                        </span>
                    </div>
                    <div class="experience-title">
                        {{ listTitle }}
                    </div>
                    <div class="experience-title-icon">
                        <span class="apithy-icon font-21 is-medium">
                            <i class="icon-air-balloon"></i>
                        </span>
                    </div>
                </div>
                <ul class="adventure sessions-list">
                    <li class="session-item"
                        v-for="(adventure,index) in adventures_list"
                        :key="index"
                        v-bind:id="'adventure_'+adventure.id"
                        v-bind:item="adventure"
                        :class="[adventure.active_class]">
                        <div v-if="showAdventuresTitles" class="session-item-title">
                            <span class="icon session-item-title-progress"><i
                                    class="fas"
                                    :class="[adventure.current_enrollment  ? adventure.current_enrollment.status_text_class :'']"></i></span>
                            <div class="session-item-title-text" @click="showSessions(index)">
                                {{ adventure.title }}
                            </div>
                            <span class="icon is-medium">
                                <i class="fas fa-chevron-right" @click="showSessions(index)"></i>
                            </span>
                            <div class="is-clearfix"></div>
                        </div>
                        <transition name="slide-fade">
                            <ul class="journey sessions-list" v-if="showAdventureList[index]">
                                <li class="session-item"
                                    v-for="(session,key) in adventure.sessions"
                                    :key="key"
                                    v-bind:id="'session_'+session.id"
                                    v-bind:item="session"
                                    @click="changeSession(session,key)"
                                    :class="[session.active_class]">
                                    <div class="session-item-title">
                            <span class="icon session-item-title-progress"><i
                                    class="fas"
                                    :class="[session.current_enrollment_progress  ? session.current_enrollment_progress.status_text_class :'']"></i></span>
                                        <div class="session-item-title-text">
                                            {{ session.title }}
                                        </div>
                                        <span class="icon session-item-title-status"><i class="fa fa-lg"></i></span>
                                        <div class="is-clearfix"></div>
                                    </div>
                                </li>
                            </ul>
                        </transition>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'apithy-experience-public-adventure-list',
        props: {
            experience:{},
            title: {
                type: String,
                required: true
            },
            experienceType: {
                type: String,
                required: true
            },
            sessions_list: {
                type: Array,
                default() {
                    return [];
                }
            },
            adventures_list: {
                type: Array,
                default() {
                    return [];
                }
            }
        },
        mounted() {
            $(".experience-title-icon").click(function () {
                $(".experience-sessions-list-launcher").show();
                $(".experience-sessions-list").fadeOut();
            });

            $(".experience-sessions-list-launcher").click(function () {
                $(".experience-sessions-list").fadeIn();
                $(this).fadeOut();
            });

            this.$nextTick(function () {
                setTimeout(function () {
                    $(".experience-title-icon").click();
                }, 3000);
            });

            if (this.journey) {
                this.loadSessions();
            }

        },
        computed: {
            adventure() {
                return (this.experienceType === 'adventure');
            },
            journey() {
                return (this.experienceType === 'journey');
            },
        },
        methods: {
            loadSessions() {
                _.each(this.adventures_list, adventure => {
                    _.each(adventure.sessions, session => {
                        this.sessions_list.push(session);
                    });
                });
            },
            translate(string) {
                switch (string) {
                    case 'adventure':
                        return 'Experiencia';
                    case 'journey':
                        return 'Viaje';
                    case 'session':
                        return 'Sesión';
                    default:
                        return string;
                }
            },
            changeSession(session, key) {
                let message = 'Necesitas ingresar o crear una cuenta para continuar.';
                let title = '¡Reto bloqueado!';
                let first_session = _.head(this.sessions_list);

                if (session.id !== first_session.id) {
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
                }
            },
            showSessions(key) {
                this.showAdventureList[key] = !this.showAdventureList[key];
                this.showAdventuresTitles = false;
                this.listTitle = this.adventures_list[key].title;
            },
            showAdventures() {
                this.showAdventureList = {
                    0: false,
                    1: false,
                    2: false
                };
                this.showAdventuresTitles = true;
                this.listTitle = this.title;
            },
            collapseList() {
                this.$nextTick(function () {
                    setTimeout(function () {
                        $(".experience-title-icon").click();
                    }, 3000);
                });
            }
        },
        data() {
            return {
                show_items: false,
                showAdventureList: {
                    0: false,
                    1: false,
                    2: false
                },
                showAdventuresTitles: true,
                listTitle: this.title
            }
        }
    }
</script>

<style scoped>

    .experience-sessions-list-launcher {
        width: 70px;
        height: 50px;
        background-color: orange;
        border-radius: 5px;
        padding: 11px 17px 0px;
        color: white;
        position: absolute;
        z-index: 1000;
        right: 5px;
        cursor: pointer;
    }

    .experience-sessions-list-wrapper {
        position: fixed;
        right: 0px;
        top: 80px;
        overflow: hidden;
        padding: 5px;
        min-height: 70px;
    }

    .experience-sessions-list .experience-title-wrapper {
        background-color: #FFA81E !important;
        padding: 10px 15px 5px;
        font-size: 1rem;
        color: white;
        font-weight: bold;
        border-radius: 5px 5px 0 0;
        -webkit-box-shadow: 0px 3px 5px 0px rgba(158, 158, 158, 1);
        -moz-box-shadow: 0px 3px 5px 0px rgba(158, 158, 158, 1);
        box-shadow: 0px 3px 5px 0px rgba(158, 158, 158, 1);
        position: relative;
        z-index: 10;
        overflow: hidden;
    }

    .experience-title, .experience-back {
        float: left;
    }

    .experience-title-icon{
        float: right;
    }

    .experience-title {
        width: 80%;
        padding-top: 5px;
    }

    .experience-title-icon, .experience-back {
        width: 10%;
        cursor: pointer
    }

    .experience-sessions-list ul, .adventure-title {
        background-color: #F0F0F0;
        z-index: 9;
        -webkit-box-shadow: 0px 3px 5px 0px rgba(158, 158, 158, 1);
        -moz-box-shadow: 0px 3px 5px 0px rgba(158, 158, 158, 1);
        box-shadow: 0px 3px 5px 0px rgba(158, 158, 158, 1);
    }

    .adventure-title {
        background-color: #F0F0F0;
        padding: 15px;
        font-weight: bold;
    }

    .session-item {
        cursor: pointer;
    }

    .session-item.is_current {
        background-color: gray;
        color: white;
    }

    .session-item-title {
        padding: 15px 20px;
        border-bottom: solid 1px gray;
    }

    .experience-sessions-list .session-item:hover {
        background-color: lightgray;
        font-weight: bold;
    }

    .session-item-title-progress, session-item-title-status, .session-item-title-text {
        float: left;
    }

    .session-item-title-progress, session-item-title-status {
        width: 5%;
    }

    .session-item-title-text {
        width: 85%;
        padding-left: 10px;
    }

    .fixed {
        position: fixed;
        top: 0;
    }

    .slide-fade-enter-active {
        transition: all .3s ease;
    }

    .slide-fade-enter
        /* .slide-fade-leave-active below version 2.1.8 */
    {
        transform: translateX(10px);
        opacity: 0;
    }

    .experience-sessions-list-wrapper .icon-air-balloon{
        font-size: 30px !important;
    }

    .experience-sessions-list-wrapper .fa-chevron-right{
        color:darkorange;
    }

    .experience-sessions-list-wrapper .fa-check-circle{
        color:forestgreen;
    }

</style>