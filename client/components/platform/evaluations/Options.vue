<template>
    <div class="">
        <component v-bind:is="getComponent" :data="data" ref="answers"></component>
    </div>
</template>

<script>
    import {bus} from "../../../app_platform";

    export default {
        name: "apithy-option",
        components: {},
        computed: {
            getComponent() {
                if (this.data.component === 'SortImages') {
                    return () => import(`./${this.data.component}.vue`);
                }
                if (this.data.component === 'ClickAndDrop') {
                    return () => import(`./${this.data.component}.vue`);
                }
                return () => import(`./${this.data.type}.vue`);
            }
        },
        methods: {
            checkAnswer(){
                return this.$refs.answers.checkAnswer();
            },
            resetAnswer(){
                this.$refs.answers.resetAnswer();
            }
        },
        props: {
            data: {
                required: true,
                type: Object
            },
            user_data: {
                required: false,
                Type: Object
            }
        },
        data() {
            return {}
        }
    }
</script>

<style>
    .img-video {
        width: 274px;
        height: 164px;
    }

    .modal-background {
        background-color: rgba(2, 62, 137, 0.8) !important;
    }

    .m-close-background {
        background-color: transparent !important;
        margin-bottom: 5px;
    }

    .animation-content {
        z-index: 50
    }

    .scrollable {
        overflow: auto;
    }
    iframe {
        width: 350px;
        height: 200px;
    }
</style>
