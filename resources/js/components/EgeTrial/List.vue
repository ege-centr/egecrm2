<template>
  <div>
    <EgeDialog ref='EgeDialog' />
    <v-data-table
      :class='config.elevationClass'
      hide-actions
      hide-headers
      :items='items'
    >
      <template slot='items' slot-scope="{ item }">
        <td>
          <SubjectGrade :item='item' />
        </td>
        <td>
          {{ item.sum }} руб.
        </td>
        <td>
          {{ item.date | date }}
        </td>
        <td>
          <span v-if='item.score > 0'>
            {{ item.score }}
            <span v-if='item.max_score > 0'>из {{ item.max_score }}</span>
            баллов
          </span>
        </td>
        <td>
          {{ item.description }}
        </td>
        <td>
          <Credentials :item='item' />
        </td>
        <td class='text-md-right'>
          <v-btn @click='$refs.EgeDialog.open(item.id)' slot='activator' flat icon color="black" class='ma-0'>
            <v-icon>more_horiz</v-icon>
          </v-btn>
        </td>
      </template>
    </v-data-table>
  </div>
</template>

<script>
import EgeDialog from './Dialog'

export default {
  props: {
    items: {
      type: Array,
      required: true
    },
  },

  components: { EgeDialog },

  data() {
    return {
    }
  },
  
}
</script>
