<template>
  <div>
    
    <TaskDialog ref='TaskDialog' />
    
    <DisplayData :api-url='API_URL' :filters='FILTERS' :paginate='15'>
      <template slot='buttons'>
        <AddBtn animated label='добавить задачу' @click.native='$refs.TaskDialog.open(null)' />
      </template>
      
      <template slot='items' slot-scope='{ items }'>
        <TaskList :items='items' />
      </template>
    </DisplayData>
  </div>
</template>

<script>

import { DisplayData } from '@/components/UI'
import { API_URL, FILTERS, TaskList, TaskDialog } from '@/components/Task'

export default {
  components: { DisplayData, TaskList, TaskDialog },

  data() {
    return {
      API_URL,
      FILTERS,
    }
  },
}
</script>
