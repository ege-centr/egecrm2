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
          <a @click='$refs.MacroDialog.open(item)'>{{ item }}</a>
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

  data() {
    return {
    }
  },
  
}
</script>
