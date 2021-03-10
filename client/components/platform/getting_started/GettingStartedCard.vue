<template>
  <div class="pt-4">
    <div class="card">
      <div class="card-body">
        <div class="card-title">
          <div class="row ml-0 mr-0 pt-3">
            <div class="col-12 col-lg-8">
              <p class="">
                <span class="icon" :class="checkClass()">
                  <i class="fa fa-check-circle" aria-hidden="true"></i>
                </span>
                <template v-if="isCompleted">
                  <del>{{number}}. <strong>{{data.title}}</strong></del>
                </template>
                <template v-else>
                  {{number}}. <strong>{{data.title}}</strong>
                </template>
              </p>
            </div>
            <div class="col-12 col-lg-4">
              <div class="text-right">
                <a href="#" @click="openModal">{{videoText()}}</a>
              </div>
            </div>
          </div>
        </div>
        <div class="card-content d-none d-lg-block pt-2" v-if="!isCompleted">
          <div class="text-center">
            <img v-bind:src="'/images/getting_started/'+ data.activity_name +'.png'" class="img-thumbnail img-video pointer" @click="openModal">
          </div>
        </div>
        <div class="pt-2" v-else></div>
      </div>
    </div>
    <b-modal :active.sync="isOpen" :has-modal-card="true" class="has-modal">
      <div>
        <div class="m-close-background">
          <div class="text-right has-text-white">
            <span class="pointer ml-0 mr-2" @click="closeModal">
              <span class="mr-2">Cerrar</span>
              <i class="fa fa-times" aria-hidden="true"></i>
            </span>
          </div>
        </div>
        <div class="tp-2"></div>
        <div class="card modal-card scrollable">
          <div class="card-body">
            <div class="card-title">
              <div class="row ml-1 mr-0 pt-4">
                <div class="col-12">
                  <p class="">
                    <strong>{{number}}. {{data.title}}</strong>
                  </p>
                </div>
              </div>
            </div>
            <div class="card-content">
              <div class="text-center">
                <apithy-video-player :poster="'/images/getting_started/'+ data.activity_name+'.png'" :video-src="data.content" @pause="hasPause" @waiting="hasWaiting" @ended="hasEnded"></apithy-video-player>
              </div>
              <div class="pt-4"></div>
              <div class="text-right" v-if="isCompleted">
                <a href="#" class="btn btn-primary btn-ready" @click="closeModal">¡Listo!</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </b-modal>
  </div>
</template>

<script>
  import {bus} from "../../../app_platform";
  export default {
    name: "GettingStartedCard",
    components: {
      'apithy-video-player': () => import('../../ApithyVideoPlayer.vue')
    },
    computed: {
      isCompleted () {
        let data = _.get(this.user_data, ['activity_data'])
        if (data) {
          let user = data.find(x => x.id === this.data.id)
          if (user) {
            return user.is_completed
          }
        }
        return false;
      }
    },
    methods: {
      updateStatus () {
        axios.post(route('getting_started.store_user'), {activity_id: this.data.id})
            .then(response => {
              this.$emit('update-user-getting', response.data.data)
              bus.$emit('update-user-getting', response.data.data)
            })
      },
      checkClass () {
        return this.isCompleted ? 'has-text-success' : 'has-text-grey'
      },
      videoText () {
        return this.isCompleted ? 'Volver a ver' : 'Ver video'
      },
      openModal () {
        this.isOpen = true
        setTimeout(() => {
          let elements = document.getElementsByClassName('modal-close');
          for (let element of elements) {
            element.parentNode.removeChild(element)
          }
        }, 100)
      },
      closeModal() {
        this.isOpen = false
      },
      hasEnded (event) {
        //console.log("hasEnded",event);
        this.updateStatus();
      },
      hasWaiting (event) {
        //console.log("hasWaiting",event);
      },
      hasPause(event) {
        //console.log("hasPause",event);
      },
    },
    props: {
      data: {
        required: true,
        type: Object
      },
      number : {
        required: true,
        type: Number
      },
      user_data: {
        required: true,
        Type: Object
      }
    },
    data () {
      return {
        isOpen: false,
        u_data: {}
      }
    }
  }
</script>

<style>
  .btn-ready {
    color: #3273dc;
  }
  .img-video {
    width: 274px;
    height: 164px;
    margin-left: auto;
    margin-right: auto;
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
