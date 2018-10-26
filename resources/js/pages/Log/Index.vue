<template>
  <div>
    <v-container grid-list-xl fluid px-0>
      <v-layout wrap>
        <v-flex md12>
          <Loader v-if='loading' />
          <v-data-table v-else
            :items='items'
            :item-key='id'
            hide-headers
            hide-actions>
            <template slot='items' slot-scope="{ item }">
              <td>
                {{ item.table }}
              </td>
              <td>
                {{ types[item.type] }}
              </td>
              <td>
                {{ item.row_id }}
              </td>
              <td>
                {{ item.user_name }}
              </td>
              <td class='log-data'>
                <table v-if="item.type == 'update'">
                  <tr v-for="d in item.data" :key='index'>
                    <td>
                      {{ d.field }}
                    </td>
                    <td>
                      <span class='grey--text'>
                        {{ d.old }}
                      </span>
                      ⟶ {{ d.new }}
                    </td>
                  </tr>
                </table>
                <table v-else>
                  <tr v-for="(value, field) in item.data">
                    <td>
                      {{ field }}
                    </td>
                    <td>
                      {{ value }}
                    </td>
                  </tr>
                </table>
              </td>
              <td>
                {{ item.created_at | date-time }}
              </td>
            </template>
          </v-data-table>
        </v-flex>
      </v-layout>
    </v-container>
  </div>
</template>

<script>

const url = 'logs'

export default {
  data() {
    return {
      types: {
        update: 'обновление',
        delete: 'удаление',
        create: 'создание'
      },
      loading: false,
      items: []
    }
  },

  created() {
    this.loadData()
  },

  methods: {
    loadData() {
      this.loading = true
      axios.get(apiUrl(url)).then(response => {
        this.items = response.data
        this.loading = false
      })
    }
  }
}
</script>

<style lang='scss'>
  .log-data table {
    & tr {
      & td {
        padding: 3px 0 !important;
        height: unset !important;
        &:first-child {
          padding-right: 30px !important;
        }
      }
    }
  }
</style>
