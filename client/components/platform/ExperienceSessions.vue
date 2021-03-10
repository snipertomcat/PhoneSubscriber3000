<template>
    <div>
        <h3>{{ componentTitle }}</h3>
        <div v-if="show_items">
            <div class="row">
                <div class="sixteen wide column">
                    <div class="ui feed timeline" v-if="adventure"> <!--v-sortable="{onEnd: sortableEnd}"-->
                        <div class="event"
                             v-for="(session,key) in sessions_list"
                             :key="key"
                             :id="'session_'+session.id">
                            <div class="label">
                                <img v-lazy="session.full_path_cover" preLoad alt="label-image"/>
                            </div>
                            <div class="content ui segment"  @click="$parent.showModalItem(session.id)">
                                <div class="summary">
                                    {{ translate('session')+': '+session.title }}
                                    <div class="date" v-html="session.summary">
                                        {{ session.summary }}
                                    </div>
                                    <div v-if="session.current_enrollment_progress">
                                            <span class="ui label"
                                                  :class="[session.current_enrollment_progress.status_text_color]">
                                                {{session.current_enrollment_progress.status_text}}
                                            </span>
                                        <span v-if="session.current_enrollment_progress.stats"
                                              v-for="stat in session.current_enrollment_progress.stats"
                                              class="ui label gray">
                                            <strong>{{ stat.verb  | capitalize }}</strong> : {{stat.total}}
                                        </span>
                                    </div>
                                </div>
                                <div v-if="session.current_enrollment_progress">
                                    <modal height="90%"
                                           width="75%"
                                           :click-to-close="false"
                                           :name="'experience-item-'+session.id"
                                           v-if="session.current_enrollment_progress.status>0">
                                        <div class="iframe-experience-wrapper ui segments">
                                            <div class="iframe-experience-header ui segment">
                                                <img src="/images/apithy_small.png" width="30">
                                                <span class="iframe-experience-header-title">
                                                    {{session.title}}
                                                </span>
                                                <button class="ui red icon button right floated close-modal"
                                                        @click="$parent.closeModalItem(session.id)">
                                                    <i class="ui cancel icon"></i>
                                                </button>
                                            </div>
                                            <div class="iframe-experience-content ui segment">
                                                <iframe class="iframe-experience-item"
                                                        :src="session.resource_url+'?track_events=true&env='+this.apithy_constants.ENV
                                                            +'&experience='+$parent.experience.id
                                                            +'&user='+$parent.auth_user.id
                                                            +'&session='+session.id
                                                            +'&access_time='+$parent.access_time
                                                            +(session.current_enrollment_progress  ? '&enrollment_progress='+session.current_enrollment_progress.id:'')"
                                                        width="99%" height="800px" frameBorder="0" >
                                                </iframe>
                                            </div>
                                        </div>
                                    </modal>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ui styled fluid accordion" v-if="journey">
                        <div v-for="(adventure, key) in adventures_list" :key="key">
                            <div class="title">
                                <i class="dropdown icon"></i>
                                {{ translate('adventure')+': '+adventure.title }}
                            </div>
                            <div class="content">
                                <div class="ui grid">
                                    <div class="four wide">
                                        <img class="ui small circular image" v-lazy="adventure.full_path_cover" preLoad>
                                    </div>
                                    <div class="ten wide column">
                                        <p>{{ adventure.summary }}</p>
                                        <br>
                                        <span class="ui label"v-for="(ability,index) in adventure.abilities">{{ ability.title }}</span>
                                    </div>
                                    <div class="three wide column">
                                        <a :href="route('experience.show.enrollment', {experience: adventure.system_id, enrollment_id: adventure.enrollment_id})">
                                            <button class="ui green labeled icon button">
                                                <i class="paper plane icon"></i>
                                                Ir a la experiencia
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'apithy-experience-sessions',
        props: {
            experienceType: {
                type: String,
                required: true
            },
            sessions_list: {
                type: Array,
                default() {return [];}
            },
            adventures_list: {
                type: Array,
                default() {return [];}
            }
        },
        computed: {
            componentTitle() {
                let title;
                switch (this.experienceType) {
                    case 'adventure':
                        title = 'Sesiones';
                        this.show_items = true;
                        break;
                    case 'journey':
                        title = 'Experiencias';
                        this.show_items = true;
                        break;
                    default:
                        title = 'No se encontró información que mostrar';
                        this.show_items = false;
                        break;
                }
                return title;
            },
            adventure() {
                return (this.experienceType === 'adventure');
            },
            journey() {
                return (this.experienceType === 'journey');
            }
        },
        methods: {
            sortableEnd(event) {
                console.log(event);
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
            translate(string) {
                switch (string) {
                    case 'adventure':
                        return 'Aventura';
                    case 'journey':
                        return 'Viaje';
                    case 'session':
                        return 'Sesión';
                    default:
                        return string;
                }
            }
        },
        data() {
            return {
                show_items: false,
            }
        }
    }
</script>

<style scoped>

</style>