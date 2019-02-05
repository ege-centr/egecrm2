<template>
  <div>
    <DisplayData :api-url='API_URL' :filters='FILTERS' :paginate='30'>
      <template slot='items' slot-scope='{ items }'>
        <ContractList :items='items' />
      </template>
    </DisplayData>
  </div>
</template>

<script>

import { DisplayData } from '@/components/UI'
import { API_URL, FILTERS, ContractList } from '@/components/Contract'

export default {
  components: { DisplayData, ContractList },

  data() {
    return {
      API_URL,
      FILTERS,
    }
  },
}
</script>
