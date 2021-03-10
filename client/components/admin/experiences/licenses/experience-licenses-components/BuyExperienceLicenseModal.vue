<template>
  <b-modal :active.sync="showBuyedModal" :width="900">
    <div class="card">
      <div class="card-content">
        <div class="media">
          <div class="media-content">
            <p class="title is-4">Tipo de licencia</p>
          </div>
        </div>
        <div class="content">
          <div class="row">
            <div class="col-12">
              <span>¿Quieres adquirir la experiencia </span>
              <span class="has-text-weight-bold">{{title}}</span>
              <span> para uso personal o de tu empresa?</span>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-12 col-md-6">
              <a class="button is-primary width-100 shadow text-white"
                 @click.stop="purchase">
                Uso personal
              </a>
            </div>
            <div class="col-12 col-md-6">
              <a class="button is-primary width-100 shadow text-white"
                 :href="assignLicense(id)">
                Uso empresarial
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </b-modal>
</template>

<script>
  export default {
    name: "BuyExperienceLicenseModal",
    computed: {
      showBuyedModal: {
        get () {
          return this.showModal
        },
        set (value) {
          this.$emit('show-buyed-modal', value)
        }
      }
    },
    methods: {
      assignLicense (id) {
        return route('experience.buy-licences', id)
      },
      purchase() {
        this.closeBuyModal()
        axios({
          method: 'POST',
          url: route('shopping-cart.add', this.id),
          params: {
            user: this.user_id
          }
        }).then(response => {
          this.is_cart_added = true;
          this.inCart();
          this.$snotify.confirm('Ir al checkout?', '¡Experiencia agregada!', {
            showProgressBar: true,
            closeOnClick: false,
            backdrop: 0.6,
            buttons: [
              {
                text: 'Si',
                action: () => this.goToCheckout(),
              },
              {
                text: 'Seguir aquí',
                action: (toast) => {
                  this.$snotify.remove(toast.id);
                }
              }
            ]
          })
        }).catch(e => {console.log(e)})
      },
      inCart() {
        let cookie;
        let re = new RegExp('cart_list' + "=([^;]+)");
        let value = re.exec(document.cookie);
        cookie = (value != null) ? JSON.parse( unescape(value[1]) ) : null;

        if(cookie) {
          Object.keys(cookie).filter((id) => {
            if (id == this.id) {
              this.already_in_cart = true;
            }
          });
        }
        this.already_in_cart = false;
      },
      goToCheckout() {
        let origin = window.location.origin;
        let path = '/shopping-cart/checkout';
        let url = origin + path;

        window.location.href = url;
      },
      closeBuyModal () {
        this.$emit('show-buyed-modal', false)
      }
    },
    props: {
      id: {
        required: true,
        // type: Number,
        default: 0
      },
      title: {
        required: true,
        type: String,
        default: ''
      },
      user_id: {
        required: true,
        type: Number,
        default: 0
      },
      showModal: {
        required: true,
        type: Boolean,
        default: false
      }
    },
    data () {
      return {
        already_in_cart: false
      }
    }
  }
</script>

<style scoped>

</style>