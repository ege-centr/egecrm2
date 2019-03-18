<template>
  <div>
    <v-data-table
      :class='config.elevationClass'
      hide-actions
      hide-headers
      :items='items'
    >
      <template slot='items' slot-scope="{ item }">
        <td>
          {{ item.phone }}
        </td>
        <td>
          {{ item.message | truncate(50) }}
        </td>
        <td>
          {{ item.status_name }}
        </td>
        <td class='text-sm-right'>
          {{ item.created_at | date-time }}
        </td>
      </template>
    </v-data-table>
  </div>
</template>

<script>

export default {
  props: {
    items: {
      type: Array,
      required: true
    },
  },

  data() {
    return {
    }
  },
  
}
</script>
