<template>
  <div>
    <DisplayData 
      ref='DisplayData'
      :api-url='API_URL' 
      :paginate='30'
    >
      <template slot='items' slot-scope='{ items }'>
        <ReviewAdminList :items='items' />
      </template>
    </DisplayData>
  </div>
</template>

<script>

import { DisplayData } from '@/components/UI'
import { API_URL } from '@/components/Review'
import ReviewAdminList from '@/components/Review/Admin/List'

export default {
  components: { DisplayData, ReviewAdminList },

  data() {
    return {
      API_URL,
    }
  },
  
}
</script>