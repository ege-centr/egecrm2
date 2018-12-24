<template>
  <v-menu transition="slide-y-transition">
    <div slot='activator' style='pointer-events: none'>
      <v-select hide-details label='Год и класс' item-value='id' item-text='title'
        :items='$store.state.data.grades' :value='grade_id' />
    </div>
    <v-list dense>
      <v-list-tile v-for='grade in $store.state.data.grades' :key='grade.id' @click="select('grade_id', grade.id)">
        <v-list-tile-title>{{ grade.title }}</v-list-tile-title>
        <v-list-tile-action class='justify-end' v-if='item.grade_id === grade.id'>
          <v-icon>check</v-icon>
        </v-list-tile-action>
      </v-list-tile>
      <v-list-tile style='height: 0; border-bottom: 1px solid #8c8c8c'>
      </v-list-tile>
      <v-list-tile v-for='year in $store.state.data.years' :key='year.value' @click="select('year', year.value)">
        <v-list-tile-title>{{ year.text }}</v-list-tile-title>
        <v-list-tile-action class='justify-end' v-if='item.year === year.value'>
          <v-icon>check</v-icon>
        </v-list-tile-action>
      </v-list-tile>
    </v-list>
  </v-menu>
</template>

<script>
export default {
  props: ['item'],

  data() {
    return {
      grade_id: null,
    }
  },

  created() {
    this.updateLabel()
  },

  methods: {
    select(field, value) {
      if (this.item[field] === value) {
        this.item[field] = null
      } else {
        this.item[field] = value
      }
      this.updateLabel()
    },

    updateLabel() {
		  if (!this.item.grade_id) {
		    this.grade_id = null
		  }
		  if (this.item.year > 0 && this.item.grade_id < 12) {
		    let years_passed = 2018 - this.item.year
		    let new_grade = parseInt(this.item.grade_id) + years_passed
			  this.grade_id = new_grade > 12 ? 12 : new_grade
		  } else {
        this.grade_id = this.item.grade_id
      }
    },
  },
}
</script>
