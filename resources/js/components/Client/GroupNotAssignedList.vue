<template>
  <div>
    <v-data-table v-if='items.length'
      :class='config.elevationClass'
      hide-actions
      hide-headers
      :items='items'
    >
      <template slot='items' slot-scope="{ item, index }">
        <td width='200'>
          Без группы
        </td>
        <td>
          {{ getData('subjects', item.subject_id).three_letters }}–{{ item.grade_id }}
        </td>
        <td class='text-md-right'>
          <v-menu
            bottom
            origin="center center"
            transition="scale-transition"
          >
            <v-btn slot='activator' small color='primary' @click='loadFittingGroups(item, index)'>присвоить группу</v-btn>
          </v-menu>
        </td>
      </template>
    </v-data-table>
    <MoveClientDialog ref='MoveClientDialog' @moved="$emit('moved')" />
  </div>
</template>

<script>

import { SUBJECT_STATUS_TERMINATED } from '@/components/Contract'
import { API_URL, GROUP_CLIENTS_API_URL, MoveClientDialog } from '@/components/Group'

export default {
  props: {
    client: {
      type: Object,
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
      this.$refs.MoveClientDialog.open(item, this.client)
    },

    // assignGroup(group) {
    //   axios.post(apiUrl(GROUP_CLIENTS_API_URL), {
    //     group_id: group.id,
    //     client_id: this.client.id,
    //   }).then(r => {
    //     this.$emit('assigned')
    //     setTimeout(() => this.items.splice(this.currentIndex, 1), 300)
    //   })
    // }
  },

  computed: {
    items() {
      const items = []
      const active_contracts = this.client.contracts.filter(e => e.is_active)

      // получить человеко-предметы
      active_contracts.forEach(contract => {
        contract.subjects.forEach(subject => {
          if (subject.status !== SUBJECT_STATUS_TERMINATED) {
            // если человек не состоит в такой группе
            // TODO: не подгружать группы клиента
            if (!this.client.groups.find(e => e.year === contract.year && e.grade_id === contract.grade_id && e.subject_id === subject.subject_id)) {
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

<style lang='scss' scoped>
  .v-card {
    height: 300px;
    width: 300px;
    overflow-y: scroll;
  }
</style>
