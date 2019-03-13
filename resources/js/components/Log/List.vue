<template>
  <v-data-table 
    class='v-datatable--small'
    :items='items'
    :item-key='id'
    hide-headers
    hide-actions>
    <template slot='items' slot-scope="{ item }">
      <tr>
        <td>
          {{ item.table }}
        </td>
        <td>
          {{ TYPES.find(e => e.id === item.type).title }}
        </td>
        <td>
          {{ item.row_id }}
        </td>
        <td>
          {{ item.user_name }}
        </td>
        <td class='log-data'>
          <table v-if="item.type == 'update'">
            <tr v-for="(d, index) in item.data" :key='index'>
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
            <tr v-for="(value, field, index) in item.data" :key='index'>
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
      </tr>
    </template>
  </v-data-table>
</template>



<script>
import { TYPES } from './'

export default {
  props: {
    items: {
      type: Array,
      required: true,
    }
  },
  
  data() {
    return {
      TYPES
    }
  },
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