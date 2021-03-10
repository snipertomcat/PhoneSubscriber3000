<template>
  <div class="">
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
                    placeholder="Escribe un nombre para la sesión">
                </b-input>
              </b-field>
            </div>
            <div class="col-12 col-md-3">
              <label class="has-text-weight-bold">{{ $t('messages.type') }}</label>
              <div class="row width-100 font-14">
                <div class="select width-100">
                  <select v-model="form.type" class="width-100">
                    <option :value="null">Selecciona un tipo de sesión</option>
                    <option :value="type.value" v-for="type in sessions_type_list">
                      {{ type.value }}
                    </option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12">
            <b-field :label="$t('messages.summary')" :type="{'is-danger':errors.has('summary')}" :message="errors.first('summary')">
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
          <div class="col-12">
            <label class="has-text-weight-bold">{{ $t('messages.resourse') }}</label>
            <b-field>
              <p class="control">
                <span class="button is-static">http://</span>
              </p>
              <b-input v-model="form.resource_url" placeholder="Escribe un recurso" expanded></b-input>
            </b-field>
          </div>
          <hr>
          <div class="col-12">
            <label class="has-text-weight-bold">{{ $t('messages.abilities') }}</label>
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
                <p v-if="abilityValue" class="has-text-weight-bold pointer text-center" @click="saveAbility">click aqui para crear habilidad</p>
                <p v-if="!abilityValue" class="has-text-weight-bold text-center">Escriba una nueva habilidad</p>
              </div>
            </el-select>
          </div>
          <hr>
          <div class="col-12">
            <label class="has-text-weight-bold">{{ $t('messages.tags') }}</label>
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
                <p v-if="tagValue" class="has-text-weight-bold pointer text-center" @click="saveTag">click aqui para crear etiqueta</p>
                <p v-if="!tagValue" class="has-text-weight-bold text-center">Escriba una nueva etiqueta</p>
              </div>
            </el-select>
          </div>
          <hr>
          <div class="col-12 row">
            <div class="col-12">
              <label class="has-text-weight-bold">{{ $t('messages.cover') }}</label>
            </div>
            <div class="col-4 session-image">
              <apithy-image-upload
                  :id="1"
                  input_name="cover"
                  field="cover"
                  name="cover"
                  :url="`${route('sessions.cover.update', [experience.system_id, session.system_id])}`"
                  :width="880"
                  :height="530"
                  :image="form.full_path_cover">
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
                :options="authorList"
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
              {{ $t('messages.partner') }}
            </label>
            <v-tags-input
                v-model="form.partner"
                class="font-14"
                placeholder="Patrocinador"
                label="name"
                track-by="id"
                :options="partnerList"
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
              <label class="has-text-weight-bold width-100">Visibilidad</label>
              <div class="row width-100 font-14">
                <div class="col">
                  <b-radio v-model="form.visibility" v-validate="validator.visibility" :native-value="1" name="visibility" type="is-info">
                    Público
                  </b-radio>
                </div>
                <div class="col">
                  <b-radio v-model="form.visibility" v-validate="validator.visibility" :native-value="0" name="visibility" type="is-info">
                    Privado
                  </b-radio>
                </div>
              </div>
              <span class="text-danger" v-if="errors.has('visibility')">{{ errors.first('visibility') }}</span>
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
    <br><br>
  </div>
</template>

<script>
import 'vue-multiselect/dist/vue-multiselect.min.css'
import vue from 'vue'
import { index } from "../../../helpers";
import { Select, Option } from 'element-ui'

vue.component(Select.name, Select)
vue.component(Option.name, Option)

