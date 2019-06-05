<template>
  <div>
    <v-data-table :items='items' item-key='id' hide-headers hide-actions :class='config.elevationClass' v-if='items.length > 0'>
      <template slot="items" slot-scope="{ item }">
        <td>
          <router-link :to="{ name: 'ClientShow', params: { id: item.id }}">
            <PersonName :item='item' />
          </router-link>
        </td>
        <td class='text-md-right'>
          <v-btn flat icon color="black" class='ma-0' @click='$refs.ClientDialog.open(item.id)'>
            <v-icon>more_horiz</v-icon>
          </v-btn>
        </td>
      </template>
    </v-data-table>
    <NoData 
      :add='() => $refs.ClientDialog.open(null)'
      box
      v-else />
    <ClientDialog ref='ClientDialog' />
  </div>
</template>

<script>
import ClientDialog from './Dialog'
export default {
  props: {
    items: {
      type: Array,
      required: false
    }
  },
  components: { ClientDialog }
}
</script>