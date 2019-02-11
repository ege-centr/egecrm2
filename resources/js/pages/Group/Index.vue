<template>
  <div>
    <ClientIndex v-if='$store.state.user.class === CLIENT_CLASS_NAME' />
    <TeacherIndex v-if='$store.state.user.class === TEACHER_CLASS_NAME' />
    <AdminIndex v-if='$store.state.user.class === ADMIN_CLASS_NAME' />
  </div>
</template>

<script>
import AdminIndex from './Admin/Index'
import ClientIndex from './Client/Index'
import TeacherIndex from './Teacher/Index'
import { CLASS_NAME as CLIENT_CLASS_NAME } from '@/components/Client'
import { CLASS_NAME as TEACHER_CLASS_NAME } from '@/components/Teacher'
import { CLASS_NAME as ADMIN_CLASS_NAME } from '@/components/Admin'

export default {
  data() {
    return {
      CLIENT_CLASS_NAME,
      TEACHER_CLASS_NAME,
      ADMIN_CLASS_NAME,
    }
  },
  
  components: { AdminIndex, TeacherIndex, ClientIndex },
}
</script>
