<template>
  <div class="container">
    <div class="row ml-2 mr-2">
      <div class="col-lg-12">
        <div v-if="!hasTaxonomy" class="pb-2 text-title-info">
          {{ $t('messages.taxonomy.messages.tag_info') }}
        </div>
      </div>
      <div class="col-lg-4">
        <taxonomy-list :type="type"></taxonomy-list>
      </div>
      <div class="col-lg-4">
        <user-taxonomy-list :type="type" v-show="hasTaxonomy"></user-taxonomy-list>
      </div>
      <div class="col-lg-4">
        <selected-users-taxonomy :type="type" v-show="hasSelectedUsers && hasTaxonomy"></selected-users-taxonomy>
      </div>
    </div>
  </div>
</template>

<script>
import TaxonomyList from "./TaxonomyList";
import {bus} from "../../../app_platform";
import UserTaxonomyList from "./UserTaxonomyList";
import SelectedUsersTaxonomy from "./SelectedUsersTaxonomy";

export default {
  name: "TaxonomyEditor",
  components: {SelectedUsersTaxonomy, UserTaxonomyList, TaxonomyList},
  mounted () {
    bus.$on('taxonomy-has-selected-taxonomy', hasTaxonomy => {
      this.hasTaxonomy = hasTaxonomy
    })
    bus.$on('has-selected-users', hasSelectedUsers => {
      this.hasSelectedUsers = hasSelectedUsers
    })
  },
  props: {
    type: {
      type: String,
      default: 'company_area'
    }
  },
  data () {
    return {
      hasTaxonomy: false,
      hasSelectedUsers: false
    }
  }
}
</script>

<style type="text/css" scoped>
  @import "ari-taxonomy-css.css";
  .text-title-info {
    opacity: 0.5;
  }
</style>