<template>
  <div>
    <Dialog ref='Dialog' />
    <data-table :items='items'>
      <tr slot-scope="{ item }">
        <td v-if='show.id'>
          <span v-if='item.report === null' class='grey--text'>
            не создан
          </span>
          <span v-else>
            отчёт №{{ item.report.id }}
          </span>
        </td>
        <td v-if='show.teacher'>
          <router-link 
            v-if='item.teacher !== null'
            :to="{name: 'TeacherShow', params: {id: item.teacher.id}}">
            <PersonName :item='item.teacher' />
          </router-link>
        </td>
        <td v-if='show.client'>
          <router-link 
            v-if='item.client !== null'
            :to="{name: 'ClientShow', params: {id: item.client.id}}">
            <PersonName :item='item.client' />
          </router-link>
        </td>
        <td v-if='show.dimension'>
          <router-link 
            v-if='item.client !== null'
            :to="{name: 'ReportTeacherDimension', params: {
              year: item.year,
              subject_id: item.subject_id,
              client_id: item.client.id,
              teacher_id: item.teacher.id,
            }}">
            <PersonName :item='item.client' />
          </router-link>
        </td>
        <td>
          <SubjectGrade :item='item' />
        </td>
        <td>
          <ReportScoreCircles :item='item.report' />
        </td>
        <td>
          {{ item.lesson_count }} занятий
        </td>
        <td>
          <div v-if='item.report !== null'>
            {{ item.report.date | date }}
          </div>
        </td>
        <td v-if='show.is_available_for_parents'>
          <span class='red--text' v-if='item.report !== null && !item.report.is_available_for_parents'>
            не доступен в ЛК
          </span>
        </td>
        <td class='text-md-right pa-0' width='100' v-if='show.actions'>
          <v-btn small color='primary' class='btn-td' flat
            @click='$refs.Dialog.open(null, {
              year: item.year,
              subject_id: item.subject_id,
              client_id: item.client_id,
              teacher_id: item.teacher_id,
            })'
            v-if='item.report === null'
          >
            добавить
          </v-btn>
          <v-btn small color='black' flat icon
            @click='$refs.Dialog.open(item.report.id)'
            v-if='item.report !== null'
          >
              <v-icon>more_horiz</v-icon>
          </v-btn>
        </td>
        <td class='text-md-right pa-0' width='100' v-else>
            <v-btn small color='primary' class='btn-td' flat v-if='item.report !== null'
              @click="$router.push({name: 'ReportShow', params: {id: item.report.id}})"
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
        teacher: true,
        client: true,
        actions: true,
        dimension: false,
      }
    }
  },
}
</script>
