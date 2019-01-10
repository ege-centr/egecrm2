<template>
  <div>
    <TestClientList :client-id='$store.state.user.id'  />
  </div>
</template>

<script>
import TestClientList from '@/components/Test/Client/List'

export default {
  components: { TestClientList },
}
</script>
