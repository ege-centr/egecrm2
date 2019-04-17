<template>
  <div class='timeline__day' :class="{'mt-2': showWeekday}">
    <div class='timeline__day__label grey--text' v-if='showWeekday'>
      {{ DAY_LABELS[weekday] }}
    </div>
    <div 
      v-for='(item, index) in items' 
      :key='index'
    >
      <v-tooltip bottom>
        <template v-slot:activator='{ on }'>
          <div 
            v-on='on'
            class="timeline__day__interval"
            :class="{
              [currentClass]: item.is_current,
              [`timeline__day__interval_${item.status}`]: item.status,
              'timeline__day__interval_overlaps': overlaps(item, index),
            }"
            :style="getGetStyle(item)"
          >
          </div>
        </template>
        <span>
          <div v-if="showDate" class='text-sm-center font-weight-medium'>
            {{ item.date | date }}
          </div>
          {{ item.start }}–{{ item.end }}
        </span>
      </v-tooltip>
    </div>
  </div>
</template>

<script>

import { DAY_LABELS } from './'

export default {
  props: {
    currentClass: {
      type: String,
      default: 'timeline__day__interval_current',
      required: false,
    },

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

    weekday: {
      type: Number,
      required: false,
    },
  },

  data() {
    return {
      DAY_LABELS,
      timeMin: '10:00',
      timeMax: '21:00',
    }
  },
  
  methods: {
    getGetStyle(item) {
      const startPercent = this.getPercent(item.start)
      const endPercent = this.getPercent(item.end)
      return {
        left: startPercent + '%',
        width: (endPercent - startPercent) + '%'
      }
    },

    getPercent(time) {
      return this.getNormalizedTimestamp(time) * 100 / this.maxTimestamp 
    },

    getTimestamp(time) {
      return Date.parse(`1970-01-01 ${time}:00 GMT`) / 1000
    },

    getNormalizedTimestamp(time) {
      return this.getTimestamp(time) - this.getTimestamp(this.timeMin)
    },

    getRange(item) {
      return moment.range(this.getTimestamp(item.start), this.getTimestamp(item.end))
    },

    // время пересекается с другим
    overlaps(item, index) {
      const range = this.getRange(item)
      let overlaps = false
      this.items.forEach((item2, index2) => {
        if (index !== index2 && !overlaps) {
          overlaps = range.overlaps(this.getRange(item2))
        }
      })
      return overlaps
    },
  },
  
  computed: {
    maxTimestamp() {
      return this.getNormalizedTimestamp(this.timeMax)
    },
  },
}
</script>

<style lang="scss">

.timeline {
  $height: 12px;
  &__day {
    display: inline-block;
    position: relative;
    flex: 1;
    height: $height;
    width: 100%;
    background: #C1D2DD;
    &__label {
      font-size: 12px; 
      white-space: nowrap; 
      position: absolute;
      top: -20px;
      left: 4px;
    }
    &__interval {
      left: 20%;
      width: 20%;
      background: #3A5162;
      position: absolute;
      height: 100%;
      &_current {
        z-index: 1;
        height: $height + 10px;
        top: -5px;
      }
      &_conducted {
        background: #4caf50;
      }
      &_planned {
        background: #2196f3;
      }
      &_overlaps {
        animation: blink 1s step-start 0s infinite;
      }
      &:hover {
        &::before {
          content: '';
          position: absolute;
          background: white;
          height: 100%;
          width: 100%;
          opacity: .25;
        }
      }
    }
  }
}
</style>
