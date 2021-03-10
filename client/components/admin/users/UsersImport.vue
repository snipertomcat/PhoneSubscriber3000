<template>
    <div class="row mt-4">
        <div class="col-12">
            <div class="apithy-import-users">
                <div class="font-20 has-text-weight-bold">
          <span>
            Importa archivos excel/csv con tus usuarios  (<a target="_blank" href="/import_users_file_example.xlsx">descargar plantilla de ejemplo</a>)
          </span>
                </div>
                <!--If the use is not Super, the company value is taked from the session values in the current auth user -->
                <div v-if="auth.is_super">
                    <div>
                        <label>{{ $t('messages.company') }}</label><br>
                        <div class="row">
                            <div class="select" style="margin-bottom:0;margin-left: 10px">
                                <select class="font-14" v-model="company" name="company_id" id="company_id">
                                    <option selected :value="0">Selecciona una empresa</option>
                                    <option v-for="company_item in companies" :key="company_item.id"
                                            :value="company_item.id">
                                        {{ company_item.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div>
                    <!--div :class="{'active': isLoading}">
                      <div class="loader">Procesando archivo</div>
                    </div-->
                    <apithy-xls-csv-parser
                            :columns="columns"
                            @on-validate="onValidate"
                            @showError="showError"
                            :help="help"
                            lang="es">
                    </apithy-xls-csv-parser>
                </div>
                <br/>
                <div class="results" v-if="!isLoading && !!paginatedResults.length">
          <span class="font-16 has-text-weight-bold">
            Resultados:
          </span>
                    <div class="card b-table">
                        <div class="card-content table-wrapper">
                            <table class="table is-fullwidth has-mobile-cards">
                                <thead>
                                <tr>
                                    <th v-for="column in tableColumns">
                                        {{ column.name }}
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(user,index) in paginatedResults">
                                    <td v-for="column in tableColumns"
                                        :class="{
                        'error': !user.imported,
                        'positive': user.imported
                      }">
                      <span v-if="column.value !== 'imported'">
                        {{ user[column.value] }}
                      </span>
                                        <span v-else>
                      {{(user[column.value]) ? 'Importado': "No importado"}}
                    </span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-6 col-sm-2 has-text-centered users-import-prev">
                                    <button class="button" @click="resultPrevPage">
                                        <span class="icon is-small"><i class="fa fa-angle-left"></i></span>
                                        <span>Anterior</span>
                                    </button>
                                </div>
                                <div class="col-6 col-sm-2 has-text-centered users-import-next">
                                    <button class="button" @click="resultNextPage">
                                        <span>Siguiente</span>
                                        <span class="icon is-small"><i class="fa fa-angle-right"></i></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {Loading} from 'element-ui'
    /*
    let areYouReallySure = false;
    function areYouSure () {
      if(allowPrompt) {
        if (!areYouReallySure && true) {
          areYouReallySure = true;
          let confMessage = "¿Seguro que desea salir?"
          return confMessage;
        }
      }else{
        allowPrompt = true;
      }
    }

    let allowPrompt = true;
    window.onbeforeunload = areYouSure;
    */
    export default {
        name: 'UsersImport',
        components: {
            'apithy-xls-csv-parser': () => import('../Apithy-Xls-Csv-Parser/index.js'),
        },
        created() {
            this.auth = _.isEmpty(this.auth_user) ? this.$root.$refs.adminNav.user : this.auth_user;
        },
        computed: {
            paginatedResults() {
                if (this.usersInfo) {
                    return this.usersInfo.filter((row, index) => {
                        let start = (this.currentPage - 1) * this.pageSize;
                        let end = this.currentPage * this.pageSize;
                        if (index >= start && index < end) return true;
                    });
                } else {
                    return [];
                }
            }
        },
        methods: {
            onValidate(results) {
                this.isLoading = true;
                this.results = results;
                this.usersInfo = [];
                this.results.forEach(value => {
                    let field = value.column;
                    value.data.forEach((fieldValue, index) => {
                        if (typeof  this.usersInfo[index] == 'undefined') {
                            this.usersInfo[index] = {};
                        }
                        this.usersInfo[index][field] = fieldValue;
                    });
                });
                this.chunkData(this.usersInfo);
            },
            clearData() {
                this.usersInfo = [];
                this.results = [];
            },
            chunkData(usersInfo) {
                /*
                let loader = Loading.service({
                    fullscreen: true,
                    //spinner: 'el-icon-loading',
                    background: 'rgba(0, 0, 0, 0.8)',
                    //text: 'Procesando petición'
                });
                */
                this.isLoading = true;
                let chunkUsers = _.chunk(usersInfo, 100)
                let data = {}
                if (this.company) {
                    data.company_id = this.company;
                }
                let count = _.size(chunkUsers)
                let eachCount = 0
                let countSave = 0
                let countErrors = 0
                let withErrors = false
                this.usersInfo = []
                this.$snotify.async('Importando 100 de '+ _.size(usersInfo), 'Procesando petición', () => new Promise(async (resolve, reject) => {
                    for (const value of chunkUsers) {
                        eachCount++
                        await this.sendData({company_id: data.company_id, users: value})
                            .then(response => {
                                let successList = _.get(response, ['data', 'data'], [])
                                successList = successList.filter(item => {
                                    return item['imported'] == true
                                })
                                if (!_.isEmpty(successList)) {
                                    this.usersInfo.push(...successList)
                                }
                                countSave += Number(_.get(response, ['data', 'countSave'], 0))
                                $(".snotifyToast__body").html('Importando '+countSave+ ' de '+_.size(usersInfo));
                            })
                            .catch(errors => {
                                withErrors = true
                                let errList = _.get(errors, ['response', 'data', 'data'], [])
                                errList = errList.filter(item => {
                                    return item['imported'] == false
                                })
                                if (!_.isEmpty(errList)) {
                                    this.usersInfo.push(...errList)
                                }
                                countErrors += Number(_.get(errors, ['response', 'data', 'countErrors'], 0))
                                $(".snotifyToast__body").html('Importando '+countErrors+ ' de '+_.size(usersInfo));
                            })
                            .then(() => {
                                if (count === eachCount) {
                                    //loader.close()
                                    this.isLoading = false;
                                    if (!withErrors) {
                                        resolve({
                                            title: 'Proceso finalizado',
                                            body: `${countSave} Usuario(s) importados`,
                                            config: {
                                                showProgressBar: true,
                                                closeOnClick: true,
                                                timeout: 3000,
                                            }
                                        });
                                    } else {
                                        reject({
                                            title: 'Proceso finalizado con errores',
                                            body: `Algunos datos no son válidos`,
                                            config: {
                                                closeOnClick: true,
                                                timeout: 3000,
                                                html: `
                      <div class="snotifyToast__title">Error!</div>
                      <div class="snotifyToast__body">${countErrors} usuario(s) ignorados por datos invalídos</div>
                      <div class="snotify-icon snotify-icon--success"></div>`
                                            }
                                        });
                                    }
                                }
                            })
                    }
                }),{
                    preventDuplicates: true,
                    onlyOne: true,
                    position: 'centerBottom',
                    backdrop: 0.6,
                })
            },
            sendData(data) {
                return axios.post('/users/import-json', data)
            },
            resultNextPage() {
                if ((this.currentPage * this.pageSize) < this.usersInfo.length) this.currentPage++;
            },
            resultPrevPage() {
                if (this.currentPage > 1) this.currentPage--;
            },
            showError() {
                //
            }
        },
        props: {
            company_id: {
                type: Number,
                default: null,
            },
            companies: {
                type: Array,
                default() {
                    return [];
                }
            },
            auth_user: {
                type: Object,
                default() {
                    return {}
                }
            }
        },
        data() {
            return {
                columns: [
                    {name: 'Email', value: 'email'},
                    {name: 'Num. Celular', value: 'phone'},
                    {name: 'Nombre', value: 'name'},
                    {name: 'Apellido', value: 'surname'},
                    {name: 'Rol', value: 'role', isOptional: true},
                    {name: 'Puesto', value: 'job_title', isOptional: true},
                    {name: 'Área', value: 'job_area', isOptional: true},
                    {name: 'Tags', value: 'tags', isOptional: true},
                ],
                tableColumns: [
                    {name: 'Email', value: 'email', isOptional: true},
                    {name: 'Num. Celular', value: 'phone', isOptional: true},
                    {name: 'Num. Celular', value: 'name'},
                    {name: 'Apellido', value: 'surname'},
                    {name: 'Rol', value: 'role', isOptional: true},
                    {name: 'Puesto', value: 'job_title', isOptional: true},
                    {name: 'Área', value: 'job_area', isOptional: true},
                    {name: 'Tags', value: 'tags', isOptional: true},
                    {name: 'Status', value: 'imported', isOptional: true},
                ],
                results: null,
                help: 'Columnas obligatorias: Nombre, Apellidos, Email/Num. Celular',
                isLoading: false,
                usersInfo: [],
                pageSize: 10,
                currentPage: 1,
                company: this.company_id,
                auth: {}
            };
        },
    };
</script>

<style scoped>
    .users-import-prev button {
        background-color: transparent;
        border: none;
        color: #106CFB;
    }

    .users-import-next button {
        background-color: transparent;
        border: none;
        color: #106CFB;
    }

    td.error {
        color: red;
    }

    td.positive {
        color: green;
    }

    .el-loading-spinner {
        display: none !important;
    }

</style>