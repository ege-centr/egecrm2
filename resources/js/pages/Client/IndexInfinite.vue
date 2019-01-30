<template lang="html">
  <div>
    <IndexPageInfinite :api-url='API_URL' :filters='FILTERS'>
      <template slot='buttons'>
        <AddBtn @click.native='$refs.ClientDialog.open(null)' label='добавить клиента' />
      </template>
      <template slot='items' slot-scope="{ items }">
        <ClientList :items='items' />
      </template>
    </IndexPageInfinite>
    <ClientDialog ref='ClientDialog' />
  </div>
</template>

<script>

// import { IndexPage } from '@/components/UI'
import IndexPageInfinite from '@/components/UI/IndexPageInfinite'
import { API_URL, FILTERS, ClientDialog, ClientList } from '@/components/Client'

export default {
  components: { IndexPageInfinite, ClientDialog, ClientList },

  data() {
    return {
      API_URL,
      FILTERS,
    }
  },
  
}
</script>
