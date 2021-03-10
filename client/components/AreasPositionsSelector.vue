<template>
    <div class="row">
        <div class="col-12 col-sm-4" v-show="company.visible">
            <input type="text" hidden v-model="company.id" name="company_id" :disabled="company.disabled">
            <label for="" class="font-14 has-text-weight-bold">Empresa</label>
            <!--div class="select width-100 font-14">
                <select class="width-100" v-model="company.selected"
                        :disabled="company.disabled">
                    <option :value="-1" v-if="isSuper">Selecciona una Empresa</option>
                    <option v-for="(option,index) in companies" :value="index">{{ option.name }}</option>
                </select>
            </div-->
            <b-autocomplete
                    icon-pack="fas"
                    icon="building"
                    field="name"
                    v-if="isSuper"
                    v-model="company.name"
                    :open-on-focus="!!company.data"
                    :disabled="company.disabled && !auth_user.is_super"
                    :data="company.filtered_data"
                    @select="option => company.selected = option"
                    @input="filteredDataArray('company')">
                <template slot="empty">No results found</template>
            </b-autocomplete>
        </div>

        <div class="col-12" v-bind:class="{'col-md-6': columns, 'col-md-4': !columns}">
            <input type="text" hidden v-model="area.id" name="area_id">
            <label for="" class="font-14 has-text-weight-bold">Área</label>
            <!--div class="select width-100 font-14">
                <select class="width-100" v-model="area.selected"
                        :disabled="area.disabled">
                    <option :value="-1">Selecciona una Área</option>
                    <option v-for="(option,index) in area.data" :value="index">{{ option.name }}</option>
                </select>
            </div-->
            <b-autocomplete
                    icon-pack="fas"
                    icon="sitemap"
                    field="name"
                    v-model="area.name"
                    :open-on-focus="!!area.data"
                    :disabled="isDisabled"
                    :data="area.filtered_data"
                    @select="option => area.selected = option"
                    @input="filteredDataArray('area')">
                <template slot="empty">No results found</template>
            </b-autocomplete>

        </div>

        <div class="col-12" v-bind:class="{'col-md-6': columns, 'col-md-4': !columns}">
            <input type="text" hidden v-model="position.id" name="position_id">
            <label for="" class="font-14 has-text-weight-bold">Posición</label>
            <!--div class="select width-100 font-14">
                <select class="width-100" v-model="position.selected"
                        :disabled="position.disabled">
                    <option :value="-1">Selecciona una Posición</option>
                    <option v-for="(option,index) in position.data" :value="index">{{ option.name }}</option>
                </select>
            </div-->
            <b-autocomplete
                    icon-pack="fas"
                    icon="briefcase"
                    field="name"
                    v-model="position.name"
                    :open-on-focus="!!position.data"
                    :disabled="isDisabled"
                    :data="position.filtered_data"
                    @select="option => position.selected = option"
                    @input="filteredDataArray('position')">
                <template slot="empty">No results found</template>
            </b-autocomplete>
        </div>

    </div>
</template>

