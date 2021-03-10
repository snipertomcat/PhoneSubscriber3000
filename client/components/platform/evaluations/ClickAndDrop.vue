<template>
  <div class>
    <div class="gallery-instruction">
      <div class="instruction w-full font-18" v-if="!!component.title">{{ `${component.title}` }}</div>
      <div
        class="instruction w-full font-18"
        v-else
      >{{ 'Selecciona las opciones correspondientes a la imágen' }}</div>
    </div>
    <div class="gallery-indicator">
      <div class="indicator bg-yellow-lighter">{{`${selected.item + 1}/${data.answers.length}`}}</div>
    </div>
    <div class="gallery-content">
      <div class="gallery-arrow left w-1/12" :class="{'disabled': prev_item}">
        <i class="fas fa-angle-left" @click="prevItem"></i>
      </div>
      <div class="gallery-items w-auto">
        <template v-for="(item, index) in items">
          <div
            class="gallery-item"
            :class="{'selected': currentItem(index)}"
            :key="index"
            @click="showModalOptions"
            :style="`background-image: url(${item.image})`"
          ></div>
        </template>
      </div>
      <div class="gallery-arrow right w-1/12" :class="{'disabled': next_item}">
        <i class="fas fa-angle-right" @click="nextItem"></i>
      </div>
    </div>
    <div class="eva-modal select-options" :class="{'show': modal.show}">
      <div class="background"></div>
      <div class="card">
        <div class="card-header">
          <div class="header-content">
            <div class="w-full font-bold font-21" v-if="false">Actividad 2.34</div>
            <div class="w-full font-18" v-if="!!component.title && false">{{ `${component.title}` }}</div>
            <div class="w-full font-18" v-else>
              Ahora, selecciona tu(s) respuestas. Para volver a empezar, da clic en
              <i>Limpiar</i>
            </div>
          </div>
          <div class="close" @click="hideModalOptions">
            <i class="fas fa-times"></i>
          </div>
        </div>
        <div class="card-content">
          <div class="items">
            <template v-for="(item, index) in available_options">
              <div class="item" :class="{'selected': isSelected(item)}" @click="selectOption(item)">
                <div class="w-1/2 lg:w-10/12">
                  <div class="flex">
                    <div class="w-auto">
                      <div class="checkbox" :class="{'selected': isSelected(item)}">
                        <template v-if="isSelected(item)">
                          <i class="fas fa-check-square text-4xl"></i>
                        </template>
                      </div>
                    </div>
                    <div class="w-full px-4 item-text">{{ `${item.title}` }}</div>
                  </div>
                </div>
              </div>
            </template>
          </div>
        </div>
        <div class="card-footer">
          <div class="accept-buttton">
            <button @click="hideModalOptions">Aceptar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "ClickAndDrop",
  props: {
    data: {
      required: true,
      type: Object
    },
    user_data: {
      required: false,
      Type: Object
    }
  },
  computed: {
    component() {
      return this.data;
    },
    items() {
      return this.data.answers;
    },
    prev_item() {
      return !(this.selected.item > 0);
    },
    next_item() {
      return !(this.selected.item < _.size(this.items) - 1);
    },
    selected_options() {
      return _.get(
        this.items,
        [this.selected.item, "configurations", "selected_options"],
        []
      );
    }
  },
  methods: {
    checkAnswer(event) {
      let correct = false;
      for (let i = 0; i < _.size(this.data.answers); i++) {
        let item_configurations = this.data.answers[i].configurations;
        correct = this.arraysEqual(
          item_configurations.related_options,
          item_configurations.selected_options,
          "title"
        );
        if (!correct) {
          return correct;
        }
      }
      return correct;
    },
    arraysEqual(_arr1, _arr2, param = null) {
      if (
        !Array.isArray(_arr1) ||
        !Array.isArray(_arr2) ||
        _arr1.length !== _arr2.length
      )
        return false;

      let arr1 = _arr1.concat().sort((a, b) => {
        if (a.title.toLowerCase() > b.title.toLowerCase()) {
          return 1;
        }
        if (a.title.toLowerCase() < b.title.toLowerCase()) {
          return -1;
        }
        return 0;
      });
      let arr2 = _arr2.concat().sort((a, b) => {
        if (a.title.toLowerCase() > b.title.toLowerCase()) {
          return 1;
        }
        if (a.title.toLowerCase() < b.title.toLowerCase()) {
          return -1;
        }
        return 0;
      });

      for (let i = 0; i < arr1.length; i++) {
        let path = [i];
        if (!_.isNull(param) && !_.isEmpty(param)) {
          path.push(param);
        }
        //if (arr1[i] !== arr2[i])
        if (_.get(arr1, path) !== _.get(arr2, path)) return false;
      }
      return true;
    },
    getUserAnswers() {
      let user_answers = [];
      _.each(this.items, answer => {
        let tmp = {
          answer_id: _.get(answer, ["id"], 0),
          configurations: answer.configurations
        };
        user_answers.push(tmp);
      });
      return user_answers;
    },
    isSelected(item) {
      return !!_.find(this.selected_options, item);
    },
    selectOption(item) {
      let selected_options = this.items[this.selected.item].configurations
        .selected_options;
      let already_in = _.findIndex(selected_options, item);
      if (already_in >= 0) {
        selected_options.splice(already_in, 1);
      } else {
        selected_options.splice(already_in, 0, item);
      }
      this.getOptions();
    },
    getOptions() {
      this.getAvailableOptions();
      this.available_options = _.sortBy(
        _.concat(this.available_options, this.selected_options),
        ["title"]
      );
    },
    getAvailableOptions() {
      let options = [];
      for (let i = 0; i < _.size(this.data.answers); i++) {
        let item_configurations = this.data.answers[i].configurations;
        if (!_.isEmpty(item_configurations.selected_options)) {
          options = _.concat(options, item_configurations.selected_options);
        }
      }
      this.available_options = _.sortBy(_.difference(this.options, options), [
        "title"
      ]);
      //this.available_options = _.shuffle(_.difference(this.options, options))
    },
    currentItem(index) {
      return this.selected.item === index;
    },
    selectItem(item) {
      this.selected.item = item;
      this.getOptions();
    },
    prevItem() {
      let current_index = this.selected.item;
      if (current_index > 0) {
        this.selectItem(current_index - 1);
      }
    },
    nextItem() {
      let current_index = this.selected.item;
      if (current_index < _.size(this.items) - 1) {
        this.selectItem(current_index + 1);
      }
    },
    showModalOptions() {
      this.modal.show = true;
    },
    hideModalOptions() {
      this.modal.show = false;
    }
  },
  created() {
    let options = [];
    for (let i = 0; i < _.size(this.data.answers); i++) {
      let item_configurations = this.data.answers[i].configurations;
      item_configurations.selected_options = [];
      options = _.concat(options, item_configurations.related_options);
    }
    this.options = options;
    this.getAvailableOptions();
  },
  data() {
    return {
      options: [],
      available_options: [],
      modal: {
        show: false
      },
      selected: {
        item: 0
      }
    };
  }
};
</script>

