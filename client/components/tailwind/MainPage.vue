<template>
  <div class="w-screen h-screen bg-white font-sarabun">
    <perfect-scrollbar class="relative">
      <div id="head" class="fixed top-0 w-full bg-white shadow-head z-10">
        <div class="lg:hidden">
          <div class="h-12 flex items-center justify-between">
            <button
              class="inline-block ml-6 flex content-center focus:outline-none"
              @click="menuOpen = !menuOpen"
            >
              <span v-if="!menuOpen" class="icon-menu text-3xl text-ap-blue"></span>
              <span v-if="menuOpen" class="icon-close text-2xl text-ap-blue"></span>
            </button>
            <a href="#mariana" class="focus:outline-none">
              <img src="/images/apithy-logo.svg" class="h-6 mr-6" alt="apithy e-learning" />
            </a>
          </div>
        </div>

        <div :class="menuOpen ? 'block' : 'hidden'" class="px-6 pt-1 pb-4">
          <div class="flex flex-wrap justify-center text-center">
            <a href="#mariana" class="block w-full text-ap-gray">Contenido</a>
            <a href="#business" class="mt-2 block w-full text-ap-gray">Productos</a>
            <a href="https://landing.apithy.com/contacto" class="mt-2 block w-full text-ap-gray">Contacto</a>
            <a href="#" class="mt-2 block w-full text-ap-gray">ES</a>
            <a
              href="/login"
              class="block mt-2 bg-ap-red font-nunito text-white text-center font-semibold py-2 w-1/2 rounded-lg focus:outline-none"
            >Ingresar</a>
          </div>
        </div>

        <div class="hidden lg:block lg:h-16 lg:flex">
          <div class="w-2/5 flex items-center">
            <a href="#mariana" class="focus:outline-none">
              <img src="/images/apithy-logo.svg" class="h-10 ml-10" alt="apithy e-learning" />
            </a>
          </div>
          <div class="w-3/5 flex items-center justify-around">
            <a href="#mariana" class="inline-block text-ap-gray">Contenido</a>
            <a href="#business" class="inline-block text-ap-gray">Productos</a>
            <a href="https://landing.apithy.com/contacto" class="inline-block text-ap-gray">Contacto</a>
            <a
              href="/login"
              class="inline-block bg-ap-red font-nunito text-white text-center font-semibold py-2 px-6 rounded-lg focus:outline-none"
            >Ingresar</a>
            <a href="#" class="inline-block text-ap-gray">ES</a>
          </div>
        </div>
      </div>

      <!-- <div id="on-air" class="w-full h-12 lg:h-16"></div>

      <div class="w-full h-rest-12 lg:h-rest-16">
        <div id="on-air-content" class="h-3/4 lg:h-4/5 bg-cover bg-center sm:bg-bottom">
          <div
            class="h-full px-12 text-center text-white flex flex-wrap content-center sm:px-32 md:px-48 lg:px-64"
          >
            <h1
              class="w-full font-nunito text-5xl font-bold leading-none animated fadeIn"
            >¡Bienvenido!</h1>
            <h2
              class="w-full mt-12 text-xl xl:text-3xl animated fadeIn delay-1s"
            >Con apithy vive la evolución de los cursos en línea y atrévete a sumergirte en experiencias digitales.</h2>
          </div>
        </div>
        <div class="h-1/4 lg:h-1/5 flex justify-center items-center">
          <a href="#mariana">
            <span class="icon-chevron-down text-ap-light-gray hover:text-ap-gray text-5xl"></span>
          </a>
        </div>
      </div>-->

      <div id="mariana" class="w-full h-12 lg:h-16"></div>

      <div class="w-screen h-rest-12 lg:h-rest-16">
        <div class="mariana-content h-3/5 pb-20 bg-cover bg-bottom text-white">
          <div class="h-full px-12 lg:px-0 flex flex-wrap content-center lg:flex-no-wrap">
            <div class="w-full lg:flex lg:w-1/2 lg:h-full lg:items-center">
              <h1
                class="block w-full lg:ml-24 lg:mr-12 font-nunito text-4xl font-bold leading-none text-center lg:text-right"
              >Haz realidad tus metas de formación</h1>
            </div>
            <div
              class="w-full mt-8 lg:mt-0 lg:flex flex-wrap text-center lg:w-1/2 lg:h-full lg:ml-12 lg:mr-24 lg:content-center lg:text-left"
            >
              <h2
                class="block w-full text-2xl font-semibold leading-none"
              >Conoce la oferta educativa para tu crecimiento y el de tus colaboradores</h2>
              <h3
                class="block w-full mt-5 text-xl leading-none hidden lg:block"
              >Ahora no te falta nada para alcanzar el éxito; síguenos para no perderte la actualización de nuestros recursos educativos</h3>
            </div>
          </div>
        </div>

        <div class="w-screen h-2/5 flex items-center justify-center">
          <div class="w-10/12 mt-2 font-sarabun leading-tight">
            <carousel
              :perPage="1"
              :perPageCustom="[[640, 2], [900, 3], [1366, 4], [1920, 5]]"
              :paginationEnabled="false"
              :navigationEnabled="true"
              :navigationNextLabel="`<i class='block w-8 h-8 fas fa-chevron-right text-3xl text-blue-medium'></i>`"
              :navigationPrevLabel="`<i class='block w-8 h-8 fas fa-chevron-left text-3xl text-blue-medium'></i>`"
            >
              <slide v-for="(card, index) in experiences" :key="index">
                <ExperienceCard :cardDetails="card"></ExperienceCard>
              </slide>
            </carousel>
          </div>
        </div>
      </div>

      <!-- <div class="w-full min-h-screen">
        <div class="lg:hidden">
          <div class="pt-2 flex-wrap items-center">
            <div class="text-center px-12">
              <h2 class="font-nunito text-2xl text-ap-blue font-bold leading-none">
                Te presentamos a nuestros partners y su
                oferta educativa
              </h2>
              <h3
                class="mt-3 text-xl text-ap-gray font-semibold leading-none"
              >Comienza a cumplir tus metas del 2020</h3>
              <div class="mt-3 text-ap-gray">
                Constantemente iremos agregando más contenido que te ayude en tu camino al
                éxito. Síguenos para no perderte la actualización de nuestra oferta educativa.
              </div>
              <picture>
                <source
                  class="h-64 mt-5 mx-auto"
                  :srcset="apithy_assets.mariana.webp"
                  type="image/webp"
                />
                <source
                  class="h-64 mt-5 mx-auto"
                  :srcset="apithy_assets.mariana.jpg"
                  type="image/jpg"
                />
                <img :src="apithy_assets.mariana.jpg" alt="Mariana Verner" />
              </picture>
            </div>
            <div class="mt-3 px-3">
              <a
                class="block bg-ap-red font-nunito text-white text-center font-semibold py-3 px-4 rounded-lg w-full focus:outline-none"
                href="https://www.apithy.com/experiences/pBD3j/preview"
              >Conoce a Mariana Verner</a>
            </div>
            <div class="mt-10 font-sarabun leading-tight">
              <carousel :perPage="1" :perPageCustom="[[640, 2], [900, 3], [1366, 5], [1920, 5]]">
                <slide v-for="(card, index) in experiences_data" :key="index">
                  <ExperienceCard :cardDetails="card"></ExperienceCard>
                </slide>
              </carousel>
            </div>
          </div>
        </div>

        <div class="hidden lg:block">
          <div class="w-screen h-rest-16">
            <div class="w-screen h-2/3 flex">
              <div class="lg:w-3/5 h-full flex items-center justify-center">
                <div
                  class="h-3/4 px-16 lg:pl-12 lg:pr-16 xl:pl-24 xl:pr-32 flex flex-wrap content-center justify-center"
                >
                  <h2 class="w-full font-nunito text-5xl text-ap-blue font-bold leading-none">
                    Te presentamos a nuestros
                    partners y su oferta educativa
                  </h2>
                  <h3 class="w-full mt-5 text-3xl text-ap-gray font-semibold leading-none">
                    Comienza a cumplir tus metas del
                    2020
                  </h3>
                  <div class="w-full mt-5 text-ap-gray text-xl">
                    Constantemente iremos agregando más contenido que te ayude en
                    tu camino al éxito. Síguenos para no perderte la actualización de nuestra oferta educativa.
                  </div>
                  <div class="w-full mt-5">
                    <a
                      class="block bg-ap-red font-nunito text-white text-center font-semibold py-2 px-4 rounded-lg w-full focus:outline-none"
                      href="https://www.apithy.com/experiences/pBD3j/preview"
                    >Conoce a Mariana Verner</a>
                  </div>
                </div>
              </div>
              <div class="lg:w-2/5 pr-6 h-full flex items-center justify-center">
                <picture class="h-full object-contain">
                  <source
                    class="h-full object-contain"
                    :srcset="apithy_assets.mariana.webp"
                    type="image/webp"
                  />
                  <source
                    class="h-full object-contain"
                    :srcset="apithy_assets.mariana.jpg"
                    type="image/jpg"
                  />
                  <img
                    :src="apithy_assets.mariana.jpg"
                    alt="Mariana Verner"
                    class="h-full object-contain"
                  />
                </picture>
              </div>
            </div>
            <div class="w-screen h-1/3 flex items-center justify-center">
              <div class="w-10/12 mt-2 font-sarabun leading-tight">
                <carousel :perPage="1" :perPageCustom="[[640, 2], [900, 3], [1366, 4], [1920, 5]]">
                  <slide v-for="(card, index) in experiences" :key="index">
                    <ExperienceCard :cardDetails="card"></ExperienceCard>
                  </slide>
                </carousel>
              </div>
            </div>
          </div>
        </div>
      </div>-->

      <div id="business" class="w-full h-12 lg:h-16"></div>

      <div class="w-full min-h-screen">
        <div class="lg:hidden">
          <div class="pt-2 flex-wrap items-center">
            <div class="text-center px-12">
              <picture>
                <source
                  class="h-64 mx-auto"
                  :srcset="apithy_assets.apithy_business.webp"
                  type="image/webp"
                />
                <source
                  class="h-64 mx-auto"
                  :srcset="apithy_assets.apithy_business.jpg"
                  type="image/jpg"
                />
                <img :src="apithy_assets.apithy_business.jpg" alt="apithy e-learning" />
              </picture>
              <h2 class="mt-3 font-nunito text-2xl text-ap-blue font-bold">apithy Business</h2>
              <div
                class="mt-3 text-ap-gray"
              >Habilita un nuevo modelo de negocio con apithy Business. Lleva tu propia oferta de formación a un formato digital con tu marca, tu expertise y de la mano de nuestra plataforma.</div>
            </div>
            <div class="mt-3 px-3">
              <a
                class="block bg-ap-red font-nunito text-white text-center font-semibold py-3 px-4 rounded-lg w-full focus:outline-none"
                href="https://landing.apithy.com/business"
              >Contáctanos</a>
            </div>
          </div>
        </div>

        <div class="hidden lg:block">
          <div class="w-screen h-rest-16 flex">
            <div class="sm:3/5 lg:w-2/3 h-full flex items-center justify-center">
              <picture>
                <source
                  class="lg:h-4/5 object-contain"
                  :srcset="apithy_assets.apithy_business.webp"
                  type="image/webp"
                />
                <source
                  class="lg:h-4/5 object-contain"
                  :srcset="apithy_assets.apithy_business.jpg"
                  type="image/jpg"
                />
                <img :src="apithy_assets.apithy_business.jpg" alt="apithy e-learning" />
              </picture>
            </div>
            <div class="sm:2/5 lg:w-1/3 h-full flex items-center justify-center">
              <div
                class="h-3/4 pr-16 lg:pl-12 lg:pr-16 xl:pl-24 xl:pr-32 flex flex-wrap content-center justify-center"
              >
                <h2 class="w-full font-nunito text-5xl text-ap-blue font-bold">apithy Business</h2>
                <div
                  class="w-full mt-5 text-ap-gray text-xl"
                >Habilita un nuevo modelo de negocio con apithy Business. Lleva tu propia oferta de formación a un formato digital con tu marca, tu expertise y de la mano de nuestra plataforma.</div>
                <div class="w-full mt-5">
                  <a
                    class="block bg-ap-red font-nunito text-white text-center font-semibold py-2 px-4 rounded-lg w-full focus:outline-none"
                    href="https://landing.apithy.com/business"
                  >Contáctanos</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div id="plus" class="w-full h-12 lg:h-16"></div>

      <div class="w-full min-h-screen">
        <div class="lg:hidden">
          <div class="pt-2 flex-wrap items-center">
            <div class="text-center px-12">
              <picture>
                <source
                  class="h-64 mx-auto"
                  :srcset="apithy_assets.apithy_plus.webp"
                  type="image/webp"
                />
                <source
                  class="h-64 mx-auto"
                  :srcset="apithy_assets.apithy_plus.jpg"
                  type="image/jpg"
                />
                <img :src="apithy_assets.apithy_plus.jpg" alt="apithy e-learning" />
              </picture>
              <h2 class="mt-8 font-nunito text-2xl text-ap-blue font-bold">apithy +</h2>
              <div class="mt-3 text-ap-gray">
                Enriquece los planes de formación mediante Game-based learning, simuladores, realidad aumentada o 3D,
                superando las barreras de las aulas tradicionales y fomentando el aprendizaje.
                Elimina la inversión en infraestructura gracias a ambientes digitales a la medida.
              </div>
            </div>
            <div class="mt-3 px-3">
              <a
                class="block bg-ap-red font-nunito text-white text-center font-semibold py-3 px-4 rounded-lg w-full focus:outline-none"
                href="https://landing.apithy.com/apithyplus"
              >Just push play</a>
            </div>
          </div>
        </div>

        <div class="hidden lg:block">
          <div class="w-screen h-rest-16 flex">
            <div class="sm:2/5 lg:w-1/3 h-full flex items-center justify-center">
              <div
                class="h-3/4 px-16 lg:pl-12 lg:pr-16 xl:pl-24 xl:pr-32 flex flex-wrap content-center justify-center"
              >
                <h2 class="w-full font-nunito text-5xl text-ap-blue font-bold">apithy +</h2>
                <div class="w-full mt-5 text-ap-gray text-xl">
                  Enriquece los planes de formación mediante Game-based learning, simuladores, realidad aumentada o 3D,
                  superando las barreras de las aulas tradicionales y fomentando el aprendizaje.
                  Elimina la inversión en infraestructura gracias a ambientes digitales a la medida.
                </div>
                <div class="w-full mt-5">
                  <a
                    class="block bg-ap-red font-nunito text-white text-center font-semibold py-2 px-4 rounded-lg w-full focus:outline-none"
                    href="https://landing.apithy.com/apithyplus"
                  >Just push play</a>
                </div>
              </div>
            </div>
            <div class="sm:3/5 lg:w-2/3 pr-6 h-full flex items-center justify-center">
              <picture>
                <source
                  class="h-4/5 object-contain"
                  :srcset="apithy_assets.apithy_plus.webp"
                  type="image/webp"
                />
                <source
                  class="h-4/5 object-contain"
                  :srcset="apithy_assets.apithy_plus.jpg"
                  type="image/jpg"
                />
                <img :src="apithy_assets.apithy_plus.jpg" alt="apithy e-learning" />
              </picture>
            </div>
          </div>
        </div>
      </div>

      <div id="store" class="w-full h-12 lg:h-16"></div>

      <div class="w-full min-h-screen">
        <div class="lg:hidden">
          <div class="pt-2 flex-wrap items-center">
            <div class="text-center px-12">
              <picture>
                <source
                  class="h-64 mx-auto"
                  :srcset="apithy_assets.apithy_store.webp"
                  type="image/webp"
                />
                <source
                  class="h-64 mx-auto"
                  :srcset="apithy_assets.apithy_store.jpg"
                  type="image/jpg"
                />
                <img :src="apithy_assets.apithy_store.jpg" alt="apithy e-learning" />
              </picture>
              <h2 class="mt-8 font-nunito text-2xl text-ap-blue font-bold">apithy Store</h2>
              <div class="mt-3 text-ap-gray">
                Encuentra experiencias de formación, creadas por expertos y desde cualquier lugar o dispositivo.
                Obtén acceso inmediato a expertos en los temas de tu interés.
              </div>
            </div>
            <div class="mt-3 px-3">
              <a
                class="block bg-ap-red font-nunito text-white text-center font-semibold py-3 px-4 rounded-lg w-full focus:outline-none"
                href="https://landing.apithy.com/partner"
              >Conviértete en partner</a>
            </div>
          </div>
        </div>

        <div class="hidden lg:block">
          <div class="w-screen h-rest-16 flex">
            <div class="sm:3/5 lg:w-2/3 pl-16 h-full flex items-center justify-center">
              <picture>
                <source
                  class="h-4/5 object-contain"
                  :srcset="apithy_assets.apithy_store.webp"
                  type="image/webp"
                />
                <source
                  class="h-4/5 object-contain"
                  :srcset="apithy_assets.apithy_store.jpg"
                  type="image/jpg"
                />
                <img :src="apithy_assets.apithy_store.jpg" alt="apithy e-learning" />
              </picture>
            </div>
            <div class="sm:2/5 lg:w-1/3 h-full flex items-center justify-center">
              <div
                class="h-3/4 px-16 lg:pl-12 lg:pr-16 xl:pl-24 xl:pr-32 flex flex-wrap content-center justify-center"
              >
                <h2 class="w-full font-nunito text-5xl text-ap-blue font-bold">apithy Store</h2>
                <div class="w-full mt-5 text-ap-gray text-xl">
                  Encuentra experiencias de formación, creadas por expertos y desde cualquier lugar o dispositivo.
                  Obtén acceso inmediato a expertos en los temas de tu interés.
                </div>
                <div class="w-full mt-5">
                  <a
                    class="block bg-ap-red font-nunito text-white text-center font-semibold py-2 px-4 rounded-lg w-full focus:outline-none"
                    href="https://landing.apithy.com/partner"
                  >Conviértete en partner</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div id="contact" class="w-full h-12 lg:h-16"></div>

      <div class="w-full h-rest-16">
        <div class="lg:hidden w-full h-full flex flex-wrap items-end">
          <div
            class="contact-content h-11/12 w-full bg-cover bg-top bg-gray-500 px-12 text-white flex flex-wrap items-end"
          >
            <div class="w-full font-nunito text-3xl font-bold">Contacto</div>

            <div>
              <div class="w-full text-lg">
                <a href="http://maps.google.com/?q=Avenida Campo Real 1527, El Refugio, Querétaro">
                  Avenida Campo Real 1527,
                  El Refugio, Querétaro
                </a>
              </div>
              <div class="w-full pt-3 text-lg">
                <a href="tel:+524422275548">+ 52 (442) 227 5548</a>
              </div>
              <div class="w-full pt-3 text-lg">
                <a href="mailto:info@apithy.com">info@apithy.com</a>
              </div>
            </div>

            <div class="w-full flex-grow">
              <hr class="block my-5" />
            </div>

            <div>
              <a
                href="https://landing.apithy.com/partner"
                class="block w-full text-lg"
              >¿Quieres ser partner?</a>
              <a
                href="https://landing.apithy.com/contacto"
                class="block w-full text-lg pt-3"
              >Contacto</a>
              <a href="/aviso-de-privacidad" class="block w-full text-lg pt-3">Aviso de privacidad</a>
            </div>

            <div class="mb-6 w-full h-16 flex items-center justify-around">
              <a
                href="https://www.linkedin.com/company/apithylearning/"
                class="block w-3/5 h-3/5 text-center"
              >
                <span class="icon-linkedin text-ap-light-gray text-2xl"></span>
              </a>
              <a
                href="https://www.instagram.com/apithylearning/"
                class="block w-3/5 h-3/5 text-center"
              >
                <span class="icon-instagram text-ap-light-gray text-2xl"></span>
              </a>
              <a href="https://twitter.com/apithylearning/" class="block w-3/5 h-3/5 text-center">
                <span class="icon-twitter text-ap-light-gray text-2xl"></span>
              </a>
              <a
                href="https://www.facebook.com/apithylearning/"
                class="block w-3/5 h-3/5 text-center"
              >
                <span class="icon-facebook text-ap-light-gray text-2xl"></span>
              </a>
            </div>
          </div>
        </div>

        <div class="hidden lg:block lg:w-full lg:h-full lg:flex lg:flex-wrap lg:items-end">
          <div
            class="contact-content h-3/5 w-full bg-cover bg-top bg-gray-500 sm:px-12 lg:px-32 text-white flex flex-wrap items-end"
          >
            <div class="w-full flex">
              <div class="w-2/3">
                <div class="w-full font-nunito text-3xl font-bold">Contacto</div>

                <div>
                  <div class="w-full pt-3 text-lg">
                    <span
                      class="icon-location text-ap-light-gray text-xl w-12 inline-block text-center"
                    ></span>
                    <span class="inline-block ml-5">
                      Avenida Campo Real 1527,
                      <br class="lg:hidden" />El Refugio, Querétaro
                    </span>
                  </div>
                  <div class="w-full pt-3 text-lg">
                    <span
                      class="icon-phone text-ap-light-gray text-xl w-12 inline-block text-center"
                    ></span>
                    <span class="inline-block ml-5">+ 52 (442) 227 5548</span>
                  </div>
                  <div class="w-full pt-3 text-lg">
                    <span
                      class="icon-email text-ap-light-gray text-xl w-12 inline-block text-center"
                    ></span>
                    <a class="inline-block ml-5" href="mailto:info@apithy.com">info@apithy.com</a>
                  </div>
                </div>
              </div>

              <div class="w-1/3">
                <div class="w-full font-nunito text-3xl font-bold">Empresa</div>
                <div>
                  <a
                    href="https://landing.apithy.com/partner"
                    class="block w-full text-lg pt-3"
                  >¿Quieres ser partner?</a>
                  <a
                    href="https://landing.apithy.com/contacto"
                    class="block w-full text-lg pt-3"
                  >Contacto</a>

                  <a
                    href="/aviso-de-privacidad"
                    class="block w-full text-lg pt-3"
                  >Aviso de privacidad</a>
                </div>
              </div>
            </div>

            <div class="w-full flex-grow">
              <hr class="block" />
            </div>

            <div class="mb-6 w-1/3 h-10 flex items-center justify-around">
              <a
                href="https://www.linkedin.com/company/apithylearning/"
                class="block w-3/5 h-3/5 text-center"
              >
                <span class="icon-linkedin text-ap-light-gray text-2xl"></span>
              </a>
              <a
                href="https://www.instagram.com/apithylearning/"
                class="block w-3/5 h-3/5 text-center"
              >
                <span class="icon-instagram text-ap-light-gray text-2xl"></span>
              </a>
              <a href="https://twitter.com/apithylearning/" class="block w-3/5 h-3/5 text-center">
                <span class="icon-twitter text-ap-light-gray text-2xl"></span>
              </a>
              <a
                href="https://www.facebook.com/apithylearning/"
                class="block w-3/5 h-3/5 text-center"
              >
                <span class="icon-facebook text-ap-light-gray text-2xl"></span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </perfect-scrollbar>
  </div>
