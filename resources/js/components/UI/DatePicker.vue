<template>
  <v-menu
    :close-on-content-click="false"
    v-model="menu"
    :nudge-right="40"
    lazy
    transition="scale-transition"
    offset-y
    full-width
    
  >
    <v-text-field
      slot="activator"
      v-model="dateFormatted"
      :label="label"
      hide-details
      persistent-hint
      @blur="date = parseDate(dateFormatted)"
    ></v-text-field>
    <v-date-picker v-model="date" no-title @input="menu = false"></v-date-picker>
  </v-menu>
</template>

<script>
export default {
  props: ['date', 'label'],

  data() {
    return {
      menu: false,
      dateFormatted: null,
    }
  },

  created() {
    this.dateFormatted = this.formatDate(this.date)
  },
  
  methods: {
    formatDate(date) {
      if (!date) {
        return null
      }
      colorLog(`${date} => ` + moment(date).format('DD.MM.YYYY'), 'DarkOliveGreen')
      return moment(date).format('DD.MM.YYYY')
    },

    parseDate(date) {
      if (!date) {
        return null
      }
      const [month, day, year] = date.split('.')
      colorLog(`${date} => ` + moment([year, month, day].join('-')).format('YYYY-MM-DD'), 'DeepPink')
      return moment([year, month, day].join('-')).format('YYYY-MM-DD')
    }
  },

  watch: {
    date(val) {
      this.dateFormatted = this.formatDate(this.date)
    }
  }
}
</script>
