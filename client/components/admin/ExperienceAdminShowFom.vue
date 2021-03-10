<script>
    import ExperienceEnrollmentList from './ExperienceEnrollmentsList';
    import ExperienceAssignationList from './ApithyExperienceAssignationList';
    import ExperienceAssignationDetails from './ApithyExperienceAssignationDetails';
    import ExperienceAssignationForm from './ApithyExperienceAssignationForm';

    export default {
        name: 'apithy-experiences-show-form',
        props: {
            mode: "",
            experience: "",
            auth_user: "",
            companies_data: ""
        },
        components: {
            'apithy-experience-enrollment-list': ExperienceEnrollmentList,
            'apithy-experience-assignation-list': ExperienceAssignationList,
            'apithy-experience-assignation-details': ExperienceAssignationDetails,
            'apithy-experience-assignation-form': ExperienceAssignationForm,
        },
        data() {
            return {
                visibility: ('visibility' in this.experience) ? (this.experience.visibility) : 1,
                companies: [],
                areas: [],
                positions: [],
                assignation_id: '',
                tags: []
            }
        },
        mounted() {
            if(this.experience.tags.length > 0) {
                this.tags = this.experience.tags.map(function(tag) {
                    tag.text = tag.title;
                    tag.tiClases = ["valid"];
                    return tag;
                });
            }
        },
        methods: {
            sortableEnd(event) {
                //console.log(event);
                let session_list = {};
                event.to.childNodes.forEach(function (node, index) {
                    if (typeof  node.attributes != "undefined") {
                        console.log(index + ' : ' + node.attributes.id.nodeValue);
                        session_list[index] = node.attributes.id.nodeValue.replace('session_', '');
                    }
                });

                //console.log(session_list);
                //let session_id=event.item.attributes.id.nodeValue.replace('session_','');
                this.updateSession(session_list);
            },
            updateSession(session_list) {
                console.log(this.experience);
                axios.post(`/experiences/${this.experience.id}/sort-sessions`, {'sessions_list': session_list})
                    .then(result => {
                        //console.log(result);
                    })
                    .catch(e => {
                        //console.log(e);
                    })
            },
            showModalItem(session_id) {
                this.$modal.show('experience-item-' + session_id);
            },
            closeModalItem(session_id) {
                this.$nextTick(() => {
                    this.$modal.hide('experience-item-' + session_id)
                })
            },
            opened(){

            }
        }
    }
</script>

<style scoped>
    .tags {
        margin: 5px 0;
    }
    .tags .tag {
        background-color: #0d71bb;
        color: white;
        padding: 5px;
        border-radius: 5px;
        margin-right: 5px;
    }
</style>
