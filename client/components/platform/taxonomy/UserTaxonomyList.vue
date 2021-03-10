<template>
  <div>
    <div class="row">
      <div class="col-lg-12">
        <b-field :label="$t('messages.taxonomy.form.users_label')">
          <el-select class="width-100" v-model="pagination.option" :placeholder="$t('messages.taxonomy.form.show_users')">
            <el-option
                v-for="item in options"
                :disabled="loader"
                :key="item.id"
                :label="item.label"
                :value="item.id">
            </el-option>
          </el-select>
        </b-field>
      </div>
      <div class="col-lg-12 pt-2">
        <b-input
            :disabled="loader"
            :placeholder="$t('messages.taxonomy.form.search_user')"
            v-model.trim="pagination.search"
            type="search"
            icon="magnify">
        </b-input>
      </div>
      <div class="col-lg-12 pt-2">
        <template v-if="!isEmptyUsers">
          <div class="width-100 ari-border">
            <div class="pt-3"></div>
            <div class="mr-2 ml-2">
              <p class="has-text-weight-bold pt-1 pb-1 mb-1 pl-2 pr-2">{{countUsers}}</p>
            </div>
            <div v-bar class="vb ari-height-252">
              <div class="">
                <div class="mr-2 ml-2" v-for="user in users" :key="user.id">
                  <p class="pt-1 pb-1 mb-1 pl-2 pr-2 pointer" @click="addToList(user)">
                    {{user.full_name}}
                  </p>
                </div>
              </div>
            </div>
            <div class="row mb-2 mr-2 ml-2">
              <div class="col-12">
                <div class="width-100 text-center">
                  <el-pagination
                      class="element"
                      :disabled="loader"
                      @current-change="handleCurrentChange"
                      :hide-on-single-page="false"
                      :current-page.sync="pagination.page"
                      layout="prev, pager, next"
                      :page-count="pagination.last_page">
                  </el-pagination>
                </div>
              </div>
              <div class="col-12 pt-1">
                <div class="width-100">
                  <b-select class="text-right" v-model="pagination.per_page">
                    <option :key="0" :value="15">
                      {{ $t('messages.taxonomy.form.quantity_show_paginator', {quantity: 15}) }}
                    </option>
                    <option :key="1" :value="30">
                      {{ $t('messages.taxonomy.form.quantity_show_paginator', {quantity: 30}) }}
                    </option>
                    <option :key="2" :value="45">
                      {{ $t('messages.taxonomy.form.quantity_show_paginator', {quantity: 45}) }}
                    </option>
                    <option :key="3" :value="60">
                      {{ $t('messages.taxonomy.form.quantity_show_paginator', {quantity: 60}) }}
                    </option>
                  </b-select>
                </div>
              </div>
            </div>
          </div>
        </template>
        <template v-else-if="loader">
          <div class="mr-2 ml-2">
            <p class="ari-text pt-2 pb-2 mb-3 pl-2 pr-2 ari-radius text-center">
              {{ $t('messages.taxonomy.messages.loader_message') }}
            </p>
          </div>
        </template>
        <template v-else>
          <div class="mr-2 ml-2">
            <p class="ari-text pt-2 pb-2 mb-3 pl-2 pr-2 ari-radius text-center">
              {{ $t('messages.taxonomy.messages.empty_results') }}
            </p>
          </div>
        </template>
      </div>
    </div>
  </div>
</template>

<script>
import Vue from 'vue'
import {bus} from "../../../app_platform";
import {TaxonomyService} from "../../../services/taxonomy/TaxonomyService";
import {Pagination} from 'element-ui';
import {Select} from 'element-ui';
import {Option} from 'element-ui';
import Vuebar from 'vuebar';
Vue.use(Vuebar);

Vue.component(Pagination.name, Pagination)
Vue.component(Select.name, Select);
Vue.component(Option.name, Option);

