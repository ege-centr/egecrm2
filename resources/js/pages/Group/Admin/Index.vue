<template>
  <div>
    <GroupDialog ref='GroupDialog' />
    <DisplayData :api-url='API_URL' :filters='FILTERS' :paginate='30'>
      <template slot='buttons'>
        <AddBtn @click.native='$refs.GroupDialog.open(null)' animated label='добавить группу' />
      </template>
      <template slot='items' slot-scope="{ items }">
        <GroupList :items='items' />
      </template>
    </DisplayData>
  </div>
</template>

<script>

import { DisplayData } from '@/components/UI'
import { API_URL, FILTERS } from '@/components/Group'
import GroupList from '@/components/Group/List'
import GroupDialog from '@/components/Group/Dialog'

export default {
  components: { DisplayData, GroupList, GroupDialog },
  
  data() {
    return {
      API_URL,
      FILTERS,
    }
  },
  
}
</script>
