<template>
    <div class="entity_asignator">
        <sui-form-field class="ui segment">
            <label class="label">Nivel de empresa</label>
            <treeselect
                    :multiple="true"
                    :options="companies_data"
                    placeholder="Visible para:"
                    v-model="company_values"
            />
            <div v-for="company_value in company_values">
                <input type="hidden" name="company_visibility_settings[]" v-bind:value="company_value">
            </div>
        </sui-form-field>

        <sui-form-field class="ui segment">
            <label class="label">Nivel de usuario</label>
            <treeselect
                    :multiple="true"
                    :options="users_data"
                    placeholder="Visible para:"
                    v-model="user_values"
            />
            <div v-for="user_value in user_values">
                <input type="hidden" name="user_visibility_settings[]" v-bind:value="user_value">
            </div>
        </sui-form-field>
    </div>
</template>
<script>

    import '@riophae/vue-treeselect/dist/vue-treeselect.css'

    export default {
        name: 'apithy-entity-asignator',
        props:{
          companies_data:"",
          users_data:"",
          entity:""
        },
        components: {
            "treeselect":() => import('@riophae/vue-treeselect')
        },
        methods: {
            getCompanyValues() {
                return this.company_values;
            },
            getUserValues() {
                return this.user_values;
            }
        },
        data() {
            return {
                company_values: ('company_visibility_settings' in this.entity) ? this.entity.company_visibility_settings: [],
                user_values:('user_visibility_settings' in this.entity) ? this.entity.user_visibility_settings: [],
                options: []

            }
        }
    }
</script>
