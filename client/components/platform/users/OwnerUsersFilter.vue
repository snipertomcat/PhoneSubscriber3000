<template>
    <div class="users-filter">
        <div class="trigger">
            <span class="button is-white has-text-link has-text-weight-bold" @click="showFilter">Filtrar</span>
        </div>
        <div class="row ml-0 mr-0 mt-3" v-if="show">
            <div class="col-12 col-lg-6 mb-3 mb-lg-0">
                <div class="row ml-0 mr-0 no-gutters">
                    <div class="col col-lg-4 mr-2 mr-xl-4">
                        <el-select placeholder="Por rol" v-model="selected_role" @change="setTag($event, 'by_role')">
                            <el-option v-for="role in roles" :value="role" :key="role.id" :label="role.name">
                                <span class="text">{{ role.name }}</span>
                            </el-option>
                        </el-select>
                    </div>
                    <div class="col col-4 mr-2 mr-xl-4">
                        <el-select filterable placeholder="Por etiqueta" v-model="selected_taxonomy" @change="setTag($event, 'by_taxonomy')">
                            <el-option-group v-for="(group, index) in taxonomy" :key="index" :label="getText(index)">
                                <el-option v-for="tag in group" :value="tag" :key="tag.id" :label="tag.title">
                                    <span class="text">{{ tag.title }}</span>
                                </el-option>
                            </el-option-group>
                        </el-select>
                    </div>
                    <div class="col col-3">
                        <el-select  placeholder="Por estado" v-model="selected_status" @change="setTag($event, 'by_status')">
                            <el-option v-for="status in statuses" :value="status" :key="status.id" :label="status.label">
                                {{ $t('messages.'+status.label) }}
                            </el-option>
                        </el-select>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="search-box full-width">
                    <el-input
                            placeholder="Buscar usuario"
                            v-model="form.search" @keypress.enter.native="setTag">
                        <i class="fas fa-search" slot="prefix"></i>
                    </el-input>
                </div>
            </div>
        </div>

        <div class="row ml-0 mr-0 mt-3 no-gutters pl-3" v-if="is_any_filter_active()">
            <div class="col-auto tag-element"
                 v-for="(vtag, index) in visual_tags"
                 :class="'vtag-'+vtag.code" :key="index">
                <span class="tag" :class="vtag.class" v-if="!vtag.color">
                    {{ vtag.name }}
                    <button class="delete is-small" @click="removeTag(index, vtag)"></button>
                </span>
                <span class="tag" v-else :style="'background-color: '+vtag.color">
                    {{ vtag.name }}
                    <button class="delete is-small" @click="removeTag(index, vtag)"></button>
                </span>
            </div>
        </div>

    </div>
</template>

