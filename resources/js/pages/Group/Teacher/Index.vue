<template>
  <div>
    <GroupDialog ref='GroupDialog' />
    <DisplayData :api-url='API_URL' :paginate='30' :invisible-filters="{teacher_id: $store.state.user.id}">
      <template slot='items' slot-scope="{ items }">
        <GroupList :items='items' />
      </template>
    </DisplayData>
  </div>
</template>

<script>

import { DisplayData } from '@/components/UI'
import { GroupList, API_URL } from '@/components/Group'

export default {
  components: { DisplayData, GroupList },
  
  data() {
    return {
      API_URL,
    }
  },
  
}
</script>
