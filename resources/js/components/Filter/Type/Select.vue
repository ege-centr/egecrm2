<template>
  <FilterTypeBase :actions='false'>
    <v-list dense style='min-width: 220px'>
      <v-list-tile @click='select(option)' v-for='(option, index) in item.options' :key='index'>
        <v-list-tile-title>{{ option[titleField] }}</v-list-tile-title>
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

  methods: {
    select(option) {
      this.value = option[this.idField]
      this.apply()
    },
  }
}
</script>