</template>

<script>
import { PerfectScrollbar } from "vue2-perfect-scrollbar";
import { Carousel, Slide } from "vue-carousel";

import ExperienceCard from "./ExperienceCard.vue";

export default {
  components: {
    PerfectScrollbar,
    Carousel,
    Slide,
    ExperienceCard
  },
  beforeMount() {
    this.noScript();
  },
  props: ["experiences"],
  methods: {
    noScript() {
      document.documentElement.classList.remove("no-js");
    }
  },
  data() {
    let imagePath = "/tailwind/statics/images/main-page/";
    let videoPath = "/tailwind/statics/videos/main-page/";
    return {
      experiences_data: this.experiences,
      menuOpen: false,
      apithy_assets: {
        mariana: {
          jpg: `${imagePath}mariana-verner.jpg`,
          webp: `${imagePath}mariana-verner.webp`
        },
        apithy_business: {
          jpg: `${imagePath}apithy-business.jpg`,
          webp: `${imagePath}apithy-business.webp`
        },
        apithy_store: {
          jpg: `${imagePath}apithy-store.jpg`,
          webp: `${imagePath}apithy-store.webp`
        },
        apithy_plus: {
          jpg: `${imagePath}apithy-plus.jpg`,
          webp: `${imagePath}apithy-plus.webp`
        }
      },

      cards: [
        {
          title: "Liderazgo en el espacio",
          description: "Aprende a hacer la guerra como el Gral. Grievous",
          cost: "$657.00",
          background:
            '"https://images.unsplash.com/photo-1585221454166-ce690e60465f?ixlib=rb-1.2.1&auto=format&fit=crop&w=634&q=80"',
          logo:
            '"https://e00-elmundo.uecdn.es/assets/multimedia/imagenes/2018/01/19/15163579515168.jpg"'
        },
        {
          title: "Liderazgo en el espacio",
          description: "Aprende a hacer la guerra como el Gral. Grievous",
          cost: "$657.00",
          background:
            '"https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1350&q=80"',
          logo:
            '"https://e00-elmundo.uecdn.es/assets/multimedia/imagenes/2018/01/19/15163579515168.jpg"'
        },
        {
          title: "Liderazgo en el espacio",
          description: "Aprende a hacer la guerra como el Gral. Grievous",
          cost: "Gratis",
          background:
            '"https://images.unsplash.com/photo-1584301747283-b2e674b054df?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80"',
          logo:
            '"https://e00-elmundo.uecdn.es/assets/multimedia/imagenes/2018/01/19/15163579515168.jpg"'
        },
        {
          title: "Liderazgo en el espacio",
          description: "Aprende a hacer la guerra como el Gral. Grievous",
          cost: "$657.00",
          background:
            '"https://images.unsplash.com/photo-1528901166007-3784c7dd3653?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1350&q=80"',
          logo:
            '"https://e00-elmundo.uecdn.es/assets/multimedia/imagenes/2018/01/19/15163579515168.jpg"'
        },
        {
          title: "Liderazgo en el espacio",
          description: "Aprende a hacer la guerra como el Gral. Grievous",
          cost: "$657.00",
          background:
            '"https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=714&q=80"',
          logo:
            '"https://e00-elmundo.uecdn.es/assets/multimedia/imagenes/2018/01/19/15163579515168.jpg"'
        },
        {
          title: "Liderazgo en el espacio",
          description: "Aprende a hacer la guerra como el Gral. Grievous",
          cost: "$657.00",
          background:
            '"https://images.unsplash.com/photo-1583267627833-c4c2d30f57a4?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=634&q=80"',
          logo:
            '"https://e00-elmundo.uecdn.es/assets/multimedia/imagenes/2018/01/19/15163579515168.jpg"'
        },
        {
          title: "Liderazgo en el espacio",
          description: "Aprende a hacer la guerra como el Gral. Grievous",
          cost: "$657.00",
          background:
            '"https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=714&q=80"',
          logo:
            '"https://e00-elmundo.uecdn.es/assets/multimedia/imagenes/2018/01/19/15163579515168.jpg"'
        },
        {
          title: "Liderazgo en el espacio",
          description: "Aprende a hacer la guerra como el Gral. Grievous",
          cost: "$657.00",
          background:
            '"https://images.unsplash.com/photo-1583775698384-a71527a3468b?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=634&q=80"',
          logo:
            '"https://e00-elmundo.uecdn.es/assets/multimedia/imagenes/2018/01/19/15163579515168.jpg"'
        }
      ]
    };
  }
};
</script>

<style src="vue2-perfect-scrollbar/dist/vue2-perfect-scrollbar.css"/>

<style>
.VueCarousel-slide {
  @apply flex justify-center;
}

.VueCarousel-navigation-button:focus {
  outline: none !important;
}

.ps {
  height: 100%;
}

.no-webp .mariana-content {
  background-image: url("/tailwind/statics/images/main-page/header.jpg");
}

.webp .mariana-content {
  background-image: url("/tailwind/statics/images/main-page/header.webp");
}

.no-js .mariana-content {
  background-image: url("/tailwind/statics/images/main-page/header.jpg");
}

.no-webp .contact-content {
  background-image: url("/tailwind/statics/images/main-page/footer.jpg");
}

.webp .contact-content {
  background-image: url("/tailwind/statics/images/main-page/footer.webp");
}

.no-js .contact-content {
  background-image: url("/tailwind/statics/images/main-page/footer.jpg");
}

.h-rest-12 {
  height: calc(100vh - 3rem);
}

.h-rest-16 {
  height: calc(100vh - 4rem);
}
</style>
