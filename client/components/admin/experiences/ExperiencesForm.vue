<template>
    <div class="mb-5">
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col-12">
                        <span class="font-20 has-text-weight-bold">{{ $t('messages.general_data') }}</span>
                    </div>
                    <hr>
                    <div class="col-12 row">
                        <div class="col-12 col-md-9">
                            <b-field
                                :label="$t('messages.name')"
                                :type="{'is-danger':errors.has('name')}"
                                :message="errors.first('name')">
                                <b-input
                                    type="text"
                                    class="font-14"
                                    name="name"
                                    v-model="form.title"
                                    v-validate="validator.name"
                                    placeholder="Escribe un nombre para la experiencia">
                                </b-input>
                            </b-field>
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="has-text-weight-bold">{{ $t('messages.type') }}</label>
                            <div class="row width-100 font-14">
                                <div class="col">
                                    <b-radio v-model="form.type" native-value="journey" type="is-info">
                                        {{$t('messages.journey')}}
                                    </b-radio>
                                </div>
                                <div class="col">
                                    <b-radio v-model="form.type" native-value="adventure" type="is-info">
                                        {{$t('messages.adventure')}}
                                    </b-radio>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="col-12">
                        <b-field :label="$t('messages.summary')" :type="{'is-danger':errors.has('summary')}"
                                 :message="errors.first('summary')">
              <textarea
                  class="width-100 font-14 textarea"
                  rows="5"
                  name="summary"
                  v-model="form.summary"
                  v-validate="validator.summary"
                  :class="{'is-danger':errors.has('summary')}"
                  placeholder="Escribe un resumen">
              </textarea>
                        </b-field>
                    </div>
                    <hr>
                    <hr>
                    <div class="col-12">
                        <b-field :label="$t('messages.description')" :type="{'is-danger':errors.has('description')}"
                                 :message="errors.first('description')">
              <textarea
                  class="width-100 font-14 textarea"
                  rows="5"
                  name="description"
                  v-model="form.description"
                  placeholder="Escribe una descripción">
              </textarea>
                        </b-field>
                    </div>
                    <div class="col-12">
                        <b-field :label="$t('messages.video_teaser')" :type="{'is-danger':errors.has('video_teaser')}"
                                 :message="errors.first('video_teaser')">
                            <b-input
                                type="text"
                                class="font-14"
                                name="video_teaser"
                                v-model="form.video_teaser"
                                v-validate="validator.video_teaser"
                                placeholder="URL Video Teaser">
                            </b-input>
                        </b-field>
                    </div>
                    <hr>
                    <div class="col-12" v-if="form.type === 'journey'">
                        <label class="has-text-weight-bold">{{ $t('messages.adventures') }}</label>
                        <v-tags-input v-model="form.adventures"
                                      class="font-14"
                                      placeholder="Adventures"
                                      label="title"
                                      track-by="id"
                                      :options="adventures_list"
                                      :option-height="104"
                                      :multiple="true"
                                      :show-labels="false">
                            <template slot="singleLabel" slot-scope="props">
                <span class="option__desc">
                  <span class="option__title">@{{ form.instructor.title }}</span>
                </span>
                            </template>
                            <template slot="option" slot-scope="props">
                                <img class="option__image"
                                     :src="props.option.full_path_cover"
                                     alt="" width="50px">
                                <span class="option__desc">
                  <span class="option__title">{{ props.option.title }}</span>
                </span>
                            </template>
                        </v-tags-input>
                    </div>
                    <hr>
                    <div class="col-12 row">
                        <div class="col-12">
                            <label class="has-text-weight-bold">{{ $t('messages.cover') }}</label>
                        </div>
                        <div class="col-4 experience-image">
                            <apithy-image-upload
                                :id="0"
                                input_name="cover"
                                field="cover"
                                name="cover"
                                :url="`${route('cover.update', [experience])}`"
                                :width="880"
                                :height="640"
                                :image="form.full_path_cover">
                            </apithy-image-upload>
                        </div>
                    </div>
                    <hr>
                    <div class="col-12 row">
                        <div class="col-12">
                            <label class="has-text-weight-bold">{{ $t('messages.cover_top') }}</label>
                        </div>
                        <div class="col-4 experience-image">
                            <apithy-image-upload
                                :id="1"
                                input_name="cover_top"
                                field="cover_top"
                                name="cover_top"
                                :url="`${route('cover_top.update', [experience])}`"
                                :width="1068"
                                :height="250"
                                :image="form.full_path_cover_top">
                            </apithy-image-upload>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br><br>
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col-12">
            <span class="font-20 has-text-weight-bold">
              {{ $t('messages.objectives') }}
            </span>
                    </div>
                    <hr>
                    <div class="col-12">
                        <b-field :label="$t('messages.company_objetives')"
                                 :type="{'is-danger':errors.has('company_objetives')}"
                                 :message="errors.first('company_objetives')">
              <textarea
                  class="font-14 width-100 textarea"
                  rows="5"
                  name="company_objetives"
                  v-model="form.company_objectives"
                  v-validate="validator.company_objetives"
                  :class="{'is-danger':errors.has('company_objetives')}">
              </textarea>
                        </b-field>
                    </div>
                    <div class="col-12">
                        <b-field :label="$t('messages.area_objetives')"
                                 :type="{'is-danger':errors.has('area_objetives')}"
                                 :message="errors.first('area_objetives')">
              <textarea
                  class="font-14 width-100 textarea"
                  rows="5"
                  name="area_objetives"
                  v-model="form.area_objectives"
                  v-validate="validator.area_objetives"
                  :class="{'is-danger':errors.has('area_objetives')}">
              </textarea>
                        </b-field>
                    </div>
                    <div class="col-12">
                        <b-field class="has-text-weight-bold" :label="$t('messages.abilities')">
                            <el-select
                                id="abilities"
                                name="abilities"
                                class="width-100 font-14"
                                v-model="form.abilities"
                                filterable
                                multiple
                                :filter-method="getFilteredAbility"
                                placeholder="Añade una habilidad"
                                no-match-text="seleccione para agregar habilidad">
                                <el-option
                                    v-for="item in filterAbility"
                                    :key="item.id"
                                    :label="item.title"
                                    :value="item.id">
                                </el-option>
                                <div slot="empty">
                                    <p v-if="abilityValue" class="has-text-weight-bold pointer text-center"
                                       @click="saveAbility">click aqui para crear habilidad</p>
                                    <p v-if="!abilityValue" class="has-text-weight-bold text-center">Escriba una nueva
                                        habilidad</p>
                                </div>
                            </el-select>
                        </b-field>
                    </div>
                    <div class="col-12">
                        <b-field class="has-text-weight-bold" :label="$t('messages.tags')">
                            <el-select
                                id="tags"
                                name="tags"
                                class="width-100 font-14"
                                v-model="form.tags"
                                filterable
                                multiple
                                :filter-method="getFilteredTags"
                                placeholder="Añade una etiqueta"
                                no-match-text="seleccione para agregar etiqueta">
                                <el-option
                                    v-for="item in filterTags"
                                    :key="item.id"
                                    :label="item.title"
                                    :value="item.id">
                                </el-option>
                                <div slot="empty">
                                    <p v-if="tagValue" class="has-text-weight-bold pointer text-center"
                                       @click="saveTag">click aqui para crear etiqueta</p>
                                    <p v-if="!tagValue" class="has-text-weight-bold text-center">Escriba una nueva
                                        etiqueta</p>
                                </div>
                            </el-select>
                        </b-field>
                    </div>
                </div>
            </div>
        </div>
        <br><br>
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col-12 col-lg-3">
                        <label class="has-text-weight-bold">
                            {{ $t('messages.price') }}
                        </label>
                        <span class="control has-icons-left has-icons-right">
              <input type="text"
                     class="input font-14"
                     placeholder="Escriba un precio"
                     v-model="form.price_default">
              <span class="icon is-small is-left">
                  <i class="fa fa-usd"></i>
              </span>
              <span class="icon is-small is-right">
                .00
              </span>
            </span>
                    </div>
                    <div class="col-12 col-lg-3">
                        <label class="has-text-weight-bold">
                            <i class="fa fa-exclamation-circle apithy-color"></i>
                            {{ $t('messages.duration_limit_default') }}
                        </label>
                        <input type="text"
                               class="input font-14"
                               placeholder="Escriba un límite para la duración"
                               v-model="form.duration_limit_default">
                    </div>
                    <div class="col-12 col-lg-3">
                        <label class="has-text-weight-bold">
                            <i class="fa fa-exclamation-circle apithy-color"></i>
                            {{ $t('messages.level_default') }}
                        </label>
                        <input type="text"
                               class="input font-14"
                               placeholder="Escriba un nivel"
                               v-model="form.level_default">
                    </div>
                    <div class="col-12 col-lg-3">
                        <label class="has-text-weight-bold">
                            <i class="fa fa-exclamation-circle apithy-color"></i>
                            {{ $t('messages.points') }}
                        </label>
                        <span class="control has-icons-right">
            <input type="text"
                   class="input font-14"
                   placeholder="Escriba una puntuación"
                   v-model="form.points_default">
            <span class="icon is-small is-right">
              <i class="fa fa-certificate"></i>
            </span>
            </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-lg-3">
                        <label class="has-text-weight-bold">
                            {{ $t('messages.content_hours') }}
                        </label>
                        <!--
                        <input type="number"
                               class="input font-14"
                               min="0"
                               v-model="form.features.hours_content">
                       -->
                        <div class="row">
                            <div class="col-4">
                                <b-input v-model="form.features.hours_content.time_value"
                                         @keydown.native="isInteger"></b-input>
                            </div>
                            <div class="col-8">
                                <b-select v-model="form.features.hours_content.time_period" expanded>
                                    <option value="minutes">{{ $t('messages.minutes') }}</option>
                                    <option value="hours">{{ $t('messages.hours') }}</option>
                                </b-select>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-3">
                        <label class="has-text-weight-bold">
                            {{ $t('messages.videos_count') }}
                        </label>
                        <input type="number"
                               class="input font-14"
                               min="0"
                               v-model="form.features.num_videos">
                    </div>
                    <div class="col-12 col-lg-3">
                        <label class="has-text-weight-bold">
                            {{ $t('messages.activities_count') }}
                        </label>
                        <input type="number"
                               class="input font-14"
                               min="0"
                               v-model="form.features.num_activities">
                    </div>
                    <div class="col-12 col-lg-3">
                        <label class="has-text-weight-bold">
                            {{ $t('messages.content_downloads_count') }}
                        </label>
                        <input type="number"
                               class="input font-14"
                               min="0"
                               v-model="form.features.num_content_downloads">
                    </div>
                </div>
            </div>
        </div>
        <br><br>
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col-12">
            <span class="font-20 has-text-weight-bold">
                Autoría
            </span>
                    </div>
                    <div class="col-12">
                        <label class="has-text-weight-bold">
                            {{ $t('messages.author') }}
                        </label>
                        <v-tags-input
                            v-model="form.author"
                            class="font-14"
                            placeholder="Author"
                            label="name"
                            track-by="id"
                            :options="author_list"
                            :option-height="104"
                            :preselectFirst="false"
                            :show-labels="false">
                            <template slot="singleLabel" slot-scope="props">
                                <div v-if="form.author !== null">
                                    <img class="option__image" :src="form.author.full_path_avatar" alt="" width="50px">
                                    <span class="option__desc">
                    <span class="option__title">{{ form.author.full_name }}</span>
                  </span>
                                </div>
                            </template>
                            <template slot="option" slot-scope="props">
                                <img class="option__image" :src="props.option.full_path_avatar" alt="" width="50px">
                                <span class="option__desc">
                  <span class="option__title">{{ props.option.full_name }}</span>
                </span>
                            </template>
                        </v-tags-input>
                    </div>
                    <div class="col-12">
                        <label class="has-text-weight-bold">
                            {{ $t('messages.instructors') }}
                        </label>
                        <v-tags-input
                            v-model="form.instructors"
                            class="font-14"
                            placeholder="Instructor"
                            label="name"
                            track-by="id"
                            :options="partner_list"
                            :option-height="104"
                            :multiple="true"
                            :show-labels="false">
                            <template slot="singleLabel" slot-scope="props">
                <span class="option__desc">
                    <span class="option__title">@{{ form.instructor.full_name }}</span>
                </span>
                            </template>
                            <template slot="option" slot-scope="props">
                                <img class="option__image"
                                     :src="props.option.full_path_avatar"
                                     alt="" width="50px">
                                <span class="option__desc">
                  <span class="option__title">{{ props.option.full_name }}</span>
                </span>
                            </template>
                        </v-tags-input>
                    </div>
                    <div class="col-12">
                        <label class="has-text-weight-bold">
                            {{ $t('messages.partner') }}
                        </label>
                        <v-tags-input
                            v-model="form.partner"
                            class="font-14"
                            placeholder="Patrocinador"
                            label="name"
                            track-by="id"
                            :options="partner_list"
                            :option-height="104"
                            :preselectFirst="false"
                            :show-labels="false">
                            <template slot="singleLabel" slot-scope="props">
                                <div v-if="form.partner !== null">
                                    <img class="option__image" :src="form.partner.full_path_avatar" alt="" width="50px">
                                    <span class="option__desc">
                    <span class="option__title">{{ form.partner.full_name }}</span>
                  </span>
                                </div>
                            </template>
                            <template slot="option" slot-scope="props">
                                <img class="option__image" :src="props.option.full_path_avatar" alt="" width="50px">
                                <span class="option__desc">
                  <span class="option__title">{{ props.option.full_name }}</span>
                </span>
                            </template>
                        </v-tags-input>
                    </div>
                    <div class="col-12" v-if="is_private">
                        <label for="" class="has-text-weight-bold">
                            {{ $t('messages.company')}}
                        </label>
                        <div>
                            <el-select class="full-width" placeholder="Select a company" v-model="form.companies"
                                       filterable
                                       multiple
                                       :filter-method="getFilteredCompanies"
                            >
                                <el-option v-for="company_item in companies_list" :key="company_item.id" :label="company_item.name"
                                           :value="company_item.id"></el-option>
                            </el-select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br><br>
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col-12">
            <span class="font-20 has-text-weight-bold">
                Opciones de publicación
            </span>
                    </div>
                    <div class="col-12 row">
                        <div class="col-12 col-sm-3">
                            <label class="has-text-weight-bold width-100">Disponible a partir de</label>
                            <div class="control has-icons-left">
                                <input type="date" class="input font-14" :min="now" v-model="form.available_from">
                                <span class="icon is-small is-left">
                  <i class="fa fa-calendar"></i>
                </span>
                            </div>
                        </div>
                        <div class="col-12 col-sm-3">
                            <label class="has-text-weight-bold width-100">Disponible hasta</label>
                            <div class="control has-icons-left">
                                <input type="date" class="input font-14" :min="now" v-model="form.available_to">
                                <span class="icon is-small is-left">
                  <i class="fa fa-calendar"></i>
                </span>
                            </div>
                        </div>
                        <div class="col-12 col-sm-3">
                            <label>{{ $t('messages.visibility') }}</label>
                            <div class="row width-100 font-14">
                                <div class="col">
                                    <b-radio v-model="form.visibility" v-validate="validator.visibility"
                                             :native-value="1" name="visibility" type="is-info">
                                        Público
                                    </b-radio>
                                </div>
                                <div class="col">
                                    <b-radio v-model="form.visibility" v-validate="validator.visibility"
                                             :native-value="0" name="visibility" type="is-info">
                                        Privado
                                    </b-radio>
                                </div>
                            </div>
                            <span class="text-danger"
                                  v-if="errors.has('visibility')">{{ errors.first('visibility') }}</span>
                        </div>
                        <div class="col-12 col-sm-3">
                            <label class="has-text-weight-bold width-100">{{$t('messages.status')}}</label>
                            <div class="row font-14">
                                <div class="col-4">
                                    <b-switch
                                        v-model="form.status"
                                        type="is-info"
                                        :true-value="1"
                                        :false-value="0">
                                    </b-switch>
                                </div>
                                <div class="col-8">
                                    <span v-if="form.status">Activo</span>
                                    <span v-else>Inactivo</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br><br>
        <div class="row justify-content-center">
            <div class="col-6 col-md-3">
                <button class="button is-link width-100" :disabled="errors.any()" @click="save">Guardar</button>
            </div>
            <div class="col-6 col-md-3">
                <button class="button is-link width-100" @click="cancel">Cancelar</button>
            </div>
        </div>
    </div>
