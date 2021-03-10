<template>
    <div class="catalog-column-chooser b-table">
        <div class="table-wrapper">
        <table id="import-user-table" class="table is-fullwidth has-mobile-cards">
            <tbody>
            <tr>
                <td class="file-column" v-for="(column, index) in localUserColumns">
                    <div class="row">
                        <div class="row-item">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <span class="field-info">
                                        {{ text[lang].columnData }} <i>"{{ column.name }}"</i>
                                    </span>
                                    <multiselect
                                            :id="index"
                                            v-model="column.selection"
                                            deselect-label=""
                                            :selected-label="text[lang].selected"
                                            :select-label="text[lang].select"
                                            track-by="name"
                                            label="name"
                                            :placeholder="text[lang].selectColumn"
                                            :options="column.options"
                                            :searchable="true"
                                            :allow-empty="false"
                                            @select="onSelectChange"
                                    ></multiselect>
                                </div>
                                <div class="panel-body">
                                    <div class="">
                                        <table class="table is-fullwidth">
                                            <thead>
                                            <tr>
                                                <th class="text-center">{{ column.name }}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-for="data in column.displayedData">
                                                <td>{{ data }}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        </div>
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect';
    import _ from 'lodash';
    import text from '../lang/index';

    export default {
        name: 'ColumnChooser',
        components: {Multiselect},
        props: {
            userColumns: {
                type: Array,
                required: true,
                validator: columns => columns.every(column => _.has(column, 'name') && _.has(column, 'data')),
            },
            columns: {
                type: Array,
                required: true,
                validator: columns => columns.every(column => _.has(column, 'name') && _.has(column, 'value')),
            },
            showValidateButton: {
                type: Boolean,
                default: () => true,
            },
            lang: {
                type: String,
                default: () => 'en',
            },
        },
        watch: {
            userColumns(newColumns) {
                this.fillLocalUserColumns(newColumns);
            },
        },
        methods: {
            onSelectChange({value: selectedValue}, id) { // eslint-disable-line
                console.log(selectedValue,id);
                if(selectedValue=="email"){
                    let index_to_delete=this.requiredValues.indexOf('phone');
                    this.$delete(this.requiredValues, index_to_delete) ;

                    let index_to_check=this.requiredValues.indexOf('email');
                    if(index_to_check == -1){
                        this.requiredValues.push('email')
                    }

                    index_to_check=this.optionalValues.indexOf('phone');
                    if(index_to_check== -1){
                        this.optionalValues.push('phone')
                    }

                    index_to_check=this.optionalValues.indexOf('email');
                    this.$delete(this.optionalValues, index_to_check) ;


                }else if(selectedValue=="phone"){
                    let index_to_delete=this.requiredValues.indexOf('email');
                    this.$delete(this.requiredValues, index_to_delete) ;

                    let index_to_check=this.requiredValues.indexOf('phone');
                    if(index_to_check == -1){
                        this.requiredValues.push('phone')
                    }

                    let index_to_check_optional=this.optionalValues.indexOf('email');
                    if(index_to_check_optional == -1){
                        this.optionalValues.push('email')
                    }

                    index_to_check=this.optionalValues.indexOf('phone');
                    this.$delete(this.optionalValues, index_to_check) ;

                }

                console.log(this.optionalValues,"Optional");
                console.log(this.requiredValues,"Required");

                if (this.optionalValues.indexOf(selectedValue) !== -1) return;

                _.forEach(this.localUserColumns, (column, index) => {
                    if (index !== id) {
                        const hasSelectionConflict = column.selection && column.selection.value === selectedValue;
                        if (hasSelectionConflict) {
                            column.selection = null; // eslint-disable-line
                        }
                    }
                });
            },
            validate() {
                const hasMadeAllSelections = this.localUserColumns.every(column => column.selection !== null);
                if (false) {
                    this.showError(text[this.lang].error.selectColumn); // eslint-disable-line
                } else {
                    const selectedRequiredValues = _
                        .map(this.localUserColumns, 'selection.value')
                        .filter(value => this.requiredValues.indexOf(value) !== -1);

                    console.log(selectedRequiredValues);
                    const missingValues = _.difference(this.requiredValues, selectedRequiredValues);
                    if (missingValues.length > 0) {
                        this.showError(`${text[this.lang].error.missingColumns} ${missingValues.join(', ')}`); // eslint-disable-line
                        return;
                    }

                    this.results = this.localUserColumns.map(localColumn => ({
                        column: (localColumn.selection) ? localColumn.selection.value :'',
                        data: localColumn.data,
                    }));

                    this.$emit('on-validate', this.results);
                }
            },
            fillLocalUserColumns(newColumns) {
                this.localUserColumns = newColumns.map(column => ({
                    name: column.name,
                    displayedData: _.take(column.data, 4),
                    data: column.data,
                    options: _.clone(this.columns),
                    selection: null,
                }));
            },
            toggleHeaders() {
                if (this.withHeaders) {
                    this.localUserColumns.forEach((column, index) => {
                        const data = [column.name].concat(column.data);
                        column.name = `${text[this.lang].column} ${index + 1}`; // eslint-disable-line
                        column.data = data; // eslint-disable-line
                        column.displayedData = _.take(column.data, 4); // eslint-disable-line
                    });
                } else {
                    this.localUserColumns.forEach((column) => {
                        column.name = column.data.shift(); // eslint-disable-line
                        column.displayedData = _.take(column.data, 4); // eslint-disable-line
                    });
                }
                this.withHeaders = !this.withHeaders;
            },
            showError(message) {
                this.$snotify.error(
                    message,
                    text[this.lang].error.title,
                    {
                        showProgressBar: true,
                        closeOnClick: true,
                        timeout: 3000,
                    }
                );
            },
        },
        data() {
            return {
                withHeaders: true,
                localUserColumns: [],
                requiredValues: [],
                optionalValues: [],
                results: [],
                text,
            };
        },
        mounted() {
            this.fillLocalUserColumns(this.userColumns);
            this.optionalValues = this.columns
                .filter(column => column.isOptional)
                .map(column => column.value);
            this.requiredValues = this.columns
                .filter(column => !column.isOptional)
                .map(column => column.value);
        },
    };
</script>

<style scoped>

    .catalog-column-chooser .panel{
        padding: 10px;
    }

    .catalog-column-chooser .multiselect{
        margin-top: 5px;
    }

    .panel-heading{
        font-size: 14px;
        text-align: center;
        margin-bottom:5px;
    }

    .catalog-column-chooser .field-info{
        margin-bottom: 5px;
        min-height: 40px;
        display: block;
    }

    .catalog-column-chooser .row-item{
        width: 100%;
    }

    @media only screen and (max-width: 576px) {
        .row {
            width: 100%;
            padding: 0 !important;
            margin: 0 !important;
        }

        .b-table .table.has-mobile-cards tr:not(.detail):not(.is-empty):not(.table-footer) td{
            display: block;
        }
    }



</style>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