export default {
  name: "SessionForm",
  components: {
    'apithy-image-upload': () => import('../../ImageUpload'),
    'apithy-entity-asignator': import('../EntityAsignator'),
    'v-tags-input': () => import('vue-multiselect'),
  },
  beforeMount () {
    this.filterTags = this.tagsList
    this.filterAbility = this.abilitiesList
    const tags = _.get(this.session, ['taxonomy_tags'], []);
    this.form.tags = tags.map(item => {
      return item.id;
    });
    const abilities = _.get(this.session, ['taxonomy_ability'], []);
    this.form.abilities = abilities.map(item => {
      return item.id;
    });
    for (var key in this.session) {
      if (this.form.hasOwnProperty(key) && this.session[key] !== null) {
        this.form[key] = this.session[key]
      }
    }
  },
  mounted() {
    this.setDataTo('author',this.authorList);
    this.setDataTo('partner',this.partnerList);
    switch (this.mode) {
      case 'create':
        this.method = 'POST';
        this.url = route('sessions.store',[this.experience.system_id]).toString();
        break;
      case 'edit':
        this.method = 'PUT';
        this.url = route('sessions.update',[this.experience.system_id,this.session.system_id]).toString();
        break;
      default: break;
    }
  },
  methods: {
    addAbility (newAbility) {
      const ability = {
        title: newAbility,
      };
      this.form.abilities.push(ability);
    },
    setDataTo (target, data_list) {
      this.form[target] = data_list.find(item => {
        if (item.id === this.form[target]) {
          return item;
        }
        return null;
      });
    },
    save () {
      this.$validator.validateAll().then(result => {
        if (result) {
          axios({
            method: this.method,
            url: this.url,
            params: this.formatedParams(),
          })
              .then(response => {
                console.log(response.data)
                this.$snotify.success('Sesión guardada con éxito','Operación exitosa');
                setTimeout(() => {
                  window.location.href = route('sessions.index',[this.experience.system_id]);
                }, 2000)
              })
              .catch(error => {
                this.$snotify.error('','Error al procesar la petición.');                            });
        } else {
          this.$snotify.warning('Revise que el formulario esté correcto.','Error en el formulario');
        }
      })
    },
    cancel () {
      window.location.href = route('sesions.index');
    },
    formatedParams () {
      let params;
      params = Object.assign({},this.form);
      params.author = (typeof params.author === 'object') ? params.author.id : null;
      params.partner = (typeof params.partner === 'object') ? params.partner.id : null;
      params.empty_ability = _.isEmpty(params.abilities);
      params.empty_tags = _.isEmpty(params.tags);
      return params;
    },
    saveAbility () {
      if (_.isEmpty(this.abilityValue)) {
        return false
      }
      let data = {
        id: this.abilityValue,
        title: this.abilityValue
      }
      this.abilitiesList.push(data)
      this.form.abilities.push(data.id)
      return false
    },
    getFilteredAbility (text) {
      this.filterAbility = this.abilitiesList.filter(option => {
        return index.search(text, option, ['title'])
      })
      if (_.isEmpty(this.filterAbility)) {
        this.abilityValue = text
      } else {
        this.abilityValue = null
      }
    },
    saveTag () {
      if (_.isEmpty(this.tagValue)) {
        return false
      }
      let data = {
        id: this.tagValue,
        title: this.tagValue
      }
      this.tagsList.push(data)
      this.form.tags.push(data.id)
      return false
    },
    getFilteredTags (text) {
      this.filterTags = this.tagsList.filter(option => {
        return index.search(text, option, ['title'])
      })
      if (_.isEmpty(this.filterTags)) {
        this.tagValue = text
      } else {
        this.tagValue = null
      }
    }
  },
  computed: {
    now () {
      let date = new Date();
      return date.toLocaleDateString();
    },
    experience_status () {
      return (this.form.status);
    }
  },
  props: {
    session: {
      type: Object,
      required: true
    },
    sessions_type_list: {
      type: Array,
      required: true
    },
    experience: {
      type: Object,
      required: true
    },
    abilitiesList: {
      type: Array,
      required: true
    },
    tagsList: {
      type: Array,
      default() {
        return [];
      }
    },
    authorList: {
      type: Array,
      default() {
        return [];
      }
    },
    partnerList: {
      type: Array,
      default() {
        return [];
      }
    },
    mode: {
      type: String,
      required: true
    }
  },
  data () {
    return {
      filterTags: [],
      tagValue: null,
      filterAbility: [],
      abilityValue: null,
      form: {
        title: null,
        type: null,
        summary: null,
        description: null,
        resource_url: null,
        full_path_cover: null,
        abilities: [],
        tags: [],
        author: null,
        partner: null,
        available_from: null,
        available_to: null,
        experience_id: this.experience.id,
        visibility: 0,
        status: 0,
      },
      method: null,
      url: null,
      validator: {
        name: {
          required: true,
          min: 2,
          max: 50
        },
        summary: {
          required: true,
        },
        visibility: {
          required: true
        }
      }
    }
  }
}
</script>

<style scoped>
  .col-12 {
    margin-bottom: 2rem;
  }
  .session-image {
    height: 212px;
    overflow: hidden;
  }
</style>

<style>
  .dropdown-menu {
    display: block;
  }
</style>