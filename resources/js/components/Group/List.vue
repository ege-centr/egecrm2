<template>
  <v-data-table
    :class='config.elevationClass'
    hide-actions
    hide-headers
    :items='items'
  >
    <template slot='items' slot-scope="{ item }">
      <td width='200'>
        <router-link :to="{ name: 'GroupShow', params: {id: item.id}}">
          Группа {{ item.id }}
        </router-link>
      </td>
      <td>
        {{ getData('subjects', item.subject_id).three_letters }}–{{ item.grade_id }}
      </td>
      <td>
        <span v-if="item.cabinet">
          {{ item.cabinet.text }}
        </span>
      </td>
      <td>
        <span v-if='item.clients_count'>
          {{ item.clients_count }} ученика
        </span>
        <span v-else>нет учеников</span>
      </td>
      <td>
        {{ item.lessons_count }} занятий
      </td>
      <td>
        {{ item.teacher_name }}
      </td>
    </template>
  </v-data-table>
</template>
<script>

export default {
  props: {
    items: {
      type: Array,
      default: null,
      required: false
    }
  },
}
</script>
