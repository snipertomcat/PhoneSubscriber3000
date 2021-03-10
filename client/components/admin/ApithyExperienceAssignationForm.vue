<template>
    <div class="ui segments container">
        <div class="iframe-experience-header ui segment no-borders no-shadows">
            <img src="/images/apithy_small.png" width="30">
            <span class="iframe-experience-header-title">Nueva asignación</span>
            <button class="ui red icon button right floated close-modal"
                    @click="closeModal('apithy-experience-assignation-form')">
                <i class="ui cancel icon"></i>
            </button>
        </div>
        <div class="ui segment no-borders no-shadows">
            <form class="ui form">
                <h2 class="ui dividing header">Nueva asignación</h2>
                <div class="field">
                    <div class="two fields">
                        <div class="field">
                            <label for="tipo">Tipo</label>
                            <select name="tipo" class="ui fluid dropdown" v-model="params.type">
                                <option v-for="(type,index) in types" :value="type.value">{{type.label}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="field">
                        <label for="description">Descripción</label>
                        <textarea name="description" v-model="params.description"></textarea>
                    </div>
                    <div class="field">
                        <apithy-entity-asignator :entity="experience"
                                                 :companies_data="companies"
                                                 :users_data="users"
                                                 ref="entityAsignation">
                        </apithy-entity-asignator>
                    </div>
                    <div class="fields two">
                        <div class="field">
                            <label for="start">Inicia</label>
                            <input type="date" name="start" v-model="params.start">
                        </div>
                        <div class="field">
                            <label for="finish">Finaliza</label>
                            <input type="date" name="finish" v-model="params.finish">
                        </div>
                    </div>
                    <div class="two fields">
                        <div class="field">
                            <label for="status">Estado</label>
                            <select name="status" class="ui fluid dropdown" v-model="params.status">
                                <option v-for="(status,index) in list_status" :value="status.value">{{status.label}}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <span class="dividing header"></span>
                <div class="ui button blue labeled icon" name="submit" @click="submit"><i class="ui icon save"></i>Guardar</div>
            </form>
        </div>
    </div>
</template>

<script>
    import EntityAsignator from './EntityAsignator';

    export default {
        name: "apithy-experience-assignation-form",
        components: {
            "apithy-entity-asignator":EntityAsignator
        },
        props: [
            'experience',
            'companies',
            'users'
        ],
        methods: {
            submit() {
                this.params.company_visibility_settings = this.$refs.entityAsignation.getCompanyValues();
                this.params.user_visibility_settings = this.$refs.entityAsignation.getUserValues();
                axios({
                    method: 'POST',
                    url: this.url,
                    params: this.params
                }).then((response) => {
                    this.closeModal('apithy-experience-assignation-form');
                    window.eventbus.$emit('reloadTableAssignationList');
                    window.alert('Se guardó la asignación correctamente'); //cambiar por un modaldialog
                }).catch((error) => {
                    console.error(error);
                });
            },
        },
        data() {
            return {
                url: document.location.origin+document.location.pathname+'/setAssignations',
                params: {
                    description: '',
                    type: '',
                    status: '',
                    company_visibility_settings: '',
                    user_visibility_settings: '',
                    start: '',
                    finish: ''
                },
                list_status:[
                    {label:'Inactivo', value:0},
                    {label:'Activo', value:1},
                    {label:'Terminado',value:2}
                ],
                types: [
                    {label:'Obligación', value:'mandatory'},
                    {label:'Invitación', value:'invitation'}
                ]
            }
        }
    }
</script>

<style scoped>
    .form {
        padding: 2rem;
    }
    .close-button {
        z-index: 1;
    }
</style>