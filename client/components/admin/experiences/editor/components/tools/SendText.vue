<template>
  <div class>
    <div class="w-full">
      <textarea v-model="form.user_answer" class="w-full border"></textarea>
    </div>
    <div class="w-full">
      <div class="w-1/3">
        <button class="button rounded px-4 py-2 bg-blue-600 text-white">Enviar</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "SendText",
  props: {
    componentOptions: {
      required: true,
      type: Object
    }
  },
  inject: ['getExperience','getSession','getSubSession'],
  methods: {
    init() {
      let timestamp = Date.now()
      let config = this.component.config;
      let session = this.getSession()
      if (session.type === 'theme') {
        session = this.getSubSession('session_id')
      } else {
        session = this.getSession('session_id')
      }
      config.session = session;
      config.experience = this.getExperience('id');
      config.activity_info.apithy_activity_metadata.name = `activity_for_exp_${this.getExperience('id')}_session_${session}_${timestamp}`
      config.activity_info.apithy_activity_metadata.title = `activity_for_exp_${this.getExperience('id')}_session_${session}_${timestamp}`
    }
  },
  mounted() {
    this.init();
  },
  data() {
    return {
      target_url: "/student/progress",
      form: {
        user_answer: null
      },
      component: this.componentOptions
    };
  }
};
</script>

<style lang="scss" scoped></style>
