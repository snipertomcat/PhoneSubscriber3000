<template>
  <div class="pt-4">
    <ul>
      <li v-for="(option, index) in data.answers" :key="index" :data="option" :number="index + 1" >
        <input type="radio" v-bind:value="option.id" name="radio" v-model="user_answers">
        <label for="scales">{{ option.title }}</label>
      </li>
    </ul>
  </div>
</template>

<script>
  import {bus} from "../../../app_platform";

  export default {
    name: "apithy-single",
    components: {

    },
    computed: {
    },
    methods: {
      setAnswer (data) {
        bus.$emit('apithy-user-answer', this.user_answers);
      },
      resetAnswer() {
          this.user_answers = [];
      }
    },
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
    data () {
      return {
        user_answers:[]
      }
    }
  }
</script>

<style>
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
    z-index: 50
  }
  .scrollable {
    overflow: auto;
  }
</style>