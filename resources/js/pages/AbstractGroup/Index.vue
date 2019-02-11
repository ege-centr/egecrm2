<template>
  <div>
    <DisplayData :api-url='API_URL' :filters='FILTERS' :paginate='1'>
      <template slot='items' slot-scope='{ items }'>
        <AbstractGroupList :items='items' />
      </template>
    </DisplayData>
  </div>
</template>

<script>

import { DisplayData } from '@/components/UI'
import { API_URL, FILTERS, AbstractGroupList } from '@/components/AbstractGroup'

export default {
  components: { DisplayData, AbstractGroupList },

  data() {
    return {
      API_URL,
      FILTERS,
    }
  },
}
</script>
