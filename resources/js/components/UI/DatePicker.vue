<template>
  <v-menu
    ref="datepicker"
    :close-on-content-click="false"
    :return-value.sync="date"
    lazy
    transition="scale-transition"
    offset-y
    full-width
    min-width="290px"
    v-model='menu'
  >
    <v-text-field 
      :error-messages='errorMessages'
      :hide-details='errorMessages === undefined'
      slot="activator"
      v-model='dateFormatted'
      @keyup='handleManualInput'
      :readonly="readonly"
      :label="label"
      v-mask="'##.##.####'"
    ></v-text-field>
    <v-date-picker no-title
      locale='ru'
      :readonly="readonly"
      v-model="date"
      :first-day-of-week='1'
      @input="$refs.datepicker.save(date)">
    </v-date-picker>
  </v-menu>
</template>

<script>
export default {
  props: {
    value: {},
    label: {},
    readonly: {
      type: Boolean,
      default: false,
      required: false,
    },
    errorMessages: {
      default: undefined,
    },
  },

  data() {
    return {
      menu: false,
      date: null,
      dateFormatted: null,
    }
  },

  created() {
    this.date = this.value
    this.dateFormatted = this.formatDate()
  },
  
  methods: {
    formatDate() {
      if (!this.date) {
        return null
      }
      return moment(this.date).format('DD.MM.YYYY')
    },

    parseDateFormatted() {
      const [day, month, year] = this.dateFormatted.split('.')
      return moment([year, month, day].join('-')).format('YYYY-MM-DD')
    },

    handleManualInput() {
      if (this.dateFormatted.length > 0) {
        this.menu = false
        if (this.dateFormatted.length === 10) {
          this.$emit('input', this.parseDateFormatted())
        }
      } else {
        this.menu = true
        this.$emit('input', null)
      }
    }
  },

  watch: {
    date(val) {
      this.dateFormatted = this.formatDate()
      if (val !== undefined) {
        this.$emit('input', this.date)
      }
    }
  }
}
</script>
