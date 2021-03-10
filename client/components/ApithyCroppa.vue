<template>
  <div class="">
    <img :id="tag_id" alt="cover" class="image pointer" :src="img_src" @click="showModal(true)" />
    <apithy-modal
        :key="0"
        :active.sync="show_modal"
        :can-cancel="false"
        :is-loading="loader"
        :has-overflow="false"
        @show-apithy-modal="showModal">
      <template slot="body">
        <div class="row pt-3 pb-3 pl-3 pr-3">
          <div class="col-lg-12">
            <div class="">
              <h2>Seleccionar imagen</h2>
            </div>
          </div>
          <div class="col-lg-12 pt-2">
            <div class="text-center">
              <croppa
                  v-model="croppa"
                  @file-choose="removeError"
                  @new-image="removeError"
                  @image-remove="removeError"
                  :file-size-limit="fileSizeLimit"
                  @file-size-exceed="onFileSizeExceed"
                  @file-type-mismatch="onFileTypeMismatch"
                  :accept="accept"
                  :width="width"
                  :height="height"
                  :quality="quality">
              </croppa>
            </div>
          </div>
          <div v-if="show_error" class="col-lg-12 pt-2">
            <div class="text-center has-text-danger">
              {{ error_text }}
            </div>
          </div>
          <div class="col-lg-12 pt-2">
            <div class="text-right">
              <b-button type="is-light" @click="showModal(false)" :disabled="loader">Cancelar</b-button>
              <b-button type="is-info" :disabled="loader" @click="upload">{{ uploadText }}</b-button>
            </div>
          </div>
        </div>
      </template>
    </apithy-modal>
  </div>
</template>

<script>
import _ from 'lodash'
import Vue from 'vue'
import Croppa from 'vue-croppa'
import ApithyModal from "./ApithyModal";
Vue.use(Croppa)
export default {
  name: 'ApithyCroppa',
  components: {
    ApithyModal
  },
  beforeMount () {
    this.tag_id = `avatar-${this.input_name}`
    this.img_src = this.image
  },
  computed: {
    uploadText () {
      if (this.loader) {
        return 'Cargando'
      }
      return 'Cargar'
    }
  },
  methods: {
    showModal (show) {
      this.show_modal = show
    },
    onFileSizeExceed (file) {
      this.setErrorMessage('El tamaño máximo para la imagen es de 5 MB.')
    },
    onFileTypeMismatch (file) {
      this.setErrorMessage(`Sólo se admiten los siguientes formatos de imagen ${this.accept}`)
    },
    removeError () {
      this.show_error = false
      this.error_text = null
    },
    setErrorMessage (message) {
      this.error_text = message
      this.show_error = true
    },
    upload () {
      if (!this.croppa.hasImage()) {
        this.setErrorMessage('Debe seleccionar una imagen')
        return
      }
      this.croppa.generateBlob(blob => {
        this.loader = true
        let ext = _.split(blob.type, '/')
        ext = _.get(ext, ['2'], 'png')
        let fd = new FormData()
        fd.append(this.field, blob, `filename.${ext}`)
        axios.post(this.url, fd)
            .then(response => {
              this.img_src = _.get(response, ['data', 'src'], '')
              this.showModal(false)
            })
            .catch(errors => {
              let errs = index.hasErrors(errors)
              if (errs) {
                let message = _.get(errs, [this.field, '0'])
                this.this.setErrorMessage(message)
              }
            })
            .then(() => {
              this.loader = false
            })
      })
    }
  },
  props: {
    image: {
      type: String,
      default:'',
    },
    input_name: {
      type: String,
      default: function () {
        return _.random(20)
      }
    },
    fileSizeLimit: {
      type: Number,
      default: 5242880
    },
    url: {
      type: String,
      default: ''
    },
    method: {
      type: String,
      default: 'POST'
    },
    field: {
      type: String,
      default: 'upload'
    },
    params: {
      type: Object,
      default: null
    },
    width: {
      type: Number,
      default: 200
    },
    height: {
      type: Number,
      default: 200
    },
    quality: {
      type: Number,
      default: 1
    },
    accept: {
      type: String,
      default: ''
    }
  },
  data () {
    return {
      error_text: null,
      show_error: false,
      show_modal: false,
      loader: false,
      tag_id: 'avatar',
      show_uploader: false,
      img_src: '',
      croppa: null
    }
  }
}
</script>

<style scoped>
  @import "~vue-croppa/dist/vue-croppa.css";
</style>