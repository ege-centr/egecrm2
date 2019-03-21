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
          {{ item.message }}
        </td>
        <td :class="item.status == 1? 'green--text' : 'red--text'">
          {{ item.status_name }}
        </td>
        <td class='text-sm-right grey--text'>
          <span v-if='item.model !== null && item.model.createdUser !== null'>
            {{ item.createdUser.default_name }}
          </span>
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