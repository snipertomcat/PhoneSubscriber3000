<template>
    <div class="r-external-resource">
        <iframe :src="resource_url" frameborder="0"></iframe>
    </div>
</template>

<script>
export default {
    name: "ExternalResource",
    props: {
        componentData: {
            required: true,
            type: Object
        }
    },
    computed: {
        resource_url() {
            return this.component.config.source;
        }
    },
    methods: {},
    data() {
        return {
            component: this.componentData
        }
    }
}
</script>

<style lang="scss" scoped>
    .r-external-resource {
            height: 100%;
        iframe {
            width: 100%;
            height: 100%;
            overflow-y: auto;
        }
    }
</style>