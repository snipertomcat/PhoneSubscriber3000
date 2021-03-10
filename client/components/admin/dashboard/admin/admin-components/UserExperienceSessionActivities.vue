<template>
  <div>
    <div class="d-inline-flex mt-5 full-width">
      <div class="">
        <span class="has-text-weight-bold font-18">
          Actividades del usuario
        </span>
      </div>
    </div>
    <div class="d-inline-flex mt-3 full-width">
      <div class="" style="width:480px">
        <span>Reto</span>
      </div>
      <div class="" style="width:180px">
        <span>Fecha de inicio</span>
      </div>
      <div class="" style="width:180px">
        <span>Fecha de fin</span>
      </div>
      <div class="" style="width:180px">
        <span>Rendimiento</span>
      </div>
    </div>
    <!-- Sessions -->
    <div class="mt-3 full-width" v-for="(session, index) in sessions" :key="index">
      <div class="card full-width">
        <div class="card-content p-2 d-inline-flex align-self-center session width-100"
             :class="{'pointer': (hasActivities(session.childs)||hasActivities(session.activities))}"
             @click="toggleDetails(session)">
          <div class="has-text-link toggle-details" style="width: 50px">
            <template v-if="(hasActivities(session.childs)||hasActivities(session.activities))">
              <i class="fa"
                 :class="{ 'fa-angle-down': !session.show_details, 'fa-angle-up': session.show_details}">
              </i>
            </template>
          </div>
          <div class="" style="width: 430px">
            <span class="has-text-weight-bold">
              {{ session.title }}
            </span>
          </div>
          <div class="" style="width: 180px">
            <template v-if="hasDate(session.started_at)">
              <span class="has-text-weight-bold">
                {{ parse(session.started_at, 'datetime') }}
              </span>
            </template>
            <template v-else>
              <span class="text-gray-default">No disponible</span>
            </template>
          </div>
          <div class="" style="width: 180px">
            <template v-if="hasDate(session.finished_at)">
              <span class="has-text-weight-bold">
                {{ parse(session.finished_at, 'datetime') }}
              </span>
            </template>
            <template v-else>
              <span class="text-gray-default">No disponible</span>
            </template>
          </div>
          <div class="" style="width: 15%">
            <template v-if="hasActivities(session.activities) || hasChildrenActivities(session)">
              <span class="has-text-weight-bold"
                    :class="getClass(session.score, (hasActivities(session.activities) || hasChildrenActivities(session)))">
                {{ parse(session.score, 'score') }}
              </span>
            </template>
            <template v-else>
              <span class="text-gray-default">-</span>
            </template>
          </div>
        </div>
      </div>
      <!-- Subsessions -->
      <template v-if="session.type === 'theme'">
          <div class="mt-3 mb-4 sch-details" v-if="session.show_details && hasActivities(session.childs)">
              <template v-for="(sch, sch_index) in session.childs">
                  <div class="card mb-3">
                      <div class="card-content p-2 d-inline-flex align-self-center sch width-100"
                           :class="{'pointer': hasActivities(sch.activities)}"
                           @click="toggleDetails(sch)">
                          <div class="has-text-link toggle-details" style="width: 50px">
                              <template v-if="hasActivities(sch.activities)">
                                  <i class="fa" :class="{ 'fa-angle-down': !sch.show_details, 'fa-angle-up': sch.show_details}"></i>
                              </template>
                          </div>
                          <div class="" style="width: 430px">
                              <span class="has-text-weight-bold">{{ sch.title }}</span>
                          </div>

                          <div class="" style="width: 180px">
                              <template v-if="hasDate(sch.started_at)">
                                  <span class="has-text-weight-bold">
                                    {{ parse(sch.started_at, 'datetime') }}
                                  </span>
                              </template>
                              <template v-else>
                                  <span class="text-gray-default">No disponible</span>
                              </template>
                          </div>
                          <div class="" style="width: 180px">
                              <template v-if="hasDate(sch.finished_at)">
                                  <span class="has-text-weight-bold">
                                    {{ parse(sch.finished_at, 'datetime') }}
                                  </span>
                              </template>
                              <template v-else>
                                  <span class="text-gray-default">No disponible</span>
                              </template>
                          </div>
                      </div>
                  </div>
                <!-- Evaluation/Activities -->
                  <div class="mt-3 mb-4 session-details" v-if="sch.show_details && hasActivities(sch.activities)">
                      <div class="card">
                          <div class="card-content p-3">
                              <b-table :data="sch.activities" striped mobile-cards>
                                  <template slot-scope="props">
                                      <b-table-column field="title" label="Actividad">
                                          <span>{{ props.row.title }}</span>
                                      </b-table-column>
                                      <b-table-column field="started_at" label="Fecha de inicio">
                                          <template v-if="hasDate(props.row.started_at)">
                                              <span >{{ parse(props.row.started_at, 'datetime') }}</span>
                                          </template>
                                          <template v-else>
                                              <span class="text-gray-default">No disponible</span>
                                          </template>
                                      </b-table-column>
                                      <b-table-column field="finished_at" label="Fecha de fin">
                                          <template v-if="hasDate(props.row.finished_at)">
                                              <span >{{ parse(props.row.finished_at, 'datetime') }}</span>
                                          </template>
                                          <template v-else>
                                              <span class="text-gray-default">No disponible</span>
                                          </template>
                                      </b-table-column>
                                      <b-table-column field="score" label="Rendimiento" centered>
                                  <span :class="getClass(props.row.score, true)">
                                      {{ parse(props.row.score,'score') }}
                                  </span>
                                      </b-table-column>
                                  </template>
                              </b-table>
                          </div>
                      </div>
                  </div>
              </template>
          </div>
      </template>
      <template v-else>
          <div class="mt-3 mb-4 session-details" v-if="session.show_details && hasActivities(session.activities)">
              <div class="card">
                  <div class="card-content p-3">
                      <b-table :data="session.activities" striped mobile-cards>
                          <template slot-scope="props">
                              <b-table-column field="title" label="Actividad">
                                  <span>{{ props.row.title }}</span>
                              </b-table-column>
                              <b-table-column field="started_at" label="Fecha de inicio">
                                  <template v-if="hasDate(props.row.started_at)">
                                      <span >{{ parse(props.row.started_at, 'datetime') }}</span>
                                  </template>
                                  <template v-else>
                                      <span class="text-gray-default">No disponible</span>
                                  </template>
                              </b-table-column>
                              <b-table-column field="finished_at" label="Fecha de fin">
                                  <template v-if="hasDate(props.row.finished_at)">
                                      <span >{{ parse(props.row.finished_at, 'datetime') }}</span>
                                  </template>
                                  <template v-else>
                                      <span class="text-gray-default">No disponible</span>
                                  </template>
                              </b-table-column>
                              <b-table-column field="score" label="Rendimiento" centered>
                                  <span :class="getClass(props.row.score, true)">
                                      {{ parse(props.row.score,'score') }}
                                  </span>
                              </b-table-column>
                          </template>
                      </b-table>
                  </div>
              </div>
          </div>
      </template>
    </div>
  </div>
