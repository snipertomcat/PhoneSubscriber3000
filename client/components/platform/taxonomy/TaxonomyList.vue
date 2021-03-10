<template>
  <div class="row">
    <div class="col-lg-12">
      <b-field :label="getTitleDescription()">
        <template v-if="!hasTaxonomy">
          <b-input
              :placeholder="$t('messages.taxonomy.form.select_taxonomy_input')"
              v-model.trim="pagination.search"
              type="search"
              icon="magnify">
          </b-input>
        </template>
        <template v-else>
          <div class="row">
            <div class="col-lg-1 col-md-1" @click="showEditModal(true)">
              <span class="">
                <i class="fa fa-pencil ari-edit pointer" aria-hidden="true"></i>
              </span>
            </div>
            <div class="col-lg-11 col-md-11">
              <p class="ari-text ari-radius pl-2 pr-2" :style="'background-color: ' + selectedTaxonomy.color">
                {{ selectedTaxonomy.title }}
                <span class="float-right">
                  <i class="fa fa-times pointer" aria-hidden="true" @click="unsetSelectedTaxonomy"></i>
                </span>
              </p>
            </div>
          </div>
        </template>
      </b-field>
    </div>
    <div class="col-lg-12 pt-2">
      <div class="width-100 ari-border">
        <b-loading :is-full-page="false" :active.sync="loader"></b-loading>
        <div class="pt-3"></div>
        <template v-if="!isEmptyTaxonomy">
          <div v-bar class="vb ari-height-420">
            <div class="">
              <div
                  class="mr-2 ml-2 pointer"
                  :class="{'ari-opacity': hasTaxonomy}"
                  v-for="item in taxonomy"
                  :key="item.id"
                  @click="setSelectedTaxonomy(item)">
                <p class="ari-text pt-2 pb-2 mb-3 pl-2 pr-2 ari-radius" :style="'background-color: ' + item.color">
                  {{item.title}}
                </p>
              </div>
            </div>
          </div>
          <div class="text-center" v-if="moreTaxonomy">
            <b-button type="is-text" @click="getByTaxonomy">
              {{ $t('messages.taxonomy.messages.show_more_taxonomy') }}
            </b-button>
          </div>
        </template>
        <template v-else-if="isEmptyTaxonomy && !loader">
          <div class="mr-2 ml-2">
            <p class="ari-text pt-2 pb-2 mb-3 pl-2 pr-2 ari-radius text-center">
              {{ $t('messages.taxonomy.messages.empty_taxonomy_list') }}
            </p>
          </div>
        </template>
      </div>
    </div>
    <apithy-modal
        :key="0"
        :active.sync="showEdit"
        :can-cancel="false"
        :is-loading="loader"
        :has-overflow="false"
        @show-apithy-modal="showEditModal">
      <template slot="body">
        <header class="modal-card-head force-white no-borders pl-4 pr-4">
          <p class="modal-card-title has-text-weight-bold">
            {{ $t('messages.taxonomy.form.edit_taxonomy') }}
          </p>
        </header>
        <form class="" @submit.prevent="validateForm">
          <section class="modal-card-body pl-4 pr-4 no-scrollable">
            <b-field
                :label="$t('messages.taxonomy.form.title')"
                :type="{'is-danger': errors.first('title')}"
                :message="errors.first('title')">
              <b-input
                  name="title"
                  v-model="editTaxonomy.title"
                  :data-vv-as="$t('messages.taxonomy.form.title_label')"
                  v-validate="edit_rules.title"
                  :disabled="disableEdit">
              </b-input>
            </b-field>
            <div>
              <div>
                <label class="has-text-weight-bold">
                  {{ $t('messages.taxonomy.form.color') }}
                </label>
              </div>
              <div>
                <b-dropdown hoverable aria-role="list" :disabled="disableEdit">
                  <button type="button" class="button" slot="trigger" @click="showDrop = !showDrop">
                    <span class="ari-color-span mr-2" :style="'background-color: ' + editTaxonomy.color"></span>
                    <span>
                    <i class="fa fa-sort-desc" aria-hidden="true"></i>
                  </span>
                  </button>
                </b-dropdown>
              </div>
              <div class="drop-menu-color" :class="{'d-block': showDrop, 'd-none': !showDrop}">
                <div class="row ml-0 mr-0 no-gutters">
                  <div class="col-auto" v-for="(color, index) in predefineColors" :key="index">
                    <div class="color pointer" :style="'background-color: ' + color" @click="setColor(color)"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="pt-4 ari-blue pointer" @click="confirmDeleteTaxonomy" :disabled="disableEdit">
              <span>
                <i class="fa fa-trash" aria-hidden="true"></i>
              </span>
              <span class="has-text-weight-bold">
                {{ $t('messages.taxonomy.form.delete_taxonomy') }}
              </span>
            </div>
            <div>
              <div class="row mr-0 ml-0 mb-2 justify-content-end no-gutters">
                <div class="col-3">
                  <b-button
                      class="full-width ari-save-button"
                      native-type="submit"
                      icon-left="save"
                      icon-pack="fas"
                      :disabled="errors.any() || disableEdit">
                    <p class="has-text-white has-text-weight-bold">
                      {{ $t('messages.taxonomy.form.save') }}
                    </p>
                  </b-button>
                </div>
              </div>
              <div class="row mr-0 ml-0 justify-content-end">
                <b-button
                    class="has-text-weight-bold text-right pr-0 text-uppercase no-text-decoration"
                    type="is-text"
                    :disabled="disableEdit"
                    @click="showEditModal(false)">
                  <p class="ari-blue">{{ $t('messages.taxonomy.form.cancel') }}</p>
                </b-button>
              </div>
            </div>
          </section>
        </form>
      </template>
    </apithy-modal>
  </div>
