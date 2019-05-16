<template>
  <FilterTypeBase :apply-button='false'>
    <v-list dense style='min-width: 220px'>
      <v-list-tile @click='select(option)' v-for='(option, index) in availableOptions' :key='index'>
        <v-list-tile-title>
          {{ option[titleField] }}
           <span class='grey--text' style='float: right' 
              v-if='facet !== null && facet[option[idField]] > 0'>
              {{ facet[option[idField]] }}
            </span>
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
}
</script>