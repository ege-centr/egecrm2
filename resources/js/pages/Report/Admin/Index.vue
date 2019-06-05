<template>
  <div>
    <DisplayData ref='DisplayData' :api-url='API_URL' :filters='FILTERS' :paginate='30'>
      <template slot='items' slot-scope='{ items }'>
        <ReportList 
          v-if='items.length > 0' 
          :items='items'
          @updated='$refs.DisplayData.reloadData()'
        />
        <NoData
          v-else
          box
        />
      </template>
    </DisplayData>
  </div>
</template>

<script>

import { DisplayData } from '@/components/UI'
import { API_URL, FILTERS } from '@/components/Report'
import ReportList from '@/components/Report/List'

export default {
  components: { DisplayData, ReportList },

  data() {
    return {
      API_URL,
      FILTERS,
    }
  },
}
</script>
