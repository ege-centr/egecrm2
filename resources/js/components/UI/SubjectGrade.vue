<template>
  <span>
    <span v-if='item.subject_id'>{{ getData('subjects', item.subject_id).three_letters }}</span><span v-if='item.subject_id && item.grade_id'>–</span><span v-if='item.grade_id'>{{ getData('grades', item.grade_id).short }}</span>
  </span>
</template>

<script>
export default {
  props: {
    item: {
      type: Object,
      required: true,
    },
  }
}
</script>
