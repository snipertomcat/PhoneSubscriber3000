<template>
  <div class="row  ml-4 mr-4">
    <div class="col-lg-12 block-centered">
      <div class="row padding-tb-25 align-items-center">
        <div class="col-auto">
          <figure class="image is-64x64">
            <img :src="data.full_path_cover" class="mr-3">
          </figure>
        </div>
        <div class="col-auto">
          <h5 class="mt-0">
            <strong>{{ data.title }}</strong>
          </h5>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-4 col-sm-12">
          <buyed-and-free-licenses-table :id="id" :user_id="user_id" @buyed-licenses-data="buyedLicensesData"></buyed-and-free-licenses-table>
        </div>
        <div class="col-12 d-block d-lg-none">
          <div class="mt-4"></div>
        </div>
        <div class="col-lg-4 col-sm-12">
          <pending-licenses-table
              :id="id"
              :experiences="data"
              :pending_license_count.sync="data.pending_license_count"
              @change-pending-count="updatePendingLicence">
          </pending-licenses-table>
        </div>
        <div class="col-12 d-block d-lg-none">
          <div class="mt-4"></div>
        </div>
        <div class="col-lg-4 col-sm-12">
          <used-licenses-table
              :id="id"
              :used_license_count="data.used_license_count">
          </used-licenses-table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import BuyedAndFreeLicensesTable from "./experience-licenses-components/BuyedAndFreeLicensesTable";
  import PendingLicensesTable from "./experience-licenses-components/PendingLicensesTable";
  import UsedLicensesTable from "./experience-licenses-components/UsedLicensesTable";
  export default {
    name: "ApithyExperienceLicenses",
    components: {
      UsedLicensesTable,
      PendingLicensesTable,
      BuyedAndFreeLicensesTable
    },
    methods: {
      buyedLicensesData (data) {
        this.data = data
      },
      updatePendingLicence (pending_license_count) {
        this.data.pending_license_count = pending_license_count
        this.data.unused_license_count += 1
      }
    },
    props: {
      id: {
        required: true,
        type: Number
      },
      user_id: {
        required: true,
        type: Number
      }
    },
    data () {
      return {
        data: {}
      }
    }
  }
</script>

<style scoped>

</style>