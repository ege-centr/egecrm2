<template>
  <div>
    <DisplayData ref='DisplayData' :api-url='API_URL' :filters='FILTERS' :paginate='30'>
      <template slot='items' slot-scope='{ items }'>
        <ContractList 
          v-if='items.length > 0' 
          @updated='$refs.DisplayData.reloadData()'
          :items='items'
          :display-options='{
            client: true, 
            year: true, 
            addBtn: false,
            payments: false,
          }' />
        <NoData
          v-else
          transparent
        />
      </template>
    </DisplayData>
  </div>
</template>

<script>

import DisplayData from '@/components/UI/DisplayData'
import { API_URL, FILTERS } from '@/components/Contract'
import ContractList from '@/components/Contract/List'

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