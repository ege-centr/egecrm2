<template>
  <div>
    <DisplayData 
      ref='DisplayData'
      :api-url='API_URL' 
      :tabs="{data: 'years', field: 'year'}"
    >
      <template slot='items' slot-scope='{ items }'>
        <ReviewClientList :items='items' @updated='() => $refs.DisplayData.loadData()' />
      </template>
    </DisplayData>
  </div>
</template>

<script>

import { DisplayData } from '@/components/UI'
import { API_URL } from '@/components/Review'
import ReviewClientList from '@/components/Review/Client/List'

export default {
  components: { DisplayData, ReviewClientList },

  data() {
    return {
      API_URL,
    }
  },
  
}
</script>
