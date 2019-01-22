---
to: resources/js/components/<%= Name %>/List.vue
---
<template>
  <div>
    <<%= Name %>Dialog ref='<%= Name %>Dialog' />
    <v-data-table
      :class='config.elevationClass'
      hide-actions
      hide-headers
      :items='items'
    >
      <template slot='items' slot-scope="{ item }">
        <td>
          {{ item.id }}
        </td>
        <td class='text-md-right'>
          <v-btn @click='$refs.<%= Name %>Dialog.open(item.id)' slot='activator' flat icon color="black" class='ma-0'>
            <v-icon>more_horiz</v-icon>
          </v-btn>
        </td>
      </template>
    </v-data-table>
  </div>
</template>
<script>

import { <%= Name %>Dialog } from './'

export default {
  props: {
    items: {
      type: Array,
      required: true
    },
  },

  components: { <%= Name %>Dialog },

  data() {
    return {
    }
  },
  
}
</script>
