<template>
  <div class="container block-centered">
    <hr width="2">
    <div class="row">
      <div class="col-lg-2"></div>
      <div class="col-lg-8">
        <div class="card">
          <div class="card-content">
            <h1 class="card-title text-center">¡Bienvenido a Apithy!</h1>
            <div class="pt-3"></div>
            <div class="row">
              <div class="col-12">
                <p class="">Completa estas sencillas tareas que te ayudarán a comprender cómo funciona Apithy.</p>
              </div>
              <div class="col-12 pt-3">
                <div class="row">
                  <div class="col-6">
                    <p class="has-text-weight-bold">Progreso</p>
                  </div>
                  <div class="col-6">
                    <p class="has-text-weight-bold text-right">{{user_data.progress}}%</p>
                  </div>
                </div>
              </div>
              <div class="col-12">
                <progress class="progress is-success" :value="user_data.progress" max="100">{{user_data.progress}}%</progress>
              </div>
            </div>
            <getting-started-card v-for="(dt, index) in data" :key="index" :data="dt" :number="index + 1" :user_data.sync="user_data" @update-user-getting="updateUserData"></getting-started-card>
          </div>
        </div>
      </div>
      <div class="col-lg-2"></div>
    </div>
    <div class="row justify-content-center mt-3">
      <div class="col-5 col-md-2">
        <div class="button is-primary width-100" @click="goHome">
          <span>{{ 'Comenzar' }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: "ApithyGettingStarted",
    components: {
      'getting-started-card': () => import('./GettingStartedCard.vue')
    },
    beforeMount () {
      this.getData()
      this.getUserData()
    },
    methods: {
      updateUserData (data) {
        this.user_data = data
      },
      getData () {
        axios.get(route('getting_started.list'))
            .then(response => {
              this.data = response.data.data
            })
      },
      getUserData () {
        axios.get(route('getting_started.user'))
            .then(response => {
              this.user_data = response.data.data
            })
      },
      goHome () {
        window.location.href = '/home';
      }
    },
    data () {
      return {
        isOpen: false,
        data: [],
        user_data: {}
      }
    }
  }
</script>

<style scoped>

</style>
