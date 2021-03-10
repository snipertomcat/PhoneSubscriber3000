<template>
  <div class="w-10/12 mx-auto flex justify-between">
    <div class="w-auto">
      <button class="btn secondary-button retry" @click="retry">
        <div class="retry-tooltip">{{ `Limpiar` }}</div>
        <i class="fas fa-undo"></i>
      </button>
    </div>
    <div class="w-auto">
      <button class="btn sm:w-full lg:w-32 h-12 secondary-button"
              :class="{'disabled':disableVerify}" @click="verify">Verificar
      </button>
    </div>
  </div>
</template>

<script>
  export default {
    name: "NoEvaluableButtons",
    props: {
      retry: {
        required: true,
      },
      verify: {
        required: true
      },
      disableVerify: {
        required: false,
        default: false,
        type: Boolean
      },
      disableRetry: {
        required: false,
        default: false,
        type: Boolean
      }
    }
  }
</script>

<style lang="scss" scoped>
  .retry {
    @apply relative;
    .retry-tooltip {
      @apply shadow-md rounded;
      position: absolute;
      display: none;
      top: -100%;
      left: -50%;
      width: 100px;
      height: auto;
      font-size: 1rem;
      background-color: lightgrey;
      color: #444444;
      padding: 4px 2px;
    }

    &:hover {
      .retry-tooltip {
        display: block;
      }
    }

  }
</style>
