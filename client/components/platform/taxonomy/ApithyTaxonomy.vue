<template>
  <section class="pt-4">
    <div class="ml-5 width-100">
      <div class="ari-info-div-color width-90 div-inline">
        <p class="pt-2 mb-2 ml-2">
          {{$t('messages.taxonomy.messages.info_1')}}
          <a href="javascript:void(0)" @click="showModal(true)" class="ari-blue">
            {{$t('messages.taxonomy.messages.info_2')}}
          </a>
        </p>
      </div>
      <span class="ari-blue ml-2">
        <i class="icon-info-circle" aria-hidden="true"></i>
      </span>
    </div>
    <template v-if="type === 'index'">
      <el-tabs :lazy="true" type="border-card" v-model="activeTab">
        <el-tab-pane label="" disabled><div></div></el-tab-pane>
        <el-tab-pane :label="$t('messages.taxonomy.taxonomy_title.area')">
          <taxonomy-editor v-if="activeTab ==='1'" :type="'company_area'" :key="0"></taxonomy-editor>
        </el-tab-pane>
        <el-tab-pane :label="$t('messages.taxonomy.taxonomy_title.position')">
          <taxonomy-editor v-if="activeTab === '2'" :type="'company_position'" :key="1"></taxonomy-editor>
        </el-tab-pane>
      </el-tabs>
    </template>
    <template v-else>
      <div class="pt-3 ml-4">
        <taxonomy-editor :type="getType()" :key="2"></taxonomy-editor>
      </div>
    </template>
    <apithy-modal
        :key="0"
        :active.sync="modal"
        :can-cancel="false"
        :is-loading="loader"
        :has-overflow="false"
        @show-apithy-modal="showModal">
      <template slot="body">
        <header class="modal-card-head force-white no-borders pl-4 pr-4">
          <p class="modal-card-title has-text-weight-bold">{{$t('messages.taxonomy.form.create_taxonomy')}}</p>
        </header>
        <form class="" @submit.prevent="validateForm">
          <section class="modal-card-body pl-4 pr-4 no-scrollable">
            <div class="row align-items-end">
              <div class="col-8">
                <b-field
                    :label="$t('messages.taxonomy.form.create_title_label')"
                    :type="{'is-danger': errors.first('title')}"
                    :message="errors.first('title')">
                  <b-input
                      name="title"
                      :placeholder="$t('messages.taxonomy.form.title')"
                      v-model="form.title"
                      :data-vv-as="$t('messages.taxonomy.form.title_label')"
                      v-validate="form_rules.title"
                      :disabled="loader">
                  </b-input>
                </b-field>
              </div>
              <div class="col-4">
                <b-button
                    class="has-text-white full-width ari-save-button"
                    native-type="submit"
                    icon-left="save"
                    icon-pack="fas"
                    :disabled="errors.any() || loader">
                  <p class="has-text-weight-bold">{{ $t('messages.taxonomy.form.save') }}</p>
                </b-button>
              </div>
            </div>
            <div class="row justify-content-end pt-2">
              <div class="col-4 text-right">
                <b-button
                    class="has-text-weight-bold text-uppercase no-text-decoration"
                    type="is-text"
                    :disabled="loader"
                    @click="showModal(false)">
                  <p class="ari-blue">{{ $t('messages.taxonomy.form.cancel') }}</p>
                </b-button>
              </div>
            </div>
          </section>
        </form>
      </template>
    </apithy-modal>
  </section>
</template>

