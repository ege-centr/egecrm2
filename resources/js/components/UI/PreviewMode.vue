<template>
  <!-- <div class='preview-mode' v-if='$store.state.user.preview'> -->
  <transition
    enter-active-class="animated slideInUp"
    leave-active-class="animated slideOutDown">
    <div class='preview-mode' v-show='show'>
      <div class='flex-items align-center justify-space-between'>
        <div>
          <v-icon color='white' class='mr-1'>person</v-icon>
          {{ $store.state.user.class === CLIENT_CLASS_NAME ? 'Клиент' : 'Преподаватель' }} 
          {{ $store.state.user.id }}
        </div>
        <div>
          <!-- <v-icon color='white' class='mr-1'>visibility</v-icon> -->
          {{ $store.state.user.last_name }}
          {{ $store.state.user.first_name }}
          {{ $store.state.user.middle_name }}
        </div>
        <div>
          <v-btn small class='ma-0' flat color='white' @click='exit()' :loading='exiting'>
            <v-icon class='mr-1'>exit_to_app</v-icon>
            выход
          </v-btn>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
import { CLASS_NAME as CLIENT_CLASS_NAME } from '@/components/Client'
import Preview from '@/other/Preview'

export default {
  data() {
    return {
      CLIENT_CLASS_NAME,
      exiting: false,
      show: false,
    }
  },
  
  created() {
    setTimeout(() => this.show = true, 500)
  },

  mounted() {
    $('.application--wrap').addClass('application--preview-mode')
  },

  destroyed() {
    $('.application--wrap').removeClass('application--preview-mode')
  },

  methods: {
    exit() {
      this.exiting = true
      // this.show = false
      Preview.exit()
    }
  }
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
    animation-duration: .5s;
  }

  .v-icon {
    font-size: 16px;
  }
</style>