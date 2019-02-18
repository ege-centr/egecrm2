<template>
  <div>
    <Loader transparent v-if='items === null' />
    <div v-else>
      <div class='mb-3'>
        <v-chip v-for="item in tabsWithData" class='pointer ml-0 mr-3'
          :class="{'primary white--text': item.id == selected_tab}"
          @click='selected_tab = item.id'
          :key='item.id'
        >
          {{ item.title }}
        </v-chip>
      </div>

      <DataTable :items='currentYearItems' class='balance-table'>
        <template slot-scope='{ item }'>
          <tr style='border-bottom: none; border-top: none'>
            <td colspan='3'></td>
            <td>{{ remainder(item.date) }} руб.</td>
            <td colspan='2'></td>
          </tr>
          <tr>
            <td width='120'>
              {{ item.date | date }}
            </td>
            <td width='120'>
              <span class='green--text' v-if='item.sum >= 0'>
                +{{ item.sum }} руб.
              </span>
            </td>
            <td  width='120'>
              <span class='red--text' v-if='item.sum < 0'>
                {{ item.sum }} руб.
              </span>
            </td>
            <td width='120'></td>
            <td>
              {{ item.comment }}
            </td>
            <td class='grey--text'>
              {{ getData('admins', item.admin_id).name }}
              {{ item.created_at | date-time }}
            </td>
          </tr>
          <!-- <tr>
            <td>{{ item.date | date }}</td>
          </tr> -->
        </template>
      </DataTable>
    </div>
  </div>
</template>

<script>
const API_URL = 'balance'
import { ROLES } from '@/config'

export default {
  props: ['clientId'],

  data() {
    return {
      items: null,
      selected_tab: null,
    }
  },

  mounted() {
    axios.get(apiUrl(API_URL) + queryString({
      entity_id: this.clientId,
      entity_type: ROLES.CLIENT,
    })).then(r => {
      this.items = r.data
      Vue.nextTick(() => {
        this.selected_tab = this.tabsWithData.slice(-1)[0].id
      })
    })
  },

  methods: {
    remainder(date) {
      return this.items.reduce(function(prev, item) {
        if (item.date <= date) {
          return prev + item.sum
        }
        return prev
      }, 0)
    },
  },

  computed: {
    currentYearItems() {
      return this.items.filter(e => e.year === this.selected_tab)
    },

    tabsWithData() {
      return this.$store.state.data.years.filter(d => {
        return this.items.findIndex(e => e.year === d.id) !== -1
      })
    },
  }
}
</script>

<style lang="scss">
.balance-table {
  tr {
    td {
      font-size: 12px !important;
    }
  }
}
</style>

