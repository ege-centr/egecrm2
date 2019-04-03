<template>
  <div>
    <ClientSchedule 
      :client-id='$route.params.client_id' 
      :with-reports='true'
      :display-options="{
        price: false, 
        client: client, 
        teacher: false,
      }"
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
import { ROLES } from '@/config'

export default {
  components: { ClientSchedule },
  
  data() {
    return {
      client: {}
    }
  },
  
  created() {
    axios.get(apiUrl('person') + queryString({
      id: this.$route.params.client_id,
      class: ROLES.CLIENT
    })).then(r => this.client = r.data)
  }
}
</script>
