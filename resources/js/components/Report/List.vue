<template>
  <div>
    <Dialog ref='Dialog' @updated="$emit('updated')" />
    <data-table :items='items'>
      <tr slot-scope="{ item }">
        <td v-if='show.id'>
          <span v-if='item.report === null' class='grey--text'>
            не создан
          </span>
          <span v-else>
            {{ item.report.id }}
          </span>
        </td>
        <td v-if='show.teacher'>
          <router-link 
            v-if='item.teacher_id > 0'
            :to="{name: 'TeacherShow', params: {id: item.teacher_id}}">
            {{ getData('teachers', item.teacher_id).default_name }}
          </router-link>
        </td>
        <td v-if='show.client'>
          <router-link 
            v-if='item.client !== null'
            :to="{name: 'ClientShow', params: {id: item.client.id}}">
            {{ item.client.default_name }}
          </router-link>
        </td>
         <td>
          <SubjectGrade :item='item' />
        </td>
        <td>
          {{ item.lesson_count }} занятий
        </td>
        <td v-if='show.dimension'>
          <router-link 
            v-if='item.client !== null'
            :to="{name: 'ReportTeacherDimension', params: {
              year: item.year,
              subject_id: item.subject_id,
              client_id: item.client.id,
              teacher_id: item.teacher_id,
            }}">
            <PersonName :item='item.client' />
          </router-link>
        </td>
        <td>
          <div v-if='item.report !== null'>
            {{ item.report.date | date }}
          </div>
        </td>
        <td v-if='show.price'>
          <span v-if='item.price > 0'>{{ item.price }} руб.</span>
        </td>
        <td>
          <ReportScoreCircles :item='item.report' />
        </td>
        <td v-if='show.is_available_for_parents'>
          <v-icon color='red' v-if='item.is_not_moderated'>remove_circle</v-icon>
          <v-icon color='green' v-if='item.is_available_for_parents'>done_all</v-icon>
          <v-icon color='primary' v-if='!item.is_available_for_parents && !item.is_not_moderated'>schedule</v-icon>
        </td>
        <td class='text-md-right pa-0' width='100' v-if='show.actions'>
          <v-btn small color='primary' class='btn-td' flat
            @click='$refs.Dialog.open(null, getDefaultData(item))'
            v-if='item.report === null'
          >
            добавить
          </v-btn>
          <v-btn color='black' flat icon
            @click='$refs.Dialog.open(item.report.id, getDefaultData(item))'
            v-if='item.report !== null'
          >
              <v-icon>more_horiz</v-icon>
          </v-btn>
        </td>
        <td class='text-md-right pa-0' width='100' v-else>
            <v-btn small color='primary' class='btn-td' flat v-if='item.report !== null'
              @click='$refs.Dialog.open(item.report.id, getDefaultData(item), {readOnly: true})'
            >
              просмотр
            </v-btn>
        </td>
      </tr>
    </data-table>
  </div>
</template>

<script>
import ReportScoreCircles from '@/components/Report/ScoreCircles'
import Dialog from '@/components/Report/Dialog'
import DisplayOptions from '@/mixins/DisplayOptions'

export default {
  props: {
    items: {
      type: Array,
      required: true
    },
  },

  mixins: [ DisplayOptions ],

  components: { Dialog, ReportScoreCircles },

  data() {
    return {
      defaultDisplayOptions: {
        id: true,
        is_available_for_parents: true,
        price: true,
        teacher: true,
        client: true,
        actions: true,
        dimension: false,
      }
    }
  },

  methods: {
    getDefaultData(item) {
      return _.pick(item, [
        'year', 'subject_id', 'client_id', 'teacher_id', 
        'report_date', 'report_id', 'lesson_date' // это параметры для получения 
      ])
    }
  }
}
</script>
