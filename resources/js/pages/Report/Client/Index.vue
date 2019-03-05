<template>
  <div>
    <DisplayData 
      :invisible-filters="{
        entity_id: $store.state.user.id,
        is_available_for_parents: 1,
        exists: 1,
      }"
      :tabs="{data: 'years', field: 'year'}"
      :api-url='API_URL' 
      :filters='FILTERS'>
      <template slot='items' slot-scope='{ items }'>
        <ReportList :display-options="{
          id: false,
          is_available_for_parents: false,
          client: false,
          actions: false,
        }" :items='items' />
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
