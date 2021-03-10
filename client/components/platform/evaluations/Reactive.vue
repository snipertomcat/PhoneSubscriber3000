<template>
    <div class="py-4">
        <div class="" v-html="html">{{ html }}</div>
        <apithy-option :data="data" ref="option"></apithy-option>
    </div>
</template>

<script>
    export default {
        name: "apithy-reactive",
        components: {
            'apithy-option': () => import('./Options.vue')
        },
        provide() {
            return {
                selectAnswer: this.selectAnswer
            }
        },
        computed: {
            html() {
                return this.data.configurations.html
            }
        },
        methods: {
            selectAnswer(answer) {
                this.$parent.setAnswer(answer)
                this.reload();
            },
            reload() {
                this.loaded = false;
                setTimeout(() => {
                    this.loaded = true;
                }, 1)
            },
            getUserAnswers() {
                return this.$refs.option.$refs.answers.getUserAnswers();
            },
            checkAnswer(){
                return this.$refs.option.checkAnswer();
            },
            resetAnswer() {
                this.$refs.option.resetAnswer()
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
            return {
                loaded: true,
            }
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
</style>
