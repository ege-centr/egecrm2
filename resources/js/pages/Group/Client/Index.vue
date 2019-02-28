<template>
  <div>
    <DisplayData 
      :tabs="{data: 'years', field: 'year'}"
      :api-url='API_URL' 
      :paginate='30' 
      :invisible-filters="{client_id: $store.state.user.id}">
      <template slot='items' slot-scope="{ items }">
        <GroupList :items='items' />
      </template>
    </DisplayData>
  </div>
</template>

<script>

import { DisplayData } from '@/components/UI'
import { API_URL } from '@/components/Group'
import GroupList from '@/components/Group/List'

export default {
  components: { DisplayData, GroupList },
  
  data() {
    return {
      API_URL,
    }
  },
  
}
</script>
