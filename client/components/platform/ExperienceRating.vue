<template>
  <div>
    <template v-if="origin === 'experience'">
      <div class="experience-rating">
        <div class="experience-rating-inner container">
          <div class="experience-rating-image col-10 col-sm-6 block-centered">
            <div id="anim"></div>
          </div>
          <div class="rating-text is-center">
            <h1 class="is-bold">¡Yei!</h1>
            <h1>Has completado una experiencia, ¡felicidades!</h1>
          </div>
          <div v-if="hasCertificate && final_score >= min_score">
            <button type="button" class="button is-center goButton" @click="getCertificate">Descargar certificado</button>
          </div>
          <div v-if="!this.hasRating">
            <button type="button" class="button is-center goButton" @click="showModal">Ayúdanos a mejorar</button>
          </div>
          <div>
            <button type="button" class="button is-center goButton" @click="goToIndex">Continuar creciendo</button>
          </div>
        </div>
        <div class="">
          <br><br>
        </div>
      </div>
      <b-modal :active.sync="showRating" :has-modal-card="true" class="has-modal" :width="640">
        <div>
          <div class="row m-close-background">
            <div class="col-12 text-right has-text-white">
              <span class="pointer ml-0 mr-2" @click="closeModal">
                <span class="mr-2">Cerrar</span>
                <i class="fa fa-times" aria-hidden="true"></i>
              </span>
            </div>
          </div>
          <div class="pt-1"></div>
          <div class="card modal-card scrollable">
            <div class="card-content">
              <div class="row">
                <div class="col-lg-12 ml-1 mr-0 pt-4">
                  <p class="custom-title">
                    Antes de irte, danos tu opinión
                  </p>
                </div>
                <div class="col-lg-1"></div>
                <div class="col-lg-10 pt-5">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="row">
                        <div class="col-lg-12">
                          <p>En relación al contenido presentado mi opinión es:</p>
                        </div>
                        <div class="col-lg-12 pt-3">
                          <div class="row large-screen">
                            <div class="col-3 text-center">
                              <span
                                  class="rating-item rating-1"
                                  :class="{selected: (rating.relevance == 1) ? true : false}"
                                  @click="setRating('relevance',1)">
                              </span>
                            </div>
                            <div class="col-3 text-center">
                              <span
                                  class="rating-item rating-2"
                                  :class="{selected: (rating.relevance == 2) ? true : false}"
                                  @click="setRating('relevance',2)">
                              </span>
                            </div>
                            <div class="col-3 text-center">
                              <span
                                  class="rating-item rating-3"
                                  :class="{selected: (rating.relevance == 3) ? true : false}"
                                  @click="setRating('relevance',3)">
                              </span>
                            </div>
                            <div class="col-3 text-center">
                              <span
                                  class="rating-item rating-4"
                                  :class="{selected: (rating.relevance == 4) ? true : false}"
                                  @click="setRating('relevance',4)">
                              </span>
                              <input type="hidden" name="rating['relevance']" v-model="rating.relevance">
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-12 pt-1">
                          <hr class="custom-hr">
                        </div>
                        <div class="col-lg-12 pt-2">
                          <p>La presentación es clara:</p>
                        </div>
                        <div class="col-lg-12 pt-3">
                          <div class="row large-screen">
                            <div class="col-3 text-center">
                              <span
                                  class="rating-item rating-1"
                                  :class="{selected: (rating.presentation == 1) ? true : false}"
                                  @click="setRating('presentation',1)">
                              </span>
                            </div>
                            <div class="col-3 text-center">
                              <span
                                  class="rating-item rating-2"
                                  :class="{selected: (rating.presentation == 2) ? true : false}"
                                  @click="setRating('presentation',2)">
                              </span>
                            </div>
                            <div class="col-3 text-center">
                              <span
                                  class="rating-item rating-3"
                                  :class="{selected: (rating.presentation == 3) ? true : false}"
                                  @click="setRating('presentation',3)">
                              </span>
                            </div>
                            <div class="col-3 text-center">
                              <span
                                  class="rating-item rating-4"
                                  :class="{selected: (rating.presentation == 4) ? true : false}"
                                  @click="setRating('presentation',4)">
                              </span>
                            </div>
                            <input type="hidden" name="rating['presentation']" v-model="rating.presentation">
                          </div>
                        </div>
                        <div class="col-lg-12 pt-1">
                          <hr class="custom-hr">
                        </div>
                        <div class="col-lg-12 pt-2">
                          <p>Mi grado de satisfacción es:</p>
                        </div>
                        <div class="col-lg-12 pt-3">
                          <div class="row large-screen">
                            <div class="col-3 text-center">
                              <span
                                  class="rating-item rating-1"
                                  :class="{selected: (rating.satisfaction == 1) ? true : false}"
                                  @click="setRating('satisfaction',1)">
                              </span>
                            </div>
                            <div class="col-3 text-center">
                              <span
                                  class="rating-item rating-2"
                                  :class="{selected: (rating.satisfaction == 2) ? true : false}"
                                  @click="setRating('satisfaction',2)">
                              </span>
                            </div>
                            <div class="col-3 text-center">
                              <span
                                  class="rating-item rating-3"
                                  :class="{selected: (rating.satisfaction == 3) ? true : false}"
                                  @click="setRating('satisfaction',3)">
                              </span>
                            </div>
                            <div class="col-3 text-center">
                              <span
                                  class="rating-item rating-4"
                                  :class="{selected: (rating.satisfaction == 4) ? true : false}"
                                  @click="setRating('satisfaction',4)">
                              </span>
                            </div>
                            <input type="hidden" name="rating['satisfaction']" v-model="rating.satisfaction">
                          </div>
                        </div>
                        <div class="col-lg-12 pt-1">
                          <hr class="custom-hr">
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-12 text-center pt-4 mb-4">
                      <button type="button" class="button is-primary" @click="sendRating" :disable="isLoading || dataSent">Enviar</button>
                    </div>
                  </div>
                </div>
                <div class="col-lg-1"></div>
              </div>
            </div>
          </div>
        </div>
      </b-modal>
    </template>
    <template v-else>
      <div class="row pt-5">
        <div class="col-1"></div>
        <div class="col-10">
          <div class="card besto-shadows card-position">
            <div class="card-content">
              <div class="row">
                <div class="col-lg-12 ml-1 mr-0 pt-4">
                  <p class="custom-title">
                    Califica tu experiencia
                  </p>
                </div>
                <div class="col-lg-12 pt-2 pb-2">
                  <div class="custom-image text-center">
                    <img :src="experience.full_path_cover_top" class="" alt="...">
                    <div class="image-text">{{ experience.title }}</div>
                  </div>
                </div>
                <div class="col-lg-1"></div>
                <div class="col-lg-10 pt-5">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="row">
                        <div class="col-lg-12">
                          <p class="ml-text">En relación al contenido presentado mi opinión es:</p>
                        </div>
                        <div class="col-lg-12 pt-3">
                          <div class="row large-screen">
                            <div class="col-3 text-center">
                              <span
                                  class="rating-item rating-1"
                                  :class="{selected: (rating.relevance == 1) ? true : false}"
                                  @click="setRating('relevance',1)">
                              </span>
                            </div>
                            <div class="col-3 text-center">
                              <span
                                  class="rating-item rating-2"
                                  :class="{selected: (rating.relevance == 2) ? true : false}"
                                  @click="setRating('relevance',2)">
                              </span>
                            </div>
                            <div class="col-3 text-center">
                              <span
                                  class="rating-item rating-3"
                                  :class="{selected: (rating.relevance == 3) ? true : false}"
                                  @click="setRating('relevance',3)">
                              </span>
                            </div>
                            <div class="col-3 text-center">
                              <span
                                  class="rating-item rating-4"
                                  :class="{selected: (rating.relevance == 4) ? true : false}"
                                  @click="setRating('relevance',4)">
                              </span>
                              <input type="hidden" name="rating['relevance']" v-model="rating.relevance">
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-12 pt-1">
                          <hr class="custom-hr">
                        </div>
                        <div class="col-lg-12 pt-2">
                          <p class="ml-text">La presentación es clara:</p>
                        </div>
                        <div class="col-lg-12 pt-3">
                          <div class="row large-screen">
                            <div class="col-3 text-center">
                              <span
                                  class="rating-item rating-1"
                                  :class="{selected: (rating.presentation == 1) ? true : false}"
                                  @click="setRating('presentation',1)">
                              </span>
                            </div>
                            <div class="col-3 text-center">
                              <span
                                  class="rating-item rating-2"
                                  :class="{selected: (rating.presentation == 2) ? true : false}"
                                  @click="setRating('presentation',2)">
                              </span>
                            </div>
                            <div class="col-3 text-center">
                              <span
                                  class="rating-item rating-3"
                                  :class="{selected: (rating.presentation == 3) ? true : false}"
                                  @click="setRating('presentation',3)">
                              </span>
                            </div>
                            <div class="col-3 text-center">
                              <span
                                  class="rating-item rating-4"
                                  :class="{selected: (rating.presentation == 4) ? true : false}"
                                  @click="setRating('presentation',4)">
                              </span>
                            </div>
                            <input type="hidden" name="rating['presentation']" v-model="rating.presentation">
                          </div>
                        </div>
                        <div class="col-lg-12 pt-1">
                          <hr class="custom-hr">
                        </div>
                        <div class="col-lg-12 pt-2">
                          <p class="ml-text">Mi grado de satisfacción es:</p>
                        </div>
                        <div class="col-lg-12 pt-3">
                          <div class="row large-screen">
                            <div class="col-3 text-center">
                              <span
                                  class="rating-item rating-1"
                                  :class="{selected: (rating.satisfaction == 1) ? true : false}"
                                  @click="setRating('satisfaction',1)">
                              </span>
                            </div>
                            <div class="col-3 text-center">
                              <span
                                  class="rating-item rating-2"
                                  :class="{selected: (rating.satisfaction == 2) ? true : false}"
                                  @click="setRating('satisfaction',2)">
                              </span>
                            </div>
                            <div class="col-3 text-center">
                              <span
                                  class="rating-item rating-3"
                                  :class="{selected: (rating.satisfaction == 3) ? true : false}"
                                  @click="setRating('satisfaction',3)">
                              </span>
                            </div>
                            <div class="col-3 text-center">
                              <span
                                  class="rating-item rating-4"
                                  :class="{selected: (rating.satisfaction == 4) ? true : false}"
                                  @click="setRating('satisfaction',4)">
                              </span>
                            </div>
                            <input type="hidden" name="rating['satisfaction']" v-model="rating.satisfaction">
                          </div>
                        </div>
                        <div class="col-lg-12 pt-1">
                          <hr class="custom-hr">
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-12 text-center pt-4 mb-4">
                      <button type="button" class="button is-primary" @click="sendRating" :disable="isLoading || dataSent">Enviar</button>
                    </div>
                  </div>
                </div>
                <div class="col-lg-1"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-1"></div>
      </div>
    </template>
  </div>
