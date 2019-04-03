<template>
  <div>
    <ClientSchedule :client-id='$store.state.user.id' :display-options='{price: false}' />
  </div>
</template>

<script>
import ClientSchedule from '@/components/Client/Schedule'

export default {
  components: { ClientSchedule },
  
}
</script>