</template>

<script>
    import 'vue-multiselect/dist/vue-multiselect.min.css'
    import vue from 'vue'
    import {index} from "../../../helpers";
    import {Select, Option} from 'element-ui'

    vue.component(Select.name, Select)
    vue.component(Option.name, Option)

    export default {
        name: 'ExperiencesForm',
        components: {
            'Datepicker': import('vuejs-datepicker'),
            'apithy-image-upload': () => import('../../ImageUpload'),
            'apithy-entity-asignator': import('../EntityAsignator'),
            'v-tags-input': () => import('vue-multiselect'),
        },
        props: {
            mode: {
                type: String,
                required: true
            },
            experience: {
                type: Object,
                required: true
            },
            adventures_list: {
                type: Array,
                required: false,
                default() {
                    return [];
                }
            },
            abilities_list: {
                type: Array,
                required: true
            },
            companies_list: {
                type: Array,
                required: true
            },
            tag_list: {
                type: Array,
                default() {
                    return [];
                }
            },
            author_list: {
                type: Array,
                default() {
                    return [];
                }
            },
            partner_list: {
                type: Array,
                default() {
                    return [];
                }
            }
        },
        computed: {
            now() {
                let date = new Date();
                return date.toLocaleDateString();
            },
            experience_status() {
                return (this.form.status);
            },
            is_private: function () {
                return this.form.visibility === 0
            }
        },
        data() {
            return {
                method: '',
                url: '',
                filterTags: [],
                tagValue: null,
                filterAbility: [],
                abilityValue: null,
                filterCompanie: [],
                companyValue: null,
                form: {
                    id: 0,
                    title: null,
                    type: 'adventure',
                    summary: null,
                    description: null,
                    video_teaser: null,
                    adventures: [],
                    full_path_cover: null,
                    full_path_cover_top: null,
                    company_objectives: null,
                    area_objectives: null,
                    abilities: [],
                    tags: [],
                    author: null,
                    instructors: [],
                    partner: null,
                    visibility: ('visibility' in this.experience) ? (this.experience.visibility) : 1,
                    companies: [],
                    areas: [],
                    positions: [],
                    available_from: null,
                    available_to: null,
                    status: false,
                    price_default: 0,
                    features: {
                        hours_content: {
                            time_value: 0,
                            time_period: 'minutes'
                        },
                        num_videos: 0,
                        num_activities: 0,
                        num_content_downloads: 0
                    }
                },
                validator: {
                    name: {
                        required: true,
                        min: 2,
                        max: 50
                    },
                    summary: {
                        required: true,
                    },
                    video_teaser: {
                        required: false,
                    },
                    company_objetives: {
                        required: true
                    },
                    area_objetives: {
                        required: true
                    },
                    visibility: {
                        required: true
                    }
                }
            }
        },
        beforeMount() {
            this.filterTags = this.tag_list;
            this.filterAbility = this.abilities_list;
            this.filterCompanies = this.companies_list;
            const tags = _.get(this.experience, ['taxonomy_tags'], []);
            this.form.tags = tags.map(item => {
                return item.id;
            });
            const abilities = _.get(this.experience, ['taxonomy_ability'], []);
            this.form.abilities = abilities.map(item => {
                return item.id;
            });


            for (let key in this.experience) {
                if (this.form.hasOwnProperty(key) && this.experience[key] !== null) {
                    if (key === 'features') {
                        if (typeof this.experience[key] !== 'object') {
                            let features = JSON.parse(this.experience[key]);

                            if (typeof features.hours_content !== 'object') {
                                features.hours_content = {
                                    time_value: parseInt(features.hours_content),
                                    time_period: null,
                                    la_wea: 'fome'
                                }
                            }

                            this.form[key] = features;
                        }
                    } else {
                        this.form[key] = this.experience[key];
                    }
                }
            }

          const companies = _.get(this.experience, ['companies'], []);
          this.form.companies = companies.map(item => {
            return item.id;
          });

        },
        mounted() {
            this.setDataTo('author', this.author_list);
            this.setDataTo('partner', this.partner_list);
            this.setArrayDataTo('instructors', this.partner_list);
            switch (this.mode) {
                case 'create':
                    this.method = 'POST';
                    this.url = route('experiences.store');
                    break;
                case 'edit':
                    this.method = 'PUT';
                    this.url = route('experiences.update', [this.experience.id]);
                    break;
                default:
                    break;
            }
        },
        methods: {
            isInteger(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                    evt.preventDefault();
                } else {
                    return true;
                }
            },
            setDataTo(target, data_list) {
                this.form[target] = data_list.find(item => {
                    if (item.id === this.form[target]) {
                        return item;
                    }
                    return null;
                });
            },
            setArrayDataTo(target, data_list) {
                let array = [];

                this.form[target].forEach(value => {
                    data_list.find(item => {
                        if (item.id == value) {
                            array.push(item);
                        }
                    });
                });
                this.form[target] = array;
            },
            save() {
                this.$validator.validateAll().then(result => {
                    if (result) {
                        axios({
                            method: this.method,
                            url: this.url,
                            params: this.formatedParams(),
                        })
                            .then(response => {
                                this.$snotify.success('Experiencia guardada con éxito', 'Operación exitosa');
                                setTimeout(() => {
                                    window.location.href = route('experiences.index');
                                }, 2000)
                            })
                            .catch(error => {
                                this.$snotify.error('', 'Error al procesar la petición.');
                            });
                    } else {
                        this.$snotify.warning('Revise que el formulario esté correcto.', 'Error en el formulario');
                    }
                })
            },
            cancel() {
                window.location.href = route('experiences.index');
            },
            formatedParams() {
                let params;
                params = Object.assign({}, this.form);
                params.author = (typeof params.author === 'object') ? params.author.id : null;
                params.partner = (typeof params.partner === 'object') ? params.partner.id : null;
                params.instructors = params.instructors.map((item) => {
                    return item.id;
                });
                params.adventures = params.adventures.map((item) => {
                    return item.id;
                });
                params.empty_ability = _.isEmpty(params.abilities);
                params.empty_tags = _.isEmpty(params.tags);
                return params;
            },
            saveAbility() {
                if (_.isEmpty(this.abilityValue)) {
                    return false
                }
                let data = {
                    id: this.abilityValue,
                    title: this.abilityValue
                }
                this.abilities_list.push(data)
                this.form.abilities.push(data.id)
                return false
            },
            getFilteredAbility(text) {
                this.filterAbility = this.abilities_list.filter(option => {
                    return index.search(text, option, ['title'])
                })
                if (_.isEmpty(this.filterAbility)) {
                    this.abilityValue = text
                } else {
                    this.abilityValue = null
                }
            },
            saveTag() {
                if (_.isEmpty(this.tagValue)) {
                    return false
                }
                let data = {
                    id: this.tagValue,
                    title: this.tagValue
                }
                this.tag_list.push(data)
                this.form.tags.push(data.id)
                return false
            },
            getFilteredTags(text) {
                this.filterTags = this.tag_list.filter(option => {
                    return index.search(text, option, ['title'])
                })
                if (_.isEmpty(this.filterTags)) {
                    this.tagValue = text
                } else {
                    this.tagValue = null
                }
            },
            getFilteredCompanies(text) {
              this.filterCompanies = this.companies_list.filter(option => {
                return index.search(text, option, ['title'])
              })
              if (_.isEmpty(this.filterCompanies)) {
                this.companieValue = text
              } else {
                this.companyValue = null
              }
            },
        },
    }
</script>

<style scoped>
    .col-12 {
        margin-bottom: 2rem;
    }

    .experience-image {
        height: 212px;
        overflow: hidden;
    }
</style>

<style>
    .dropdown-menu {
        display: block;
    }
</style>
