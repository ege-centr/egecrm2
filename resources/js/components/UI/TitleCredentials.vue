<template>
  <div class='title-credentials' v-if="item !== null && (userField in item) && (timeField in item)">
    {{ item[userField].default_name }}
    {{ item[timeField] | date-time }}
  </div>
</template>

<script>
export default {
  props: {
    item: {
      required: true,
    },
    userField: {
      type: String,
      default: 'createdUser'
    },
    timeField: {
      type: String,
      default: 'created_at'
    }
  }
}
</script>