</template>

<script>
  import qs from 'qs';

  export default {
    name: 'apithy-experience-rating',
    mounted () {
      if (document.getElementById('anim')) {
        let animation = bodymovin.loadAnimation({
          container: document.getElementById('anim'),
          rederer: 'svg',
          loop: true,
          autoplay: true,
          path: '/js/data.json'
        });
      }
    },
    methods: {
      getCertificate(){
        let url_certificate=route('experience.enrollment.certificate',[this.experience.id,this.enrollment_id]);
        window.open(url_certificate, '_blank');
      },
      goToIndex() {
        window.location.href = '/home'
      },
      showModal () {
        if (this.dataSent || this.hasRating) {
          window.location.href = route('experiences.index')
        } else {
          this.showRating = true
          setTimeout(() => {
            let elements = document.getElementsByClassName('modal-close');
            for (let element of elements) {
              element.parentNode.removeChild(element)
            }
          }, 100)
        }
      },
      closeModal () {
        this.showRating = false
      },
      setRating (category, value) {
        this.rating[category] = value;
      },
      sendRating () {
        if (this.dataSent || this.hasRating) {
          window.location.href = route('experiences.index')
          return
        }
        this.isLoading = true;
        if (this.rating.relevance == 0 || this.rating.presentation == 0 || this.rating.satisfaction == 0) {
          this.$snotify.error('No has calificado la experiencia');
          this.isLoading = false;
        } else {

          let rating_params = {
            user: this.auth_user.id,
            rating: this.rating
          };

          this.$snotify.async('Rating', 'Procesando datos', () => new Promise((resolve, reject) => {
            return axios({
              method: 'POST',
              url: route('experience.rate', {experience: this.experience.system_id}),
              params: rating_params,
              paramsSerializer: function (params) {
                return qs.stringify(params, {encode: false});
              }
            }).then((response) => {
              resolve({
                title: response.data.title,
                body: response.data.message,
                config: {
                  closeOnClick: true,
                  timeout: 3000,
                  showProgressBar: true,
                  html: `
                  <div class="snotifyToast__title">` + '¡Gracias!' + `</div>
                  <div class="snotifyToast__body">` + 'Tu valoración ha sido guardada' + `</div>
                  <div class="snotify-icon snotify-icon--success"></div>
                  `
                }
              });
              this.isLoading = true;
              this.dataSent = true;
              this.showRating = false;
              window.location.href = route('experiences.index')
            }).catch((error) => {
              reject({
                title: 'Error!!!',
                body: error.response.data.message,
                config: {
                  closeOnClick: true,
                  timeout: 3000,
                  showProgressBar: true,
                  html: `
                  <div class="snotifyToast__title">` + error.data.title + `</div>
                  <div class="snotifyToast__body">` + error.data.message + `</div>
                  <div class="snotify-icon snotify-icon--success"></div>`
                }
              });
              this.isLoading = false;
            });
          }));
        }
      }
    },
    props: {
      enrollment_id:{
        required: false
      },
      final_score:{
        required: false
      },
      experience: {
        type: Object,
        required: true
      },
      auth_user: {
        type: Object,
        required: true
      },
      origin: {
        type: String
      }
    },
    computed: {
      hasRating: {
        get() {
          return _.get(this.experience, ['has_rating'], false)
        }
      },
      hasCertificate(){
        return !_.isEmpty(this.experience.current_company_certificates)
      }
    },
    data () {
      return {
        rating: {
          relevance: 0,
          presentation: 0,
          satisfaction: 0
        },
        dataSent: false,
        showRating: false,
        isLoading: false,
        min_score:this.experience.min_score
      }
    }
  }
