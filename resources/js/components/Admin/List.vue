<template>
  <div>
    <AdminDialog ref='AdminDialog' @updated="$emit('updated')" />
    <v-data-table 
      v-if='items.length > 0'
      :items='items' 
      item-key='id' 
      hide-headers 
      hide-actions 
      :class='config.elevationClass'
    >
      <template slot="items" slot-scope="{ item }">
        <td>
            {{ item.default_name }}
        </td>
        <td class='text-md-right'>
          <v-btn flat icon color="black" class='ma-0' @click='$refs.AdminDialog.open(item.id)'>
            <v-icon>more_horiz</v-icon>
          </v-btn>
        </td>
      </template>
    </v-data-table>
    <NoData 
      v-else 
      transparent 
      :add='() => $refs.AdminDialog.open(null)' />
  </div>
</template>

<script>

import AdminDialog from '@/components/Admin/Dialog'

export default {
  props: {
    items: {
      type: Array,
      required: true,
    }
  },

  components: { AdminDialog },
}
</script>