<style lang="scss" scoped>
.gallery-indicator {
  @apply flex justify-center mb-12;
  @media (max-device-width: 580px) {
    @apply mb-4;
  }

  .indicator {
    @apply w-2/12 rounded-full text-center;
  }
}

.gallery-instruction {
  @apply flex justify-center mb-4 mt-4;
  .instruction {
    @apply w-full;
  }
}

.gallery-content {
  @apply flex justify-between mb-4 items-center text-5xl;
  color: #2d7efc;

  .gallery-arrow {
    @apply text-center;
    &.left {
      @apply text-left;
      @media (max-device-width: 580px) {
        @apply mr-4;
      }

      i {
        @apply cursor-pointer;
      }
    }

    &.right {
      @apply text-right;
      @media (max-device-width: 580px) {
        @apply ml-4;
      }

      i {
        @apply cursor-pointer;
      }
    }

    &.disabled {
      @apply text-gray-300 cursor-not-allowed;
    }
  }

  .gallery-item {
    @apply mx-auto;
    display: none;
    max-width: 400px;
    max-height: 400px;
    min-width: 350px;
    min-height: 350px;
    width: 100%;
    height: 100%;
    background-position: center;
    background-size: cover;
    @media (max-device-width: 580px) {
      min-width: 275px;
      min-height: 275px;
    }
    @media (max-device-width: 400px) {
      min-width: 150px;
      min-height: 150px;
    }

    &.selected {
      display: block;
      box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
      border-radius: 10px 10px 10px 10px;
    }

    img {
      max-width: 100%;
      @media (max-device-width: 580px) {
        max-width: 100%;
      }
    }
  }
}

.eva-modal {
  display: none;

  &.show {
    display: block;
  }

  .card {
    @media (max-device-width: 580px) {
      @apply w-full h-full m-0;
    }

    .card-header {
      @apply bg-yellow-lighter;
      .close {
        font-size: 2rem;
        color: #1f9af5;
        opacity: 1;

        &:hover {
          color: #167bc6;
        }

        @media (max-device-width: 580px) {
          font-size: 1.5rem;
        }
      }

      .header-content {
        @apply pr-4;
        max-height: 54px;
        height: 54px;
        overflow-y: auto;
        @media (max-device-width: 580px) {
          max-height: 75px;
          height: 75px;
        }
      }

      @media (max-device-width: 580px) {
        @apply py-2 px-4 items-start;
        .font-21 {
          font-size: 1rem;
        }
        .font-18 {
          font-size: 1rem;
        }
      }
    }

    .card-content {
      @apply p-0;
      height: 100%;
      @media (max-device-width: 580px) {
        height: 300px;
      }

      .items {
        .item {
          @apply flex justify-center items-center select-none;
          height: 100px;
          border-bottom: 0.5px solid #d8d8d8;
          @media (max-device-width: 580px) {
            height: 75px;
            > div {
              width: 90%;
              margin-left: auto;
              margin-right: auto;
            }
          }

          &.selected {
            color: #1f9af5;
            background: #ecf9ff;
          }

          &:hover {
            @apply bg-gray-300 cursor-pointer;
          }

          .item-text {
            max-height: 55px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
          }
        }
      }
    }

    .card-footer {
      @apply bg-white p-10;
      @media (max-device-width: 580px) {
        @apply p-4;
      }

      .accept-buttton {
        @apply w-full flex justify-end;
        button {
          width: 15rem;
          height: 3rem;
          font-size: 1.375rem;
          color: #ffffff;
          background: #1a6bf7;
          border: 1px solid #1a6bf7;
          box-sizing: border-box;
          border-radius: 4px;

          &:hover {
            @apply bg-blue-darker shadow-md;
          }

          @media (max-device-width: 580px) {
            @apply w-full;
          }
        }
      }
    }
  }
}
</style>
