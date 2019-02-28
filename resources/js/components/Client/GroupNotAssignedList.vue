<template>
  <div>
    <v-data-table v-if='items.length'
      class='group-not-assigned-table'
      :class='config.elevationClass'
      hide-actions
      hide-headers
      :items='items'
    >
      <template slot='items' slot-scope="{ item, index }">
        <td width='200'>
          Без группы
        </td>
        <td width='300'></td>
        <td>
          {{ getData('subjects', item.subject_id).three_letters }}–{{ item.grade_id }}
        </td>
        <td class='text-md-right pa-0' width='180'>
          <v-btn slot='activator' small class='btn-td' flat
            color='primary' @click='loadFittingGroups(item, index)'>присвоить группу</v-btn>
        </td>
      </template>
    </v-data-table>
    <MoveClientDialog ref='MoveClientDialog' @moved="$emit('moved')" />
  </div>
</template>

<script>

import { SUBJECT_STATUS_TERMINATED } from '@/components/Contract'
import { API_URL, GROUP_CLIENTS_API_URL } from '@/components/Group'
import MoveClientDialog from '@/components/Group/MoveClientDialog'

export default {
  props: {
    clientId: {
      type: Number,
      required: true,
    },

    groups: {
      type: Array,
      required: true,
    },

    contracts: {
      type: Array,
      required: true,
    },

    year: {
      type: Number,
    }
    // groups: {
    //   type: Array,
    //   required: true,
    // },
  },

  components: { MoveClientDialog },
  
  data() {
    return {
      // TODO:
      currentIndex: null,
    }
  },

  methods: {
    loadFittingGroups(item, index) {
      this.currentIndex = index
      this.$refs.MoveClientDialog.open(item, this.clientId)
    },
  },

  computed: {
    items() {
      const items = []
      const active_contracts = this.contracts.filter(e => e.is_active)
      console.log(active_contracts)
      // получить человеко-предметы
      active_contracts.forEach(contract => {
        contract.subjects.forEach(subject => {
          if (subject.status !== SUBJECT_STATUS_TERMINATED) {
            // если человек не состоит в такой группе
            // TODO: не подгружать группы клиента
            if (!this.groups.find(e => e.year === contract.year && e.grade_id === contract.grade_id && e.subject_id === subject.subject_id)) {
              items.push({
                year: contract.year,
                grade_id: contract.grade_id,
                subject_id: subject.subject_id,
              })
            }
          }
        })
      })

      return items
    }
  }
}
</script>

<style lang='scss'>
  .group-not-assigned-table {
    clip-path: inset(0px -10px -10px -10px);
    & .v-table tbody tr:first-child {
      border-top: 1px solid #e0e0e0;
    }
  }
    
</style>
