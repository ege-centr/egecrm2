<template>
  <span class='grey--text'>
    {{ getData('admins', item[field]).name }}
    {{ item.created_at | date-time }}
  </span>
</template>

<script>
export default {
  props: {
    item: {
      type: Object,
      required: true,
    },
    field: {
      type: String,
      default: 'created_admin_id',
      required: false,
    }
  }
}
</script>
