<template>
  <div>
     <DisplayData 
        ref='DisplayData'
        :invisible-filters="{mode: selectedMode}"
        :api-url='API_URL' 
        :filters='filters' 
        :paginate='50'
    >
      <template slot='buttons'>
         <v-chip v-for="(label, id) in TIME_MODE" class='pointer ml-0 mr-3'
            :class="{'primary white--text': id === selectedMode}"
            @click='selectedMode = id'
            :key='id'
          >
            {{ label }}
          </v-chip>
      </template>
      <template slot='items' slot-scope="{ items }">
        <StatList :items='items' :selected-mode='selectedMode' />
      </template>
    </DisplayData>
  </div>
</template>

<script>

import DisplayData from '@/components/UI/DisplayData'
import { TIME_MODE } from '@/other'
import { API_URL } from '@/components/Stat'
import StatList from '@/components/Stat/List'

export default {
  components: { DisplayData, StatList },
  
  data() {
    return {
      TIME_MODE,
      API_URL,
      filters: [
        {label: 'Класс', field: 'grade_id', type: 'multiple', options: this.$store.state.data.grades},
      ],
      selectedMode: 'day',
      selectedFilters: {},
    }
  }, 

  watch: {
      selectedMode(newVal, oldVal) {
        this.$refs.DisplayData.reloadData()
      }
  },
}
</script>
