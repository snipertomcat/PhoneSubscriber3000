<template>
  <div class="mt-3 mb-3">
    <div class="w-full px-2">
      <div class="question">{{component.title}}</div>
    </div>
    <tutorial :animationFor="component.component"></tutorial>
    <div class="w-full sort-image-container" :id="`sic_${this._uid}`">
      <div
        v-sortable="sortable_options"
        v-if="!loading"
        class="answers grid grid-cols-2 lg:grid-cols-3 gap-0 gap-lg-4 row-gap-4"
      >
        <div
          class="image-answer"
          v-for="(option, index) in component.answers"
          :class="[option.selected ?  'answer-label-checked': 'answer-label-unchecked']"
          :key="index"
          :data="option"
          :number="index + 1"
          @click="selectAnswer(option)"
        >
          <div class="answer">
            <div class="answer-image">
              <img :src="option.image" alt="option.configurations.imageData.title" />
            </div>
            <div class="answer-text" v-if="option.configurations.imageText">
              <span>{{option.configurations.imageText}}</span>
            </div>
          </div>
          <div class="indicator relative">
            <div class="absolute left-0">
              <i class="fas fa-grip-horizontal"></i>
            </div>
            <div class="num">{{index + 1}}</div>
          </div>
        </div>
      </div>
    </div>
    <div class="w-full flex justify-end">
      <div class="w-auto">
        <button
          class="btn sm:w-full lg:w-32 h-12 secondary-button"
          :class="{'disabled': sort_success}"
          @click="validateSort"
        >Verificar</button>
      </div>
    </div>
    <div class="eva-modal left-0" v-if="modals.show">
      <div class="background"></div>
      <div class="answer-modal w-full h-full">
        <div class="card">
          <div class="card-header flex justify-end items-center p-2">
            <!-- Close button -->
          </div>

          <div class="card-content flex flex-wrap content-around justify-center lg:px-64">
            <!-- Instructor image -->
            <!--img
              src="https://i.pinimg.com/564x/1e/62/88/1e6288fed858b3ed34e1e4fe574995f8.jpg"
              alt="Evan Peters"
              class="w-16 h-16 rounded-full object-cover"
            /-->

            <div class="w-full">
              <div class="w-24 h-24 mx-auto rounded-full object-cover">
                <img :src="evaluation_logo" alt />
              </div>
            </div>

            <div v-if="sort_success">
              <!-- Answer feedback title -->
              <h1 class="block w-full mt-2 text-3xl font-bold text-center">¡Correcto!</h1>

              <!-- Answer feedback text -->
              <p
                class="mt-2 text-center w-full"
              >Cada respuesta acertada suma a tus competencias, ¡sigue así!</p>

              <!-- Answer score
              <div class="w-full" :class="evaluation.configuration.score ? '' : 'invisible'">
                  <span class="block text-5xl text-center font-black text-yellow-dark">10</span>
                  <span class="block -mt-3 text-center">Puntos</span>
              </div>
              -->
            </div>
            <div v-else>
              <!-- Answer feedback title -->
              <h1 class="block w-full mt-2 text-3xl font-bold text-center">¡No!</h1>

              <!-- Answer feedback text -->
              <p
                class="mt-2 text-center w-full"
              >Recuerda que puedes revisar los retos cuantas veces sea necesario.</p>

              <!-- Answer score
              <div class="w-full" :class="evaluation.configuration.score ? '' : 'invisible'">
                  <span class="block text-5xl text-center font-black text-yellow-dark">10</span>
                  <span class="block -mt-3 text-center">Puntos</span>
              </div>
              -->
            </div>
          </div>

          <div class="card-footer flex items-center justify-between px-4">
            <!-- Retry button -->
            <button class="block icon-retry text-4xl text-blue-medium invisible"></button>

            <!-- Next question button -->
            <button class="block text-2xl text-blue-medium" @click="hideModal">
              <span>Aceptar</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "image-sort",
  props: {
    componentData: {
      required: true,
      type: Object
    },
    user_data: {
      required: false,
      Type: Object
    }
  },
  inject: ["getExperience"],
  components: {
    tutorial: () => import("./Tutorial.vue")
  },
  computed: {
    evaluation_logo() {
      let company = this.getExperience().company_author;
      return _.get(company, ["full_path_logo"], "/images/grow.svg");
    }
  },
  created() {
    this.getCorrectAnswers();
    this.component.answers = _.shuffle(this.component.answers);
    this.updateWeight();
  },
  mounted() {
    setTimeout(() => {
      this.getMinHeight();
    }, 1000);
  },
  methods: {
    getMinHeight() {
      let el = document.getElementById(`sic_${this._uid}`);
      el.style.minHeight = `${el.clientHeight}px`;
    },
    validateSort() {
      let sort_success = this.checkAnswer();
      if (sort_success) {
        this.sort_success = sort_success;
      }
      this.modals.show = true;
    },
    selectAnswer(option) {
      let check_answers = this.checkAnswer();
    },
    checkAnswer(event) {
      let real_answers = [],
        user_answers = [];
      _.forEach(this.correct_answers, answer => {
        real_answers.push({
          answer_id: answer.answer_id,
          order: answer.configurations.order.weight
        });
      });
      _.forEach(this.getUserAnswers(), answer => {
        user_answers.push({
          answer_id: answer.answer_id,
          order: answer.configurations.order.weight
        });
      });
      return _.isEqual(real_answers, user_answers);
    },
    getComponent(component_name) {
      return () => import(`./${component_name}.vue`);
    },
    getUserAnswers() {
      return this.component.answers;
    },
    getCorrectAnswers() {
      this.correct_answers = this.component.configurations.correct_answer;
    },
    sortableEnd(event) {
      let from = event.oldIndex,
        to = event.newIndex,
        array = this.component.answers,
        item = array[from];
      if (from < to) {
        array.splice(to + 1, 0, item);
        array.splice(from, 1);
      } else {
        array.splice(to, 0, item);
        array.splice(from + 1, 1);
      }
      this.updateWeight();
      this.reload();
    },
    reload() {
      this.loading = true;
      setTimeout(() => {
        this.loading = false;
      }, 10);
    },
    updateWeight() {
      _.each(this.component.answers, (answer, index) => {
        answer.configurations.order.weight = index;
      });
    },
    resetAnswer() {
      this.user_answers = null;
    },
    hideModal() {
      this.modals.show = false;
    }
  },
  data() {
    return {
      min_height: 0,
      user_answers: null,
      correct_answers: [],
      component: this.componentData,
      loading: false,
      sort_success: false,
      sortable_options: {
        handle: ".image-answer .indicator",
        onEnd: this.sortableEnd,
        forceFallback: true
      },
      modals: {
        show: false
      }
    };
  }
};
</script>

