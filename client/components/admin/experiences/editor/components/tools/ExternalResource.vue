<template>
  <div class>
    <div class="w-full">
      <div class="w-2/3">
        <label for>Resource url:</label>
        <input type="url" placeholder="Resource url" v-model="component.config.source"/>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "ExternaResource",
  props: {
    componentOptions: {
      required: true,
      type: Object
    }
  },
  methods: {},
  mounted() {},
  data() {
    return {
      component: this.componentOptions
    };
  }
};
</script>
