<template>
  <div>
    <div class="row">
      <div class="col-lg-12">
        <b-field :label="$t('messages.taxonomy.users.user_label')"></b-field>
      </div>
      <div class="col-lg-12">
        <div class="width-100 ari-border">
          <div class="pt-3"></div>
          <div class="mr-2 ml-2">
            <p class="has-text-weight-bold pt-1 pb-1 mb-1 pl-2 pr-2">{{countUsers}}</p>
          </div>
          <div v-bar class="vb ari-height-420">
            <div class="">
              <div class="mr-2 ml-2" v-for="user in users" :key="user.id">
                <p class="pt-1 pb-1 mb-1 pl-2 pr-2 pointer" @click="removeFromList(user)">
                  {{user.full_name}}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row pt-2 mr-3 ml-3 no-gutters justify-content-between width-100">
        <div class="col-auto">
          <b-button
              class="has-text-weight-bold pr-0 text-uppercase no-text-decoration"
              type="is-text"
              :disabled="loader"
              @click="clear">
            <p class="ari-blue">{{ $t('messages.taxonomy.form.user_clean') }}</p>
          </b-button>
        </div>
        <div class="col-auto">
          <b-button
              class="ari-save-button has-text-white"
              :icon-left="theIcon"
              icon-pack="fa"
              @click="showConfirm(true)"
              :disabled="loader">
            <p class="has-text-weight-bold">{{theText}}</p>
          </b-button>
        </div>
      </div>
    </div>
    <apithy-modal
        :key="0"
        :active.sync="showModal"
        :can-cancel="false"
        :is-loading="loader"
        :has-overflow="false"
        @show-apithy-modal="showConfirm">
      <template slot="body">
        <section class="modal-card-body pl-4 pr-4 no-scrollable">
          <header class="modal-card-head force-white no-borders pl-0">
            <p class="modal-card-title has-text-weight-bold">{{modalTitle}}</p>
          </header>
          <div class="">
            <p>{{modalText}}</p>
          </div>
          <div class="">
            <div class="row mr-0 ml-0 mb-2 justify-content-end no-gutters pt-4">
              <div class="col-3">
                <b-button
                    class="full-width has-text-white ari-save-button"
                    @click="syncUsers"
                    :icon-left="theIcon"
                    icon-pack="fa"
                    :disabled="loader">
                  <p class="has-text-weight-bold">{{theText}}</p>
                </b-button>
              </div>
            </div>
            <div class="row mr-0 ml-0 justify-content-end">
              <b-button
                  class="has-text-weight-bold text-right pr-0 text-uppercase no-text-decoration"
                  type="is-text"
                  :disabled="loader"
                  @click="showConfirm(false)">
                <p class="ari-blue">{{ $t('messages.taxonomy.form.cancel') }}</p>
              </b-button>
            </div>
          </div>
        </section>
      </template>
    </apithy-modal>
  </div>
</template>

<script>
import {bus} from "../../../app_platform";
import ApithyModal from "../../ApithyModal";
import {TaxonomyService} from "../../../services/taxonomy/TaxonomyService";
import Vue from 'vue'
import Vuebar from 'vuebar';
Vue.use(Vuebar);

export default {
  name: "SelectedUsersTaxonomy",
  components: {ApithyModal},
  mounted () {
    bus.$on('add-selected-users', ([option, users]) => {
      this.users = users
      this.option = option
    })
    bus.$on('has-selected-users', hasSelectedUsers => {
      if (!hasSelectedUsers) {
        this.users = []
      }
    })
    bus.$on('taxonomy-selected-data', taxonomy => {
      this.taxonomy = taxonomy
    })
  },
  computed: {
    countUsers () {
      if (this.option === 1) {
        return this.$t('messages.taxonomy.users.users_with_tag', {quantity: this.users.length})
      }
      return this.$t('messages.taxonomy.users.users_without_tag', {quantity: this.users.length})
    },
    theIcon () {
      if (this.option === 1) {
        return 'user-times'
      }
      return 'user-plus'
    },
    theText () {
      if (this.option === 1) {
        return this.$t('messages.taxonomy.form.user_remove')
      }
      return this.$t('messages.taxonomy.form.user_add')
    },
    modalTitle () {
      if (this.option === 1) {
        return this.$t('messages.taxonomy.form.user_remove_title')
      }
      return this.$t('messages.taxonomy.form.user_add_title')
    },
    modalText () {
      let total = this.users.length
      if (this.option === 1) {
        return this.$t('messages.taxonomy.messages.selected_remove_users', {quantity: total, taxonomy: this.taxonomy.title})
      }
      return this.$t('messages.taxonomy.messages.selected_add_users', {quantity: total, taxonomy: this.taxonomy.title})
    }
  },
  methods: {
    syncUsers () {
      this.loader = true
      this.showConfirm(false)
      let form = {
        id: this.taxonomy.id,
        users: this.users,
        type: this.type,
        option: this.option
      }
      this.$snotify.async(this.$t('messages.taxonomy.messages.update_body'), this.$t('messages.taxonomy.messages.update_title'), () => new Promise((resolve, reject) => {
        TaxonomyService.syncUsers(form)
            .then(response => {
              resolve({
                title: this.$t('messages.taxonomy.messages.success_update'),
                config: {
                  closeOnClick: true,
                  timeout: 3000,
                  backdrop: 0.6
                }
              })
              this.loader = false
              this.users = []
              bus.$emit('sync-users-taxonomy', true)
            })
            .catch(errors => {
              this.loader = false
              reject({
                title: this.$t('messages.taxonomy.messages.error_update'),
                config: {
                  closeOnClick: true,
                  timeout: 3000,
                  backdrop: 0.6
                }
              })
            });
      }), {backdrop: 0.6})
    },
    showConfirm (value) {
      this.showModal = value
    },
    removeFromList (user) {
      let hasData = !_.isEmpty(this.users)
      if (hasData && !this.loader) {
        this.users = _.remove(this.users, x => x.id !== user.id)
        let users = Object.assign([], this.users)
        bus.$emit('remove-selected-users', users)
      }
    },
    clear () {
      this.users = []
      this.loader = false
      this.showModal = false
      bus.$emit('remove-selected-users', Object.assign([], this.users))
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
      taxonomy: [],
      option: 1,
      loader: false,
      showModal: false
    }
  }
}
</script>

<style type="text/css" scoped>
  @import "ari-taxonomy-css.css";
  @import "../../../styles/vuebar.css";
</style>