<template>
  <FilterTypeBase>
    <v-list dense style='min-width: 300px'>
      <v-list-tile @click='select(option)' v-for='(option, index) in $store.state.data.admins' :key='index'>
        <v-list-tile-title :class="{'grey--text': option.is_banned}">{{ option.name }}</v-list-tile-title>
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
      this.value = option.id
      this.apply()
    }
  }
}
</script>
