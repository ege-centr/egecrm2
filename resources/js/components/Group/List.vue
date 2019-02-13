<template>
  <v-data-table
    :class='config.elevationClass'
    hide-actions
    hide-headers
    :items='items'
  >
    <template slot='items' slot-scope="{ item }">
      <tr @click='handleRowClick(item)'>
        <td width='1' v-if='selectable' class='pr-0'>
          <v-radio-group class='ma-0' v-model='selected_group_id' @change="$emit('update:selected_group_id', selected_group_id)" hide-details>
            <v-radio :value='item.id' class='ma-0'></v-radio>
          </v-radio-group>
        </td>
        <td width='200'>
          <router-link :to="{ name: 'GroupShow', params: {id: item.id}}">
            Группа {{ item.id }}
          </router-link>
        </td>
        <td v-if='show.teacher'>
          {{ item.teacher ? item.teacher.names.abbreviation : '' }}
        </td>
        <td>
          <span v-if='item.subject_id'>
            {{ getData('subjects', item.subject_id).three_letters }}–{{ item.grade_id }}
          </span>
        </td>
        <td>
          <span v-if="item.cabinet">
            {{ item.cabinet.text }}
          </span>
        </td>
        <td>
          <span v-if='item.schedule !== null'>
            {{ item.schedule.label }}
          </span>
        </td>
        <td>
          <span v-if='item.clients_count'>
            {{ item.clients_count }} ученика
          </span>
          <span v-else>нет учеников</span>
        </td>
        <td>
          <span v-if='item.lessons_count > 0 && item.lessons_conducted_count > 0'>
            {{ item.lessons_conducted_count }} из {{ item.lessons_count }} уроков
          </span>
          <span v-if='item.lessons_count > 0 && item.lessons_conducted_count === 0'>
            старт {{ item.first_lesson_date | day-month }}
          </span>
        </td>
      </tr>
    </template>
  </v-data-table>
</template>

<script>

import DisplayOptions from '@/mixins/DisplayOptions'

export default {
  props: {
    items: {
      type: Array,
      default: null,
      required: false
    },
    selectable: {
      type: Boolean,
      default: false,
      required: false,
    }
  },

  mixins: [ DisplayOptions ],

  data() {
    return {
      selected_group_id: null,
      defaultDisplayOptions: {
        teacher: true,
      }
    }
  },

  methods: {
    handleRowClick(item) {
      if (this.selectable) {
        this.selected_group_id = item.id
      }
    }
  }
}
</script>
