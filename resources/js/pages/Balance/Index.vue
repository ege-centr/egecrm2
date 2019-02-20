<template>
  <Balance 
    :display-options="{created_at: false}"
    :entity-type='$store.state.user.class'
    :entity-id='$store.state.user.id'  />
</template>

<script>
import Balance from '@/components/Balance/Balance'

export default {
  components: { Balance },
}
</script>
