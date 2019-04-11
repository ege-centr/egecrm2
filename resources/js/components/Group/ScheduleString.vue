<template>
  <span>
    <span v-for='(item, index) in items' :key='index'>
      {{ labels[item.weekday] }}
      {{ item.start }}{{ (index === items.length - 1) ? '' : ', ' }}
    </span>
  </span>
</template>

<script>
export default {
  props: ['items'],

  data() {
    return {
      labels: {
        1: 'ПН',
        2: 'ВТ',
        3: 'СР',
        4: 'ЧТ',
        5: 'ПТ',
        6: 'СБ',
        0: 'ВС',
      }
    }
  }, 
}
</script>

