<template>
  <div>
    <GroupDialog ref='GroupDialog' />
    <DisplayDataAlgolia :api-url='API_URL' :filters='FILTERS' :paginate='50'>
      <template slot='buttons'>
        <AddBtn @click.native='$refs.GroupDialog.open(null)' animated label='добавить группу' />
      </template>
      <template slot='items' slot-scope="{ items }">
        <GroupList :items='items' />
      </template>
    </DisplayDataAlgolia>
  </div>
</template>

<script>

import DisplayDataAlgolia from '@/components/UI/DisplayDataAlgolia'
import { API_URL, FILTERS } from '@/components/Group'
import GroupList from '@/components/Group/List'
import GroupDialog from '@/components/Group/Dialog'

export default {
  components: { DisplayDataAlgolia, GroupList, GroupDialog },
  
  data() {
    return {
      API_URL,
      FILTERS,
    }
  },
}
</script>
