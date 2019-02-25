<template>
  <div>
    <EgeTrialDialog ref='EgeTrialDialog' />
    <v-data-table
      v-if='items.length > 0'
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
          <v-btn @click='$refs.EgeTrialDialog.open(item.id)' slot='activator' flat icon color="black" class='ma-0'>
            <v-icon>more_horiz</v-icon>
          </v-btn>
        </td>
      </template>
    </v-data-table>
    <center class='mt-5' v-if='items.length === 0'>
      <AddBtn @click.native='$refs.EgeTrialDialog.open(null, {client_id: clientId})' />
    </center>
  </div>
</template>

<script>
import EgeTrialDialog from './Dialog'

export default {
  props: {
    items: {
      type: Array,
      required: true
    },
    clientId: {
      required: true,
    },
  },

  components: { EgeTrialDialog },

  data() {
    return {
    }
  },
  
}
</script>