</template>

<script>
import {TaxonomyService} from "../../../services/taxonomy/TaxonomyService";
import {bus} from "../../../app_platform";
import {index} from "../../../helpers";
import ApithyModal from "../../ApithyModal";
import Vue from 'vue'
import Vuebar from 'vuebar';
Vue.use(Vuebar);

export default {
  name: "TaxonomyList",
  components: {ApithyModal},
  beforeMount () {
    let type = this.type
    if (type === 'company_area') {
      type = 'areas'
    } else if(type === 'company_position') {
      type = 'positions'
    } else if (type === 'ability') {
      type = 'abilities'
    }
    this.pagination.type = type
    this.getByTaxonomy()
  },
  mounted () {
    bus.$on('add-new-taxonomy', taxonomy => {
      this.clear()
    })
  },
  watch: {
    'pagination.search' (value) {
      this.searchTaxonomy()
    }
  },
  computed: {
    hasTaxonomy () {
      let hasTaxonomy = !_.isEmpty(this.selectedTaxonomy)
      bus.$emit('taxonomy-has-selected-taxonomy', hasTaxonomy)
      return hasTaxonomy
    },
    isEmptyTaxonomy () {
      let isEmpty = _.isEmpty(this.taxonomy)
      bus.$emit('taxonomy-has-taxonomy-list', isEmpty)
      return isEmpty
    },
    moreTaxonomy () {
      return !this.disableTaxonomy && !_.isEmpty(this.taxonomy)
    }
  },
  methods: {
    getTitleDescription () {
      let aux = '_description'
      let title = 'messages.taxonomy.taxonomy_title'
      switch (this.type) {
        case 'company_area':
          title = `${title}.area${aux}`
          return this.$t(title)
        case 'company_position':
          title = `${title}.position${aux}`
          return this.$t(title)
        case 'ability':
          title = `${title}.ability${aux}`
          return this.$t(title)
        case 'certifications':
          title = `${title}.certification${aux}`
          return this.$t(title)
        case 'teams':
          title = `${title}.team${aux}`
          return this.$t(title)
        case 'custom':
          title = `${title}.custom${aux}`
          return this.$t(title)
        case 'tag':
          title = `${title}.tag${aux}`
          return this.$t(title)
      }
    },
    validateForm () {
      this.$validator.validateAll()
          .then((result) => {
            if (result) {
              this.updateTaxonomy();
              return false;
            }
          }).catch(() => {});
    },
    confirmDeleteTaxonomy () {
      this.disableEdit = true
      this.$snotify.confirm(
          this.$t('messages.taxonomy.messages.confirm_taxonomy_delete_message', {taxonomy: this.editTaxonomy.title}),
          this.$t('messages.taxonomy.messages.confirm_taxonomy_delete_title'),
          {
            closeOnClick: true,
            backdrop: 0.6,
            buttons: [
              {text: this.$t('messages.taxonomy.form.accept'), action: (toast) => {this.$snotify.remove(toast.id); this.deleteTaxonomy()}},
              {text: this.$t('messages.taxonomy.form.cancel'), action: (toast) => {this.$snotify.remove(toast.id); this.disableEdit = false}},
            ]
          }
      )
    },
    deleteTaxonomy() {
      this.disableEdit = true
      this.$snotify.async(this.$t('messages.taxonomy.messages.delete_body'), this.$t('messages.taxonomy.messages.delete_title'), () => new Promise((resolve, reject) => {
        TaxonomyService.deleteTaxonomy(this.editTaxonomy.id)
            .then(response => {
              resolve({
                title: this.$t('messages.taxonomy.messages.success_delete'),
                config: {
                  closeOnClick: true,
                  timeout: 3000,
                }
              })
              this.clear()
            })
            .catch(errors => {
              this.disableEdit = false
              reject({
                title: this.$t('messages.taxonomy.messages.error_delete'),
                config: {
                  closeOnClick: true,
                  timeout: 3000,
                }
              })
            });
      }), {backdrop: 0.6})
    },
    updateTaxonomy () {
      this.disableEdit = true
      let form = Object.assign({}, this.editTaxonomy)
      form.type = this.type
      this.$snotify.async(this.$t('messages.taxonomy.messages.update_body'), this.$t('messages.taxonomy.messages.update_title'), () => new Promise((resolve, reject) => {
        TaxonomyService.updateTaxonomy(form)
            .then(response => {
              resolve({
                title: this.$t('messages.taxonomy.messages.success_update'),
                config: {
                  closeOnClick: true,
                  timeout: 3000,
                }
              })
              this.clear()
            })
            .catch(errors => {
              this.disableEdit = false
              let list = index.hasErrors(errors)
              if (list) {
                index.setErrors(this.$validator.errors, list)
              }
              reject({
                title: this.$t('messages.taxonomy.messages.error_update'),
                config: {
                  closeOnClick: true,
                  timeout: 3000,
                }
              })
            });
      }), {backdrop: 0.6})
    },
    clear () {
      this.disableEdit = false
      this.showDropdown(false)
      this.showEditModal(false)
      this.selectedTaxonomy = {}
      this.editTaxonomy = {}
      this.$validator.errors.clear()
      this.getByTaxonomy()
    },
    setColor (color) {
      this.editTaxonomy.color = color
      this.showDropdown(false)
    },
    showDropdown (value) {
      this.showDrop = value;
    },
    showEditModal (value) {
      this.editTaxonomy = Object.assign({}, this.selectedTaxonomy)
      this.showDropdown(false)
      this.showEdit = value
    },
    unsetSelectedTaxonomy () {
      this.selectedTaxonomy = {}
    },
    setSelectedTaxonomy (taxonomy) {
      if (!this.hasTaxonomy) {
        this.selectedTaxonomy = taxonomy
        bus.$emit('taxonomy-selected-data', taxonomy)
      }
    },
    searchTaxonomy: _.debounce(function () {
      this.pagination.page = 1
      this.disablePaginator = false
      this.taxonomy = []
      this.getByTaxonomy()
    }, 500),
    getByTaxonomy () {
      this.getTaxonomy()
    },
    getTaxonomy () {
      this.taxonomy = []
      this.loader = true;
      TaxonomyService.getByTaxonomy(this.pagination)
          .then(response => {
            let last_page = _.get(response, ['data', 'meta', 'last_page'], 1)
            if (this.pagination.page === last_page) {
              this.disableTaxonomy = true
            } else {
              this.pagination.page ++
            }
            let taxonomy = _.get(response, ['data', 'data'], [])
            _.forEach(taxonomy, (item, key) => {
              this.taxonomy.push(item)
            })
          })
          .catch(errors => {
            //
          })
          .then(() => {
            this.loader = false
          })
    }
  },
  props: {
    type: {
      type: String,
      default: 'company_area'
    }
  },
  data () {
    return {
      showDrop: false,
      showEdit: false,
      taxonomy: [],
      editTaxonomy: {},
      selectedTaxonomy: {},
      filter_option: null,
      loader: false,
      disableTaxonomy: false,
      disableEdit: false,
      predefineColors: [
        '#FFD79D',
        '#FFE89E',
        '#FE9EA2',
        '#8FD2FE',
        '#FEB396',
        '#BFC7B1',
        '#6FAAFB',
        '#D6CDF0',
        '#9DE5C0',
        '#DEF1BC'
      ],
      edit_rules: {
        id: 'required',
        title: 'required|max:191',
        color: 'required'
      },
      pagination: {
        page: 1,
        search: null,
        type: null,
        ajax: 1
      }
    }
  }
}
</script>

