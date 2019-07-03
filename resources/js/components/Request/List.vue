<template>
  <div>
    <RequestDialog ref='RequestDialog' />
    <ClientDialog ref='ClientDialog' />

     <RequestItem v-for='item in items' 
        @openDialog='(id) => $refs.RequestDialog.open(id)' 
        @openClientDialog='(id) => $refs.ClientDialog.open(id)' 
        :item='item' 
        :key='item.id' 
      />
  </div>
</template>



<script>

import ClientDialog from '@/components/Client/Dialog'
import RequestDialog from '@/components/Request/Dialog'
import RequestItem from '@/components/Request/Item'

export default {
  props: {
    items: {
      type: Array,
      required: false
    }
  },
  components: { RequestItem, RequestDialog, ClientDialog }
}
</script>