<template lang="html">
  <div>
    <DisplayData :api-url='API_URL' :filters='FILTERS'>
      <template slot='buttons'>
        <AddBtn @click.native='$refs.ClientDialog.open(null)' label='добавить клиента' />
      </template>
      <template slot='items' slot-scope="{ items }">
        <ClientList :items='items' />
      </template>
    </DisplayData>
    <ClientDialog ref='ClientDialog' />
  </div>
</template>

<script>

import { DisplayData } from '@/components/UI'
import { API_URL, FILTERS, ClientDialog, ClientList } from '@/components/Client'

export default {
  components: { DisplayData, ClientDialog, ClientList },

  data() {
    return {
      API_URL,
      FILTERS,
    }
  },
  
}
</script>
