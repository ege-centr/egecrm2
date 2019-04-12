<template>
  <div class='timeline__wrapper'>
    <!-- <span v-if='showDates' class='timeline-weekday-label timeline-weekday-label_start mr-2'>
      {{ startOfWeek }}
    </span> -->
    <div v-for='weekday in weekdays' :key='weekday' :class="{
      'mr-3': weekday === 5,
      'mr-1': weekday,
    }" class='timeline__day'>
      <div class='timeline'>
        <div 
          class='timeline__item'
          v-for='(item, index) in getItemsByWeekday(weekday)' 
          :key='index' >
          <v-tooltip bottom>
            <template v-slot:activator='{ on }'>
              <div 
                v-on='on'
                class="timeline__interval"
                :class="{
                  [currentClass]: item.is_current
                }"
                :style="getGetStyle(item)"
              >
              </div>
            </template>
            <span>
              <div v-if="showDates" class='text-sm-center font-weight-medium'>
                {{ item.date | date }}
              </div>
              {{ item.start }}–{{ item.end }}
            </span>
          </v-tooltip>
        </div>
      </div>
    </div>
    <!-- <span v-if='showDates' class='timeline-weekday-label timeline-weekday-label_end ml-2'>
      {{ endOfWeek }}
    </span> -->
  </div>
</template>


<script>

export default {
  props: {
    items: {
      type: Array,
      required: true,
    },

    showDates: {
      type: Boolean,
      required: false,
      default: false,
    },

    currentClass: {
      type: String,
      default: 'timeline__interval_current',
      required: false,
    },
  },

  data() {
    return {
      weekdays: [1, 2, 3, 4, 5, 6, 0],
      timeMin: '10:00',
      timeMax: '21:00',
      // items: [
      //   ['10:30', '11:40'],
      //   ['18:30', '20:40'],
      // ],
    }
  },

  methods: {
    getTimestamp(time) {
      return Date.parse(`1970-01-01 ${time}:00 GMT`) / 1000
    },

    getNormalizedTimestamp(time) {
      return this.getTimestamp(time) - this.getTimestamp(this.timeMin)
    },

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

    getItemsByWeekday(weekday) {
      return this.items.filter(e => e.weekday == weekday)
    }
  },

  computed: {
    maxTimestamp() {
      return this.getNormalizedTimestamp(this.timeMax)
    },

    startOfWeek() {
      return moment(this.items[0].date).startOf('week').format('DD.MM.YY')
    },

    endOfWeek() {
      return moment(this.items[0].date).endOf('week').format('DD.MM.YY')
    },
  }
}
</script>

<style lang="scss">
.timeline {
  $height: 12px;
  height: $height;
  width: 100%;
  background: #C1D2DD;
  position: relative;
  &__interval {
    left: 20%;
    width: 20%;
    background: #3A5162;
    position: absolute;
    height: 100%;
    &_current {
      height: $height + 10px;
      top: -5px;
    }
    &::before {
      content: '';
    }
    &:hover {

      .timeline__interval::before {
        content: '123';
        // position: absolute;
        // background: black;
        // left: 0;
        // top: 0;
        // width: 100%;
        // height: 100%;
      }
      background: rgb(80, 105, 124);
    }
  }
  &__day {
    display: inline-block;
    position: relative;
    flex: 1;
  }
  &__wrapper {
    display: flex;
    align-items: center;
    width: 100%;
  }
}

.timeline-weekday-label {
  font-size: 10px;
  width: 40px;
  top: 1px; 
  position: relative;
  &_start {
    // text-align: right;
  }
}
</style>

