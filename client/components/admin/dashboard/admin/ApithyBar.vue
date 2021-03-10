<template>
  <div class="dataset" :id="`dataset_${index}`">
    <div class="label">
      <div class="name">{{ `${item.label}` }}</div>
      <div class="value">{{ `${item.value}` }}</div>
    </div>
    <div class="bar-container">
      <div class="bar"
           :data-width="item.width * 100"
           :data-background="item.background_color">
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: "ApithyBar",
    props: {
      itemData: {
        required: true,
        type: Object
      },
      index: {
        required: false,
        default: this._uid
      },
    },
    watch: {
      'item.width': function (value) {
        this.drawBar(`dataset_${this.index}`)
      }
    },
    mounted () {
      this.drawBar(`dataset_${this.index}`)
    },
    methods: {
      drawBar (id = null) {
        if (_.isNull(id) || _.isEmpty(id)) {
          return 0;
        }
        setTimeout(() => {
          let element = document.querySelector(`#${id}`);
          if (_.isNull(element) || _.isEmpty(element) || element === 'undefined') {
            return 0;
          }
          let bar = element.querySelector('.bar')
          let width = _.get(bar, ['dataset', 'width'], 0);
          let background_color = _.get(bar, ['dataset', 'background'], '#B7C5DB');
          if (_.has(this.item, 'colored_label') && this.item.colored_label) {
            let bar_label = element.querySelector('.label');
            bar_label.style.color = _.get(bar, ['dataset', 'background'], '#444444');
          }

          bar.style.width = `${width}%`;
          bar.style.backgroundColor = `${background_color}`;
        }, 100)
      },
    },
    data () {
      return {
        item: this.itemData
      }
    }
  }
</script>

<style lang="scss" scoped>
  .dataset {
    display: flex;
    flex-flow: row wrap;
    margin-top: 1rem;
    margin-bottom: 1rem;

    .label {
      width: 30%;
      margin: unset;
      display: flex;
      flex-flow: row wrap;
      align-items: center;
      justify-content: space-between;

      .name {
      }

      .value {
      }
    }

    .bar-container {
      width: 70%;
      padding-left: 1rem;
      .bar {
        width: 0%;
        height: 40px;
        transition: width 1.5s;
      }
    }
  }
</style>
