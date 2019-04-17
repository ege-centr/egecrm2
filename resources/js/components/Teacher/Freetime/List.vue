<template>
  <div>
    <Dialog ref='Dialog' :teacher-id='teacherId' @updated='loadData' />
    <Loader transparent v-if='loading' />
    <div v-else>
      <div v-if='items.length > 0'>
        <div class='text-sm-right mb-3'>
          <AddBtn @click.native='add' icon='edit' />
        </div>
        <v-card :class='config.elevationClass'>
          <v-card-text>
            <TimelineWeek :items='items' :show-weekday='true' />
          </v-card-text>
        </v-card>
      </div>
      <v-card v-else :class='config.elevationClass'>
        <v-card-text class='pa-0'>
          <NoData 
            :height='300'
            transparent
            text='У этого преподавателя нет данных о свободном времени'
            :add='add'
          />
        </v-card-text>
      </v-card>
    </div>
  </div>
</template>


<script>

import Dialog from  './Dialog'
import { API_URL } from './'
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
    }
  },
  

  created() {
    this.loadData()
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

    add() {
      this.$refs.Dialog.open(null, {teacher_id: this.teacherId})
    }
  },

}
</script>
