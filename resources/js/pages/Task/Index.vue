<template>
  <div>
    
    <TaskDialog ref='TaskDialog' />
    
    <IndexPage :api-url='API_URL' :filters='FILTERS'>
      <template slot='buttons'>
        <AddBtn label='добавить задачу' @click.native='$refs.TaskDialog.open(null)' />
      </template>
      
      <template slot='items' slot-scope='{ items }'>
        <TaskList :items='items' />
      </template>
    </IndexPage>
  </div>
</template>

<script>

import { IndexPage } from '@/components/UI'
import { API_URL, FILTERS, TaskList, TaskDialog } from '@/components/Task'

export default {
  components: { IndexPage, TaskList, TaskDialog },

  data() {
    return {
      API_URL,
      FILTERS,
    }
  },
}
</script>
