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
        <td v-if='show.client'>
          <router-link :to="{name: 'ClientShow', params: {id: item.client.id}}">
            <PersonName :item='item.client' field='abbreviation' />
          </router-link>
        </td>
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
          <span v-if='item.payment_count > 0'>
            {{ item.payment_count }} платежа
          </span>
          <span v-else>
            платежей нет
          </span>
        </td>
        <td>
          <span v-if='item.grade_id'>
            {{ getData('grades', item.grade_id).title }}
          </span><span v-if='show.year && item.year'>, {{ getData('years', item.year).title }}</span>
        </td>
        <td>
          <span v-for='subject in item.subjects' :class="{
            'error--text': subject.status == SUBJECT_STATUSES[2],
            'orange--text': subject.status == SUBJECT_STATUSES[1]
          }" :key='subject.id'>
            {{ getData('subjects', subject.subject_id).three_letters }}
            <span class='grey--text'>{{ subject.lessons }}</span>
          </span>
        </td>
        <td>
          {{ item.createdAdmin.names.short }}
          {{ item.created_at | date-time }}
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
import ContractDialog from '@/components/Contract/Dialog'
import DisplayOptions from '@/mixins/DisplayOptions'
import { MODEL_DEFAULTS, SUBJECT_STATUSES } from './'

export default {
  props: {
    items: {
      type: Array,
      required: true
    },
  },

  components: { ContractDialog, Print },

  mixins: [ DisplayOptions ],

  data() {
    return {
      SUBJECT_STATUSES,
      defaultDisplayOptions: {
        client: false,
        year: false,
      },
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
  },
}
</script>