<style scoped>
  @import "../../../styles/vuebar.css";
  .force-white {
    background-color: white !important;
  }
  form .button {
    border-radius: 4px !important;
  }
  .ari-save-button {
    background: #1A6BF7;
    box-shadow: 2px 2px 6px rgba(88, 88, 88, 0.413949);
    border-radius: 4px;
  }
  .ari-blue {
    color: #1C6BF8;
  }
  .ari-color-span {
    width: 138px;
    height: 25px;
    left: 421px;
    top: 417px;
  }
  .no-text-decoration {
    text-decoration: none !important;
  }
  .no-scrollable {
    overflow: unset !important;
  }
  .drop-menu-color {
    position: absolute;
    margin-top: 0.25rem;
    width: 205px;
    border-radius: 5px;
    overflow: hidden;
    background: #FFFFFF;
    box-shadow: -4px 4px 12px rgba(68, 68, 68, 0.3);
  }
  .drop-menu-color .row {
    max-height: 250px;
    overflow-y: auto;
  }
  .drop-menu-color .color {
    width: 24px;
    height: 24px;
    margin: 8px;
    border-radius: 4px;
  }
</style>

<style>
  .dropdown-content {
    max-height: 200px;
    overflow-y: auto;
  }
  .dropdown-menu {
    display: none!important;
  }
</style>