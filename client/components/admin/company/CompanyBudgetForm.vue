<template>
    <div class="container">
        <hr width="2">
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <span class="col-12 col-md-3 font-18 has-text-weight-bold">
                        Presupuesto
                        <span v-if="typeof company.budget_balance  !='undefined' && company.budget_balance !=null"
                              v-money-format="company.budget_balance">

                        </span>
                    </span>
                </div>
                <br>
                <div class="row font-14">
                    <div class="col-12 col-md-4">
                        <b-input icon-pack="fas"
                                 icon="money"
                                 name="init_balance"
                                 placeholder="Importe"
                                 @keypress.native="isNumber"
                                 v-model="form.amount"
                                 v-validate="validations.init_balance">
                        </b-input>
                        <span class="has-text-danger" v-show="errors.has('init_balance')">{{ errors.first('init_balance') }}</span>
                    </div>
                    <!--
                    <div class="col-12 col-md-4">
                        <div class="select width-100">
                            <select name="type"
                                    class="width-100"
                                    v-model="form.type"
                                    v-validate="validations.type">
                                <option value="">Periodo</option>
                                <option :value="type.value" v-for="type in budget_types">{{ type.label }}</option>
                            </select>
                        </div>
                        <span class="has-text-danger" v-show="errors.has('type')">{{ errors.first('type') }}</span>
                    </div>
                    <div class="col-12 col-md-4">
                        <b-autocomplete
                                v-model="raw.area"
                                :data="filteredDataObj"
                                field="name"
                                placeholder="Asignar al area..."
                                @select="option => form.area_id = option.id">
                            <template slot="empty">{{ $t('messages.budgets.area_not_found') }}</template>
                        </b-autocomplete>
                        <span class="has-text-danger" v-show="errors.has('area')">{{ errors.first('area') }}</span>
                    </div>
                    -->
                </div>
            </div>
        </div>
        <hr width="1">
        <div class="row justify-content-center">
            <div class="col-12 col-md-2 has-text-centered">
                <button class="button is-link width-100" @click="sendForm" :disabled="errors.any()">Añadir</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "CompanyBudgetForm",
        props: {
            authUser: {
                type: Object,
                required: true
            },
            company: {
                type: Object,
                required: true,
            },
        },
        methods: {
            isNumber(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                    evt.preventDefault();
                } else {
                    //let el = evt.target;
                    //if (parseInt(el.value.length) <= 10) {
                        return true;
                    //}
                    //evt.preventDefault();
                }
            },
            sendForm() {
                this.$validator.validateAll().then(result => {
                    if (result) {
                        this.form.current_balance = this.form.amount;
                        this.$snotify.async('Empresa', 'Procesando', () => new Promise((resolve, reject) => {
                            return axios({
                                method: 'POST',
                                url: route('companies.budget.store', [this.company.id]),
                                params: this.form,
                            }).then((response) => {
                                resolve({
                                    title: 'Petición exitosa',
                                    body: 'Presupuesto creado con éxito.',
                                    config: {
                                        closeOnClick: true,
                                        html: `
                                        <div class="snotifyToast__title">Petición exitosa</div>
                                        <div class="snotifyToast__body">Empresa creada con éxito.</div>
                                        <div class="snotify-icon snotify-icon--success"></div>
                                        `
                                    }
                                });

                                document.location.href = route('companies.budget.create',[this.company.id]);

                            }).catch((error) => {
                                console.log(error);
                                reject({
                                    title: 'Error!!',
                                    body: 'No se pudo procesar la petición correctamente.',
                                    config: {
                                        closeOnClick: true,
                                        html: `
                                         <div class="snotifyToast__title">Error!!</div>
                                        <div class="snotifyToast__body">No se pudo procesar la petición correctamente.</div>
                                        <div class="snotify-icon snotify-icon--error"></div>
                                        `
                                    }
                                });
                            });

                        }));
                    }
                });
            }
        },
        computed: {
            filteredDataObj() {
                return this.company.areas.filter((option) => {
                    return option.name
                        .toString()
                        .toLowerCase()
                        .indexOf(option.name.toLowerCase()) >= 0
                })
            },
        },
        data() {
            return {
                budget_types: [
                    {
                        'label': 'Anual',
                        'value': 'annual'
                    },
                    {
                        'label': 'Semestral',
                        'value': 'biannual'
                    },
                    {
                        'label': 'Trimestral',
                        'value': 'quarterly'
                    },
                    {
                        'label': 'Mensual',
                        'value': 'monthly'
                    },
                ],
                raw: {
                    area: ''
                },
                form: {
                    area_id: null,
                    amount: null,
                    type: "",
                    company_id: this.company.id,
                    user_id: this.authUser.id
                },
                actions: {
                    create: {
                        cancel_button: false,
                        confirm_button: true,
                        label: 'Añadir'
                    },
                    edit: {
                        cancel_button: true,
                        confirm_button: true,
                        label: 'Actualizar'
                    },
                    show: {
                        cancel_button: false,
                        confirm_button: false,
                    }
                },
                validations: {
                    init_balance: {
                        required: true,
                        max_value: 100000000,
                        min: 1
                    },
                    type: {
                        required: true
                    }
                }
            }
        }
    }
</script>

<style scoped>

</style>