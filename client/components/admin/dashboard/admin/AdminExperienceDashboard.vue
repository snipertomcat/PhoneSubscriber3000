<template>
    <div class="ml-3 pl-md-5 pl-lg-0 mt-4 mb-5 mr-3">
        <div class="container pb-5">
            <div class="row ml-0 mr-0 no-gutters" v-if="selection_empty">
                <div class="col-auto">
                <span class="indication">
                    Elige una experiencia o un usuario para ver sus datos
                </span>
                </div>
            </div>

            <!-- Filters -->
            <div class="row ml-0 mr-0 mt-4 no-gutters">
                <div class="col-12 col-lg-6 pr-lg-3">
                    <div class="row mr-0 ml-0 no-gutters">
                        <div class="col">
                            <span class="has-text-weight-bold">Filtrar experiencia</span>
                        </div>
                    </div> <!-- experiences -->
                    <template v-if="is_experience_selected || is_both_selected">
                        <div class="row mr-0 ml-0 no-gutters mt-2">
                            <div class="col-12 col-lg-10 experience-item selected">
                                <div class="row ml-0 mr-0 align-items-center full-height">
                                    <div class="col-auto mr-3">
                                        <img :src="experience_selected.full_path_cover" alt="" class="image">
                                    </div>
                                    <div class="col">
                                    <span class="font-18">
                                        {{ experience_selected.title }}
                                    </span>
                                    </div>
                                    <div class="col-auto">
                                    <span class="pointer" @click="clear('experience')">
                                        <i class="fa fa-times"></i>
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template v-else>
                        <div class="row mr-0 ml-0 no-gutters mt-2">
                            <div class="col">
                                <el-select placeholder="Buscar experiencia"
                                           default-first-option
                                           autocomplete
                                           filterable
                                           remote
                                           v-model="experiences_users.experiences.search"
                                           :remote-method="filterExperience"
                                           :class="{'full-width': true}"
                                           @change="selectExperience">
                                    <i slot="prefix" class="el-input__icon fas fa-search"></i>
                                    <el-option v-for="(item, key) in experiences_users.experiences.filtered_list" :key="key" :value="item.title">
                                        <span>{{ item.title }}</span>
                                    </el-option>
                                </el-select>
                            </div>
                        </div>
                    </template>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="row mr-0 ml-0 no-gutters mt-3 mt-lg-0">
                        <div class="col">
                            <span class="has-text-weight-bold">Filtrar usuario</span>
                        </div>
                    </div> <!-- users -->
                    <template v-if="is_user_selected || is_both_selected">
                        <div class="row mr-0 ml-0 no-gutters mt-2">
                            <div class="col-12 col-lg-10 user-item selected">
                                <div class="row ml-0 mr-0 align-items-center full-height">
                                    <div class="col-auto mr-3">
                                        <img :src="user_selected.full_path_avatar" alt="" class="image">
                                    </div>
                                    <div class="col">
                                    <span class="font-18">
                                        {{ user_selected.full_name }}
                                    </span>
                                    </div>
                                    <div class="col-auto">
                                    <span class="pointer" @click="clear('user')">
                                        <i class="fa fa-times"></i>
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template v-else>
                        <div class="row mr-0 ml-0 no-gutters mt-2">
                            <div class="col">
                                <el-select placeholder="Buscar usuario"
                                           default-first-option
                                           autocomplete
                                           filterable
                                           remote
                                           v-model="experiences_users.users.search"
                                           :remote-method="filterUser"
                                           :class="{'full-width': true}"
                                           @change="selectUser">
                                    <i slot="prefix" class="el-input__icon fas fa-search"></i>
                                    <el-option v-for="(item, key) in experiences_users.users.filtered_list" :key="key" :value="item.full_name">
                                        <span>{{ item.full_name }}</span>
                                    </el-option>
                                </el-select>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div class="row mr-0 ml-0 no-gutters mt-4 ">
                <div class="col-12 separator" style="border-bottom:2px solid lightgray;"></div>
            </div>

            <template v-if="selection_empty">
                <div class="row ml-0 mr-0 mt-4 mb-4 no-gutters">
                    <div class="col-12 col-lg-6 pr-lg-3">
                        <div class="row mr-0 ml-0 no-gutters">
                            <div class="col">
                                <div class="card">
                                    <div class="card-content p-2">
                                        <div class="experience-container">
                                            <div class="experience-item pointer" v-for="(experience, key) in experiences_users.experiences.list" @click="select(experience, 'experience')">
                                                <div class="row mr-0 ml-0 align-items-center full-height" :class="{'ari-background-gray':key%2}">
                                                    <div class="col-auto">
                                                        <img class="image" :src="experience.full_path_cover" alt="">
                                                    </div>
                                                    <div class="col">
                                                        {{experience.title}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mr-0 ml-0 mt-4 no-gutters justify-content-between">
                            <div class="col-12 col-md-6 justify-content-between has-text-centered">
                                <div class="row has-text-link has-text-weight-bold">
                                    <div class="col-lg-12">
                                        <el-pagination
                                            :key="0"
                                            class="element-paginator-text-16"
                                            @current-change="onPageChange($event, {parent: 'experiences_users', target: 'experiences', server_side: true})"
                                            :hide-on-single-page="false"
                                            :current-page.sync="experiences_users.experiences.current_page"
                                            layout="prev, pager, next"
                                            :page-count="experiences_users.experiences.last_page">
                                        </el-pagination>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 has-text-centered">
                                <div class="row justify-content-end mr-0 ml-0 no-gutters">
                                    <div class="col-auto">
                                        <b-select v-model="experiences_users.experiences.per_page"
                                                  @input="setPageSize($event, {parent: 'experiences_users', target: 'experiences', server_side: true})">
                                            <option value="3">
                                                Ver 3
                                            </option>
                                            <option value="5">
                                                Ver 5
                                            </option>
                                            <option value="10">
                                                Ver 10
                                            </option>
                                            <option value="15">
                                                Ver 15
                                            </option>
                                        </b-select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="row mr-0 ml-0 no-gutters">
                            <div class="col">
                                <div class="card">
                                    <div class="card-content p-2">
                                        <div class="user-container">
                                            <div class="user-item pointer" v-for="(user, key) in experiences_users.users.list" @click="select(user, 'user')">
                                                <div class="row mr-0 ml-0 align-items-center full-height" :class="{'ari-background-gray':key%2}">
                                                    <div class="col-auto">
                                                        <img class="image" :src="user.full_path_avatar" alt="">
                                                    </div>
                                                    <div class="col">
                                                        {{user.full_name}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mr-0 ml-0 mt-4 no-gutters justify-content-between">
                            <div class="col-12 col-md-6 justify-content-between has-text-centered">
                                <div class="row has-text-link has-text-weight-bold">
                                    <div class="col-lg-12">
                                        <el-pagination
                                                :key="1"
                                                class="element-paginator-text-16"
                                                @current-change="onPageChange($event, {parent: 'experiences_users', target: 'users', server_side: true})"
                                                :hide-on-single-page="false"
                                                :current-page.sync="experiences_users.users.current_page"
                                                layout="prev, pager, next"
                                                :page-count="experiences_users.users.last_page">
                                        </el-pagination>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 has-text-centered">
                                <div class="row justify-content-end mr-0 ml-0 no-gutters">
                                    <div class="col-auto">
                                        <b-select v-model="experiences_users.users.per_page"
                                                  @input="setPageSize($event, {parent: 'experiences_users', target: 'users', server_side: true})">
                                            <option value="3">
                                                Ver 3
                                            </option>
                                            <option value="5">
                                                Ver 5
                                            </option>
                                            <option value="10">
                                                Ver 10
                                            </option>
                                            <option value="15">
                                                Ver 15
                                            </option>
                                        </b-select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <template v-if="is_user_selected">
                <user-stats ref="user_stats"></user-stats>
            </template>

            <template v-if="is_experience_selected">
                <experience-stats ref="experience_stats"></experience-stats>
            </template>

            <template v-if="is_both_selected">
                <div class="row mr-0 ml-0 mt-4 no-gutters">
                    <div class="col-12 col-lg-6 pr-lg-3">
                        <enrollment-user-details :experience-id.sync="experienceId" :user-id.sync="userId"></enrollment-user-details>
                    </div>
                    <div class="col-12 col-lg-6 mt-3 mt-lg-0">
                        <user-experience-recurrence :experience-id.sync="experienceId" :user-id.sync="userId"></user-experience-recurrence>
                    </div>
                </div>
                <user-experience-session-activities :experience-id.sync="experienceId" :user-id.sync="userId"></user-experience-session-activities>
            </template>
        </div>
    </div>
</template>

<script>
    import { Input, Select, Option, Pagination } from 'element-ui';
    import { bus } from "../../../../app_platform";
    import { index } from "../../../../helpers";
    import StatsByExperience from './AdminExperienceStats';
    import StatsByUser from './AdminStatsByUser';
    import Qs from 'qs';

    export default {
        name: "AdminExperienceDashboard",
        components: {
            'user-experience-session-activities': () => import('./admin-components/UserExperienceSessionActivities'),
            'user-experience-recurrence': () => import('./admin-components/UserExperienceRecurrence'),
            'enrollment-user-details': () => import('./admin-components/EnrollmentUserDetails'),
            'el-input': Input,
            'el-select': Select,
            'el-option': Option,
            'user-stats': StatsByUser,
            'experience-stats': StatsByExperience,
            'el-pagination': Pagination
        },
        watch: {
            'is_experience_selected': function (value) {
                if (value) {
                    setTimeout(()=>{
                        this.$refs.experience_stats.setExperience(this.experience_selected)
                    },500)
                }
            }
        },
        computed: {
            has_enrollment: function () {
                return !_.isEmpty(this.both.enrollment)
            },
            has_valid_enrollment: function () {
                return !_.isEmpty(this.both.enrollment.started_at)
            },
            is_user_selected: function () {
                return (!_.isEmpty(this.user_selected) && _.isEmpty(this.experience_selected))
            },
            is_experience_selected: function () {
                return (!_.isEmpty(this.experience_selected) && _.isEmpty(this.user_selected))
            },
            is_both_selected: function () {
                return (!_.isEmpty(this.user_selected) && !_.isEmpty(this.experience_selected))
            },
            selection_empty: function () {
                return (_.isEmpty(this.user_selected) && _.isEmpty(this.experience_selected))
            },
            table_has_data: function () {
                return !_.isEmpty(this.experience.enrolled_users.items)
            },
            userId () {
                return _.get(this.user_selected, ['id'], 0)
            },
            experienceId () {
                return _.get(this.experience_selected, ['id'], 0)
            }
        },
        mounted: function () {
            bus.$on('user_enrollments', enrollments => {
                this.experiences_users.experiences.list = enrollments
            })

            let url = new URL (window.location.href)
            let experience_param = url.searchParams.get('exp_item')
            let user_param = url.searchParams.get('user_item')

            if (!_.isNull(experience_param)) {
                this.find(experience_param, 'experience')
            }

            if (!_.isNull(user_param)) {
                this.find(user_param, 'user')
            }

            if (_.isNull(experience_param)) {
                this.send()
            }
        },
        methods: {
            hasStartDate: function (item) {
                if ('started_at' in item && item.started_at !== null)
                    return true;
                if (item.started_at === null && item.finished_at !== null)
                    return true;
                return false;
            },
            hasFinishDate: function (item) {
                return ('finished_at' in item && item.finished_at !== null);
            },
            startDate: function (item) {
                return (!_.isNull(item.started_at) || !_.isEmpty(item.started_at)) ? item.started_at : item.created_at;
            },
            finishDate: function (item) {
                return item.finished_at;
            },
            parse: function (time, type = 'date', time_scale = 'minutes') {
                return index.parse(time, type, time_scale, this)
            },
            getClass: function (performance, is_scoreable = true) {
                if (!is_scoreable) {
                    return 'text-gray-default'
                }
                let clase = ''
                performance= performance * 100

                switch (true) {
                    case (performance >= 90):
                        clase = 'has-text-ari-green';
                        break;
                    case (performance >= 70 && performance < 90):
                        clase = 'has-text-ari-yellow';
                        break;
                    case (performance < 70):
                        clase = 'has-text-ari-red';
                        break;
                    default:
                        clase = '';
                        break;
                }

                return clase
            },
            getSession: function (item, prop = null) {
                let session = ('session' in item) ? item.session : item

                return (prop in session) ? session[prop] : null
            },
            durationFormat: function (string, value_to_get = 'seconds') {
                //string = string.replace('-','')
                return moment.duration(string, value_to_get).humanize()
            },
            hasActivities: function (item) {
                return _.size(item.activities) > 0
            },
            toggleDetails: function (item) {
                item.show_details = !item.show_details
            },
            clear: function (target) {
                let prop = target + '_selected'
                let aux = this.experiences_users[target+'s'].search
                let loader = index.getLoader()
                this.$data[prop] = null

                if (target === 'user') {
                    this.experiences_users.users.search = null
                    this.experiences_users.users.filtered_list = []
                    this.user_selected = null
                    if (this.experiences_users.experiences.search !== null) {
                        this.selectExperience(this.experiences_users.experiences.search)
                    }
                } else if (target === 'experience') {
                    this.experiences_users.experiences.search = null
                    this.experiences_users.experiences.filtered_list = []
                    this.experience_selected = null
                    if (this.experiences_users.users.search !== null) {
                        this.viewUserDetails(this.experiences_users.users.search)
                    }
                }

                if(this.experience_selected === null && this.user_selected === null) {
                    this.send(null, loader)
                } else {
                    setTimeout(() => {
                        loader.close()
                    }, 1000)
                }
            },
            viewUserDetails: function (event) {
                this.experiences_users.users.search = event;
                this.selectUser(event);
            },
            selectExperience: function (event) {
                let parent = this.experiences_users.experiences
                let item = _.find(parent.list, item => {
                    return (item.title === event || item.system_id === event)
                })

                if (_.isEmpty(item)) {
                    axios({
                        method: 'GET',
                        url: route('experiences.index'),
                        params: {
                            search: parent.search
                        }
                    })
                        .then(response => {
                            console.log(response.data)
                            //this.select(item, 'experience')
                        })
                        .catch(error => { console.error(error) })
                } else {
                    this.select(item, 'experience')
                }
            },
            selectUser: function (event) {
                let parent = this.experiences_users.users
                let item = _.find(parent.list, { full_name: parent.search})

                if (_.isEmpty(item)) {
                  let activity = [];
                    axios({
                        method: 'GET',
                        url: route('dashboard.experiences'),
                        params: {
                            ajax_request: true,
                            give: 'search',
                            target: 'user',
                            search: event
                        }
                    })
                        .then(response => {
                            activity = response.data.view.activity
                            item = _.head(activity.search.result)
                        })
                        .catch(error => { console.error(error) })
                        .then(() => {
                          if (_.isEmpty(item)) {
                            axios({
                              url: route('users.index'),
                              method: 'GET',
                              params: {
                                search: event,
                              }
                            })
                            .then(response => {
                              item = _.get(response, ['data','users','data','0'], null)
                              this.select(item, 'user')
                            })
                          }
                          else {
                            this.select(item, 'user')
                          }
                        })
                } else {
                  this.select(item, 'user')
                }
            },
            select: function (element, target) {
                let loader = index.getLoader()
                let prop = target + '_selected'
                this.$data[prop] = element

                if (this.is_user_selected && !this.is_experience_selected) {
                    this.experiences_users.users.search = this.user_selected.full_name
                    target = 'user'
                    this.send(target, loader)
                }
                else if (this.is_experience_selected && !this.is_user_selected) {
                    this.experiences_users.experiences.search = this.experience_selected.title
                    target = 'experience'
                    this.loading = loader
                }
                else if (this.is_both_selected) {
                    target = 'both'
                    this.send(target, loader)
                }
                else {
                    target = 'experiences_users'
                    this.send(target, loader)
                }
            },
            send: function (give = 'experiences_users', loaderInstance = null) {
                let loader = (loaderInstance !== null) ? loaderInstance : index.getLoader()
                axios({
                    method: 'GET',
                    url: route('dashboard.experiences'),
                    params: {
                        ajax_request: true,
                        give: give,
                        user: {
                            id: _.get(this.user_selected,['id'],null),
                            search: this.experiences_users.users.search,
                            page: this.experiences_users.users.current_page,
                            per_page: this.experiences_users.users.per_page
                        },
                        experience: {
                            id: _.get(this.experience_selected,['id'],null),
                            search: this.experiences_users.experiences.search,
                            page: this.experiences_users.experiences.current_page,
                            per_page: this.experiences_users.experiences.per_page
                        },
                    },
                    paramsSerializer: function (params) {
                        return Qs.stringify(params)
                    },
                })
                    .then(response => {
                        let activity = response.data.view.activity;
                        let target = _.head(Object.getOwnPropertyNames(activity))
                        let data = null

                        this.give = target

                        if (target === 'experiences_users') {
                            data = this.experiences_users;
                            _.each(activity[target], (item, key) => {
                                data[key].list = item.data
                                data[key].current_page = item.current_page
                                data[key].per_page = item.per_page
                                data[key].last_page = item.last_page
                            })
                        }

                        if (false && target === 'experience'&& !this.is_user_selected) {
                            //data = this.experience;
                            //this.$refs.experience_stats.setExperience(this.experience_selected)
                        }

                        if (target === 'user' && !this.is_experience_selected) {
                            //this.loading = loader
                            this.$refs.user_stats.setUser(this.user_selected)
                        }

                        if (target === 'both') {
                            data = this.both;
                            _.each(activity[target], (item, key) => {
                                switch (key) {
                                    case 'recurrence':
                                        let labels = []
                                        let datas = []

                                        Object.entries(item).forEach((entry) => {
                                            labels.push(entry[0])
                                            datas.push(entry[1].total)
                                        });

                                        let el = document.querySelector('.recurrence.chart canvas');

                                        let chart = {
                                            type: 'line',
                                            data: {
                                                labels: labels,
                                                datasets: [
                                                    {
                                                        label: this.$t('messages.dashboard.datasets.recurrence'),
                                                        borderColor: '#000000',
                                                        data: datas,
                                                        fill: false,
                                                    }
                                                ],
                                            },
                                            options: {
                                                responsive: true,
                                                maintainAspectRatio: false,
                                                legend: {
                                                    display: false
                                                },
                                                scales: {
                                                    yAxes: [{
                                                        ticks: {
                                                            beginAtZero: true,
                                                            stepSize: 10,
                                                            fontSize: 14
                                                        }
                                                    }],
                                                    xAxes: [{
                                                        ticks: {
                                                            fontSize: 14
                                                        }
                                                    }]
                                                },
                                                tooltips: {
                                                    bodyFontSize: 14,
                                                    titleFontSize: 14
                                                }
                                            }
                                        }
                                        // todo desactivado temporalmente
                                        // new Chart(el, chart)

                                        break;
                                    default:
                                        break;
                                }
                                this.$data[target][key] = item
                            })
                        }
                    })
                    .catch(error => {
                        console.error(error)
                    })
                    .then(() => { loader.close() })
            },
            getPercent: function (raw, max_value) {
                if (raw > 0) {
                    return (raw / max_value) * 100
                } else {
                    return 0
                }
            },
            filterExperience: function (query) {
                let parent = this.experiences_users.experiences

                parent.search = query
                parent.current_page = 1

                this.send();

                setTimeout(() => {
                    if (!_.isEmpty(query)) {
                        parent.filtered_list = _.filter(parent.list, item => {
                            let regex = new RegExp(query, 'gim')
                            return (regex.test(item.title))
                        })
                    } else {
                        parent.filtered_list = []
                    }
                }, 500)
            },
            filterUser: function (query) {
                let parent = this.experiences_users.users

                parent.search = query
                parent.current_page = 1

                this.send();

                setTimeout(() => {
                    if (!_.isEmpty(query)) {
                        parent.filtered_list = _.filter(parent.list, item => {
                            let regex = new RegExp(query, 'gim')
                            return (regex.test(item.name) || regex.test(item.surname))
                        })
                    } else {
                        parent.filtered_list = []
                    }
                }, 500)
            },
            filterItems: function (event, props, array_key = 'items') {
                let filtered = []
                if (!_.isEmpty(event)) {
                    filtered = _.filter(this.$data[props.parent][props.target][array_key], item => {
                        let regex = new RegExp(event, 'gim')
                        return (regex.test(item.full_name))
                    })
                }
                this.$data[props.parent][props.target].filtered_items = filtered
            },
            find: function (query = null, target = null) {
                if (_.isEmpty(query) || _.isEmpty(target))
                    return null;

                axios({
                    method: 'GET',
                    url: route('dashboard.experiences'),
                    params: {
                        ajax_request: true,
                        give: 'search',
                        target: target,
                        search: query
                    },
                })
                    .then(response => {
                        let parent = response.data.view.activity.search

                        if (!_.isEmpty(parent.result)) {
                            if (parent.target === 'user') {
                                // John Doe
                            }
                            if (parent.target === 'experience') {
                                let item = _.head(parent.result)
                                this.experiences_users.experiences.search = item.title
                                this.select(item, parent.target)
                            }
                        }

                    })
                    .catch(error => { console.error(error) })
            },
            onPageChange (page, props) {
                if (props.server_side) {
                    this.send()
                }
            },
            setPageSize: function (value, props) {
                this.$data[props.parent][props.target].per_page = parseInt(value)
                this.$data[props.parent][props.target].current_page = 1
                if (props.server_side) {
                    this.send()
                }
            }
        },
        data() {
            return {
                give: null,
                loading: null,
                user_selected: null,
                experience_selected: null,
                experiences_users : {
                    experiences: {
                        list: [],
                        filtered_list: [],
                        search: null,
                        current_page: 1,
                        per_page: 5,
                        enrolled_users: {
                            list: [],
                            search: null
                        }
                    },
                    users: {
                        list: [],
                        filtered_list: [],
                        search: null,
                        current_page: 1,
                        per_page: 5
                    }
                },
                experience: {
                    assignations: {
                        total: {
                            value: 0
                        },
                        started: {
                            value: 0
                        },
                        waiting: {
                            value: 0
                        }
                    },
                    invitations: {
                        total: {
                            value: 0
                        },
                        accepted: {
                            value: 0
                        },
                        waiting: {
                            value: 0
                        }
                    },
                    enrollment: {
                        total: {
                            value: 0
                        },
                        started: {
                            value: 0
                        },
                        finished: {
                            value: 0
                        },
                        waiting: {
                            value: 0
                        }
                    },
                    duration_average: 0,
                    performance_average: {
                        value: 0,
                        class: ''
                    },
                    enrolled_users: {
                        search: '',
                        per_page: 5,
                        current_page: 1,
                        items: [],
                        filtered_items: [],
                    },
                },
                both: {
                    enrollment: {
                        started_at: null,
                        finished_at: null,
                        duration: null,
                        progress: null,
                        score: null
                    },
                    sessions: []
                },
            }
        }
    }
</script>

<style>
    .bar {
        min-height: 14px;
    }
    .bar.big {
        min-height: 44px;
    }
    .indication {
        color: #BEBEBE;
    }
    .selector {
        position: absolute;
    }
    .recurrence.chart {
        max-height: 250px;
    }
    .experience-item {
        height: 70px;
    }
    .experience-item .ari-background-gray {
        background-color: #F2F2F2;
    }
    .experience-item.selected {
        background-color: #FFBB4E;
        border-radius: 4px;
        color: #FFFFFF;
    }
    .experience-item .image {
        max-width: 70px;
        min-width: 70px;
    }
    .experience-item.selected .image {
        max-width: 70px;
    }
    .user-item {
        height: 70px;
    }
    .user-item .ari-background-gray {
        background-color: #F2F2F2;
    }
    .user-item.selected {
        background-color: #FFBB4E;
        border-radius: 4px;
        color: #FFFFFF;
    }
    .user-item .image {
        max-width: 50px;
        min-width: 50px;
        border-radius: 50%;
    }
    .activity {
        height: 42px;
    }
    .activity.ari-background-gray {
        background-color: #F2F2F2;
    }
    .ari-b-gray {
        background-color: #B7C5DB;
    }
    .ari-b-blue {
        background-color: #0098FF;
    }
    .ari-b-cyan {
        background-color: #67C2FF;
    }
    .ari-b-pink {
        background-color: #FCB399;
    }
    .ari-b-green {
        background-color: #22BC6B;
    }
    .ari-text-gray {
        color: #B7C5DB;
    }
    .ari-text-blue {
        color: #0098FF;
    }
    .ari-text-cyan {
        color: #67C2FF;
    }
    .ari-text-pink {
        color: #FCB399;
    }
    .ari-text-green {
        color: #22BC6B;
    }
    .has-text-ari-green {
        color: #22BC6B;
    }
    .has-text-ari-yellow {
        color: #FFA81E;
    }
    .has-text-ari-red {
        color: #FF5E63;
    }
    .text-gray-default {
        color: #BEBEBE !important;
    }
    .card .canvas-container {
        height: 250px;
    }
    @media screen and (min-width: 992px){
        .session-details {
            margin-left: 130px;
        }
    }
    .b-table .table-wrapper {
        margin-bottom: 0;
    }
    .b-table .level {
        display: none;
    }
    .el-input__inner:focus {
        border-color: #FFBB4E;
    }
</style>