</script>

<style scoped>

  .custom-title {
    font-family: Montserrat,serif;
    font-style: normal;
    font-weight: 600;
    font-size: 24px;
    line-height: 29px;
    color: black;
  }

  .custom-hr {
    display: block !important;
    margin: 0.1rem 0 !important;
    border: 1px solid #BEBEBE;
  }

  .scrollable {
    overflow: auto;
  }

  .card-position {
    width: 888px;
    margin: 0 auto;
  }

  .custom-image {
    overflow: hidden;
    height: 130px;
    width: 100%;
  }

  .image-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-family: Montserrat,serif;
    font-style: normal;
    font-weight: 600;
    font-size: 20px;
    line-height: 24px;
    color: #FFFFFF;
  }

  @media (min-width: 1024px) {
    .large-screen {
      width: 70%;
      margin: 0 auto;
    }
    .besto-shadows {
      box-shadow: -4px 4px 12px rgba(68, 68, 68, 0.3);
    }
    .ml-text {
      margin-left: 4rem!important;
    }
  }

  .experience-rating {
    position: relative !important;
    width: 100%;
    height: 100vh;
    background-color: #042A56;
    overflow-y: auto;
    z-index: 9;
  }

  .rating-text {
    color: white;
    text-align: center;
  }

  .experience-rating-image {
    color: white;
    text-align: center;
  }

  .experience-rating .button {
    background-color: dodgerblue;
    color: white;
    display: block;
    margin: 40px auto;
    padding: 5px 20px 0 20px;
  }

  .rating-items span {
    color: black;
    height: 24px;
    display: inline-block;
    vertical-align: top;
  }

  .rating-item {
    cursor: pointer;
  }

  .rating-item:hover, .rating-item.selected {
    -webkit-filter: grayscale(0) !important;
    filter: grayscale(0) !important;
    opacity: 1 !important;
  }

  .rating-1 {
    background: url('/images/emojis.png') no-repeat 0 0;
    width: 24px;
    height: 25px;
    display: inline-block;
    -webkit-filter: grayscale(0);
    filter: grayscale(0);
    opacity: 0.5;
  }

  .rating-2 {
    background: url('/images/emojis.png') no-repeat -55px 0;
    width: 24px;
    height: 25px;
    display: inline-block;
    -webkit-filter: grayscale(0);
    filter: grayscale(0);
    opacity: 0.5;
  }

  .rating-3 {
    background: url('/images/emojis.png') no-repeat -177px 0;
    width: 24px;
    height: 25px;
    display: inline-block;
    -webkit-filter: grayscale(0);
    filter: grayscale(0);
    opacity: 0.5;
  }

  .rating-4 {
    background: url('/images/emojis.png') no-repeat -239px 0;
    width: 24px;
    height: 25px;
    display: inline-block;
    -webkit-filter: grayscale(0);
    filter: grayscale(0);
    opacity: 0.5;
  }

  .goButton{
    text-decoration: none;
    padding-top:5px;
  }

</style>

<style>
  body {
    background-color: white;
  }
</style>
