<template>
  <FilterTypeBase 
    :apply-enabled='value.length > 0'
  >
    <v-list dense style='min-width: 300px' class='v-list_no-transitions'>
      <v-list-tile :class="{
        'primary white--text': value.indexOf(option[idField]) !== -1
      }" 
        @click='selectMultiple(option)' v-for='(option, index) in item.options' :key='index'>
        <v-list-tile-title>
          {{ getTitle(option) }} 
          <span style='float: right' :class="{'grey--text': value.indexOf(option[idField]) === -1}" v-if='facet !== null && facet[option[idField]] > 0'>
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

  data() {
    return {
      value: [],
    }
  },
  
  methods: {
    getTitle(option) {
      return _.get(option, this.titleField)
    },

    // getSelectedLabel(filter) {
    //   const label = []
    //   if (Array.isArray(filter.value)) {
    //     filter.value.forEach(v => {
    //       label.push(_.get(
    //         filter.item.options.find(e => e.id === v), 
    //         filter.item.textField || 'title'
    //       ))
    //     })
    //   }
    //   return label.join(', ')
    // },
  }
}
</script>
