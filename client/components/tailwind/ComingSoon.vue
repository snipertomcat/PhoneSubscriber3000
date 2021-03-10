<template>
<div class="w-screen h-screen">
  <div class="wrapper">
    <div class="h-80% w-full relative">
      <p class="text centering text-2xl lg:text-5xl">
        ¡Próximamente al aire!
        <br />
        <br />Vive la evolución de los cursos en línea y sumérgete en experiencias digitales con apithy.
      </p>
    </div>
    <div class="h-20% relative">
      <img class="image centering" width="218" height="48" src="/logo_beta_azul.png" />
    </div>
  </div>
</div>
  
</template>

<script>
export default {
  name: 'ComingSoon'
}
</script>

<style>
.wrapper {
  @apply h-full pb-5 bg-cover bg-no-repeat bg-left-top font-semibold;
  background-image: url("/images/coming_soon/fake-homepage.jpg");
  font-family: 'Montserrat', 'sans';
}

.centering {
  @apply absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

.text {
  @apply w-3/4 text-white text-center font-bold ;
  /* font-size: 5vh; */
}

.image {
  @apply w-auto px-5;
}
</style>