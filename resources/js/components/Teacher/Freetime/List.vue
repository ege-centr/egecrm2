<template>
  <div>
    <Dialog ref='Dialog' @updated='loadData' />
    <Loader transparent v-if='loading' />
    <div v-else>
      <div v-if='items.length > 0'>
        <div class='text-sm-right mb-3'>
          <AddBtn @click.native='add' />
        </div>
        <v-data-table
          hide-actions
          hide-headers
          :items='items'
          :class='config.elevationClass'
        >
          <template v-slot:items="{ item, index }">
            <tr v-if='index === 0'>
              <td colspan='2'>
                <TimelineWeek :items='items' />
              </td>
            </tr>
            <tr>
              <td>
                {{ DAY_LABELS[item.weekday] }}
                <span v-if='item.from'>с {{ item.from }}</span>
                <span v-if='item.to'>до {{ item.to }}</span>
              </td>
              <td class='text-sm-right'>
                <v-btn @click='$refs.Dialog.open(item.id)' flat icon color="black" class='ma-0'>
                  <v-icon>more_horiz</v-icon>
                </v-btn>
              </td>
            </tr>
          </template>
        </v-data-table>
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
