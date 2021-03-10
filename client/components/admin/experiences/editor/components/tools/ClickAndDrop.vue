<template>
  <div class="">
    <div class="w-full">
      <textarea type="text"
                class="w-full instruction-area"
                v-model="component.title"
                rows="3"
                placeholder="Añade la instrucción o descripción de la actividad"
      ></textarea>
    </div>
    <div class="w-full flex mt-4">
      <div class="flex w-11/12">
        <div class="gallery-arrow" @click="prevItem">
          <div class="arrow-content">
            <div class="arrow-icon"><i class="fas fa-angle-left"></i></div>
            <div class="arrow-text">Anterior imagen</div>
          </div>
        </div>
        <div class="gallery-items">
          <template v-for="(item, index) in items">
            <div class="gallery-item" :class="{'selected': currentItem(index)}" :key="index">
              <div class="gallery-item-remove" @click="removeItem(index)">
                <i class="fas fa-times"></i>
              </div>
              <div class="gallery-item-content">
                <template v-if="hasImage(item)">
                  <div class="">
                    <img :src="item.image" :alt="item.title">
                  </div>
                </template>
                <template v-else>
                  <div class="placeholder" @click="addImageFor(`answer_${index}_${uuid}`)">
                    <div class="placeholder-icon">
                      <input :id="`answer_${index}_${uuid}`" type="file" class="hidden"
                             v-on:change="updateFile($event, item)">
                      <i class="fas fa-file-image"></i>
                    </div>
                    <div class="placeholder-text">
                      Utiliza una imagen de 900 x 900px
                    </div>
                  </div>
                </template>
              </div>
            </div>
          </template>
        </div>
        <div class="gallery-arrow" @click="nextItem">
          <div class="arrow-content">
            <div class="arrow-icon"><i class="fas fa-angle-right"></i></div>
            <div class="arrow-text">Siguiente imagen</div>
          </div>
        </div>
      </div>
      <div class="gallery-add-item">
        <div class="add-item-icon w-auto" @click="addItem">
          <i class="fas fa-plus"></i>
        </div>
      </div>
    </div>
    <div class="w-full mt-4">
      <div class="answers">
        <div class="correct-answers">
          <template v-for="(option, index) in options">
            <div class="answer" :key="index">
              <div class="answer-content">
                <input type="text" class="w-full" v-model="option.title" placeholder="Ingresa una respuesta">
              </div>
              <div class="remove-answer">
                <div class="add-option-icon w-auto" @click="removeOption(index)">
                  <div class="fas fa-trash"></div>
                </div>
              </div>
              <div class="add-answer">
                <div class="add-option-icon w-auto" @click="addOption">
                  <div class="fas fa-plus"></div>
                </div>
              </div>
            </div>
          </template>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import {index} from '../../../../../../helpers/index.js';

  export default {
    name: 'ClickAndDrop',
    props: {
      componentOptions: {
        type: Object,
        required: true
      }
    },
    computed: {
      items () {
        return this.component.answers
      },
      uuid () {
        return this._uid
      },
      upload_route () {
        let url = route('sessions.media.upload', {
          experience: this.$parent.getExperience('system_id'),
          session: this.$parent.getSession('system_id')
        });
        return url;
      },
    },
    created () {
      if (_.size(this.component.answers) < 1) {
        this.addItem()
      } else {
        this.selectItem(0)
      }
      this.counter = _.size(this.component.answers)
    },
    methods: {
      addItem () {
        let item = index.clone(this.component.configurations.default_answer)
        this.component.answers.push(item)
        this.selectItem(this.counter)
        this.counter += 1
      },
      removeItem (index) {
        setTimeout(() => {
          let item = this.items[index]
          if (!_.isEmpty(item) && !_.isNull(item) && item !== 'undefined') {
            for (let i = _.size(item.configurations.related_options); i > 0; i--) {
              item.configurations.related_options.splice(i-1, 1)
            }
          }
          this.items.splice(index, 1)
          this.counter -= 1
          if (_.size(this.items) < 1) {
            this.addItem()
          }
          this.selectItem(_.size(this.items)-1)
        }, 200)
      },
      selectItem (item) {
        this.selected.item = item
        this.options = this.getOptions(item)
      },
      currentItem (index) {
        return this.selected.item === index
      },
      prevItem (index) {
        let current_index = this.selected.item
        if (current_index > 0) {
          this.selectItem(current_index - 1)
        }
      },
      nextItem (index) {
        let current_index = this.selected.item
        if (current_index < (_.size(this.items) - 1)) {
          this.selectItem(current_index + 1)
        }
      },
      getOptions (item) {
        if (_.size(this.items) < 1) {
          return []
        }
        if (_.size(this.items[item].configurations.related_options) < 1) {
          this.addOption()
        }
        return this.items[item].configurations.related_options
      },
      addOption () {
        let option = index.clone(this.template.option)
        this.items[this.selected.item].configurations.related_options.push(option)
      },
      removeOption (index) {
        let related_options = this.items[this.selected.item].configurations.related_options;
        if (_.size(related_options) > 1) {
          related_options.splice(index, 1)
        }
      },
      hasImage (item) {
        return !_.isEmpty(item.image)
      },
      async updateFile (event, answer) {
        let file = _.first(event.target.files);
        answer.image = null;
        let uploaded_image = await this.uploadImage(file, answer)
        answer.configurations.imageData = uploaded_image.data
        answer.image = uploaded_image.data.full_path_url
      },
      addImageFor (element_id) {
        let input = document.getElementById(element_id);
        input.click();
      },
      uploadImage (file, answer) {
        let formData = new FormData();
        formData.append('uuid', this._uid);
        formData.append('title', `image-${file.name}`);
        formData.append('description', '');
        formData.append('media-type', 'image');
        formData.append('file', file);
        let options = {
          headers: {
            'Content-Type': 'multipart/form-data'
          },
          onUploadProgress: (progressEvent) => {
            if (progressEvent.lengthComputable) {
              console.log(progressEvent)
              let loaded = (progressEvent.loaded / progressEvent.total) * 100;
              answer.configurations.uploadPercent = parseInt(loaded);
              answer.configurations.uploadfinished = parseInt(loaded) === 100;
            }
          }
        };
        return axios.post(this.upload_route, formData, options)
      }
    },
    data () {
      return {
        component: this.componentOptions,
        counter: 0,
        selected: {
          item: 0
        },
        options: [],
        template: {
          option: {
            title: null
          }
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
  .instruction-area {
    resize: none;
  }
  .gallery-arrow {
    @apply w-3/12 flex items-center justify-center cursor-pointer;
    background: #F7F7F7;
    border: 3px dashed #A6B6C2;
    box-sizing: border-box;

    .arrow-content {
      @apply text-center;
      .arrow-icon {
        @apply text-5xl;
        color: #2D7EFC;
      }

      .arrow-text {
        @apply w-3/4 mx-auto text-2xl;
        color: #A6B6C2;
      }
    }
  }

  .gallery-items {
    @apply relative w-6/12 mx-4 flex items-center justify-center;
    background: #F7F7F7;
    border: 3px dashed #A6B6C2;
    box-sizing: border-box;

    .gallery-item {
      display: none;

      &.selected {
        display: block;
      }

      .gallery-item-remove {
        @apply flex items-center justify-center right-0 top-0 w-8 h-8 bg-red-600 text-gray-100 rounded-full cursor-pointer z-10;
        position: absolute !important;
      }

      .gallery-item-content {
        @apply z-0;
        .placeholder {
          @apply cursor-pointer;
          color: #A6B6C2;

          .placeholder-icon {
            @apply w-1/2 mx-auto text-5xl text-center;
          }

          .placeholder-text {
            @apply w-3/4 mx-auto text-2xl text-center;
          }
        }
      }
    }
  }

  .gallery-add-item {
    @apply w-1/12 flex items-center justify-center;
    .add-item-icon {
      @apply border border-solid rounded flex items-center justify-center cursor-pointer;
      width: 38px;
      height: 38px;
      border-color: #1A9AF7;
      color: #1A9AF7;
      &:hover {
        background-color: #1A9AF7;
        color: #FFFFFF;
      }
    }
  }

  .answers {
    @apply mb-4;
    .correct-answers {
      .answer {
        @apply flex items-center;
        &:not(:last-child) {
          margin-bottom: 1rem !important;
        }

        .answer-content {
          @apply w-10/12 p-2 border-2 border-solid rounded flex items-center;
          height: 66px;
          background: #EEFFE9;
          border-color: #4BEA9C;
          box-sizing: border-box;

          input {
            @apply bg-transparent;
            border: unset !important;
            color: #4D9218;

            &:focus {
              outline: unset !important;
              border-bottom: 1px solid #4D9218 !important;
            }
          }
        }

        .add-answer {
          @apply w-1/12 border border-solid rounded flex items-center justify-center cursor-pointer;
          display: none;
          width: 38px;
          height: 38px;
          border-color: #1A9AF7;
          color: #1A9AF7;
          &:hover {
            background-color: #1A9AF7;
            color: #FFFFFF;
          }
        }
        &:last-child {
          .add-answer {
            display: flex;
          }
        }

        .remove-answer {
          @apply w-1/12 flex items-center justify-center;
          color: #1A9AF7;

          .fas, .fa {
            @apply cursor-pointer;
            &:hover {
              @apply text-red-600;
            }
          }
        }
      }
    }
  }
</style>
