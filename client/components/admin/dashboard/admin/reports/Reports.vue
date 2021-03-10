<template>
  <div class="mx-auto container">
    <div class="row mt-3">
      <div class="select-content col-12 col-lg-4">
        <div class="select-label text-bold"><strong>Seleccionar categoria</strong></div>
        <div class="select-input">
          <el-select
              v-model="selects.category"
              @change="reloadTags"
              placeholder="Selecciona una categoría">
            <el-option
                v-for="(category, index) in categories"
                :key="index"
                :label="category.name"
                :value="category.value">
            </el-option>
          </el-select>
        </div>
      </div>
      <div class="select-content col-12 col-lg-4">
        <div class="select-label text-bold"><strong>Seleccionar etiqueta</strong></div>
        <div class="select-input">
          <el-select
              v-model="selects.tag"
              @change="reloadData"
              @remove-tag="reloadData"
              multiple
              collapse-tags
              ref="select_tags"
              placeholder="Selecciona una etiqueta">
            <el-option
                v-for="(tag, index) in tags"
                :key="tag.id"
                :label="tag.title"
                :value="tag.id">
            </el-option>
          </el-select>
        </div>
      </div>
      <div class="select-content col-12 col-lg-4">
        <div class="select-label text-bold"><strong>Período</strong></div>
        <div class="select-input">
          <el-datepicker unlink-panels
                         type="daterange"
                         align="right"
                         start-placeholder="Desde"
                         end-placeholder="Hasta"
                         format="dd-MM-yy"
                         value-format="yyyy-MM-dd"
                         v-model="date_picker.value"
                         :picker-options="date_picker.options"
                         @change="reloadData"
          ></el-datepicker>
        </div>
      </div>
    </div>
    <div class="report" id="report">
      <div class="row ml-0 mr-0 mb-4 justify-content-center">
        <div class="col-lg-4">
          <div class="compliance-percent">
            <div class="col-12">
              <div class="percent">
                {{ `${courses.total_users.value}` }}
              </div>
            </div>
            <div class="col-12">
              <div class="label">
                {{ `Alumnos inscritos` }}
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row mr-0 ml-0 mb-5">
        <div class="col-lg-8">
          <div class="graph">
            <template v-for="(item, index) in courses">
              <template v-if="index !== 'total_users'">
                <apithy-bar :item-data="item" :index="index"></apithy-bar>
              </template>
            </template>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="compliance-percent">
            <div class="col-12">
              <div class="percent">
                {{ `${cutDecimals(convertion.value, 1)}%` }}
              </div>
            </div>
            <div class="col-12">
              <div class="label">
                {{ `Porcentaje de cumplimiento` }}
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row mr-0 ml-0 pt-5 border-t-1" v-show="show_charts">
        <div class="col-8">
          <div class="title">
            <strong>Comparativos</strong>
          </div>
          <div class="">
            <strong>Top de Cumplimiento</strong>
          </div>
        </div>
        <div class="col-4">
          <div class="chart-legend">
            <div class="chart-legend-content">
              <template v-for="(legend, index) in legends_list">
                <div class="chart-legend-item" :key="index">
                  <div class="row mr-0 ml-0">
                    <div class="col-4">
                      <div class="legend-color-reference" :style="`background-color: ${legend.color};`"></div>
                    </div>
                    <div class="col-8 align-self-center">
                      <div class="legend-text-reference">
                        {{ `${legend.title}` }}
                      </div>
                    </div>
                  </div>
                </div>
              </template>
            </div>
          </div>
        </div>
      </div>
      <div class="row mr-0 ml-0" v-show="show_charts">
        <div class="col-lg-12">
          <div class="chart">
            <div class="row mr-0 ml-0 justify-content-center">
              <div class="col-lg-10">
                <div class="">
                  <strong>Comparativo cumplimiento</strong>
                </div>
              </div>
              <div class="col-lg-10 mt-4">
                <bars :chart-data="convertion.chart_data"
                      :chart-options="charts_options.bars"
                ></bars>
              </div>
              <div class="col-lg-10">
                <div class="text-center mt-5">
                  {{ `${cutDecimals(convertion.value, 1)}% Porcentaje de cumplimiento` }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row mr-0 ml-0" v-show="show_charts">
        <div class="col-lg-12">
          <div class="row mr-0 ml-0">
            <div class="col-lg-4">
              <div class="chart">
                <div class="row ml-0 mr-0 justify-content-center">
                  <div class="col-lg-12"><strong>Cursos finalizados</strong></div>
                  <div class="col-lg-12"></div>
                  <div class="col-lg-12 mt-4">
                    <pie :chart-data="courses.finished.chart_data"
                         :chart-options="charts_options.pie"
                    ></pie>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="chart">
                <div class="row ml-0 mr-0 justify-content-center">
                  <div class="col-lg-12"><strong>Cursos asignados</strong></div>
                  <div class="col-lg-12"></div>
                  <div class="col-lg-12 mt-4">
                    <pie :chart-data="courses.asigned.chart_data"
                         :chart-options="charts_options.pie"
                    ></pie>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="chart">
                <div class="row ml-0 mr-0 justify-content-center">
                  <div class="col-lg-12"><strong>Alumnos inscritos</strong></div>
                  <div class="col-lg-12"></div>
                  <div class="col-lg-12 mt-4">
                    <pie :chart-data="courses.total_users.chart_data"
                         :chart-options="charts_options.pie"
                    ></pie>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row col-12 justify-content-center mb-3">
      <div class="col-lg-2">
        <button class="button is-link has-text-weight-bold" @click="downloadExcel">Descargar XLS</button>
      </div>
      <div class="col-lg-2">
        <button class="button is-link has-text-weight-bold" @click="downloadPdf">Descargar PDF</button>
      </div>
    </div>
  </div>
</template>

<script>
  import {DatePicker, Select, Option} from "element-ui";
  import {bus} from "../../../../../app_platform";
  import jsPDF from 'jspdf';
  import html2canvas from 'html2canvas';
  import { index } from "../../../../../helpers";



  export default {
    name: "Reports",
    components: {
      'apithy-bar': () => import('../ApithyBar.vue'),
      'bars': () => import('../../BarsGraph.vue'),
      'pie': () => import('../../PieGraph.vue'),
      'el-datepicker': DatePicker,
      'el-select': Select,
      'el-option': Option,
    },
    props: {
      tags: {
        required: true,
        type: Array
      },
      user: {
        required: true,
        type: Object
      }
    },
    beforeMount: function () {
      bus.$on('item-active', item => {
        this.item_active = item.target
      })

      this.tags.push({
        'id':'all',
        'title':'Todas',
        'value':'all'
      });

      this.tags.reverse();
    },
    methods: {
      downloadPdf2 (report) {
        //let report = await this.prepareReport()
        let pdf = new jsPDF('portrait', 'pt');
        let reportPage = {
          width: (report.width > pdf.internal.pageSize.getWidth()) ? pdf.internal.pageSize.getWidth() : report.width,
          height: (report.height > pdf.internal.pageSize.getHeight()) ? pdf.internal.pageSize.getHeight() : 250
        };

        pdf.addImage(report, 'PNG', 0, 0, reportPage.width, reportPage.height);
        pdf.setFontSize(8);

        let img = new Image();
        img.src=this.user.company[0].full_path_logo;
        pdf.addImage(img, 'PNG', pdf.internal.pageSize.getWidth()-100, 10, 70, 70);

        let date=new Date();
        let current_date=date.getFullYear()+'-'+
          (((date.getMonth()+1) < 10) ? ('0'+(date.getMonth()+1)) : (date.getMonth()+1)) +'-'+
          (((date.getDate()+1) < 10) ? ('0'+(date.getDate())) : date.getDate());

        if (_.get(this.date_picker, ['value', 0], null)) {
          pdf.text(15, 30, 'Periodo reportado: '+ _.get(this.date_picker, ['value', 0], null) + ' al  '+ _.get(this.date_picker, ['value', 1], null));
          pdf.text(15, 40, 'Descargado el: '+ current_date);
        }else{
          pdf.text(15, 30, 'Descargado el: '+ current_date);
        }
        console.log('ready')
        pdf.save('report.pdf');
        this.loader.close();
      },
      prepareReport () {
        console.log('preparing...')
        let reportPage = {
          el: document.getElementById('report'),
          width: document.getElementById('report').clientWidth,
          height: document.getElementById('report').clientHeight,
          options: {
            x: 0,
            y: 0,
            width: document.getElementById('report').clientWidth,
            height: document.getElementById('report').clientHeight,
            scrollY: -300,
            scrollX: -document.getElementById('report').offsetParent.offsetLeft,
          }
        };

        //return html2canvas(reportPage.el, reportPage.options)
        html2canvas(reportPage.el, reportPage.options).then(canvas => {
          console.log('rendering...')
          this.downloadPdf2(canvas)
        })
      },
      async downloadPdf () {
        //let loader = index.getLoader();
        this.loader = await index.getLoader();
        //this.downloadPdf2();
        setTimeout(() => {
          this.prepareReport()
        }, 200)
      },
      downloadExcel () {
        let url = '/dashboard/accomplishment-excel';
        let params = null;
        let queryString = "";

        if (_.isNull(params)) {
          params = {
            tags: this.selects.tag,
            period: null
          }

          if (!_.isEmpty(params.tags)) {
            queryString = '&tags[]=' + queryString + params.tags.join('&tags[]=');
          }

          if (!_.isNull(this.date_picker) && _.size(this.date_picker) > 0) {
            let started_at = _.get(this.date_picker, ['value', 0], null)
            let finished_at = _.get(this.date_picker, ['value', 1], null)

            if (started_at !== null && finished_at !== null) {
              params.period = {
                start_date: null,
                end_date: null
              }
              params.period.start_date = started_at;
              params.period.end_date = finished_at;

              queryString = queryString + '&period[start_date]=' + started_at + '&period[end_date]=' + finished_at;
            }

          }
        }

        console.log(queryString);
        url = url + '?format=xls' + queryString;
        window.open(url, '_blank');

      },
      reloadTags () {
        let params = {
          ajax: 1,
          page: -1
        }
        this.selects.tag=[];
        console.log(this.selects.category);

        axios({
          url: '/taxonomy/' + this.selects.category,
          method: 'GET',
          params: params
        })
          .then(response => {
            this.tags = response.data.data;
            if(!_.isEmpty(response.data.data)){
              this.tags.push({
                'id':'all',
                'title':'Todas',
                'value':'all'
              })
              this.tags.reverse();
            }
          })
          .catch(errors => {
          })
      },
      getDatePickerValue: function () {
        return this.date_picker.value
      },
      cutDecimals (number, decimals) {
        if (decimals === 0) {
          return parseInt(number)
        }
        return parseFloat(number).toFixed(decimals)
      },
      getPercent (total = 0, current = 0) {
        let percent = 0;
        if (total === 0 || current === 0) {
          return percent
        }
        //@todo remove toPrecition
        percent = parseFloat(current / total).toPrecision(3)
        return percent
      },
      cleanChartsDatas () {
        this.convertion.chart_data = {
          labels: [],
          datasets: []
        }
        _.forEach(this.courses, item => {
          item.chart_data = {
            labels: [],
            datasets: []
          }
        })
      },
      getTagColor (tag_id) {
        let tag_color = '#FFCECE'
        if (typeof tag_id === 'string') {
          tag_id = parseInt(tag_id)
        }
        let tag = _.find(this.tags, {id: tag_id})
        if (!_.isEmpty(tag) && !_.isNull(tag) && tag !== 'undefined') {
          tag_color = _.get(tag, ['color'], tag_color)
        }
        return tag_color;
      },
      fillCharts (data) {
        this.cleanChartsDatas()
        _.forEach(data, (item, index) => {
          _.forEach(item, (item1, index1) => {
            let tag_color = this.getTagColor(index);
            if (_.has(this.courses, index1)) {
              this.courses[index1].chart_data.labels.push(item.name)
              if (_.size(this.courses[index1].chart_data.datasets) < 1) {
                this.courses[index1].chart_data.datasets.push({
                  backgroundColor: [tag_color],
                  data: [item1]
                })
              } else {
                this.courses[index1].chart_data.datasets[0].backgroundColor.push(tag_color)
                this.courses[index1].chart_data.datasets[0].data.push(item1)
              }
            }
            if (index1 === 'convertion' || index1 === 'covertion') {
              this.convertion.chart_data.labels.push(item.name)
              if (_.size(this.convertion.chart_data.datasets) < 1) {
                this.convertion.chart_data.datasets.push({
                  label: item.name,
                  backgroundColor: [tag_color],
                  data: [item1]
                })
              } else {
                this.convertion.chart_data.datasets[0].backgroundColor.push(tag_color)
                this.convertion.chart_data.datasets[0].data.push(item1)
              }
            }
          })
        })
        bus.$emit('updateChart')
      },
      getRandomColor () {
        let letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) {
          color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
      },
      reloadData () {
        let url = route('dashboard.accomplishment.tags')

        console.log(this.selects.tag.indexOf("all"));

        if(this.selects.tag.indexOf("all") >= 0){
          this.selects.tag = [];
          for(let i=0; i < this.tags.length; i++) {
            if(this.tags[i].id !="all") {
              this.selects.tag.push(this.tags[i].id);
            }
          }
        }

        let params = {
          tags: this.selects.tag,
        }

        if (!_.isNull(this.date_picker) && _.size(this.date_picker) > 0) {
          let started_at = _.get(this.date_picker, ['value', 0], null);
          let finished_at = _.get(this.date_picker, ['value', 1], null);

          if (started_at !== null && finished_at !== null) {
            params.period = {
              start_date: null,
              end_date: null
            }
            params.period.start_date = started_at;
            params.period.end_date = finished_at;
          }

        }

        this.legends_list = []
        _.forEach(this.selects.tag, tag_id => {
          let tag = _.find(this.tags, {id: tag_id})
          this.legends_list.push(tag)
        })
        this.getAccomplishment()
          .then(response => {
            this.setAccomplishment(response.data);
          })
          .catch(errors => {
          })
        if (_.size(params.tags) > 0) {
          this.show_charts = true
          this.getAccomplishment(url, params)
            .then(response => {
              this.fillCharts(response.data);
            })
            .catch(errors => {
            })
        } else {
          this.cleanChartsDatas()
          bus.$emit('updateChart')
          this.show_charts = false
        }
      },
      getAccomplishment (url = route('dashboard.accomplishment'), params = null) {
        if (_.isNull(params)) {
          params = {
            tags: this.selects.tag,
          }

          if (!_.isNull(this.date_picker) && _.size(this.date_picker) > 0) {
            let started_at = _.get(this.date_picker, ['value', 0], null)
            let finished_at = _.get(this.date_picker, ['value', 1], null)

            if (started_at !== null && finished_at !== null) {
              params.period = {
                start_date: null,
                end_date: null
              }
              params.period.start_date = started_at;
              params.period.end_date = finished_at;
            }

          }
        }

        return axios({
          url: url,
          method: 'GET',
          params: params
        })
      },
      setAccomplishment (data = null) {
        if (_.isEmpty(data) || _.isNull(data)) {
          return 0;
        }
        let max_value = 0
        _.each(data, (item, index) => {
          if (item > max_value && index !== 'total_users' && index !== 'covertion') {
            max_value = item
          }
        })

        _.each(data, (item, index) => {
          if (_.has(this.courses, index)) {
            this.courses[index].value = item
            if (max_value === 0) {
              this.courses[index].width = 0
            } else {
              this.courses[index].width = this.getPercent(max_value, item)
            }
          }
          if (index === 'covertion') {
            this.convertion.value = item
          }
        })
      }
    },
    created () {
      this.getAccomplishment(route('dashboard.accomplishment'), false).then(response => {
        this.setAccomplishment(response.data);
      })
        .catch(errors => {
        })
    },
    data () {
      return {
        date_picker: {
          value1: '',
          value2: '',
          options: {
            disabledDate (date) {
              return date.getTime() > new Date().getTime()
            },
            shortcuts: [
              {
                text: 'Hoy',
                onClick (picker) {
                  const end = new Date();
                  const start = new Date();
                  start.setTime(start.getTime());
                  picker.$emit('pick', [start, end]);
                }
              },
              {
                text: 'Ayer',
                onClick (picker) {
                  const end = new Date();
                  const start = new Date();
                  start.setTime(start.getTime() - 3600 * 1000 * 24);
                  picker.$emit('pick', [start, end]);
                }
              },
              {
                text: 'Últimos 7 días',
                onClick (picker) {
                  const end = new Date();
                  const start = new Date();
                  start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
                  picker.$emit('pick', [start, end]);
                }
              },
              {
                text: 'Últimos 30 días',
                onClick (picker) {
                  const end = new Date();
                  const start = new Date();
                  start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
                  picker.$emit('pick', [start, end]);
                }
              },
            ]
          },
        },
        selects: {
          category: "all_list",
          tag: [],
          period: null
        },
        categories: [
          {
            name: 'Todas',
            value: 'all_list'
          },
          {
            name: 'Áreas',
            value: 'areas'
          },
          {
            name: 'Puestos',
            value: 'positions'
          },
          {
            name: 'Habilidades',
            value: 'abilities'
          },
          {
            name: 'Certificaciones',
            value: 'certifications'
          },
          {
            name: 'Personalizadas',
            value: 'custom'
          }
        ],
        convertion: {
          total: 0,
          value: 0,
          chart_data: {
            labels: [],
            datasets: []
          }
        },
        legends_list: [],
        courses: {
          total_users: {
            label: 'Alumnos inscritos',
            value: 0,
            width: 0,
            background_color: '#49B4FC',
            colored_label: true,
            chart_data: {
              labels: [],
              datasets: []
            }
          },
          asigned: {
            label: 'Cursos asignados',
            value: 0,
            width: 0,
            background_color: '#B7C5DB',
            colored_label: false,
            chart_data: {
              labels: [],
              datasets: []
            }
          },
          finished: {
            label: 'Cursos finalizados',
            value: 0,
            width: 0,
            background_color: '#FCB399',
            colored_label: false,
            chart_data: {
              labels: [],
              datasets: []
            }
          },
        },
        show_charts: false,
        charts_options: {
          bars: {
            legend: {
              display: false
            },
            scales: {
              xAxes: [{
                gridLines: {
                  display: false
                }
              }],
              yAxes: [{
                ticks: {
                  stepSize: 50,
                  suggestedMin: 0,
                  suggestedMax: 100,
                  callback: function (value, index, values) {
                    return `${value}%`
                  }
                }
              }]
            },
            tooltips: {
              bodyFontSize: 16,
              callbacks: {
                title: function (tooltipItem, data) {
                  return ''
                },
                label: function (tooltipItem, data) {
                  let value = parseFloat(tooltipItem.yLabel).toFixed(1)
                  return `${tooltipItem.xLabel}: ${value}%`
                }
              }
            }
          },
          pie: {
            legend: {
              display: false
            },
            tooltips: {
              bodyFontSize: 16,
              callbacks: {
                title: function (tooltipItem, data) {
                  return ''
                },
                label: function (tooltipItem, data) {
                  let value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index]
                  let label = data.labels[tooltipItem.index]
                  let total = 0
                  let percent = 0
                  _.forEach(data.datasets[tooltipItem.datasetIndex].data, item => {
                    total += item
                  })
                  percent = parseFloat((value / total) * 100).toFixed(1)
                  return `${label}: ${percent}%`
                }
              }
            },
            chartArea: {
              backgroundColor: 'rgba(251, 85, 85, 0.4)'
            }
          }
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
  .el-date-table td {
    text-align: center !important;
  }

  .el-range-editor.is-active {
    border-color: #FFA81E;
  }

  .el-date-table td.start-date span {
    background-color: #FFA81E;
  }

  .el-date-table td.end-date span {
    background-color: #FFA81E;
  }

  .el-date-table td.in-range div {
    background-color: #fff6e8;
  }

  .el-picker-panel__shortcut:hover {
    background-color: #fff6e8;
    color: #FFA81E;
  }

  .select-content {
    .select-label {
    }

    .select-input {
      select {
        width: 100%;
        max-width: 414px;
        height: 35px;
        border: 1px solid #BEBEBE;
        background-color: #FFFFFF;
        box-sizing: border-box;
        border-radius: 4px;
      }
    }
  }

  .report {
    /*display: flex;
    flex-flow: row wrap;
    justify-content: space-between;
    align-items: center;*/
    margin-top: 4rem;
    @media (max-device-width: 768px) and (max-device-height: 1024px) {
      margin-top: 1rem;
    }

    .border-t-1 {
      border-top: 1px solid #BEBEBE;
    }

    .chart-legend {
      min-height: 200px;
      padding: 2rem;
      background: #FFFFFF;
      //box-shadow: -4px 4px 12px rgba(68, 68, 68, 0.3);

      .chart-legend-item {
        &:not(:last-child) {
          margin-bottom: 0.5rem;
        }

        .legend-color-reference {
          width: 4rem;
          height: 2rem;
        }
      }
    }

    .compliance-percent {
      display: flex;
      flex-flow: row wrap;
      padding: 2rem;
      width: 100%;
      height: 233px;
      background: #FFFFFF;
      //box-shadow: -4px 4px 12px rgba(68, 68, 68, 0.3);

      .percent {
        width: 50%;
        margin-left: auto;
        margin-right: auto;
        font-weight: 500;
        font-size: 4.1rem;
        line-height: 5rem;
        text-align: center;
        color: #0098FF;
      }

      .label {
        width: 75%;
        margin-left: auto;
        margin-right: auto;
        font-weight: normal;
        font-size: 1.5rem;
        line-height: 1.83rem;
        text-align: center;
        color: #444444;
      }
    }

    .chart {
      margin-top: 2rem;
      margin-bottom: 2rem;
      padding: 2rem;
      background: #FFFFFF;
      //box-shadow: -4px 4px 12px rgba(68, 68, 68, 0.3);
    }
  }
</style>
