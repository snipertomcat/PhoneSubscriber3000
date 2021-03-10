<template>
    <section>
        <b-message :title="get_header" :type="get_type" :active.sync="has_message">
            {{ get_message }}
        </b-message>
    </section>
</template>

<script>
    export default {
        name: "FlashMessage",
        props: {
            type: {
                required: false,
                type: String,
                default: null
            },
            header: {
                required: false,
                type: String,
                default: null
            },
            message: {
                required: true,
                type: String
            }
        },
        computed: {
            has_message() {
                return !!this.message;
            },
            has_header() {
                return !!this.header;
            },
            has_type() {
                return !!this.type;
            },
            get_type() {
                return (this.has_type) ? 'is-' + this.type : null;
            },
            get_header() {
                return (this.has_header) ? this.header : null;
            },
            get_message() {
                return (this.has_message) ? this.message : null;
            }
        }
    }
</script>

<style scoped>

</style>