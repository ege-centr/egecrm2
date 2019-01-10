<template>
  <v-app id="inspire">
    <ClientLayout v-if='$store.state.user.class === CLIENT_CLASS_NAME' @logout='logout' />
    <AdminLayout @logout='logout' v-else />
    <v-content>
      <v-container fluid fill-height v-show="$store.state.loading || !initialDataLoaded">
        <v-layout justify-center align-center>
          <v-progress-circular :size="50" color="primary" indeterminate></v-progress-circular>
        </v-layout>
      </v-container>
      <v-container fluid v-show="!$store.state.loading" v-if='initialDataLoaded'>
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
    
    async created() {
      await this.$store.dispatch('loadInitial')
    },

    components: { AdminLayout, ClientLayout },
    
    // TODO
    computed: {
      initialDataLoaded() {
        return this.$store.state.data.years !== null
      }
    },

    methods: {
      logout() {
        axios.get(apiUrl('logout'))
        this.$store.commit('setUser', null)
      }
    }
  }
</script>
