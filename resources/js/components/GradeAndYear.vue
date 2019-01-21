<template>
  <v-menu transition="slide-y-transition">
    <div slot='activator' style='pointer-events: none'>
      <v-select hide-details label='Класс и год' item-value='id' item-text='title'
        :items='$store.state.data.grades' :value='item.grade_id'>
        <template slot="selection" slot-scope="{ item }">
          <span>{{ label }}</span>
        </template>
      </v-select>
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

export const LABEL_TYPES = {
  CALCULATED_GRADE: 'CALCULATED_GRADE',
  GRADE_AND_YEAR: 'GRADE_AND_YEAR',
}

export default {
  props: {
    item: {
      type: Object,
      required: true,
    },
    labelType: {
      type: String,
      default: LABEL_TYPES.CALCULATED_GRADE,
    }
  },

  data() {
    return {
      label: null,
    }
  },

  created() {
    Vue.nextTick(() => this.updateLabel())
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
      switch(this.labelType) {
        case LABEL_TYPES.CALCULATED_GRADE:
          if (!this.item.grade_id) {
            this.label = null
          }
          if (this.item.year > 0 && this.item.grade_id < 12) {
            let years_passed = 2018 - this.item.year
            let new_grade = parseInt(this.item.grade_id) + years_passed
            this.label = this.getData('grades', new_grade > 12 ? 12 : new_grade).title
          } else {
            this.label = this.getData('grades', this.item.grade_id).title
          }
          break
        case LABEL_TYPES.GRADE_AND_YEAR:
          const label = []
          if (this.item.grade_id) {
            label.push(this.getData('grades', this.item.grade_id).title)
          }
          if (this.item.year) {
            label.push(this.getData('years', this.item.year).text)
          }
          this.label = label.join(', ')
          break
      }
    },
  },
}
</script>
