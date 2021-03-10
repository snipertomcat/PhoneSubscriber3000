<template>
  <div>
    <div class="card">
      <div class="card-content">
        <div class="row">
          <div class="col-7 license-title">
            <span class="has-text-weight-bold">Licencias compradas</span>
          </div>
          <div class="col-5">
            <div class="row">
              <div class="col-12 text-center">
                <h1>
                  <strong>{{data.licenses_count}}</strong>
                </h1>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-12 text-center">
                <button class="button is-primary col-sm-12" @click="openBuyModal">Comprar</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <br>
    <div class="card">
      <div class="card-content">
        <div class="row">
          <div class="col-7 license-title">
            <span class="has-text-weight-bold">Licencias disponibles</span>
          </div>
          <div class="col-5">
            <div class="row">
              <div class="col-12 text-center">
                <h1>
                  <strong>{{data.unused_license_count}}</strong>
                </h1>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-12 text-center">
                <button class="button is-primary col-sm-12" @click="toBuyLicense">Asignar</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <buy-experience-license-modal
        ref="buyModal"
        :id="data.system_id"
        :user_id="user_id"
        :title="data.title"
        :show-modal.sync="show_buy_modal"
        @show-buyed-modal="showBuyedModal">
    </buy-experience-license-modal>
  </div>
</template>

<script>
  import BuyExperienceLicenseModal from "./BuyExperienceLicenseModal";
  export default {
    name: "BuyedAndFreeLicensesTable",
    components: {BuyExperienceLicenseModal},
    beforeMount () {
      this.getExperience()
    },
    methods: {
      toBuyLicense () {
        window.location = route('experience.buy-licences', this.id)
      },
      getExperience () {
        this.isLoading = true
        axios.get(route('experience.show.experience', this.id))
            .then(response => {
              this.data = response.data.data
              this.$emit('buyed-licenses-data', this.data)
            })
            .catch(e => {})
            .then(() => {
              this.isLoading = false
            })
      },
      showBuyedModal (value) {
        this.show_buy_modal = value
      },
      openBuyModal () {
        this.show_buy_modal = true;
      },
      closeBuyModal () {
        this.show_buy_modal = false;
      }
    },
    props: {
      id: {
        required: true,
        type: Number,
        default: 0
      },
      user_id: {
        required: true,
        type: Number,
        default: 0
      }
    },
    data () {
      return {
        data: {},
        isLoading: false,
        show_buy_modal: false
      }
    }
  }
</script>

<style scoped>
.license-title {
  padding-top: 10px;
}
</style>