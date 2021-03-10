<template>
  <b-modal :active.sync="isOpen" :has-modal-card="hasModalCard" :can-cancel="canCancel">
    <div>
      <div class="m-close-background">
        <div class="text-right has-text-white">
            <span class="pointer ml-0 mr-2" @click="closeModal">
              <span class="mr-2">Cerrar</span>
              <i class="fa fa-times" aria-hidden="true"></i>
            </span>
        </div>
      </div>
      <div class="tp-2"></div>
      <div :class="{
      'modal-card card': hasModalCard,
      'scrollable': hasOverflow,
      'no-scrollable': !hasOverflow
      }">
        <slot name="body"></slot>
      </div>
    </div>
  </b-modal>
</template>

<script>
  export default {
    name: "ApithyModal",
    computed: {
      isOpen: {
        get () {
          setTimeout(() => {
            let elements = document.getElementsByClassName('modal-close');
            for (let element of elements) {
              element.parentNode.removeChild(element)
            }
          }, 100)
          return this.active
        },
        set (value) {
          this.$emit('show-apithy-modal', value)
        }
      }
    },
    methods: {
      closeModal () {
        if (!this.isLoading) {
          this.isOpen = false
        }
      }
    },
    props: {
      hasModalCard: {
        type: Boolean,
        default: true
      },
      active: {
        required: true,
        type: Boolean
      },
      hasOverflow: {
        type: Boolean,
        default: true
      },
      isLoading: {
        type: Boolean,
        default: false
      },
      canCancel: {
        type: Boolean | Array,
        default:true
      }
    }
  }
</script>

<style scoped>

</style>

<style>
  .modal-background {
    background-color: rgba(2, 62, 137, 0.8) !important;
  }
  .m-close-background {
    background-color: transparent !important;
    margin-bottom: 5px;
  }
  .animation-content {
    z-index: 50
  }
  .scrollable {
    overflow-y: auto;
  }
  .no-scrollable {
    overflow: unset !important;
  }
</style>