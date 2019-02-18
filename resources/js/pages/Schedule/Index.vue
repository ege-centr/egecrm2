<template>
  <div>
    <ClientSchedule :client-id='$store.state.user.id' />
  </div>
</template>

<script>
import ClientSchedule from '@/components/Client/Schedule'

export default {
  components: { ClientSchedule },
  
}
</script>
