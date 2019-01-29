<template>
  <v-flex md12>
    <ContractDialog ref='ContractDialog' @updated="$emit('updated')" />
    <Print ref='Print' :params="{type: 'contract'}" />
    <v-data-table
      :items="items"
      :class='config.elevationClass'
      hide-actions
      hide-headers
    >
      <template slot='items' slot-scope="{ item }">
        <td>
          <span v-if='item.id'>
            №{{ item.number }} версия {{ item.version }}
          </span>
        </td>
        <td>
          от {{ item.date | date }}
        </td>
        <td>
          {{ item.sum }} руб.
        </td>
        <td>
          <span v-if='item.payments.length'>
            {{ item.payments.length }} платежа
          </span>
          <span v-else>
            платежей нет
          </span>
        </td>
        <td>
          {{ getData('grades', item.grade_id).title }}, {{ getData('years', item.year).title }}
        </td>
        <td>
          <span v-for='subject in item.subjects' :class="{
            'error--text': subject.status == SUBJECT_STATUSES[2],
            'orange--text': subject.status == SUBJECT_STATUSES[1]
          }">
            {{ getData('subjects', subject.subject_id).three_letters }}
            <span class='grey--text'>{{ subject.lessons }}</span>
          </span>
        </td>
        <td>
          <span v-if='item.id'>
            {{ getData('admins', item.created_admin_id).name }}
            {{ item.created_at | date-time }}
          </span>
        </td>
        <td class='text-md-right'>
          <v-menu left>
            <v-btn slot='activator' flat icon color="black" class='ma-0'>
              <v-icon>more_horiz</v-icon>
            </v-btn>
            <v-list dense>
              <v-list-tile @click='$refs.ContractDialog.open(item.id)'>
                  <v-list-tile-action>
                    <v-icon>edit</v-icon>
                  </v-list-tile-action>
                  <v-list-tile-content>
                    <v-list-tile-title>Редактировать</v-list-tile-title>
                  </v-list-tile-content>
              </v-list-tile>
              <v-list-tile @click='addVersion(item)'>
                  <v-list-tile-action>
                    <v-icon>file_copy</v-icon>
                  </v-list-tile-action>
                  <v-list-tile-content>
                    <v-list-tile-title>Добавить версию</v-list-tile-title>
                  </v-list-tile-content>
              </v-list-tile>
              <v-list-tile @click='$refs.Print.open({id: item.id})'>
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
  </v-flex>
</template>

<script>

import Print from '@/components/Print'
import { MODEL_DEFAULTS, SUBJECT_STATUSES, ContractDialog } from './'

export default {
  props: {
    items: {
      type: Array,
      required: true
    },
    client: {
      type: Object,
      required: true,
    }
  },

  components: { ContractDialog, Print },

  data() {
    return {
      SUBJECT_STATUSES,
      contract: MODEL_DEFAULTS,
    }
  },
  methods: {
    addVersion(item) {
      this.$refs.ContractDialog.open(null, {
        ...clone(item),
        id: undefined
      })
    },

    // TODO: перенести в Dialog
    destroy(item) {
      const index = this.items.findIndex(e => e.id == item.id)
      this.items.splice(index, 1)
      axios.delete(apiUrl(API_URL, item.id))
    },
  }
}
</script>
