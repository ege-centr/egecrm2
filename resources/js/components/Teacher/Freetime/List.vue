<template>
  <div>
    <Dialog ref='Dialog' :teacher-id='teacherId' @updated='loadData' />
    <Loader transparent v-if='loading' />
    <div v-else>
      <div>
        <v-card :class='config.elevationClass'>
          <v-card-text>
            <div class='flex-items align-center'>
              <TimelineWeek :items='items' :show-weekday='true' />
              <v-btn flat icon color="black" class='ma-0' @click.native='add'>
                <v-icon>more_horiz</v-icon>
              </v-btn>
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
