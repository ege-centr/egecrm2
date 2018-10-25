<template>
  <v-flex md12>
    <ContractDialog ref='ContractDialog' :item='contract' @saved='contractSaved' />
    <v-data-table
      v-if='items.length'
      :items="items"
      class="elevation-1"
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
          {{ getData('grades', item.grade).title }}
        </td>
        <td>
          <span v-for='subject in item.subjects' :class="{
            'error--text': subject.status == subject_statuses[2],
            'orange--text': subject.status == subject_statuses[1]
          }">
            {{ getData('subjects', subject.subject_id).three_letters }}
            <span class='grey--text'>{{ subject.lessons }}</span>
          </span>
        </td>
        <td :class="{'text-md-right': !editable}">
          <span v-if='item.id'>
            {{ getData('admins', item.created_admin_id).name }}
            {{ item.created_at | date-time }}
          </span>
        </td>
        <td class='text-md-right' v-if='editable'>
          <v-menu left>
            <v-btn slot='activator' flat icon color="black" class='ma-0'>
              <v-icon>more_horiz</v-icon>
            </v-btn>
            <v-list dense>
              <v-list-tile @click='openContract(item)'>
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
              <v-list-tile @click='openContract(item)'>
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

    <v-flex md12 px-0 v-if='editable'>
      <v-btn color='blue white--text darken-1' small class='ma-0' @click='openContract()'>
        <v-icon class="mr-1">add</v-icon>
        добавить
      </v-btn>
    </v-flex>

  </v-flex>
</template>

<script>

import ContractDialog from './Dialog'
import { model_defaults, subject_statuses } from './data'

export default {
  props: {
    items: {
      type: Array,
      required: true
    },
    editable: {
      type: Boolean,
      default: false
    }
  },
  components: { ContractDialog },
  data() {
    return {
      contract: model_defaults,
      subject_statuses
    }
  },
  methods: {
    openContract(contract) {
      if (contract === undefined) {
        contract = clone(model_defaults)
      }
      this.contract = clone(contract)
      this.$refs.ContractDialog.dialog = true
    },
    addContractVersion(contract) {
      this.openContract({
        ...clone(contract),
        id: undefined
      })
    },
    contractSaved(contract) {
      if (contract.id) {
        const index = this.items.findIndex(e => e.id == contract.id)
        Vue.set(this.items, index, contract)
      } else {
        this.items.push(contract)
      }
    },
  }
}
</script>
