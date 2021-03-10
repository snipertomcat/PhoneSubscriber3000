<template>
    <video-player
            class="vjs-layout-medium"
            ref="videoPlayer"
            :options="playerOptions"
            :playsinline="true"
            @play="onPlayerPlay($event)"
            @pause="onPlayerPause($event)"
            @ended="onPlayerEnded($event)"
            @waiting="onPlayerWaiting($event)"
            @playing="onPlayerPlaying($event)"
            @loadeddata="onPlayerLoadeddata($event)"
            @timeupdate="onPlayerTimeupdate($event)"
            @canplay="onPlayerCanplay($event)"
            @canplaythrough="onPlayerCanplaythrough($event)"
            @statechanged="playerStateChanged($event)"
            @ready="playerReadied">
    </video-player>
</template>

<script>
    import 'video.js/dist/video-js.css'

    export default {
        name: "ApithyVideoPlayer",
        components: {
            'video-player': () => import('vue-video-player/src/player.vue')
        },
        beforeMount() {
            this.playerOptions.sources[0].src = this.videoSrc
            this.playerOptions.poster = this.poster
        },
        computed: {
            player() {
                return this.$refs.videoPlayer.player
            }
        },
        methods: {
            // listen event
            onPlayerPlay(player) {
                this.$emit('play', player)
                this.sendEnrollmentData('started')
            },
            onPlayerPause(player) {
                this.sendEnrollmentData('paused')
                this.$emit('pause', player)
            },
            // or listen state event
            onPlayerEnded(event) {
                if (event.isFullscreen()) {
                    event.exitFullscreen()
                }
                this.$emit('ended', event)
                //this.sendEnrollmentData('finished')
            },
            onPlayerWaiting(event) {
                this.$emit('waiting', event)
            },
            onPlayerPlaying(event) {
                this.$emit('playing', event)
            },
            onPlayerLoadeddata(event) {
                this.$emit('loadeddata', event)
            },
            onPlayerTimeupdate(event) {
                this.$emit('timeupdate', event)
            },
            onPlayerCanplay(event) {
                this.$emit('canplay', event)
            },
            onPlayerCanplaythrough(event) {
                this.$emit('canplaythrough', event)
            },
            playerStateChanged(event) {
                this.$emit('statechanged', event)
            },
            playerReadied(event) {
                this.$emit('ready', event)
                if (!event.isFullscreen() && this.isMobile().any() && this.allowFullscreen) {
                    event.requestFullscreen()
                }
            },
            sendEnrollmentData(event) {
                if (this.enrollment && this.activity) {
                    let server_url = "/student/progress";
                    let video_event = {
                        user: this.enrollment.user,
                        experience: this.enrollment.experience,
                        session: this.enrollment.session,
                        enrollment_progress: this.enrollment.enrollment_progress,
                        access_time: this.enrollment.access_time,
                        data_type: 'video_event',
                        data: event,
                        activity_info: this.activity
                    }

                    axios({
                        method: 'POST',
                        url: server_url,
                        params: test_data,
                    }).then((response) => {
                        //console.log(response);
                    }).catch((error) => {
                        //console.log(error);
                    });

                }

            },
            isMobile() {
                return {
                    Android() {
                        return navigator.userAgent.match(/Android/i);
                    },
                    BlackBerry() {
                        return navigator.userAgent.match(/BlackBerry/i);
                    },
                    iOS() {
                        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
                    },
                    Opera() {
                        return navigator.userAgent.match(/Opera Mini/i);
                    },
                    Windows() {
                        return navigator.userAgent.match(/IEMobile/i);
                    },
                    any() {
                        return (this.Android() || this.BlackBerry() || this.iOS() || this.Opera() || this.Windows());
                    }
                }
            }
        },
        props: {
            videoSrc: {
                required: true,
                type: String
            },
            poster: {
                type: String
            },
            /*
            *
            * let enrollment = {
                     user: 536,
                     experience: 79,
                     session: 223,
                     enrollment_progress: 2338,
                     access_time: 1579804883,
                   }
            * */
            enrollment: {
                required: false,
                default() {
                    return false
                }
            },
            /*
            *   activity_info: {
            *      id: 189,
            *      title: "Video Frenos"
            *   }
            */
            activity: {
                required: false,
                default() {
                    return false
                }
            },
            allowFullscreen: {
                type: Boolean,
                default() {
                    return true
                }
            }
        },
        data() {
            return {
                playerOptions: {
                    fluid: true,
                    muted: false,
                    language: 'es',
                    playbackRates: [0.7, 1.0, 1.5, 2.0],
                    sources: [{
                        type: "video/mp4",
                        src: ''
                    }]
                }
            }
        }
    }
</script>

<style>
    .vjs-big-play-button {
        top: calc(50% - 1.5rem) !important;
        left: calc(50% - 3rem) !important;
    }
</style>
