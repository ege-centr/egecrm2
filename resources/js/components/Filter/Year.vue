<template>
  <div>
    <v-chip v-for="year in $store.state.data.years" class='pointer ml-0 mr-3'
      :class="{'primary white--text': year.id == selected_year}"
      @click='setYear(year.id)'
      :key='year.id'>{{ year.title }}</v-chip>
    <!-- <div style='border-bottom: 1px solid red; padding-bottom: 100px'></div> -->
  </div>
</template>


<script>
export default {
  data() {
    return {
      selected_year: null,
    }
  },

  created() {
    this.setYear(this.$store.state.data.academic_year)
  },

  methods: {
    setYear(year) {
      if (year !== this.selected_year) {
        this.selected_year = year
        this.$emit('updated', { year })
      }
    },
  }
  
}
</script>
