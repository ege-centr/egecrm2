<template>
  <div>
    <Dialog ref='Dialog' :teacher-id='teacherId' @updated='loadData' />
    <YearTabs :items='$store.state.data.years' :selected-year.sync='selectedYear' class='mb-3' />
    <Loader transparent v-if='loading || scheduleLoading' />
    <div v-else>
      <div>
        <v-card :class='config.elevationClass'>
          <v-card-text>
            <div class='flex-items align-center'>
              <TimelineWeek :items='items' :show-weekday='true' color='amber' />
              <v-btn flat icon color="black" class='ma-0' @click.native='add'>
                <v-icon>more_horiz</v-icon>
              </v-btn>
            </div>
            <div v-if='schedule !== null'>
              <TimelineWeek regular :items='schedule.regular' class='mt-3 mb-2' />
              <div v-for='(weekItems, index) in schedule.detailed' :key='index' class='mb-2'>
                <TimelineWeek 
                  current-class='red'
                  :items='weekItems' 
                  :show-date='true'
                />
              </div>
            </div>
          </v-card-text>
        </v-card>
      </div>
    </div>
  </div>
</template>


<script>

import Dialog from  './Dialog'
import { API_URL } from './'
import { API_URL as TIMELINE_API_URL } from '@/components/Timeline'
import { DAY_LABELS } from '@/components/Timeline'
import TimelineWeek from '@/components/Timeline/Week'

export default {
  props: {
    teacherId: {
      type: Number,
      requried: true,
    }
  },

  components: { TimelineWeek, Dialog },

  data() {
    return {
      DAY_LABELS,
      loading: false,
      items: null,
      scheduleLoading: false,
      schedule: null,
      selectedYear: this.$store.state.data.years.splice(-1)[0].id
    }
  },
  

  created() {
    this.loadData()
  },

  watch: {
    selectedYear(newVal) {
      if (newVal) {
        this.loadSchedule()
      }
    }
  },

  methods: {
    loadData() {
      this.loading = true
      const params = {
        teacher_id: this.teacherId
      }
      axios.get(apiUrl(API_URL), { params }).then(r => {
        this.items = r.data.data
        this.loading = false
      })
    },

    loadSchedule() {
      this.scheduleLoading = true
      axios.post(apiUrl(TIMELINE_API_URL, 'teacher'), {
        current: {teacher_id: this.teacherId},
        year: this.selectedYear,
      }).then(r => {
        this.schedule = r.data
        this.scheduleLoading = false
      }) 
    },

    add() {
      this.$refs.Dialog.open(null, {teacher_id: this.teacherId})
    }
  },

}
</script>
