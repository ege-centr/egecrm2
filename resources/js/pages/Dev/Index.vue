<template>
  <div>
    <h1>DEV</h1>

    <div class='flex-items'>
      <AllFilter :items="[{label: 'Период', field: 'created_at', type: 'interval'}]" />
      <AddBtnAnimated label='максимально замалымно' />
    </div>
  </div>
</template>

<script>

import AddBtnAnimated from '@/components/UI/AddBtnAnimated'
import { AllFilter } from '@/components/Filter'

export default {
  components: { AddBtnAnimated, AllFilter },
  data() {
    return {
     
    }
  },

 
}
</script>
