<template>
  <div>
    <DisplayData 
      :invisible-filters="{'teacher_id': $store.state.user.id}"
      :tabs="{data: 'years', field: 'year'}"
      :api-url='API_URL' 
      :filters='FILTERS'>
      <template slot='items' slot-scope='{ items }'>
        <ReportList :display-options="{teacher: false}" :items='items' />
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
