<template>
  <div>
    <TeacherShow v-if='$store.state.user.class === TEACHER_CLASS_NAME' />
    <AdminShow v-if='$store.state.user.class === ADMIN_CLASS_NAME' />
  </div>
</template>

<script>
import AdminShow from './Admin/Show'
import TeacherShow from './Teacher/Show'
import { CLASS_NAME as TEACHER_CLASS_NAME } from '@/components/Teacher'
import { CLASS_NAME as ADMIN_CLASS_NAME } from '@/components/Admin'

export default {
  data() {
    return {
      TEACHER_CLASS_NAME,
      ADMIN_CLASS_NAME,
    }
  },
  
  components: { AdminShow, TeacherShow },
}
</script>
