<template>
  <div>
    <div v-for='item in items' :key='item.date'>
      <div class='headline mb-4'>
        {{ formatDate(item.date) }} 
        <span v-if='isToday(item.date)'>(сегодня)</span>
      </div>
      <v-data-table :items='item.items' item-key='id' hide-headers hide-actions :class='config.elevationClass' class='mb-5'>
        <template slot="items" slot-scope="{ item }">
          <td width='44' class='pr-0'>
            <LessonStatusCircles :item='item' />
          </td>
          <td>
            <router-link :to="{ name: 'GroupShow', params: { id: item.group.id}}">
              Группа {{ item.group.id }}
            </router-link>
          </td>
          <td>
            {{ item.time }}
          </td>
          <td>
            <span v-if='item.clients_count > 0'>
              {{ item.clients_count }} учеников
            </span>
          </td>
          <td>
            <span v-if='item.group.subject_id'>
              <SubjectGrade :item='item.group' />
            </span>
          </td>
          <td>
            <span v-if='item.cabinet_id'>
              {{ getData('cabinets', item.cabinet_id).title }}
            </span>
          </td>
          <td>
            <span v-if='item.teacher_id'>
              {{ getData('teachers', item.teacher_id).names.abbreviation }}
            </span>
          </td>
          <td class='grey--text'>
            <span v-if='item.conducted_email_id'>{{ getData('admins', item.conducted_email_id).name }} {{ item.created_at | date-time }}</span>
          </td>
        </template>
        <NoData slot='no-data' />
      </v-data-table>
    </div>
  </div>
</template>

<script>
import { LESSON_STATUS, LessonStatusCircles } from '@/components/Lesson'

export default {
  props: {
    items: {
      type: Array,
      required: true
    },
  },

  components: { LessonStatusCircles },

  data() {
    return {
      LESSON_STATUS,
    }
  },
  
  methods: {
    isToday(date) {
      return date === moment().format('YYYY-MM-DD')
    },

    formatDate(date) {
      return moment(date).format('D MMMM YYYY')
    }
  }
}
</script>

<style lang="scss" scoped>
 .v-datatable {
    & tr {
      & td {
        position: relative;
      }
    }
  }
</style>
