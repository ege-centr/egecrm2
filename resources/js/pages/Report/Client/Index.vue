<template>
  <div>
    <DisplayData 
      :invisible-filters="{
        client_id: $store.state.user.id,
        is_available_for_parents: 1,
        exists: 1,
      }"
      :tabs='true'
      :api-url='API_URL' 
    >
      <template slot='items' slot-scope='{ items }'>
        <ReportList 
          :display-options="{
            id: false,
            is_available_for_parents: false,
            price: false,
            client: false,
            actions: false,
          }" 
          :items='items' 
          v-if='items.length > 0'
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
