<template>
  <div>
    <ClientSchedule 
      :client-id='$route.params.client_id' 
      :with-reports='true'
      :params="{
        teacher_id: $route.params.teacher_id,
        subject_id: $route.params.subject_id,
        year: $route.params.year,
      }"
    />
  </div>
</template>



<script>
import ClientSchedule from '@/components/Client/Schedule'

export default {
  components: { ClientSchedule },
}
</script>
