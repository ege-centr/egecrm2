<template lang="html">
  <div>
    <v-container grid-list-md fluid class="px-0">
      <PaymentList />
    </v-container>
  </div>
</template>

<script>

import PaymentList from '@/components/Payment/List'

export default {
  components: { PaymentList }
}
</script>
