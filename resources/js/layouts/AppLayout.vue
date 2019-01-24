<template>
  <v-app id="inspire">
    <ClientLayout v-if='$store.state.user.class === CLIENT_CLASS_NAME' @logout='logout' />
    <AdminLayout @logout='logout' v-else />
    <v-content>
      <v-container fluid>
        <transition name="fade">
          <router-view></router-view>
        </transition>
      </v-container>
    </v-content>
    <!-- <ListenToLogout></ListenToLogout> -->
  </v-app>
</template>

<script>
  
  import AdminLayout from '@/layouts/Admin/Layout'
  import ClientLayout from '@/layouts/Client/Layout'
  import { CLASS_NAME as CLIENT_CLASS_NAME } from '@/components/Client'

  export default {
    data() {
      return {
        CLIENT_CLASS_NAME,
      }
    },

    components: { AdminLayout, ClientLayout },
    
    methods: {
      logout() {
        axios.get(apiUrl('logout'))
        this.$store.commit('setUser', null)
      }
    }
  }
</script>
