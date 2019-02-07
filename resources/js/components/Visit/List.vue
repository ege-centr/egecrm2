<template>
  <div>
    <div v-for='item in items' :key='item.date'>
      <div class='title mb-2'>{{ formatDate(item.date) }}</div>
      <v-data-table :items='item.items' item-key='id' hide-headers hide-actions :class='config.elevationClass' class='mb-5'>
        <template slot="items" slot-scope="{ item }">
          <td width='10' class='pr-0 grey--text' :class="{'purple lighten-5': item.is_unplanned}">
            <div class='lesson-status' :class="{
              'blue': item.status === LESSON_STATUS.PLANNED,
              'green': item.status === LESSON_STATUS.CONDUCTED,
              'grey': item.status === LESSON_STATUS.CANCELLED,
            }"></div>
          </td>
          <td>
            <router-link :to="{ name: 'GroupShow', params: { id: item.group_id}}">
              Группа {{ item.group_id }}
            </router-link>
          </td>
          <td :class="{'purple lighten-5': item.is_unplanned}">
            {{ item.time }}
          </td>
          <td :class="{'purple lighten-5': item.is_unplanned}">
            <span v-if='item.cabinet_id'>
              {{ getData('cabinets', item.cabinet_id).title }}
            </span>
          </td>
          <td :class="{'purple lighten-5': item.is_unplanned}">
            <span v-if='item.teacher_id'>
              {{ getData('teachers', item.teacher_id).names.abbreviation }}
            </span>
          </td>
          <td class='grey--text' :class="{'purple lighten-5': item.is_unplanned}">
            <span v-if='item.conducted_email_id'>{{ getData('admins', item.conducted_email_id).name }} {{ item.created_at | date-time }}</span>
          </td>
        </template>
        <NoData slot='no-data' />
      </v-data-table>
    </div>
  </div>
</template>

<script>
import { LESSON_STATUS } from '@/components/Lesson'

export default {
  props: {
    items: {
      type: Array,
      required: true
    },
  },

  data() {
    return {
      LESSON_STATUS,
    }
  },
  
  methods: {
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

  .lesson-status {
    border-radius: 50%;
    height: 8px;
    width: 8px;
    position: absolute;
    left: 16px;
    top: 20px;
  }
</style>
