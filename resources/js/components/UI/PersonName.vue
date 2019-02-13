<template>
  <span>{{ hasName ? item.names[field] : 'пусто' }}</span>
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
      default: 'short',
      required: false,
    }
  },

  computed: {
    hasName() {
      return this.item.names.full.replace(/ /g, '').length > 0
    },
  }
}
</script>
