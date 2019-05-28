<template>
  <v-data-table 
    v-if='items.length > 0'
    class='v-datatable--small'
    :class='config.elevationClass'
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
          <span v-if='item.createdUser'>
            <span v-if='item.createdUser.type === ROLES.CLIENT'>
              <router-link :to="{name: 'ClientShow', params: {id: item.createdUser.id}}">
                {{ item.createdUser.default_name }}
              </router-link>
            </span>
            <span v-else-if='item.createdUser.type === ROLES.TEACHER'>
              <router-link :to="{name: 'TeacherShow', params: {id: item.createdUser.id}}">
                {{ item.createdUser.default_name }}
              </router-link>
            </span>
            <span v-else>
              {{ item.createdUser.default_name }}
            </span>
          </span>
          <div v-if='item.previewModeUser' class='grey--text'>
            ({{ item.previewModeUser.default_name }})
          </div>
        </td>
        <td class='log-data'>
          <table v-if="item.type == 'update'">
            <tr v-for="(d, index) in item.data" :key='index'>
              <td width='150'>
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
              <td width='150'>
                {{ field }}
              </td>
              <td>
                {{ value }}
              </td>
            </tr>
          </table>
        </td>
        <td class='text-md-right grey--text'>
          {{ item.created_at | date-time }}
        </td>
      </tr>
    </template>
  </v-data-table>
  <NoData
    :height='200'
    :class='config.elevationClass'
    square
    v-else
  />
</template>



<script>
import { TYPES } from './'
import { ROLES } from '@/config'

export default {
  props: {
    items: {
      type: Array,
      required: true,
    }
  },
  
  data() {
    return {
      TYPES,
      ROLES,
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