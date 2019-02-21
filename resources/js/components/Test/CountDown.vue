<template>
  <span v-if='interval !== null'>
    {{ timeLeft.format("mm:ss") }}
  </span>
</template>


<script>
export default {
  props: {
    from: {
      type: String,
      required: true,
    },
    minutes: {
      type: Number,
      required: false,
      default: 30,
    }
  },

  data() {
    return {
      now: moment(),
      interval: null,
    }
  },

  created() {
    this.interval = setInterval(() => this.now = moment(), 1000)
  },

  methods: {
    end() {
      clearInterval(this.interval)
      this.interval = null
      this.$emit('end')
    }
  },

  computed: {
    timeLeft() {
      const timeFrom = moment(this.from).toDate().getTime()
      const timeNow = this.now.toDate().getTime()
      const minutes = moment.duration(this.minutes, 'minute').valueOf()
      const timestamp = minutes - (timeNow - timeFrom)
      if (timestamp < 1000) {
        this.end()
      }
      return moment(timestamp).utcOffset(-180)
    },
  }
}
</script>
