<template>
  <div>
    <v-data-table v-if='items.length'
      class="elevation-1"
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
            <v-card>
              <v-card-text class="full-height">
                <Loader v-if='fittingGroups === null' />
                <div class='full-height' v-else>
                  <div v-if='fittingGroups.length'>
                    <v-list dense class='pa-0' v-for='group in fittingGroups' :key='group.id'>
                      <v-list-tile @click='assignGroup(group)'>
                        Группа {{ group.id }}
                      </v-list-tile>
                    </v-list>
                  </div>
                  <div v-else class='d-flex align-center justify-center grey--text full-height'>
                    нет подходящих групп
                  </div>
                </div>
              </v-card-text>
            </v-card>
          </v-menu>
        </td>
      </template>
    </v-data-table>
  </div>
</template>

<script>

import { SUBJECT_STATUS_TERMINATED } from '@/components/Contract/data'
import { API_URL, GROUP_CLIENTS_API_URL } from '@/components/Group/data'

export default {
  props: {
    contracts: {
      type: Array,
      required: true,
    },
    groups: {
      type: Array,
      required: true
    }
  },

  data() {
    return {
      items: [],
      // подходящие для присвоения группы
      fittingGroups: null,
      // TODO:
      currentIndex: null,
    }
  },

  created() {
    const active_contracts = this.contracts.filter(e => e.is_active)

    // получить человеко-предметы
    active_contracts.forEach(contract => {
      contract.subjects.forEach(subject => {
        if (subject.status !== SUBJECT_STATUS_TERMINATED) {
          // если человек не состоит в такой группе
          if (!this.groups.find(e => e.year === contract.year && e.grade_id === contract.grade_id && e.subject_id === subject.subject_id)) {
            this.items.push({
              year: contract.year,
              grade_id: contract.grade_id,
              subject_id: subject.subject_id,
            })
          }
        }
      })
    })
  },

  methods: {
    loadFittingGroups(item, index) {
      this.currentIndex = index
      this.fittingGroups = null
      axios.get(apiUrl(`${API_URL}?${queryString(item)}`)).then(r => {
        this.fittingGroups = r.data
      })
    },

    assignGroup(group) {
      axios.post(apiUrl(GROUP_CLIENTS_API_URL), {
        group_id: group.id,
        client_id: this.contracts[0].client_id
      }).then(r => {
        this.$emit('assigned')
        setTimeout(() => this.items.splice(this.currentIndex, 1), 300)
      })
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
