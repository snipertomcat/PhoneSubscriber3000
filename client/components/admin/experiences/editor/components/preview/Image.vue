<template>
    <div class="r-image">
        <img :src="this.component.config.source" :width="image_width" :alt="this.component.config.name">
    </div>
</template>

<script>
    export default {
        name: "Image",
        props: {
            componentData: { required: true }
        },
        computed: {
            component () { return this.componentData },
            image_width () { return `${this.component.config.width}%`}
        }
    }
</script>
