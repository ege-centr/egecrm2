<template lang="html">
  <div>
    <DisplayData :api-url='API_URL' :filters='FILTERS' :sort='SORT'>
      <template slot='items' slot-scope='{ items }'>
        <PaymentList :items='items' />
      </template>
    </DisplayData>
  </div>
</template>

<script>

import { DisplayData } from '@/components/UI'
import { API_URL, FILTERS, SORT, ENUMS } from '@/components/Payment'
import PaymentList from '@/components/Payment/List'

export default {
  components: { DisplayData, PaymentList },

  data() {
    return {
      API_URL,
      ENUMS,
      SORT,
      FILTERS,
    }
  },
  
}
</script>