</template>

<script>
import { AdminDashboardService } from '../../../../../services/dashboard/AdminDashboardService'
import { index } from '../../../../../helpers'
import _ from 'lodash'

const CONTAINER = 'theme';
export default {
  name: 'UserExperienceSessionActivities',
  beforeMount () {
    this.getUserExperienceSessionActivities()
  },
  methods: {
    getActivity(session_index, evaluation_id, prop) {
        let activities_array = _.get(this.sessions, [session_index, 'activities'], [])
        if (!_.isEmpty(activities_array)) {
            let activity = _.find(activities_array, {id: evaluation_id})
            return _.get(activity, [prop], null);
        }
    },
    getUserExperienceSessionActivities () {
      let form = {
        'user_id': this.userId,
        'experience_id': this.experienceId
      }
      AdminDashboardService.getUserExperienceSessionActivities(form)
          .then(response => {
            let sessions = [];
            let data = _.get(response, ['data', 'data'], []);
            _.each(data, item => {
              item.json_data = JSON.parse(item.json_data);
              item.type = _.get(item, ['json_data', 'type'], null);
              if (!_.isNull(item.type) && !_.isEmpty(item.type)) {
                item.show_details = false;
                if (item.type === CONTAINER) {
                  item.childs = _.each(item.childs, sch => {
                    sch.show_details = false;
                  })
                }
                sessions.push(item)
              }
            });
            this.sessions = sessions
          })
    },
    hasActivities (activities) {
      return !_.isEmpty(activities)
    },
    hasChildrenActivities (session) {
      let hasChildrenActivities = false;
      if (_.isEmpty(session.childs)) return hasChildrenActivities;
      _.each(session.childs, child => {
        if (!_.isEmpty(child.activities)) {
          hasChildrenActivities = true;
        }
      });
      return hasChildrenActivities;
    },
    toggleDetails (session) {
      session.show_details = !session.show_details
    },
    hasDate (date) {
      return !_.isEmpty(date)
    },
    parse (time, type = 'date', time_scale = 'minutes') {
      return index.parse(time, type, time_scale, this)
    },
    getClass (score, isEvaluation = true) {
      if (!isEvaluation) {
        return 'text-gray-default'
      }
      score = score * 100
      switch (true) {
        case (score >= 90):
          return 'has-text-ari-green';
        case (score >= 70 && score < 90):
          return 'has-text-ari-yellow';
        case (score < 70):
          return 'has-text-ari-red';
        default:
          return ''
      }
    }
  },
  props: {
    experienceId: {
      required: true,
      type: Number
    },
    userId: {
      required: true,
      type: Number
    }
  },
  data () {
    return {
      sessions: []
    }
  }
}
</script>

<style scoped>
@import "../admin-css/admin-dashboard-css.css";
</style>
