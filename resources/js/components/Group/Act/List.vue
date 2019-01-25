<template>
  <div>
    <v-data-table
      :class='config.elevationClass'
      hide-actions
      hide-headers
      :items='items'
    >
      <template slot='items' slot-scope="{ item }">
        <td width='200'>
          <a @click='$refs.GroupActDialog.open(item.id)'>
            Акт №{{ item.id }}
          </a>
        </td>
        <td>
          <span v-if='item.teacher_id'>
            {{ getData('teachers', item.teacher_id).names.abbreviation }}
          </span>
        </td>
        <td>
          <span v-if='item.sum'>
            {{ item.sum }} руб.
          </span>
        </td>
        <td>
          <span v-if='item.lesson_count'>
            {{ item.lesson_count }} занятий
          </span>
        </td>
        <td>
          <span v-if='item.date'>
            {{ item.date | date }}
          </span>
        </td>
        <td class='grey--text'>
          {{ item.createdAdmin.name }} {{ item.created_at | date-time }}
        </td>
      </template>
    </v-data-table>
    <GroupActDialog ref='GroupActDialog' />
  </div>
</template>
<script>

import { GroupActDialog } from './' 

export default {
  props: {
    items: {
      type: Array,
      required: true
    },
  },

  components: { GroupActDialog },
}
</script>