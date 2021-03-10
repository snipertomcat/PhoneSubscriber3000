<script>
    export default {
        props: {
            disable: { type: Boolean, required: true }
        },
        data () {
            return {
                toggle: true
            };
        },
        created () {
            this.toggle = !! this.disable;
        }
    }
</script>
