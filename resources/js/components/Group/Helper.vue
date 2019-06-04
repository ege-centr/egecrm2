<template>
  <div>
    <v-btn icon flat color='primary' @click='dialog = true'>
      <v-icon>
        assessment
      </v-icon>
    </v-btn>
    <v-layout row justify-center>
      <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay>
        <v-card>
          <v-toolbar dark color="primary">
            <v-toolbar-title>Помощник</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
              <v-btn icon dark @click.native="dialog = false">
                <v-icon>close</v-icon>
              </v-btn>
            </v-toolbar-items>
          </v-toolbar>
          <v-card-text class='px-0'>
            <v-container grid-list-xl class="pa-0 ma-0 relative" fluid>
              <v-layout wrap class='mb-4 px-3'>
                <v-flex md12 class='pb-0'>
                  <div class='flex-items align-center'>
                    <AllFilter :items='filters' :disable-pin='true' @updated='(filters) => selectedFilters = filters' />
                    <v-spacer></v-spacer>
                    <v-chip v-for="(label, id) in mode" class='pointer ml-0 mr-3'
                      :class="{'primary white--text': id === selectedMode}"
                      @click='selectedMode = id'
                      :key='id'
                    >
                      {{ label }}
                    </v-chip>
                  </div>
                </v-flex>
                <v-flex md12 class='relative'>
                  <Loader v-if='loading' class='loader-wrapper_fullscreen-dialog' />
                  <div v-if='!loading && data !== null'>
                    <v-data-table
                      hide-headers
                      hide-actions
                      :items='dates'
                    >
                      <template v-slot:items="{ item, index }">
                        <tr v-if='index === 0'>
                          <td width='150'>

                          </td>
                          <td v-for='i in [0, 1, 2, 3]' 
                            :key='i' 
                            :width="i === 3 ? 'auto' : 200"
                            :class="{
                              'grey--text': i !== 0
                            }"
                          >
                            {{ getData('years', getData('academic_year') - i).title }}
                          </td>
                        </tr>
                        <tr>
                          <td>
                            {{ formatDate(item) }}
                          </td>
                          <td v-for='i in [0, 1, 2, 3]' 
                            :key='i'
                            :class="{
                              'grey--text': i !== 0
                            }"
                          >
                            {{ getSum(item, i) | hideZero }}
                          </td>
                        </tr>
                      </template>
                    </v-data-table>
                  </div>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card-text>
        </v-card>
      </v-dialog>
    </v-layout>
  </div>
</template>


<script>

import { API_URL } from './'
import { AllFilter } from '@/components/Filter'

export default {
  components: { AllFilter },
  
  data() {
    return {
      dialog: false,
      loading: false,
      filters: [
        {label: 'Предмет', field: 'subject_id', type: 'select', options: this.$store.state.data.subjects, textField: 'name'},
        {label: 'Класс', field: 'grade_id', type: 'select', options: this.$store.state.data.grades},
      ],
      mode: {
        day: 'дни',
        week: 'недели',
        month: 'месяцы',
      },
      selectedMode: 'day',
      selectedFilters: {},
      data: null
    }
  },
  
  methods: {
    loadData() {
      this.loading = true
      axios.post(apiUrl(API_URL, 'helper'), {
        ...this.selectedFilters,
        mode: this.selectedMode,
      }).then(r => {
        this.data = r.data
        this.loading = false
      })
    },

    formatDate(date) {
      let format
      if (this.selectedMode === 'month') {
        format = 'MMMM'
      } else {
        format = 'D MMMM'
      }
      return moment(date).format(format)
    },

    getSum(date, index) {
      const d = moment(date).subtract(index, 'years').format('YYYY-MM-DD')
      const yearData = this.data[this.getData('academic_year') - index]
      switch(this.selectedMode) {
        case 'month': 
          return _.chain(yearData)
            .filter(e => moment(e.date).format('M') === moment(d).format('M'))
            .sumBy('sum')
        case 'week':
          const monthDateFormat = 'MM-DD'
          const dateStart = moment(date).subtract(1, 'week').endOf('week').format(monthDateFormat)
          return _.chain(yearData)
            .filter(e => moment(e.date).format(monthDateFormat) <= moment(date).format(monthDateFormat) && moment(e.date).format(monthDateFormat) > dateStart)
            .sumBy('sum')
        default: 
          return _.get(yearData.find(e => e.date === d), 'sum')
      }
    },
  },

  watch: {
    selectedFilters() {
      if (Object.keys(this.selectedFilters).length >= 2) {
        this.loadData()
      } else {
        this.data = null
      }
    }
  },

  computed: {
    dates() {
      const now = moment()
      const result = []
      let times
      switch(this.selectedMode) {
        case 'week':
          times = 14
          break
        case 'month':
          times = 4
          break
        default:
          times = 90
      }

      _.times(times, () => {
        result.push(now.format('YYYY-MM-DD'))
        now.subtract(1, this.selectedMode).endOf(this.selectedMode)
      })
     
      return result
    }
  },
}
</script>
