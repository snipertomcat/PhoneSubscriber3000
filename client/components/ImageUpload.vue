<template>
  <div class="">
    <vue-image-crop-upload
        @src-file-set="srcFileSet"
        @crop-success="cropSuccess"
        @crop-upload-success="cropUploadSuccess"
        @crop-upload-fail="cropUploadFail"
        @click="toggleShow"
        v-model="show_uploader"
        :url="url"
        :method="method"
        :field="field"
        :params="params"
        :headers="headers"
        :lang-type="langType"
        :langExt="langExt"
        :width="width"
        :height="height"
        :img-format="imgFormat"
        :img-bgc="imgBgc"
        :no-circle="noCircle"
        :no-square="noSquare"
        :no-rotate="noRotate"
        :with-credentials="withCredentials">
    </vue-image-crop-upload>
    <img :id="tag_id" class="image pointer" :src="img_src" @click="toggleShow" />
  </div>
</template>

<script>
import _ from 'lodash'
  export default {
    name:'apithy-image-upload',
    components: {
      'vue-image-crop-upload': () => import('vue-image-crop-upload')
    },
    beforeMount () {
      this.tag_id = `avatar-${this.input_name}`
      this.img_src = this.image
    },
    methods: {
      toggleShow () {
        this.show_uploader = !this.show_uploader
      },
      cropSuccess (imgDataUrl, field) {
        this.$emit('cropSuccess', imgDataUrl, field)
      },
      cropUploadSuccess (jsonData, field) {
        this.$emit('cropUploadSuccess', jsonData, field)
        this.img_src = _.get(jsonData, ['src'], '')
      },
      cropUploadFail (status, field) {
        this.$emit('cropUploadFail', status, field)
      },
      srcFileSet (fileName, fileType, fileSize) {
        this.$emit('srcFileSet', fileName, fileType, fileSize)
      }
    },
    props: {
      input_name: {
        type: String,
        default: 'avatar'
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
      headers: {
        type: Object,
        default: function () {
          return {
            'X-Requested-With': 'XMLHttpRequest'
          }
        }
      },
      langType: {
        type: String,
        default: 'es-MX'
      },
      langExt: {
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
      imgFormat: {
        type: String,
        default: 'png'
      },
      imgBgc: {
        type: String,
        default: '#fff'
      },
      noCircle: {
        type: Boolean,
        default: false
      },
      noSquare: {
        type: Boolean,
        default: false
      },
      noRotate: {
        type: Boolean,
        default: false
      },
      withCredentials: {
        type: Boolean,
        default: false
      },
      image: {
        type: String,
        default:'',
      },
      maxFileSize: {
        type: Number,
        default: 5242880
      }
    },
    data () {
      return {
        tag_id: 'avatar',
        show_uploader: false,
        img_src: ''
      }
    },
  };
</script>

<style>
  .vue-image-crop-upload .vicp-wrap .vicp-operate a {
    color: #FFA81E !important;
  }
  .vue-image-crop-upload .vicp-wrap .vicp-step2 .vicp-crop .vicp-crop-left .vicp-range input[type=range]::-webkit-slider-thumb {
    background-color: #FFA81E !important;
  }
  .vue-image-crop-upload .vicp-wrap .vicp-step2 .vicp-crop .vicp-crop-left .vicp-range input[type=range]::-moz-range-thumb {
    background-color: #FFA81E !important;
  }
  .vue-image-crop-upload .vicp-wrap .vicp-step2 .vicp-crop .vicp-crop-left .vicp-range input[type=range]::-ms-thumb {
    background-color: #FFA81E !important;
  }
  .vue-image-crop-upload .vicp-wrap .vicp-step2 .vicp-crop .vicp-crop-left .vicp-range input[type=range]::-webkit-slider-runnable-track {
    background-color: rgba(255, 168, 30, 0.3) !important;
  }
  .vue-image-crop-upload .vicp-wrap .vicp-step2 .vicp-crop .vicp-crop-left .vicp-range input[type=range]::-moz-range-track {
    background-color: rgba(255, 168, 30, 0.3) !important;
  }
  .vue-image-crop-upload .vicp-wrap .vicp-step2 .vicp-crop .vicp-crop-left .vicp-range input[type=range]::-ms-fill-lower {
    background-color: rgba(255, 168, 30, 0.3) !important;
  }
  .vue-image-crop-upload .vicp-wrap .vicp-step2 .vicp-crop .vicp-crop-left .vicp-range input[type=range]::-ms-fill-upper {
    background-color: rgba(255, 168, 30, 0.15) !important;
  }
  .vue-image-crop-upload .vicp-wrap .vicp-step2 .vicp-crop .vicp-crop-left .vicp-range input[type=range]:focus::-webkit-slider-runnable-track {
    background-color: rgba(255, 168, 30, 0.5) !important;
  }
  .vue-image-crop-upload .vicp-wrap .vicp-step2 .vicp-crop .vicp-crop-left .vicp-range input[type=range]:focus::-moz-range-track {
    background-color: rgba(255, 168, 30, 0.5) !important;
  }
  .vue-image-crop-upload .vicp-wrap .vicp-step2 .vicp-crop .vicp-crop-left .vicp-range input[type=range]:focus::-ms-fill-lower {
    background-color: rgba(255, 168, 30, 0.45) !important;
  }
  .vue-image-crop-upload .vicp-wrap .vicp-step2 .vicp-crop .vicp-crop-left .vicp-range input[type=range]:focus::-ms-fill-upper {
    background-color: rgba(255, 168, 30, 0.25) !important;
  }
</style>