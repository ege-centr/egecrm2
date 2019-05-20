<template>
  <FilterTypeBase 
    :apply-enabled='value.length > 0'
  >
    <v-list dense style='min-width: 300px' class='v-list_no-transitions'>
      <v-list-tile 
        :class="{
          'primary white--text': value.indexOf(option[idField]) !== -1
        }" 
        @click='selectMultiple(option)' 
        v-for='(option, index) in availableAdmins' :key='index'>
        <v-list-tile-title :class="{
          'grey--text': option.is_banned,
        }">
          {{ option.default_name }}
          <span style='float: right' 
            v-if='facet !== null && facet[option[idField]] > 0'
            :class="{'grey--text': value.indexOf(option[idField]) === -1}">
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

  computed: {
    availableAdmins() {
      const options = this.$store.state.data.admins
      if (this.facet !== null) {
        return options.filter(option => this.facet[option[this.idField]] > 0)
      }
      return options
    }
  }
}
</script>
