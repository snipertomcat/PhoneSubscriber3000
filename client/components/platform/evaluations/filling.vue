<template>
    <div class="pt-4">
        <div v-html="data.configurations.text"></div>
        <div class="mt-4 flex flex-wrap justify-center">
            <!-- Answer -->
            <div
                class="answer-badge my-2 mx-2 py-3 px-3"
                :class="[answer.selected ?  'border answer-label-checked': 'bg-yellow-lighter']"
                v-for="(answer, index) in data.answers"
                :key="index"
                @click="selectAnswer(answer)">
                <span v-html="answer.configurations.html">{{answer.configurations.html}}</span>
            </div>
        </div>
    </div>
</template>

<script>
    import {bus} from "../../../app_platform";

    export default {
        name: "apithy-filling-blanks",
        components: {},
        computed: {},
        methods: {
            getComponent(component_name) {
                return () => import(`./${component_name}.vue`);
            },
            createFillSlot(text){
                return text.replace(new RegExp("#_",'g'),"<div><input type='text'></div>")
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
    .answer-badge span p {
        margin-bottom: unset;
    }
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
