<template>
  <div class='timeline__week' :class="{
    'timeline__week_regular': regular === '',
  }">
    <TimelineDay 
      v-for='weekday in weekdays' 
      :key='weekday' 
      :items='getItemsByWeekday(weekday)'
      :class="{
        'mr-3': weekday === 5,
        'mr-1': weekday,
      }"
      :current-class='currentClass'
      :show-date='showDate'
      :weekday='weekday'
      :show-weekday='showWeekday'
    />
  </div>
</template>


<script>

import TimelineDay from './Day'

export default {
  props: {
    items: {
      type: Array,
      required: true,
    },

    showDate: {
      type: Boolean,
      required: false,
      default: false,
    },

    showWeekday: {
      type: Boolean,
      required: false,
      default: false,
    },

    currentClass: {
      type: String,
      default: 'timeline__day__interval_current',
      required: false,
    },

    regular: {
      type: String,
      required: false,
    }
  },

  components: { TimelineDay },

  data() {
    return {
      weekdays: [1, 2, 3, 4, 5, 6, 0],
    }
  },

  methods: {
    getItemsByWeekday(weekday) {
      return this.items.filter(e => moment(e.date).day() === weekday)
    }
  },
}
</script>

<style lang="scss">

.timeline {
  &__week {
    display: flex;
    align-items: center;
    width: 100%;
    &_regular {
      filter: grayscale(100%);
    }
  }
}
</style>

