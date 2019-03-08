<template>
  <div>
    <ConfirmPasswordDialog 
      v-if='!PreviewMode.isActive()' 
      @confirmed='confirmed = true' />
    <Balance v-if='PreviewMode.isActive() || confirmed'
      :display-options="{created_at: false}"
      :entity-type='$store.state.user.class'
      :entity-id='$store.state.user.id'  />
  </div>
</template>

<script>
import Balance from '@/components/Balance/Balance'
import ConfirmPasswordDialog from '@/components/ConfirmPasswordDialog'
import PreviewMode from '@/other/PreviewMode'

export default {
  components: { Balance, ConfirmPasswordDialog },

  data() {
    return {
      PreviewMode,
      confirmed: false,
    }
  },
  
}
</script>
