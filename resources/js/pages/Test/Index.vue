<template>
  <div>
    <ClientIndex v-if='$store.state.user.class === CLIENT_CLASS_NAME' />
    <AdminIndex v-else />
  </div>
</template>

<script>
import AdminIndex from './Admin/Index'
import ClientIndex from './Client/Index'
import { CLASS_NAME as CLIENT_CLASS_NAME } from '@/components/Client'

export default {
  data() {
    return {
      CLIENT_CLASS_NAME,
    }
  },
  
  components: { AdminIndex,  ClientIndex },
}
</script>
