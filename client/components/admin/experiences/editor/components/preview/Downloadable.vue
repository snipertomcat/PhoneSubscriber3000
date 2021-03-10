<template>
  <div class="p-3">
    <div class="flex flex-row align-center">
      <div class="w-full">
        <p v-html="description">
          {{ description }}
        </p>
      </div>
    </div>
    <div class="w-full h-auto flex justify-center">
      <a :href="file_selected" target="_blank">
        <button class="my-6 w-40 text-blue-medium">
          <span class="block w-full">Descarga el contenido</span>
          <span class="mt-2 w-auto block text-4xl">
            <i class="fas fa-download my-button"></i>
          </span>
        </button>
      </a>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'Downloadable',
    props: {
      componentData: {
        required: true,
        type: Object
      }
    },
    computed: {
      component () {
        return this.componentData
      },
      file_selected () {
        return this.component.config.source
      },
      file_name () {
        return this.component.config.name
      },
      description () {
        return this.component.config.description
      }
    }
  }
</script>

<style scoped>
  .my-button {
    padding: 10px;
    border-radius: 50%;
    border: 2px solid #1a6bf7;
  }
  .text-blue-medium {
    color: #1a6bf7;
  }
  .my-6 {
    margin-top: 1.5rem;
    margin-bottom: 1.5rem;
  }
</style>
