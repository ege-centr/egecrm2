<template>
  <div>
    <DisplayData :api-url='API_URL'
      :tabs='true'
      :invisible-filters="{teacher_id: $store.state.user.id}">
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
