import _ from 'lodash'
import { Loading } from 'element-ui'

const index = {
  /**
   * Obtiene el conjunto de errores (error 422), si no hay, retorna false.
   *
   * @param errors
   * @returns {*}
   */
  hasErrors (errors) {
    let status = _.get(errors, ['response', 'status'])
    if (status === 422) {
      return _.get(errors, ['response', 'data', 'errors'], false)
    }
    return false
  },
  /**
   *
   * Prepara los errores de validacion del servidor para mostrarlos mediante Veevalidate
   *
   * @param vErrors - Objecto errors de Veevalidate
   * @param errors - Lista de errores de validacion del servidor
   */
  setErrors (vErrors, errors) {
    for (const key of Object.keys(errors)) {
      if (errors.hasOwnProperty(key)) {
        vErrors.add({
          field: key,
          msg: errors[key][0],
          rule: key
        })
      }
    }
  },
  onlyNumbers (evt) {
    evt = ((evt) ? evt : window.event);
    let charCode = (evt.which) ? evt.which : evt.keyCode;
    switch (true) {
      case (charCode >= 48 && charCode <= 105):
      case (charCode === 8):
      case (charCode === 9):
      case (charCode === 27):
      case (charCode === 37):
      case (charCode === 39):
      case (charCode === 46):
      case (charCode === 144):
        return true
      default:
        evt.preventDefault();
        break;
    }
  },
  // FILTERS
  /**
   * Remove all diatrics from the given text.
   *
   * @access public
   * @param {String} text
   * @returns {String}
   */
  normalizeToBase (text) {
    const rExps = [
      {re: /[\xC0-\xC6]/g, ch: 'A'},
      {re: /[\xE0-\xE6]/g, ch: 'a'},
      {re: /[\xC8-\xCB]/g, ch: 'E'},
      {re: /[\xE8-\xEB]/g, ch: 'e'},
      {re: /[\xCC-\xCF]/g, ch: 'I'},
      {re: /[\xEC-\xEF]/g, ch: 'i'},
      {re: /[\xD2-\xD6]/g, ch: 'O'},
      {re: /[\xF2-\xF6]/g, ch: 'o'},
      {re: /[\xD9-\xDC]/g, ch: 'U'},
      {re: /[\xF9-\xFC]/g, ch: 'u'},
      {re: /[\xC7-\xE7]/g, ch: 'c'},
      {re: /[\xD1]/g, ch: 'N'},
      {re: /[\xF1]/g, ch: 'n'}
    ]
    rExps.forEach((element) => {
      text = text ? text.replace(element.re, element.ch) : ''
    })
    return text
  },
  /**
   * Search string in object
   *
   * @param {string} terms - Terminos de busqueda
   * @param {array} obj - Objecto en donde se va a buscar (arreglo de objetos)
   * @param {array} path - Mapa de la llave del objeto
   */
  search (terms, obj, path) {
    const query = this.normalizeToBase(_.get(obj, path, '').toLowerCase())
    return query.indexOf(this.normalizeToBase(terms.toLowerCase())) > -1
  },
  validDomains (domains) {
    let valid_domains = domains;
    let aux = [];
    if(!_.isEmpty(valid_domains)) {
      valid_domains.forEach(object => {
        if( typeof object !== 'object') {
          object = JSON.parse(object);
        }
        aux.push(object);
      });
    }
    return aux;
  },
  getAsyncValidatorConfig (field_name, db_field_name = null) {
    return {
      getMessage: (field, params, data) => {
        return _.get(data, ['message'], '')
      },
      validate: value => {
        db_field_name = (db_field_name !== null) ? db_field_name : field_name;
        return axios({
          method: 'post',
          url: '/utilities/validate-field',
          params: {
            [db_field_name]: value
          }
        }).then(response => {
          return {
            valid: true
          }
        }).catch(error => {
          return {
            valid: false,
            data: {
              message: _.get(error, ['response', 'data', 'errors', db_field_name, '0'], '')
            }
          }
        });
      }
    }
  },
  genderList () {
    return [
      {id: 'M', name: 'Masculino'},
      {id: 'F', name: 'Femenino'}
    ]
  },
  activatedList () {
    return [
      {label: 'Activo', value: 1},
      {label: 'Suspendido', value: 3},
      {label: 'Eliminado', value: 2},
      {label: 'Sin confirmar', value: 0},
      {label: 'Estableciendo contraseña', value: 4},
      {label: 'Contraseña establecida', value: 5}
    ]
  },
  getPhoneRegex () {
    return /^((\\+[0-9]{1,3})(-|)|([0-9]{1,3})(-|)|)([0-9]{3,4})(-|)([0-9]{2,3})(-|)([0-9]{2,4})(-|)(([0-9]{2}))$/
  },
  getEmailRegex () {
    return /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
  },
  getOnlyNumberPhoneRegex ()
  {
    return /^([0-9]){8,10}$/
  },
  isPhone (phone) {
    let pattern = this.getOnlyNumberPhoneRegex();
    return pattern.test(phone);
  },
  /**
   * Loader
   * @url https://element.eleme.io/#/es/component/loading
   * @param options
   * @returns {ElLoadingComponent}
   */
  getLoader (options) {
    return Loading.service({
      fullscreen: _.get(options, ['fullscreen'], true),
      target: _.get(options, ['target'], undefined),
      text: _.get(options, ['text'], 'Cargando'),
      background: _.get(options, ['background'], 'rgba(255, 255, 255, 0.8)'),
      spinner: _.get(options, ['spinner'], 'el-icon-loading')
    })
  },
  isMobile () {
    return {
      Android () {
        return navigator.userAgent.match(/Android/i);
      },
      BlackBerry () {
        return navigator.userAgent.match(/BlackBerry/i);
      },
      iOS () {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
      },
      Opera () {
        return navigator.userAgent.match(/Opera Mini/i);
      },
      Windows () {
        return navigator.userAgent.match(/IEMobile/i);
      },
      iPhone () {
        return navigator.userAgent.match(/iPhone|iPod/i);
      },
      any () {
        return (this.Android() || this.BlackBerry() || this.iOS() || this.Opera() || this.Windows());
      },
      phone () {
        return (this.Android() || this.BlackBerry() || this.iPhone() || this.Opera() || this.Windows());
      }
    }
  },
  getCompanyURL(account_name=null, environment='prod') {
    let url = 'apithy.com';

    if (environment !== 'prod')
      url = environment + '.' + url;

    if (!_.isEmpty(account_name))
      url = account_name + '.' + url;

    return url;
  },
  getURLParams() {
    let params = {};
    let query = _.replace(window.location.search, '?', '');
    if(!_.isEmpty(query)) {
      let paramsArray = _.split(query, '&');
      _.each(paramsArray, queryParam => {
        let param = _.split(queryParam, '=');
        params[param[0]] = param[1];
      });
    }
    return params;
  },
  parse (time, type = 'date', time_scale = 'minutes', _this = {}) {
    switch (type) {
      case 'score':
        let score = 0;
        if (time !== null)
          score = Math.round(time * 100);
        return _.head(score.toString().match(/^-?\d+(?:\.\d{0,2})?/));
      case 'progress':
        let progress = (time !== null) ? parseFloat(time) : 0;
        return _.head(progress.toString().match(/^-?\d+(?:\.\d{0,2})?/));
      case 'duration':
        return (time !== null || time > 0) ? time + ' ' + _this.$t('messages.'+time_scale) : 'No disponible';
      case 'date':
        return (time !== null) ? moment(time).format('DD-MM-YYYY') : 'No disponible';
      case 'datetime':
        return (time !== null) ? moment(time).format('DD-MM-YYYY HH:mm:ss') : 'No disponible';
      default:
        return time
    }
  },
  extractEmailDomain(email) {
    let aux = _.split(email, '@')
    return _.get(aux, [1], null)
  },
  /**
   * Return the same object without the reference to original object
   **/
  clone (object) {
    return JSON.parse(JSON.stringify(object))
  }
}

export { index }
