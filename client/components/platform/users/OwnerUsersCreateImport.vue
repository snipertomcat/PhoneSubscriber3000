<template>
  <div class="mr-0">
    <b-collapse class="card" aria-id="userForm" :open.sync="m_create.open">
      <div
          slot="trigger"
          slot-scope="props"
          class="card-header"
          role="button"
          aria-controls="userForm">
        <a class="card-header-icon">
          <i class="fa" :class="props.open ? 'fa-arrow-up' : 'fa-arrow-down'" aria-hidden="true"></i>
        </a>
        <p class="card-header-title">
          Nuevo usuario
        </p>
      </div>
      <div class="container block-centered">
        <apithy-create-user
            :is_super="createData.is_super"
            :companies="createData.companies"
            :current_company="createData.current_company"
            :valid_domains="createData.valid_domains"
            :roles="createData.roles"
            :taxonomy_areas="createData.taxonomy_areas"
            :taxonomy_abilities="createData.taxonomy_abilities"
            :taxonomy_teams="createData.taxonomy_teams"
            :taxonomy_positions="createData.taxonomy_positions"
            :taxonomy_certifications="createData.taxonomy_certifications"
            :taxonomy_customs="createData.taxonomy_customs">
        </apithy-create-user>
      </div>
    </b-collapse>
    <b-collapse class="card" aria-id="userBulkForm" :open.sync="m_import.open">
      <div
          slot="trigger"
          slot-scope="props"
          class="card-header"
          role="button"
          aria-controls="userBulkForm">
        <a class="card-header-icon">
          <i class="fa" :class="props.open ? 'fa-arrow-up' : 'fa-arrow-down'" aria-hidden="true"></i>
        </a>
        <p class="card-header-title">
          Importar de base de datos
        </p>
      </div>
      <div class="container block-centered">
        <apithy-import-user
            :companies="importData.companies"
            :company_id="importData.company_id">
        </apithy-import-user>
      </div>
    </b-collapse>
  </div>
</template>

<script>
  import Create from '../../admin/users/UserForm.vue';
  import Import from '../../admin/users/UsersImport.vue';

export default {
  name: "OwnerUsersCreateImport",
  components: {
    'apithy-create-user': Create,
    'apithy-import-user': Import,
  },
  props: {
    createData: {
      type: Object,
      required: true
    },
    importData: {
      type: Object,
      required: true
    },
  },
  data () {
    return {
      m_create: {
        open: false
      },
      m_import: {
        open: false
      }
    }
  }
}
</script>

<style scoped>

</style>