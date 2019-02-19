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
          Акт №{{ item.id }}
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
        <td class='text-md-right'>
          <v-menu left>
            <v-btn slot='activator' flat icon color="black" class='ma-0'>
              <v-icon>more_horiz</v-icon>
            </v-btn>
            <v-list dense>
              <v-list-tile @click='$refs.GroupActDialog.open(item.id)'>
                <v-list-tile-action>
                  <v-icon>edit</v-icon>
                </v-list-tile-action>
                <v-list-tile-content>
                  <v-list-tile-title>Редактировать</v-list-tile-title>
                </v-list-tile-content>
              </v-list-tile>
              <v-list-tile  @click='$refs.Print.open({group_id: item.group_id, id: item.id})'>
                <v-list-tile-action>
                  <v-icon>print</v-icon>
                </v-list-tile-action>
                <v-list-tile-content>
                  <v-list-tile-title>Печать</v-list-tile-title>
                </v-list-tile-content>
              </v-list-tile>
            </v-list>
          </v-menu>
        </td>
      </template>
    </v-data-table>
    <GroupActDialog ref='GroupActDialog' @updated="$emit('updated')" />
    <Print ref='Print' :params="{type: 'act'}" />
  </div>
</template>
<script>

import { GroupActDialog } from './' 
import Print from '@/components/Print'

export default {
  props: {
    items: {
      type: Array,
      required: true
    },
  },

  components: { GroupActDialog, Print },
}
</script>