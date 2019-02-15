<template>
  <div>
    <div class="headline mb-4">
      {{ $store.state.user.last_name }}
      {{ $store.state.user.first_name }}
      {{ $store.state.user.middle_name }}
    </div>
    <v-card>
      <v-card-text>
        <AvatarLoader 
          :entity-type='ROLES.CLIENT' 
          :item='$store.state.user'
        />
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
import AvatarLoader from '@/components/AvatarLoader'
import { ROLES } from '@/config'

export default {
  components: { AvatarLoader },

  data() {
    return {
      ROLES,
    }
  },
   
}
</script>