<script>
  import Vue from 'vue'
  import { Tabs, TabPane } from 'element-ui'
  import TaxonomyEditor from "./TaxonomyEditor";
  import ApithyModal from "../../ApithyModal";
  import {bus} from "../../../app_platform";
  import {index} from "../../../helpers";
  import {TaxonomyService} from "../../../services/taxonomy/TaxonomyService";

  Vue.component(Tabs.name, Tabs);
  Vue.component(TabPane.name, TabPane);

  export default {
    name: "ApithyAdminTaxonomy",
    components: {TaxonomyEditor, ApithyModal},
    beforeMount () {
      let params = index.getURLParams();
      if ('tab' in params) {
        switch (params.tab) {
          case 'positions':
            this.activeTab = '2';
            break;
          case 'areas':
          default:
            this.activeTab ='1';
            break
        }
      }

      let type = this.type
      if (type === 'abilities') {
        type = 'ability'
      }
      if (type === 'index') {
        if (this.activeTab === '1') {
          type = 'company_area'
        } else if(this.activeTab === '2') {
          type = 'company_position'
        }
      }
      this.setType(type)
    },
    watch: {
      activeTab (value) {
        if (this.type === 'index') {
          if (value === '1') {
            this.setType('company_area')
          } else if(value === '2') {
            this.setType('company_position')
          }
        }
      }
    },
    methods: {
      getType() {
        if (this.type === 'abilities') {
          return 'ability'
        }
        return this.type
      },
      setType (type) {
        this.form.type = type
      },
      validateForm () {
        this.$validator.validateAll()
            .then((result) => {
              if (result) {
                this.storeTaxonomy();
                return false;
              }
            }).catch(() => {});
      },
      storeTaxonomy () {
        this.loader = true
        this.$snotify.async(this.$t('messages.taxonomy.messages.store_body'), this.$t('taxonomy.messages.store_title'), () => new Promise((resolve, reject) => {
          TaxonomyService.storeTaxonomy(this.form)
              .then(response => {
                resolve({
                  title: this.$t('messages.taxonomy.messages.success_store'),
                  config: {
                    closeOnClick: true,
                    timeout: 3000,
                  }
                })
                this.showModal(false)
                bus.$emit('add-new-taxonomy', true)
              })
              .catch(errors => {
                this.loader = false
                let list = index.hasErrors(errors)
                if (list) {
                  index.setErrors(this.$validator.errors, list)
                }
                reject({
                  title: this.$t('messages.taxonomy.messages.error_store'),
                  config: {
                    closeOnClick: true,
                    timeout: 3000,
                  }
                })
              });
        }), {backdrop: 0.6})
      },
      showModal (value) {
        this.modal = value
        this.reset()
      },
      reset () {
        this.loader = false
        this.form.title = null
        this.$validator.reset()
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
        activeTab: '1',
        modal: false,
        loader: false,
        form: {
          title: null,
          type: null
        },
        form_rules: {
          title: 'required|max:191'
        }
      }
    }
  }
</script>

<style scoped>
@import "ari-taxonomy-css.css";
  .width-90 {
    width: 90%;
  }
  .div-inline {
    display: inline-block;
  }
  form .button {
    border-radius: 4px !important;
  }
</style>

<style>
  .page-content {
    background-color: white;
  }
  .el-tabs--border-card>.el-tabs__header .el-tabs__item.is-active {
    color: #F5A623 !important;
    font-family: Montserrat,serif !important;
    font-style: normal !important;
    font-weight: normal !important;
    border-right-color: #F5A623 !important;
    border-left-color: #F5A623 !important;
    border-top-color: #F5A623 !important;
    margin-top: auto !important;
    width: 143px;
    text-align: center;
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
  }
  .el-tabs--border-card>.el-tabs__header .el-tabs__item {
    color: #444444 !important;
    font-family: Montserrat,serif !important;
    font-style: normal !important;
    font-weight: normal !important;
    width: 143px;
    text-align: center;
    font-size: 18px;
  }
  .el-tabs--border-card>.el-tabs__header {
    border-bottom: 1px solid #F5A623 !important;
  }
  .el-tabs--border-card>.el-tabs__header {
    background-color: white !important;
  }
  .el-tabs--border-card {
    border: 1px solid transparent !important;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
  }
  .is-disabled {
    width: 53px !important;
  }
</style>