<script>
    import { Select, Option, OptionGroup, Input } from 'element-ui';

    export default {
        name: "OwnerUsersFilter",
        components: {
            'el-select': Select,
            'el-option': Option,
            'el-option-group': OptionGroup,
            'el-input': Input,
        },
        props: {
            statuses: {
                type: Array,
                default () { return [] }
            },
            roles: {
                type: Array,
                default () { return [] }
            },
            taxonomy: {
                default () { return [] }
            },
        },
        data () {
            return {
                selected_taxonomy: '',
                selected_status: '',
                selected_role: '',
                show: false,
                form: {
                    by_role: [],
                    by_taxonomy: [],
                    by_status: [],
                    search: null
                },
                visual_tags: []
            }
        },
        methods: {
            is_any_filter_active: function () {
                return _.size(this.visual_tags) > 0
            },
            showFilter () {
                this.show = true
            },
            removeTag (index, tag) {
                console.log(tag)
                if ('is_user' in tag) {
                    this.form.search = null;
                } else if ('is_status' in tag) {
                    _.remove(this.form.by_status, value => {return value === tag.id})
                } else if ('is_role' in tag) {
                    _.remove(this.form.by_role, value => {return value === tag.id})
                } else if ('is_taxonomy' in tag) {
                    _.remove(this.form.by_taxonomy, value => {return value === tag.id})
                }
                this.$delete(this.visual_tags, index)
                this.$parent.loadData(1, this.form);
            },
            setTag (value, el = 'search') {
                if (el in this.form) {
                    switch (el) {
                        case 'by_role':
                            if (value !== null) {
                                value.is_role = true;
                                if (!_.find(this.visual_tags, {is_role: true, id: value.id})) {
                                    this.visual_tags.push(this.setColor(value));
                                }
                                this.selected_role = '';
                            }
                            break;
                        case 'by_taxonomy':
                            if (value !== null) {
                                value.is_taxonomy = true;
                                if (!_.find(this.visual_tags, {is_taxonomy: true, id: value.id})) {
                                    this.visual_tags.push({
                                        id: value.id,
                                        code: value.type,
                                        name: value.title,
                                        color: value.color,
                                        is_taxonomy: true
                                    });
                                }
                                this.selected_taxonomy = '';
                            }
                            break;
                        case 'by_status':
                            if (value !== null) {
                                value.is_status = true;

                                let index = _.indexOf(this.visual_tags, _.find(this.visual_tags, {is_status: true}));
                                this.$delete(this.visual_tags, index);

                                this.visual_tags.push(
                                    this.setColor({
                                        id: value.value,
                                        code: value.label,
                                        name: this.$t('messages.' + value.label),
                                        is_status: true
                                    })
                                );

                                this.form[el] = [value.value];
                                this.selected_status = '';
                            }
                            break;
                        case 'search':
                            let index = _.indexOf(this.visual_tags, _.find(this.visual_tags, {is_user: true}));
                            this.$delete(this.visual_tags, index);

                            if (!_.isEmpty(this.form[el])) {
                                this.visual_tags.push({
                                    class: 'apithy-color-black',
                                    name: this.form[el],
                                    is_user: true
                                });
                            }
                            break;
                        default:
                            break;
                    }

                    if (!_.find(this.form[el], value.id) && el !== 'search') {
                        if (el !== 'by_status') {
                            this.form[el].push(value.id);
                        }
                    }

                    if (el in this.$refs) {
                        this.$refs[el].selected = null;
                    }

                    // @ToDo: Hacer busqueda
                    this.$parent.loadData(1, this.form);
                }
            },
            setColor (el) {
                if (_.isEmpty(el)) {
                    el = {
                        name: 'Sin rol',
                        class: 'apithy-color-gray'
                    };
                } else {
                    switch (el.code) {
                        case "SA":
                            el.class = 'apithy-color-super-admin';
                            break;
                        case "SP":
                            el.class = 'apithy-color-support';
                            break;
                        case "AD":
                            el.class = 'apithy-color-admin';
                            break;
                        case "SUP":
                            el.class = 'apithy-color-supervisor';
                            break;
                        case "GJ":
                            el.class = 'apithy-color-gest-jerq';
                            break;
                        case "PC":
                            el.class = 'apithy-color-prov-content';
                            break;
                        case "AU":
                            el.class = 'apithy-color-author';
                            break;
                        case "ER":
                            el.class = 'apithy-color-enroller';
                            break;
                        case "STU":
                            el.class = 'apithy-color-student';
                            break;
                        case "PT":
                            el.class = 'apithy-color-partner';
                            break;
                        case 'unconfirmed':
                            el.class = 'apithy-color-status-unconfirmed';
                            break;
                        case 'active':
                            el.class = 'apithy-color-status-active';
                            break;
                        case 'deleted':
                            el.class = 'apithy-color-status-deleted';
                            break;
                        case 'suspended':
                            el.class = 'apithy-color-status-suspended';
                            break;
                        default:
                            el.class = 'apithy-color-gray';
                            break;
                    }
                }

                return el;
            },
            getText (string) {
                switch (string) {
                    case 'company_area':
                        return 'Área';
                    case 'company_position':
                        return 'Posición';
                    case 'ability':
                        return 'Habilidad';
                    case 'certifications':
                        return 'Certificaciones';
                    case 'teams':
                        return 'Equipo';
                    case 'custom':
                        return 'Personalizadas';
                    case 'tag':
                        return 'Etiqueta';
                    default:
                        return string;
                }
            },
        }
    }
</script>

<style scoped>
    .users-filter select option {
        /*font-family: icomoon;*/
    }
    .users-filter select option.text {
        font-family: 'Montserrat', sans-serif;
    }
    .search-box {
        width: 323px;
    }
    .search-box .el-input__prefix i {
        margin-top: 10px;
        font-size: 20px;
    }
    .tag-element {
        margin-right: 1rem;
    }
</style>