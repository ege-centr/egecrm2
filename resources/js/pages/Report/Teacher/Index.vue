<template>
  <div>
    <DisplayData 
      :invisible-filters="{'teacher_id': $store.state.user.id}"
      :tabs='true'
      :api-url='API_URL' 
    >
      <template slot='items' slot-scope='{ items }'>
        <ReportList :display-options="{
          teacher: false, 
          client: false,
          dimension: true,
          is_available_for_parents: false,
        }" :items='items' />
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
