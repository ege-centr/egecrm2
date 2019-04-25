<template>
  <div>
    <TaskDialog ref='TaskDialog' @updated="$refs.DisplayData.reloadData()" />
    <DisplayData ref='DisplayData' :api-url='API_URL' :filters='FILTERS' :paginate='15'>
      <template slot='buttons'>
        <AddBtn animated label='добавить задачу' @click.native='$refs.TaskDialog.open(null)' />
      </template>
      
      <template slot='items' slot-scope='{ items }'>
        <TaskList 
          v-if='items.length > 0'
          :items='items' 
          @updated="$refs.DisplayData.reloadData()"
        />
        <NoData 
          v-else
          transparent
          :add='() => $refs.TaskDialog.open(null)'
        />
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
