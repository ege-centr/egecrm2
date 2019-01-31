<template>
  <FilterTypeBase 
    :apply-button='true'
    :apply-enabled='value.length > 0'
  >
    <v-list dense style='min-width: 300px'>
      <v-list-tile @click='select(option)' v-for='(option, index) in item.options' :key='index'>
        <v-list-tile-title>
          {{ option[titleField] }} 
          <v-icon style='float: right' v-if='value.indexOf(option[idField]) !== -1'>check</v-icon>
        </v-list-tile-title>
      </v-list-tile>
    </v-list>
  </FilterTypeBase>
</template>

<script>
import FilterTypeBase from './Base'
import { FilterTypeMixin } from '@/mixins'

export default {
  components: { FilterTypeBase },

  mixins: [ FilterTypeMixin ],

  data() {
    return {
      value: [],
    }
  },
  
  methods: {
    select(option) {
      const value = option[this.idField]
      const value_index = this.value.indexOf(value)
      if (value_index === -1) {
        this.value.push(value)
      } else {
        this.value.splice(value_index, 1)
      }
    },
  }
}
</script>
