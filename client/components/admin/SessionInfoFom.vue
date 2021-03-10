<script>
    import Datepicker from 'vuejs-datepicker';
    import ImageUpload from '../ImageUpload';
    import Asignator from './EntityAsignator';

    export default {
        name: 'apithy-sessions-info-form',
        components: {
            'Datepicker': Datepicker,
            'apithy-image-upload': ImageUpload,
            'apithy-entity-asignator':Asignator
        },
        props: {
            mode: "",
            experience: "",
            session: "",
            auth_user: "",
            companies_data:""
        },
        data() {
            return {
                visibility: ('visibility' in this.session) ? (this.session.visibility):1,
                companies:[],
                areas: [],
                positions:[]
            }
        }
    }
</script>
