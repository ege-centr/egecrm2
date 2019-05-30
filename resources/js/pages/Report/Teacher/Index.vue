<template>
  <div>
    <DisplayData 
      :invisible-filters="{'teacher_id': $store.state.user.id}"
      :tabs='true'
      :api-url='API_URL' 
    >
      <template slot='items' slot-scope='{ items }'>
         <ReportList 
            v-if='items.length > 0' 
            :items='items'
            @updated='$refs.DisplayData.reloadData()'
          />
        <NoData
          v-else
          transparent
        />
      </template>
    </DisplayData>
  </div>
</template>

<script>

import { DisplayData } from '@/components/UI'
import { API_URL } from '@/components/Report'
import ReportList from '@/components/Report/List'

export default {
  components: { DisplayData, ReportList },

  data() {
    return {
      API_URL,
    }
  },
}
</script>