<script>
    export default {
        name: "apithy-select",
        props: {
            companies: {
                type: Array,
                required: true
            },
            company_id: {
                type: Number,
                required: false,
                default: null
            },
            auth_user: {
                type: Object,
                required:true
            },
            disabled: {
                type: Boolean,
                required: false,
                default: false
            },
            view: '',
        },
        computed:{
            columns() {
                return !this.company.visible;
            },
            isDisabled() {
                return (this.view === 'profile' || this.disabled);
            }
        },
        watch: {
            'company.selected'(value) {
                if (this.view === 'profile') { return 0 ;}
                if(value && value.id) {
                    this.company.id = value.id;
                    this.area.data = value.areas_positions;
                    this.area.filtered_data = value.areas_positions;
                    this.reset('area');
                }
                else {
                    this.company.id = null;
                    this.company.selected = '';
                    this.reset('area');
                }
            },
            'area.selected'(value) {
                if (this.view === 'profile') { return 0 ;}
                if(value && value.id) {
                    this.area.id = value.id;
                    this.position.data = value.positions;
                    this.position.filtered_data = value.positions;
                    this.reset('position');
                }
                else {
                    this.area.id = null;
                    this.area.selected = '';
                    this.reset('position');
                }
            },
            'position.selected'(value) {
                if (this.view === 'profile') { return 0 ;}
                if(value && value.id) {
                    this.position.id = value.id;
                }
                else {
                    this.position.id = null;
                    this.position.selected = '';
                    this.reset('position');
                }
            }
        },
        mounted() {
            if (!this.isSuper()) {
                if (!this.company_id) {
                    this.company.selected = _.head(this.company.data);
                }
                else {
                    this.setUserPositionData(this.company, this.companies, this.company_id);
                }

                this.company.id = this.company.selected.id;
                this.company.name = this.company.selected.name;

                if (this.view === 'profile') {
                    this.customClass = "two";
                    this.company.visible = false;

                    this.area.data = this.company.selected.areas_positions;

                    if(this.auth_user.position.length) {
                        let area_id = this.auth_user.position[0].area_id;
                        let position_id = this.auth_user.position[0].id;

                        this.setUserPositionData(this.area, this.company.selected.areas_positions, area_id);

                        this.setUserPositionData(this.position, this.area.selected.positions, position_id);
                    }

                }
                else {
                    this.setUserPositionData(this.area, this.company.selected.areas_positions);

                    if (this.area.selected >= 0) {
                        this.setUserPositionData(this.position, this.area.selected.positions);
                    }
                }
            }
        },
        methods: {
            /**
             * Set the user position data to selects.
             *
             * @param object
             * @param array
             * @param data
             */
            setUserPositionData(object, array = [], data = 'undefined') {
                if (data >= 0 && array.length > 0) {
                    for (let i = 0; i < array.length; i++) {
                        if (array[i].id === data) {
                            object.selected = array[i];
                            object.data = array;
                            object.id = array[i].id;
                            object.name = array[i].name;
                        }
                    }
                }
                else {
                    object.selected = "";
                    object.id = null;
                    object.name = "";
                }
            },
            updateUserPositionData(target, value = 'undefined') {
                if(this[target].data) {
                    this.setUserPositionData(this[target], this[target].data, value);
                }
                else {
                    setTimeout( () => {
                        this.setUserPositionData(this[target], this[target].data, value);
                    },50);
                }
            },

            /**
             * Reset the specified select's options
             *
             * @param select string
             * @param withData boolean
             */
            reset: function (select, withData = false) {
                let option = select;
                switch (option) {
                    case 'company':
                        this.company.id = null;
                        this.company.selected = '';
                        this.company.name = '';
                        break;
                    case 'area':
                        if (withData) {
                            this.area.data = null;
                        }
                        this.area.id = null;
                        this.area.selected = '';
                        this.area.name = '';
                        break;
                    case 'position':
                        if (withData) {
                            this.position.data = null;
                        }
                        this.position.id = null;
                        this.position.selected = '';
                        this.position.name = '';
                        break;
                    default:
                        return;
                }
            },

            /**
             * Return true if the logged user is root.
             *
             * @returns {boolean}
             */
            isSuper() {
                return this.auth_user.is_super;
            },

            /**
             * Return true if the logged user is a company admin.
             *
             * @returns {boolean}
             */
            isAdmin() {
                return this.auth_user.is_admin;
            },

            getUserPosition() {
                return {
                    area: this.area.id,
                    company: this.company.id,
                    position: this.position.id
                }
            },

            filteredDataArray(target) {
                this[target].filtered_data = this[target].data.filter((option) => {
                    return option.name
                        .toString()
                        .toLowerCase()
                        .indexOf(this[target].name.toLowerCase()) >= 0
                });
            }
        },
        data() {
            return {
                customClass: (this.isSuper() || this.isAdmin()) ? 'three' : 'two',
                company: {
                    name: '',
                    selected: '',
                    id: null,
                    data: this.companies,
                    filtered_data: [],
                    disabled: !this.isSuper(),
                    visible: (this.isAdmin() || this.isSuper())
                },
                area: {
                    name: '',
                    selected: '',
                    id: null,
                    data: null,
                    disabled: !(this.isAdmin() || this.isSuper())
                },
                position: {
                    name: '',
                    selected: '',
                    id: null,
                    data: null,
                    disabled: !(this.isAdmin() || this.isSuper())
                }
            }
        }
    }
</script>

<style scoped>
    .width-100 {
        width: 100%;
    }
    .font-20 {
        font-size: 20px;
    }
    .font-14 {
        font-size: 14px;
    }
</style>