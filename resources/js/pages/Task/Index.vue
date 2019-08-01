<template>
  <div>
    <DisplayData ref='DisplayData' :api-url='API_URL' :filters='FILTERS' :paginate='15'>
      <template slot='buttons'>
        <AddBtn animated label='добавить задачу' @click.native='$refs.TaskDialog.open(null)' />
      </template>
      
      <template slot='items' slot-scope='{ items }'>
        <div v-if='items.length > 0'>
          <TaskItem 
            v-for='item in items' 
            :item='item' 
            :key='item.id'
            @edit='$refs.TaskDialog.open' 
            class='mb-4' 
          />
        </div>
        <NoData 
          v-else
          box
          :add='() => $refs.TaskDialog.open(null)'
        />
      </template>
    </DisplayData>
    <TaskDialog ref='TaskDialog' 
      @updated='(payload) => $refs.DisplayData.updateItem(payload)'
    />
  </div>
</template>

<script>

import { DisplayData } from '@/components/UI'
import { API_URL, FILTERS, TaskItem, TaskDialog } from '@/components/Task'

export default {
  components: { DisplayData, TaskItem, TaskDialog },

  data() {
    return {
      API_URL,
      FILTERS,
    }
  },
}
</script>
