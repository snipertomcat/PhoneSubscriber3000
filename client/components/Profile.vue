<script>
    import Datepicker from 'vuejs-datepicker';

    export default {
        components: {
            Datepicker
        },
        data () {
            return {};
        },
        created () {}
    }
</script>

<style>

input{
    width: 100%;
    border: 1px solid;
    border-color: #dbdbdb; 
    border-radius: 3px;
    font-size: 1rem;
    height: 2.25em;
    padding-bottom: calc(0.375em - 1px);
    padding-left: calc(0.625em - 1px);
    padding-right: calc(0.625em - 1px);
    padding-top: calc(0.375em - 1px);
}
    
input:focus, input.is-focused, input:active {
    border-color: #7957d5;
}
</style>
