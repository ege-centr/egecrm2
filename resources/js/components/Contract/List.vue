<template>
  <v-flex md12>
    <Dialog ref='Dialog' v-if='clientId'
      :client-id='clientId'
      @updated='updated'
      @stored='stored' />
    <v-data-table
      v-if='items.length'
      :items="items"
      class="elevation-3"
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
          {{ getData('grades', item.grade_id).title }}
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
        <td :class="{'text-md-right': !clientId}">
          <span v-if='item.id'>
            {{ getData('admins', item.created_admin_id).name }}
            {{ item.created_at | date-time }}
          </span>
        </td>
        <td class='text-md-right' v-if='clientId'>
          <v-menu left>
            <v-btn slot='activator' flat icon color="black" class='ma-0'>
              <v-icon>more_horiz</v-icon>
            </v-btn>
            <v-list dense>
              <v-list-tile @click='openDialog(item)'>
                  <v-list-tile-action>
                    <v-icon>edit</v-icon>
                  </v-list-tile-action>
                  <v-list-tile-content>
                    <v-list-tile-title>Редактировать</v-list-tile-title>
                  </v-list-tile-content>
              </v-list-tile>
              <v-list-tile @click='addContractVersion(item)'>
                  <v-list-tile-action>
                    <v-icon>file_copy</v-icon>
                  </v-list-tile-action>
                  <v-list-tile-content>
                    <v-list-tile-title>Добавить версию</v-list-tile-title>
                  </v-list-tile-content>
              </v-list-tile>
              <v-list-tile @click='print(item)'>
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

    <v-flex md12 px-0 mt-3 v-if='clientId'>
      <v-btn fab dark small color="red" @click='openDialog(null)'>
        <v-icon dark>add</v-icon>
      </v-btn>
    </v-flex>

    <Print ref='Print' />
  </v-flex>
</template>

<script>

import Dialog from './Dialog'
import Print from './Print'
import { MODEL_DEFAULTS, SUBJECT_STATUSES } from './data'

export default {
  props: {
    items: {
      type: Array,
      required: true
    },
    clientId: {
      type: Number,
      required: false,
      default: null
    }
  },

  components: { Dialog, Print },

  data() {
    return {
      SUBJECT_STATUSES,
      contract: MODEL_DEFAULTS,
    }
  },
  methods: {
    openDialog(item) {
      this.$refs.Dialog.open(item)
    },
    addContractVersion(item) {
      this.openDialog({
        ...clone(item),
        id: undefined
      })
    },
    destroy(item) {
      const index = this.items.findIndex(e => e.id == item.id)
      this.items.splice(index, 1)
      axios.delete(apiUrl(API_URL, item.id))
    },
    updated(item) {
      const index = this.items.findIndex(e => e.id == item.id)
      Vue.set(this.items, index, item)
    },
    stored(item) {
      this.items.push(item)
    },
    print(item) {
      this.$refs.Print.open(item.id)
    },
  }
}
</script>
