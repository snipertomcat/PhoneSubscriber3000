<template>
  <div
    class="w-64 h-48 rounded-lg overflow-hidden bg-cover bg-center text-white"
    :style="{'background-image': 'url(' + cardDetails.full_path_cover + ')'}"
  >
    <div class="w-full h-full tint relative">
      <div
        class="absolute top-0 right-0 mt-2 mr-2 w-10 h-10 rounded-full bg-contain bg-center"
        :style="{'background-image': 'url(' + cardDetails.company_author.full_path_logo + ')'}"
      ></div>

      <div class="absolute bottom-0 mb-12 px-3 w-full">
        <div class="w-4/8 font-nunito text-xl text-yellow-medium mb-1">{{cardDetails.title}}</div>
        <!--<div class="w-5/6 font-nunito mb-1">{{cardDetails.description}}</div>-->
        <hr />
      </div>

      <div class="absolute bottom-0 h-12 w-full flex items-center justify-around">
        <div v-if="cardDetails.price_default > 0">
          <span class="text-sm">Costo</span>
          <br />
          <span class="text-xl text-yellow-medium font-bold">${{cardDetails.price_default}} MX</span>
        </div>
        <div v-else>
          <span class="text-sm">Costo</span>
          <br />
          <span class="text-xl text-yellow-medium font-bold">Gratis</span>
        </div>
        <a v-bind:href="cardDetails.url" class="block text-xl font-bold">
          Comenzar
          <i class="fas fa-chevron-right"></i>
        </a>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "ExperienceCard",
  props: ["cardDetails"]
};
</script>

<style>
button:focus {
  @apply outline-none;
}

.tint {
  background: linear-gradient(360deg, #000000 -22.61%, rgba(0, 0, 0, 0) 70.3%);
}
</style>
