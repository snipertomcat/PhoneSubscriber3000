<template>
    <div class="">
        <div class="r-video">
            <div class="" oncontextmenu="return false;">
                <video-player :poster="cover"
                              :video-src="component.config.source"
                              ref="videoplayer"
                              disablePictureInPicture
                              controlsList="nodownload"
                              @pause="hasPause"
                              @waiting="hasWaiting"
                              @ended="hasEnded"
                              @timeupdate="isPlaying"
                              :allow-fullscreen="false">
                </video-player>
            </div>
        </div>
    </div>
</template>

<script>
    import VideoPlayer from '../../../../../ApithyVideoPlayer';
    export default {
        name: "Video",
        props: {
            componentData: { required: true }
        },
        components: { 'video-player':VideoPlayer },
        inject: ['getExperience','startTime'],
        computed: {
            component () { return this.componentData },
            experience() {return this.getExperience()},
            cover() {
                let company = this.getExperience();
                let cover = _.get(company, ['full_path_cover'], this.component.config.cover);
                return cover;
            }
        },
        methods: {
            hasEnded (event) {
                console.log("hasEnded",event);
            },
            hasWaiting (event) {
                console.log("hasWaiting",event);
            },
            hasPause(event) {
                console.log("hasPause",event);
            },
            isPlaying(event) {
                console.log("isPlaying");
                this.startTime();
            },
        }
    }
</script>

<style>
    .r-video img {
        max-height: 480px;
        width: 100%;
    }
    .r-video .video-js {
        max-height: 480px;
        width: 100%;
    }
</style>
