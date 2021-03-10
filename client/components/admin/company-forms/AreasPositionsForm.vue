<template>
    <div class="ui equal width sixteen column left aligned padded grid stackable">
        <div class="ui segment">
            <div class="ui standard form">
                <div class="field">
                    <label for="parent_id">Responde a:</label>
                    <div class="ui left icon selectable">
                        <select name="parent_id" v-model="form.parent_id">
                            <option :value="-1">Ninguno</option>
                            <option v-for="(parent, index) in source.parent_list"
                                    :value="parent.id">
                                {{ parent.name }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="field" v-if="source.data_list.length">
                    <label for="company_area">{{ $t('messages.job_area') }}</label>
                    <div class="ui left icon selectable">
                        <select name="area_id" v-model="form.area_id">
                            <option v-for="(data,index) in source.data_list"
                                    :value="data.id">
                                {{ data.name }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="field">
                    <label for="name">Nombre</label>
                    <div class="ui left icon input">
                        <input name="name"
                               type="text"
                               v-model="form.name"
                               class="input"
                               placeholder="Nombre"
                               v-validate="validate.name">
                        <i class="icon user"></i>
                    </div>
                    <span class="text-danger" v-show="errors.has('name')">{{ errors.first('name') }}</span>
                </div>
                <div class="field">
                    <label for="short_name">Nombre corto</label>
                    <div class="ui left icon input">
                        <input name="short_name"
                               type="text"
                               v-model="form.short_name"
                               class="input"
                               placeholder="Nombre corto"
                               v-validate="validate.short_name">
                        <i class="icon user"></i>
                    </div>
                    <span class="text-danger" v-show="errors.has('short_name')">{{ errors.first('short_name') }}</span>
                </div>
                <div class="field">
                    <label for="description">Nombre</label>
                    <div class="ui left icon input">
                        <textarea name="description"
                                  class="input"
                                  v-model="form.description"
                                  placeholder="Descripción"
                                  cols="30"
                                  rows="10"
                                  v-validate="validate.description">
                        </textarea>
                    </div>
                    <span class="text-danger" v-show="errors.has('description')">{{ errors.first('description') }}</span>
                </div>
                <div class="field">
                    <button class="ui button primary" type="submit" @click="send">Guardar</button>
                    <a class="ui button red icon" :href="cancelUrl">{{$t('messages.cancel')}}</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "PositionsForm",
        props: {
            url: {
                type: String,
                required: true
            },
            method: {
                type: String,
                required: true
            },
            csrf: {
                type: String,
                required: true
            },
            parent: {
                type: Array,
                default() {return []}
            },
            dataList: {
                type: Array,
                default() {return []}
            },
            currentArea: {
                default: 0
            },
            currentCompany: {
                default: 0
            },
            currentData: {
                default() {return {}}
            },
            cancelUrl: {
                type: String,
                default: '#'
            }
        },
        mounted() {
            this.params.params = this.form;
            if(this.currentArea) {
                this.form.area_id = this.currentArea;
            }
            if(this.currentData.name) {
                this.load();
            };
        },
        methods: {
            load() {
                this.form.parent_id = (this.currentData.parent_id) ? this.currentData.parent_id : -1;
                this.form.company_id = this.currentData.company_id;
                this.form.area_id = this.currentData.area_id;
                this.form.name = this.currentData.name;
                this.form.short_name = this.currentData.short_name;
                this.form.description = this.currentData.description;
            },
            reset() {
                this.form.parent_id = -1;
                this.form.area_id = Number.parseInt(this.currentArea);
                this.form.name = '';
                this.form.short_name = '';
                this.form.description = '';
            },
            send() {
                this.$validator.validate().then(result => {
                    if(result) {
                        let title = 'Guardando';
                        let msg = 'Guardando información.';
                        this.$snotify.async(title, msg, () => new Promise((resolve, reject) => {
                            return axios(this.params)
                                .then((response)=>{
                                    title = 'Guardado correctamente';
                                    resolve({
                                        title: title,
                                        body: response.data.message,
                                        config: {
                                            closeOnClick: true,
                                            html: `
                                        <div class="snotifyToast__title">`+title+`</div>
                                        <div class="snotify-icon snotify-icon--success"></div>
                                        `
                                        }
                                    });
                                    this.reset()
                                })
                                .catch((response)=>{
                                    title = 'Error!!!';
                                    console.log(response.data);

                                    reject({
                                        title: title,
                                        body: response.data.message,
                                        config: {
                                            closeOnClick: true,
                                            html : `
                                        <div class="snotifyToast__title">`+title+`</div>
                                        <div class="snotifyToast__body">`+response.data.message+`</div>
                                        <div class="snotify-icon snotify-icon--success"></div>
                                        `
                                        }
                                    })

                                })
                        }));
                    }
                    else {
                        this.$snotify.warning(
                            'Revise que los datos sean correctos',
                            'Campos inválidos',
                            {
                                timeout: 2000,
                                showProgressBar: false,
                                closeOnClick: true,
                                pauseOnHover: true
                            }
                        );
                    }
                });
            },
        },
        data() {
            return {
                form: {
                    parent_id: -1,
                    company_id: Number.parseInt(this.currentCompany),
                    area_id: Number.parseInt(this.currentArea),
                    name: '',
                    short_name: '',
                    description: ''
                },
                params: {
                    url: this.url,
                    method: this.method,
                    headers: {
                        'X-CSRF-TOKEN': this.csrf
                    },
                    params: {},
                },
                source: {
                    parent_list: this.parent,
                    data_list: this.dataList,
                },
                validate: {
                    name: 'alpha_spaces|required',
                    short_name: 'alpha_spaces',
                    description: 'max:250'
                }
            };
        }
    }
</script>

<style scoped>

</style>