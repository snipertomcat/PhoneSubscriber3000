<template>
  <div class="tutorial mt-2">
    <div class="help-button-container">
      <div class="help-button" @click="openModal">
        <i class="fa fa-question"></i>
      </div>
    </div>
    <div class="eva-modal left-0" v-if="modals.show">
      <div class="background"></div>
      <div class="animation-modal">
        <div class="animation-container">
          <div class="card">
            <div class="card-header">
              <div class="w-1/12"></div>
              <div class="header-title">
                <i class="fa fa-question"></i>
              </div>
              <div class="close-modal">
                <i class="fa fa-times" @click="closeModal"></i>
              </div>
            </div>
            <div class="card-content">
              <div class="tutorial-description">
                <div class="w-full text-center">Cómo usar esta actividad</div>
              </div>
              <template v-if="has_animation_file">
                <template v-if="display_animation">
                  <div class="animation">
                    <lottie :options="animation_options" :width="animation_width" :height="animation_height"
                            @animCreated="handleAnimation"></lottie>
                  </div>
                </template>
                <template v-else>
                  <div class="animation-start">
                    <button class="secondary-button" @click="showAnimation">Ver tutorial</button>
                  </div>
                </template>
              </template>
              <template v-else>
                <div class="animation-error">
                  <div class=""> La animación no pudo cargarse.</div>
                </div>
              </template>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import * as clickDropAnim from '../../../../../../static_json/lottie_animations/clickAndDrop.json';
  import * as sortImagesAnim from "../../../../../../static_json/lottie_animations/sortImages.json";

  export default {
    name: "Tutorial",
    props: {
      animationFor: {
        type: String,
        required: true
      }
    },
    components: {
      'lottie': () => import('vue-lottie')
    },
    computed: {
      has_animation_file () {
        let animationData = this.animation_options.animationData
        return (!_.isNull(animationData) && !_.isEmpty(animationData) && 'undefined' !== animationData)
      }
    },
    beforeMount () {
      this.loadAnimation()
    },
    methods: {
      openModal () {
        this.modals.show = true
        this.showAnimation()
      },
      closeModal () {
        this.modals.show = false
        this.stopLottie()
      },
      loadAnimation () {
        if (this.animationFor === 'ClickAndDrop') {
          this.animation_options.animationData = clickDropAnim
        }
        if (this.animationFor === 'SortImages') {
          this.animation_options.animationData = sortImagesAnim
        }
      },
      showAnimation () {
        if (window.innerWidth > 700) {
          this.animation_width = 285
          this.animation_height = 400
        }
        if (window.innerWidth < 700) {
          this.animation_width = 250
          this.animation_height = 300
        }
        if (window.innerWidth < 370) {
          this.animation_width = 150
          this.animation_height = 200
        }
        this.display_animation = true
      },
      handleAnimation (animation) {
        this.animation = animation
        //this.stopLottie()
      },
      playLottie () {
        this.animation.play()
      },
      stopLottie () {
        this.animation.stop()
      },
      pauseLottie () {
        this.animation.pause()
      }
    },
    data () {
      return {
        animation: null,
        animation_width: 100,
        animation_height: 100,
        animation_options: {
          animationData: null
        },
        display_animation: false,
        modals: {
          show: false
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
  .tutorial {
    .help-button-container {
      @apply relative h-10;
      .help-button {
        @apply absolute w-10 h-10 top-0 right-0 flex justify-center items-center mr-4 rounded-full;
        background-color: #D1D8E0;

        &:hover {
          @apply cursor-pointer shadow-md;
        }

        font-size: 1.3rem;
      }
    }

    .animation-container {
      @apply absolute top-0 left-0 w-full h-full bg-white;
      .card-header {
        @apply h-16 flex justify-center items-center p-2;
        background-color: #FFF9D8;
        @media (min-device-width: 700px) {
          @apply h-32;
          .header-title {
            @apply text-3xl;
          }
          .close-modal {
            @apply text-2xl;
          }
        }

        .header-title {
          @apply w-5/6 flex justify-center text-2xl;
          @media (min-device-width: 700px) {
            @apply text-4xl;
          }
        }

        .close-modal {
          @apply relative flex justify-center m-0 p-0 w-1/12 cursor-pointer;
          top: unset;
          right: unset;
          font-size: 2rem;
          color: #1A9AF7;
        }
      }

      .card-content {
        @apply px-4;
        .tutorial-description {
          @media (min-device-width: 700px) {
            font-size: 2rem;
          }
        }
        .animation-start {
          @apply flex items-center justify-center mt-12;
          @media (min-device-width: 700px) {
            @apply mt-48;
            button {
              @apply w-2/3 h-16;
            }
          }

          button {
            @apply w-full text-center h-12;
          }
        }

        .animation {
          @apply mt-8;
        }
      }
    }
  }
</style>
