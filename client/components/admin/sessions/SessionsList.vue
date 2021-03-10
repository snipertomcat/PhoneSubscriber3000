<template>
    <div class="">
        <apithy-sessions-filter :experience="experience"></apithy-sessions-filter>
        <br>
        <div class="sessions-container offset-md-1" v-sortable="{ onEnd: sortableEnd }">
            <div class="session-item pointer" v-for="(session, index) in list_data.data" :id="'session_' + session.id">
                <div class="row">
                    <div class="col-12 col-md-11">
                        <div class="session-line">
                            <div class="line"></div>
                        </div>
                        <div class="card">
                            <div class="card-container">
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="row justify-content-center">
                                            <div class="col-12 col-md-6">
                                                <div class="session-image">
                                                    <img :src="session.full_path_cover">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6 font-14">
                                                <div class="session-title has-text-weight-bold">
                                                    <p class="truncate">
                                                        {{ session.title }}
                                                    </p>
                                                </div>
                                                <br>
                                                <div class="session-summary">
                                                    <p class="truncate-multiline">
                                                        {{ session.summary }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="row align-items-end height-100">
                                            <div class="col-12 col-md-4">
                                                <a class="button is-outlined is-link width-100">
                                                    <span class="icon">
                                                        <i class="fas fa-eye"></i>
                                                    </span>
                                                    <span>{{ $t('messages.show') }}</span>
                                                </a>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <a class="button is-outlined is-link width-100" :href="route('sessions.edit',[experience.system_id,session.system_id])">
                                                    <span class="icon">
                                                        <i class="fa fa-pencil"></i>
                                                    </span>
                                                    <span>{{ $t('messages.edit') }}</span>
                                                </a>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <a class="button is-outlined is-link width-100" v-if="(session.visibility)" @click="session.visibility = 0">
                                                    <span class="icon">
                                                        <i class="fas fa-toggle-off"></i>
                                                    </span>
                                                    <span>{{ $t('messages.disable') }}</span>
                                                </a>
                                                <a class="button is-outlined is-link width-100" v-else @click="session.visibility = 1">
                                                    <span class="icon">
                                                        <i class="fas fa-toggle-on"></i>
                                                    </span>
                                                    <span>{{ $t('messages.enable') }}</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="sessions-end has-text-centered" v-if="list_data.last_page === list_data.current_page">
                    <span>Fin</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import SessionFilter from './SessionsFilter';
    export default {
        name: "SessionsList",
        components: {
            'apithy-sessions-filter':SessionFilter,
        },
        props: {
            experience: {
                type: Object,
                required: true
            }
        },
        mounted() {
            this.getListData();
        },
        methods: {
            sortableEnd (event) {
                let session_list = {};
                event.to.childNodes.forEach(function (node, index) {
                    if (typeof  node.attributes != "undefined") {
                        console.log(index + ' : ' + node.attributes.id.nodeValue);
                        session_list[index] = node.attributes.id.nodeValue.replace('session_', '');
                    }
                });
                this.updateSession(session_list);
            },
            updateSession(session_list) {
                axios.post(`/experiences/${this.experience.id}/sort-sessions`, {'sessions_list': session_list})
                    .then(result => {
                        //console.log(result);
                    })
                    .catch(e => {
                        //console.log(e);
                    })
            },
            getListData() {
                axios({
                    method: 'GET',
                    url: route('sessions.index',[this.experience.system_id])
                })
                    .then(response => {
                        this.setListData(response.data);
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            setListData(data) {
                this.list_data = data;
            }
        },
        data() {
            return {
                list_data: []
            }
        }
    }
</script>

<style scoped>
    .card-container {
        padding: 10px;
    }
    .session-image {
        height: 120px;
        overflow: hidden;
        position: relative;
    }
    .session-image img {
        position:absolute;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        margin: auto;
        width: 100%;
    }
    .height-100 {
        height: 100%;
    }
    .session-line .line {
        position: absolute;
        left: 75px;
        width: 5px;
        height: 170px;
        background-color: #0d71bb;
    }
    .sessions-end {
        position: absolute;
        left: 140px;
        width: 100px;
        padding: 10px;
        background-color: #0d71bb;
        color: #FFF;
        border-radius: 25px;
    }
</style>