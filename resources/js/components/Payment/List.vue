<template>
  <div>
    <PaymentDialog ref='PaymentDialog' />
    <v-data-table
      :class='config.elevationClass'
      hide-actions
      hide-headers
      :items='items'
    >
      <template slot='items' slot-scope="{ item }">
        <td>
          {{ ENUMS.types.find(e => e.id == item.type).title }}
        </td>
        <td>
          {{ ENUMS.methods.find(e => e.id == item.method).title }}
        </td>
        <td>
          {{ ENUMS.categories.find(e => e.id == item.category).title }}
        </td>
        <td>
          {{ item.sum }} руб.
        </td>
        <td>
          {{ item.date | date }}
        </td>
        <td>
          <span v-if='item.id'>
            {{ getData('admins', item.created_admin_id).name }}
            {{ item.created_at | date-time }}
          </span>
        </td>
        <td class='text-md-right'>
          <v-btn @click='$refs.PaymentDialog.open(item.id)' slot='activator' flat icon color="black" class='ma-0'>
            <v-icon>more_horiz</v-icon>
          </v-btn>
        </td>
      </template>
    </v-data-table>
  </div>
</template>
<script>

import { ENUMS, PaymentDialog } from './'

export default {
  props: {
    items: {
      type: Array,
      required: true
    },
  },

  components: { PaymentDialog },

  data() {
    return {
      ENUMS,
    }
  },
  
}
</script>
