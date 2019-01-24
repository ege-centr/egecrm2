<template lang="html">
  <div>
    <IndexPage :api-url='API_URL' :filters='FILTERS' :sort='SORT'>
      <template slot='items' slot-scope='{ items }'>
        <PaymentList :items='items' />
      </template>
    </IndexPage>
  </div>
</template>

<script>

import { IndexPage } from '@/components/UI'
import { API_URL, FILTERS, SORT, ENUMS } from '@/components/Payment'
import PaymentList from '@/components/Payment/List'

export default {
  components: { IndexPage, PaymentList },

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
