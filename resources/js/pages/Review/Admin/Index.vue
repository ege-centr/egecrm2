<template>
  <div>
    <DisplayData 
      ref='DisplayData'
      :api-url='API_URL' 
      :paginate='30'
      :filters='FILTERS'
    >
      <template slot='items' slot-scope='{ items }'>
        <ReviewAdminList :items='items' @updated='() => $refs.DisplayData.reloadData()' v-if='items.length > 0' />
        <NoData
          v-else
          transparent
        />
      </template>
    </DisplayData>
  </div>
</template>



<script>
import { DisplayData } from '@/components/UI'
import { API_URL, FILTERS } from '@/components/Review'
import ReviewAdminList from '@/components/Review/Admin/List'

export default {
  components: { DisplayData, ReviewAdminList },

  data() {
    return {
      API_URL,
      FILTERS,
    }
  },
  
}
</script>