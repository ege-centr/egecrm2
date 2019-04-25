<template>
  <div>
    <TaskDialog ref='TaskDialog' @updated="$emit('updated')" />
    <TaskItem v-for='item in items' :item='item' :key='item.id' @edit='(id) => $refs.TaskDialog.open(id)' class='mb-4' />
  </div>
</template>
<script>

import { TaskDialog, TaskItem } from './'

export default {
  props: {
    items: {
      type: Array,
      required: true
    },
  },

  components: { TaskDialog, TaskItem },
}
</script>
