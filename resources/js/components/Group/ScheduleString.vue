<template>
  <span>
    <span v-for='(item, index) in items' :key='index'>
      {{ getDayLabel(item) }}
      {{ item.start }}{{ (index === items.length - 1) ? '' : ', ' }}
    </span>
  </span>
</template>

<script>

import { DAY_LABELS } from '@/components/Timeline'

export default {
  props: ['items'],

  methods: {
    getDayLabel(item) {
      return DAY_LABELS[moment(item.date).day()]
    },
  },
}
</script>

