<template>
  <div>
    <DisplayData 
      :tabs='true'
      :api-url='PAYMENT_ADDITIONAL_API_URL' 
      :invisible-filters="{entity_id: $store.state.user.id, entity_type: $store.state.user.class}"
    >
      <template slot='items' slot-scope='{ items }'>
        <PaymentAdditionalList 
          :items='items' 
          :display-options='{edit: false}'
        />
      </template>
    </DisplayData>
  </div>
</template>

<script>
import DisplayData from '@/components/UI/DisplayData'
import {
  API_URL as PAYMENT_ADDITIONAL_API_URL,
  PaymentAdditionalList,
} from '@/components/Payment/Additional'

export default {
  components: { DisplayData, PaymentAdditionalList },

  data() {
    return {
      PAYMENT_ADDITIONAL_API_URL
    }
  },
  
}
</script>