<style lang="scss" scoped>
.sort-image-container {
}

.answers {
  @apply py-4 text-center;
  @media (min-device-width: 700px) {
    //width: 560px;
  }

  .image-answer {
    @apply mx-auto;
    width: 90%;
    @media (min-device-width: 700px) {
      width: 11rem;
    }

    .indicator {
      @apply w-full h-6 text-center;
      background-color: #f9fafb;
      cursor: grab;

      .num {
        @apply w-auto;
      }
    }

    .answer {
      @apply w-full border rounded shadow-md;
      @media (min-device-width: 700px) {
        width: 11rem;
      }

      .answer-image {
        @apply flex w-full h-full justify-center items-center;
        @media (min-device-width: 700px) {
          height: 11rem;
        }
        font-size: 4rem;

        img {
          width: 125px;
          height: 125px;
          @media (min-device-width: 700px) {
            @apply w-full h-full;
          }
        }
      }

      .answer-text {
        @apply py-4;
        .image-text {
          @apply flex w-full px-2 justify-center items-center;
        }
      }
    }
  }
}

.answer-label span p {
  margin-bottom: unset;
}

.img-video {
  width: 274px;
  height: 164px;
}

.modal-background {
  background-color: rgba(2, 62, 137, 0.8) !important;
}

.m-close-background {
  background-color: transparent !important;
  margin-bottom: 5px;
}

.animation-content {
  z-index: 50;
}

.scrollable {
  overflow: auto;
}

.eva-modal .card .card-footer {
  @apply p-4 bg-yellow-lighter border-t-8;
  padding: 1rem !important;
  background-color: #fff8d2 !important;
  -moz-border-image: url("/img/border-crayon.png") 30 30 repeat; /* Old firefox */
  -webkit-border-image: url("/img/border-crayon.png") 30 30 repeat; /* Safari */
  -o-border-image: url("/img/border-crayon.png") 30 30 repeat; /* Opera */
  border-image: url("/img/border-crayon.png") 30 30 repeat;
}
</style>
