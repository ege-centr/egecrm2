<template>
  <div>
    <div v-for='weekday in weekdays' :key='weekday' :class="{'mr-1': weekday}" class='timeline__wrapper'>
      <div class='timeline'>
        <div 
          v-for='(item, index) in getItemsByWeekday(weekday)' 
          :key='index' >
          <v-tooltip bottom>
            <template v-slot:activator='{ on }'>
              <div 
                v-on='on'
                class="timeline__interval"
                :style="getGetStyle(item)"
              >
              </div>
            </template>
            <span>
              {{ item.start }}–{{ item.end }}
            </span>
          </v-tooltip>
        </div>
      </div>
    </div>
  </div>
</template>


<script>

export default {
  props: {
    items: {
      type: Array,
      required: true,
    }
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
    }
  }
}
</script>

<style lang="scss">
.timeline {
  height: 12px;
  width: 100%;
  background: #C1D2DD;
  position: relative;
  &__interval {
    left: 20%;
    width: 20%;
    background: #3A5162;
    position: absolute;
    height: 100%;
    &:hover {
      background: rgb(80, 105, 124);
    }
  }
  &__wrapper {
    display: inline-block;
    width: 50px;
    position: relative;
  }
}
</style>