export default {
  name: "UserTaxonomyList",
  mounted () {
    this.pagination.type = this.type;
    bus.$on('taxonomy-has-selected-taxonomy', value => {
      if (!value) {
        this.reset()
      }
      this.hasTaxonomy = value
    })
    bus.$on('taxonomy-selected-data', taxonomy => {
      this.taxonomy = taxonomy
      this.pagination.id = taxonomy.id
      this.getUsers()
    })
    bus.$on('remove-selected-users', users => {
      this.selectedUsers = users
      bus.$emit('has-selected-users', !_.isEmpty(this.selectedUsers))
    })
    bus.$on('sync-users-taxonomy', event => {
      this.selectedUsers = []
      bus.$emit('has-selected-users', !_.isEmpty(this.selectedUsers))
      this.getUsers()
    })
  },
  watch: {
    'pagination.search' (value) {
      if (this.hasTaxonomy) {
        this.searchUser()
      }
    },
    'pagination.option' (value) {
      this.pagination.page = 1
      if (this.hasTaxonomy) {
        this.selectedUsers = []
        bus.$emit('has-selected-users', this.hasSelectedUsers)
        this.getUsers()
      }
    },
    'pagination.per_page' (value) {
      this.handleSizeChange(value)
    }
  },
  computed: {
    isEmptyUsers () {
      return _.isEmpty(this.users)
    },
    hasSelectedUsers () {
      let hasSelectedUsers = !_.isEmpty(this.selectedUsers)
      bus.$emit('has-selected-users', hasSelectedUsers)
      return hasSelectedUsers
    },
    countUsers () {
      if (this.pagination.option === 1) {
        return this.$t('messages.taxonomy.users.selected_users', {quantity: this.pagination.total})
      }
      return this.$t('messages.taxonomy.users.selected_users', {quantity: this.pagination.total})
    }
  },
  methods: {
    addToList (user) {
      let exist = _.find(this.selectedUsers, x => x.id === user.id)
      if (_.isEmpty(exist)) {
        this.selectedUsers.push(Object.assign({}, user))
        bus.$emit('add-selected-users', [this.pagination.option, Object.assign([], this.selectedUsers)])
        bus.$emit('has-selected-users', this.hasSelectedUsers)
      }
    },
    handleSizeChange (value) {
      if (this.hasTaxonomy) {
        this.pagination.per_page = value
        this.pagination.page = 1
        this.getUsers()
      }
    },
    handleCurrentChange (value) {
      if (this.hasTaxonomy) {
        this.getUsers()
      }
    },
    getUsers() {
      this.loader = true
      this.users= []
      TaxonomyService.getUserTaxonomy(this.pagination)
          .then(response => {
            this.users = _.get(response, ['data', 'data'], [])
            this.pagination.total = _.get(response, ['data', 'meta', 'total'], 0)
            this.pagination.last_page = _.get(response, ['data', 'meta', 'last_page'], 1)
          })
          .catch(errors => {})
          .then(() => {
            this.loader = false
          })
    },
    searchUser: _.debounce(function () {
      this.pagination.page = 1
      this.getUsers()
    }, 500),
    reset() {
      bus.$emit('has-selected-users', false)
      this.selectedUsers = []
      this.pagination = {
        id: null,
        option: 1,
        page: 1,
        type: null,
        search: null,
        per_page: 15,
        total: 0,
        last_page: 1
      }
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
      users: [],
      selectedUsers: [],
      taxonomy: {},
      loader: false,
      hasTaxonomy: false,
      options: [
        {id: 1, label: this.$t('messages.taxonomy.form.user_remove_title')},
        {id: 2, label: this.$t('messages.taxonomy.form.user_add_title')}
      ],
      pagination: {
        id: null,
        option: 1,
        page: 1,
        type: null,
        search: null,
        per_page: 15,
        total: 0,
        last_page: 1
      }
    }
  }
}
</script>

<style type="text/css" scoped>
  @import "ari-taxonomy-css.css";
  @import "../../../styles/vuebar.css";
</style>
