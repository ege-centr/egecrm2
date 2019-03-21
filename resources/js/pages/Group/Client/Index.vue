<template>
  <div>
    <DisplayDataAlgolia 
      :tabs='true'
      :api-url='API_URL' 
      :paginate='30' 
      :invisible-filters="{client_ids: $store.state.user.id}">
      <template slot='items' slot-scope="{ items }">
        <GroupList :items='items' />
      </template>
    </DisplayDataAlgolia>
  </div>
</template>

<script>

import DisplayDataAlgolia from '@/components/UI/DisplayDataAlgolia'
import { API_URL } from '@/components/Group'
import GroupList from '@/components/Group/List'

export default {
  components: { DisplayDataAlgolia, GroupList },
  
  data() {
    return {
      API_URL,
    }
  },
}
</script>
