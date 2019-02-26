<template>
  <div>
    <ReviewDialog ref='ReviewDialog' />
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
          <v-btn @click='$refs.ReviewDialog.open(item.id)' slot='activator' flat icon color="black" class='ma-0'>
            <v-icon>more_horiz</v-icon>
          </v-btn>
        </td>
      </template>
    </v-data-table>
  </div>
</template>

<script>
import { ReviewDialog } from './'

export default {
  props: {
    items: {
      type: Array,
      required: true
    },
  },

  components: { ReviewDialog },

  data() {
    return {
    }
  },
  
}
</script>
