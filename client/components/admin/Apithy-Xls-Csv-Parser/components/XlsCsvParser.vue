<template>
  <div class="xls-csv-parser">
    <parse-file @fileDataReceived="fileDataReceived" :help="help" ref="parseFile" :lang="lang"></parse-file>
    <br><br>
    <column-chooser
      v-if="showColumnChooser"
      ref="columnChooser"
      :userColumns="userColumns"
      :columns="columns"
      :showValidateButton="showValidateButton"
      :lang="lang"
      @on-validate="onValidate"
    >
    </column-chooser>
    <div class="parser-hidden-columns-input" v-for="(result, i) in results">
      <input type="hidden" v-for="(data, i) in result.data" :name="`${result.column}[${i}]`" :value="data">
    </div>
    <br />
    <button class="button is-primary" v-if="showValidateButton"  @click="validate" id="validate-columns" labeled icon="save"> {{ text[lang].submit }}</button>
    <button class="button is-primary" v-if="showValidateButton" @click="reset" icon="plus circle">{{ text[lang].file.tryAgain }}</button>
  </div>
</template>

<script>
  import _ from 'lodash';
  import ColumnChooser from './ColumnChooser';
  import ParseFile from './ParseFile';
  import text from '../lang/index';

  export default {
    name: 'XlsCsvParser',
    components: {
        ColumnChooser,
        ParseFile},
    props: {
      columns: {
        type: Array,
        required: true,
        validator: columns => columns.every(column => _.has(column, 'name') && _.has(column, 'value')),
      },
      validateButtonId: {
        type: String,
        default: () => null,
      },
      help: {
        type: String,
      },
      lang: {
        type: String,
        validator: language => ['en', 'fr','es'].indexOf(language) !== -1,
        default: () => 'es',
      },
    },
    methods: {
      fileDataReceived(fileData) {
        this.results = [];
        this.usersInfo = [];
        const requiredColumns = this.columns.filter(column => !column.isOptional);
        if (fileData.length < requiredColumns.length) {
          const message = `${text[this.lang].error.columnNumber}<br> ${this.columns.map(column => column.name).join('<br> ')}`;
          this.showError(message);
          return;
        }
        this.userColumns = fileData;
        this.showColumnChooser = true;
        this.showValidateButton=true;
        this.$parent.clearData();

      },
      onValidate(results) {
        this.results = results;
        this.$emit('on-validate', results);
      },
      validate(event) {
        event.preventDefault();
        if (!this.showColumnChooser) {
          this.showError(text[this.lang].error.fileNumber);
        } else {
          this.$refs.columnChooser.validate();
        }
      },
      showError(message) {
        this.$snotify.error(
                message,
                text[this.lang].error.title,
                {
                  showProgressBar: false,
                  closeOnClick: false,
                  timeout: 0,
                  buttons: [
                    {
                      bold: true,
                      text: text[this.lang].close,
                      action: toast => {
                        this.$refs.parseFile.reset();
                        this.results=[];
                        vm.$snotify.remove(toast.id);
                        },
                    }
                  ]
                }
        );
      },
      reset(){
          this.$refs.parseFile.reset();
      },
    },
    mounted() {
      if (this.validateButtonId) {
        this.showValidateButton = false;
        document.getElementById(this.validateButtonId).addEventListener('click', this.validate);
      }
    },
    data() {
      return {
        showColumnChooser: false,
        showHiddenInputs: false,
        showValidateButton: false,
        results: [],
        usersInfo:[],
        userColumns: [],
        text
      };
    },
  };
</script>

