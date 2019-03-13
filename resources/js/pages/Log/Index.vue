<template>
  <div>
    <DisplayData :api-url='API_URL' :paginate='50' :filters='FILTERS'>
      <template slot='items' slot-scope='{ items }'>
        <LogList :items='items'/>
      </template>
    </DisplayData>
  </div>
</template>

<script>

import { DisplayData } from '@/components/UI'
import { API_URL, FILTERS } from '@/components/Log'
import LogList from '@/components/Log/List'

export default {
  components: { DisplayData, LogList },

  data() {
    return {
      API_URL,
      FILTERS,
    }
  },
  
}
</script>
