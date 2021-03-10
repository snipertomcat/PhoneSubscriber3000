<template>
  <div class="r-send-text py-4">
    <div class="w-full">
      <textarea v-model="form.user_answer" class="w-full border"></textarea>
    </div>
    <div class="w-full">
      <div class="w-1/3">
        <button class="button rounded px-4 py-2 bg-blue-600 text-white" @click="prepareData">Enviar</button>
      </div>
    </div>
  </div>
</template>

<script>
let section = $(this)
  .parent()
  .parent()
  .find("#section")
  .val();
let activity_num = $(this)
  .parent()
  .parent()
  .find("#activity_num")
  .val();
let game = $(this)
  .parent()
  .parent()
  .find("#game")
  .val();
let activity_id = $(this)
  .parent()
  .parent()
  .find("#activity_id")
  .val();
let user_response = $(this);
export default {
  name: "SendText",
  props: {
    componentData: {
      required: true,
      type: Object
    }
  },
  methods: {
    prepareData() {
      let form = this.session_info
      form.activity_id = this.component.config.activity_data.id
      form.data = {
        verb: {
          display: {
            "en-US": "answered"
          }
        },
        object: {
          id: ""//`${this.section}-${this.activity_num}-${this.game}`
        },
        library_data: {
          versionedName: "apithy.SendText-1.0",
          versionedNameNoSpaces: "apithy.SendText",
          machineName: "apithy.SendText"
        },
        user_response: {
          activity_info: {
            user_input: this.form.user_answer,
            section: "",//section,
            activity_num: "",//activity_num,
            game: ""//game
          }
        }
      };
      form.library_data = {
        versionedName: "apithy.SendText-1.0",
        versionedNameNoSpaces: "apithy.SendText",
        machineName: "apithy.SendText"
      };
      form.activity_info = {
        apithy_activity_metadata: this.component.config.activity_data
      }
      form.activity_info.apithy_activity_metadata.cid = 0
      form.activity_info.apithy_activity_metadata.is_mandatory = 0

      this.sendData(form)
    },
    sendData(form) {
      axios
        .post(this.target_url, form)
        .then(resp => {
          console.log(response.data);
          console.log(
            "<div><h3>Respuesta enviada, recibirás la retroalimentación de parte de tu tutor.</h3></br></div>"
          );
        })
        .catch(errors => {
          console.log(errors.data);
        });
    }
  },
  data() {
    return {
      target_url: "/student/progress",
      component: this.componentData,
      form: {
        user_answer: null
      },
      session_info: {
        access_time: Date.now(),
        user: this.$parent.auth_user.id,
        experience: this.$parent.experience.id,
        session: this.$parent.session.id,
        enrollment_progress: this.$parent.session.current_enrollment_progress.id,
        activity_id: null,
        data_type: "xapi"
      }
    };
  }
};
</script>

<style lang="scss" scoped></style>
