<template>
  <div>
    <GroupDialog ref='GroupDialog' />
    <IndexPage :api-url='API_URL' :filters='FILTERS' :pagination='false'>
      <template slot='buttons'>
        <AddBtn @click.native='$refs.GroupDialog.open(null)' label='добавить группу' />
      </template>
      <template slot='items' slot-scope="{ items }">
        <GroupList :items='items' />
      </template>
    </IndexPage>
  </div>
</template>

<script>

import { IndexPage } from '@/components/UI'
import { GroupList, GroupDialog, API_URL, FILTERS } from '@/components/Group'

export default {
  components: { IndexPage, GroupList, GroupDialog },
  
  data() {
    return {
      API_URL,
      FILTERS,
    }
  },
  
}
</script>
