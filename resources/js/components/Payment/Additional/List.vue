<template>
  <display-list 
    :dialog-component='Dialog'
    :items='items'
    :model-defaults='modelDefaults'
    :add-btn='show.edit'
    add-btn-label='добавить услугу'
    ref='DisplayList'
    @updated="$emit('updated')"
  >
    <template slot='item' slot-scope="{ item }">
      <td>
        {{ item.sum }} руб.
      </td>
      <td>
        {{ item.purpose }}
      </td>
      <td>
        {{ item.date | date }}
      </td>
      <td class='text-md-right' v-if='show.edit'>
        <v-btn @click='$refs.DisplayList.edit(item.id)' slot='activator' flat icon color="black" class='ma-0'>
          <v-icon>more_horiz</v-icon>
        </v-btn>
      </td>
    </template>
  </display-list>
</template>



<script>
import Dialog from './Dialog'
import DisplayList from '@/components/UI/DisplayList'
import DisplayOptions from '@/mixins/DisplayOptions'

export default {
  props: {
    items: {
      type: Array,
      required: true
    },
    
    modelDefaults: {
      type: Object,
      required: false,
      default: () => {}
    },
  },

  components: { DisplayList },

  mixins: [ DisplayOptions ],

  data() {
    return {
      Dialog,
      defaultDisplayOptions: {
        edit: true,
      }
    }
  },
}
</script>
