<template>
  <div>
    <GroupDialog ref='GroupDialog' />
    <DisplayData :api-url='API_URL' :filters='FILTERS' :paginate='50'>
      <template slot='buttons'>
        <GroupHelper />
        <AddBtn @click.native='$refs.GroupDialog.open(null)' animated label='добавить группу' />
      </template>
      <template slot='items' slot-scope="{ items }">
        <GroupList v-if='items.length > 0' :items='items' />
        <NoData 
          :add='() => $refs.GroupDialog.open(null)'
          transparent 
          v-else />
      </template>
    </DisplayData>
  </div>
</template>

<script>

import DisplayData from '@/components/UI/DisplayData'
import { API_URL, FILTERS } from '@/components/Group'
import GroupList from '@/components/Group/List'
import GroupDialog from '@/components/Group/Dialog'
import GroupHelper from '@/components/Group/Helper'

export default {
  components: { DisplayData, GroupList, GroupDialog, GroupHelper },
  
  data() {
    return {
      API_URL,
      FILTERS,
    }
  },
}
</script>
