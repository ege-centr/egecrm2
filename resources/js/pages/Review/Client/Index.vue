<template>
  <div>
    <DisplayData :api-url='API_URL' :tabs="{data: 'years', field: 'year'}">
      <template slot='items' slot-scope='{ items }'>
        <ReviewClientList :items='items' />
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
