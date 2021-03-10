<template>
    <div id="experience-detail" class="ui segment no-shadows no-borders sixteen wide tablet ten wide computer column centered">
        <h1 class="ui header">
            <div class="header">
                {{ experience.title }}
            </div>
        </h1>
        <button class="ui red labeled icon button right floated"
                @click="enrollUpdate"
                v-if="enrolled_button">
            <i class="ui calendar times icon"></i>
            Abandonar
        </button>
        <div class="extra content">
            <div class="description" v-html="experience.summary">
                {{ experience.summary }}
            </div>

            <div class="meta abilities">
                <!--@foreach($experience->abilities as $ability)-->
                <span class="ui label"v-for="(ability,index) in experience.abilities">{{ ability.title }}</span>
                <!--@endforeach-->
            </div>
        </div>

        <div class="ui segments">
            <div v-if="!experience.video_intro" class="ui segment">
                <div class="ui centered fluid rounded image">
                    <img v-lazy="experience.full_path_cover">
                </div>
            </div>

            <div v-if="experience.video_intro" class="ui segment">
                <div class="ui centered fluid">
                    <iframe v-bind:src="experience.video_intro" width="100%" height="560px" frameBorder="0"></iframe>
                </div>
            </div>

            <div class="ui segment">
                <div class="description" v-html="experience.description">
                    {{ experience.description }}
                </div>
            </div>
        </div>

        <apithy-experience-sessions
                :experience-type="experience.type"
                :sessions_list="experience.sessions"
                :adventures_list="experience.adventures">
        </apithy-experience-sessions>

    </div>
</template>
<script>
    import ExperienceSessions from './ExperienceSessions';
    export default {
        name: 'apithy-experience-view',
        components: {
            'apithy-experience-sessions': ExperienceSessions,
        },
        props: {
            mode: "",
            experience: "",
            auth_user: "",
            companies_data: "",
            enrollment_progress:""
        },
        beforeMount() {
            this.hasEnrolled();
        },
        methods: {
            showModalItem(session_id){
                    this.$modal.show('experience-item-'+session_id)
            },
            closeModalItem(session_id){
                this.$nextTick(() => {
                    this.$modal.hide('experience-item-' + session_id)
                })
            },
            enrollUpdate() {
                axios({
                    method: 'PUT',
                    url: route('experience.update.enroll',{experience:this.experience.system_id}),
                    params: {
                        user: this.auth_user.id,
                        status: 0
                    }
                }).then((response) => {
                    this.$modal.show('dialog', {
                        title: response.data.title,
                        text: response.data.message,
                        buttons: [
                            {
                                title: 'Close'
                            }
                        ]
                    });
                    this.enrolled_button = false;
                }).catch((error) => {
                    this.$modal.show('dialog', {
                        title: error.response.data.title,
                        text: error.response.data.message,
                        buttons: [
                            {
                                title: 'Close'
                            }
                        ]
                    });
                });
            },
            hasEnrolled() {
                if(this.auth_user.enrollments.length < 1) {
                    return false;
                }
                let v = false;
                this.auth_user.enrollments.some((experience,index)=>{
                    if (this.experience.id === experience.id && experience.pivot.status === 1) {
                        v = true;
                    }
                });
                this.enrolled_button = v;
            }
        },
        data() {
            return {
                visibility: ('visibility' in this.experience) ? (this.experience.visibility) : 1,
                companies: [],
                areas: [],
                positions: [],
                enrolled_button: false,
                access_time: Math.round(+new Date()/1000),
            }
        }
    }
</script>
