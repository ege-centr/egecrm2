<template>
  <div>
    <MacroDialog ref='MacroDialog' />
    <v-data-table
      :class='config.elevationClass'
      hide-actions
      hide-headers
      :items='items'
    >
      <template slot='items' slot-scope="{ item }">
        <td>
          {{ item }}
        </td>
        <td class='text-sm-right'>
          <v-btn @click='$refs.MacroDialog.open(item)' flat icon color="black" class='ma-0'>
            <v-icon>more_horiz</v-icon>
          </v-btn>
        </td>
      </template>
    </v-data-table>
  </div>
</template>
<script>

import MacroDialog from './Dialog'

export default {
  props: {
    items: {
      type: Array,
      required: true
    },
  },

  components: { MacroDialog },
}
</script>
