<template>
  <div>
    <Loader transparent v-if='items === null' />
    <div v-else>
      <div class='mb-3'>
        <v-chip v-for="year in yearsWithData" class='pointer ml-0 mr-3'
          :class="{'primary white--text': year == selected_year}"
          @click='selected_year = year'
          :key='year'
        >
          {{ getData('years', year).title }}
        </v-chip>
      </div>

      <data-table :items='Object.keys(currentYearItems)' class='balance-table'>
        <template slot-scope='{ item }'>
          <tr style='border-top: 1px solid #e0e0e0; border-bottom: none'>
            <td colspan='3'></td>
            <td>
              {{ remainder(item) }} руб. 
            </td>
            <td colspan='2'></td>
          </tr>
          <tr v-for='(i, date) in currentYearItems[item]' 
            class='no-borders'
          >
            <td width='130'>
              <span v-if='currentYearItems[item].length === currentYearItems[item].findIndex(e => e === i) + 1'>
                {{ i.date | date }}
              </span>
            </td>
            <td width='130'>
              <span class='green--text' v-if='i.sum > 0'>
                +{{ i.sum }} руб.
              </span>
            </td>
            <td  width='130'>
              <span class='red--text' v-if='i.sum <= 0'>
                <span v-if='i.sum === 0'>
                  бесплатно
                </span>
                <span v-else>
                  {{ i.sum }} руб.
                </span>
              </span>
            </td>
            <td width='130'></td>
            <td>
              {{ i.comment }}
            </td>
            <td  v-if='show.created_at && i.user'>
              <Credentials :item='i' user-field='user' />
            </td>
          </tr>
        </template>
      </data-table>
    </div>
  </div>
</template>



<script>
import { API_URL } from '@/components/Balance'
import DisplayOptions from '@/mixins/DisplayOptions'

export default {
  props: ['entityId', 'entityType'],

  mixins: [ DisplayOptions ],

  data() {
    return {
      items: null,
      selected_year: null,
      defaultDisplayOptions: {
        created_at: true,
      },
    }
  },

  mounted() {
    axios.get(apiUrl(API_URL) + queryString({
      entity_id: this.entityId,
      entity_type: this.entityType,
    })).then(r => {
      this.items = r.data
      this.selected_year = this.yearsWithData.slice(-1)[0]
    })
  },

  methods: {
    remainder(date) {
      let sum = 0
      _.each(this.currentYearItems, (items, d) => {
        if (d <= date) {
          sum += items.reduce(function(prev, item) {
            return prev + item.sum
          }, 0)
        }
      })
      return sum
    },
  },

  computed: {
    currentYearItems() {
      return Object.keys(this.items).length > 0 ? this.items[this.selected_year] : []
    },

    yearsWithData() {
      return Object.keys(this.items)
    },
  }
}
</script>

<style lang="scss">
.balance-table {
  & tr {
    &.no-borders {
      border-bottom: none !important; 
      border-top: none !important;
    }
    & td {
      font-size: 12px !important;
    }
  }
}
</style>

