<template>
  <div class='preview-mode'>
    <div class='flex-items align-center justify-space-between'>
      <div>
        <v-icon color='white' class='mr-1'>person</v-icon>
        {{ $store.state.user.class === CLIENT_CLASS_NAME ? 'Клиент' : 'Учитель' }} 
        {{ $store.state.user.id }}
      </div>
      <div>
        <!-- <v-icon color='white' class='mr-1'>visibility</v-icon> -->
        {{ $store.state.user.last_name }}
        {{ $store.state.user.first_name }}
        {{ $store.state.user.middle_name }}
      </div>
      <div>
        <v-btn small class='ma-0' flat color='white' @click='exiting = true; Preview.exit()' :loading='exiting'>
          <v-icon class='mr-1'>exit_to_app</v-icon>
          выход
        </v-btn>
      </div>
    </div>
  </div>
</template>

<script>
import { CLASS_NAME as CLIENT_CLASS_NAME } from '@/components/Client'
import Preview from '@/other/Preview'

export default {
  data() {
    return {
      CLIENT_CLASS_NAME,
      Preview,
      exiting: false,
    }
  },
  
}
</script>


<style lang="scss" scoped>
  @import '~sass/_variables';

  .preview-mode {
    position: fixed;
    bottom: 0;
    left: 0;
    background: rgba($blue, .8);
    width: 100%;
    color: white;
    z-index: 3;
    padding: 5px 20px;
  }

  .v-icon {
    font-size: 16px;
  }
</style>