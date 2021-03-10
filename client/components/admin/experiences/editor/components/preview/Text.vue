<template>
    <div class="r-text">
        <div class="" v-html="component.config.html">
            {{ component.config.html }}
        </div>
    </div>
</template>

<script>
    export default {
        name: "Text",
        props: {
            componentData: { required: true }
        },
        computed: {
            component () { return this.componentData }
        }
    }
</script>

<style scoped>

</style>