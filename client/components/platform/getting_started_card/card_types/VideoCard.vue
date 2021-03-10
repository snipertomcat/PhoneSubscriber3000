<template>
    <div class="video-card">
        <template v-if="hasTitle">
            <div class="title">
                <span>{{ card.title }}</span>
            </div>
        </template>
        <template v-if="hasVideo && positionTop">
            <div class="player">
                <ApithyVideoPlayer
                    :video-src="card.elements.video"
                    :poster="card.elements.video_poster"
                    :with-ready="false"
                    @play="openFullscreen"
                ></ApithyVideoPlayer>
            </div>
        </template>
        <template v-if="hasDescription">
            <div class="description" v-html="card.elements.text">
                {{ card.elements.text }}
            </div>
        </template>
        <template v-if="hasVideo && positionBottom">
            <div class="player">
                <ApithyVideoPlayer
                    :video-src="card.elements.video"
                    :poster="card.elements.video_poster"
                    :with-ready="false"
                    @play="openFullscreen"
                ></ApithyVideoPlayer>
            </div>
        </template>
    </div>
</template>

<script>
import { mutations } from "../../../../helpers/store";
import ApithyVideoPlayer from "../../../ApithyVideoPlayer";

export default {
    name: 'VideoCard',
    props: {
        dataCard: {
            required: true,
            default() {
                return {};
            }
        }
    },
    components: {
        ApithyVideoPlayer
    },
    computed: {
        card() {
            return this.dataCard;
        },
        isOnTop() {
            return this.$parent.index === mutations.getCurrentCard().index;
        },
        hasTitle() {
            return !!this.card.title;
        },
        hasDescription() {
            return !!this.card.elements.text;
        },
        hasVideo() {
            return !!this.card.elements.video;
        },
        positionTop() {
            return (
                !this.card.elements.video_position ||
                this.card.elements.video_position.toLowerCase() === 'top'
            );
        },
        positionBottom() {
            return this.card.elements.video_position.toLowerCase() === 'bottom';
        }
    },
    methods: {
        openFullscreen(player) {
            if (this.isOnTop && !player.isFullscreen()) {
                player.requestFullscreen();
                player.isFullscreen(true);
                document.querySelector('.vjs-big-play-button').style.display =
                    'none';
                document.querySelector('.vjs-control-bar').style.display =
                    'flex';
            }
        },
        showPlayButton(player) {
            if (player.isFullscreen()) {
                player.isFullscreen(false);
                player.requestFullscreen();
                player.exitFullscreen();
                document.querySelector('.vjs-big-play-button').style.display =
                    'block';
                document.querySelector('.vjs-control-bar').style.display =
                    'none';
            }
        }
    },
    data() {
        return {
            options: {
                autoplay: false,
                preload: false,
                controls: true,
                fluid: true
            }
        };
    }
};
</script>

<style scoped>
.title {
    font-style: normal;
    font-weight: bold;
    font-size: 16px;
    text-align: left;
    line-height: 20px;
}
.description {
    font-style: normal;
    font-weight: normal;
    text-align: left;
    font-size: 16px;
    line-height: 20px;
}
.player {
    margin-top: 16px;
    margin-bottom: 16px;
    box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
}
</style>
<style>
.video-js .vjs-big-play-button {
    border: none;
    background: #fd664a;
    border-radius: 50%;
    height: 50px;
    width: 50px;
    left: 50% !important;
    top: 50% !important;
    transform: translate(-50%, -50%);
}
</style>