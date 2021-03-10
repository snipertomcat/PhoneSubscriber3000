<template>
    <div class="r-title">
        <div class="text-center" v-html="component.config.html">
            {{ component.config.html }}
        </div>
    </div>
</template>

<script>
    export default {
        name: "Title",
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