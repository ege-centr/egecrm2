<template>
  <RoleIndex :items='items' />
</template>

<script>
import RoleIndex from '@/components/RoleIndex'
import AdminIndex from './Admin/Index'
import TeacherIndex from './Teacher/Index'
import ClientIndex from './Client/Index'

export default {
  components: { RoleIndex },

  data() {
    return {
      items: {
        AdminIndex,
        TeacherIndex,
        ClientIndex,
      },
    }
  },
}
</script>
