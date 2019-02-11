<template>
  <v-app id="inspire">
    <ClientLayout v-if='$store.state.user.class === CLIENT_CLASS_NAME' @logout='logout' />
    <TeacherLayout v-if='$store.state.user.class === TEACHER_CLASS_NAME' @logout='logout' />
    <AdminLayout @logout='logout' v-if='$store.state.user.class === ADMIN_CLASS_NAME' />
    <v-content>
      <v-container fluid>
        <transition name="fade">
          <router-view v-show='$store.state.search === null'></router-view>
        </transition>
        <SearchResults v-show='$store.state.search !== null' />
      </v-container>
    </v-content>
    <!-- <ListenToLogout></ListenToLogout> -->
  </v-app>
</template>

<script>
  
  import AdminLayout from '@/layouts/Admin/Layout'
  import ClientLayout from '@/layouts/Client/Layout'
  import TeacherLayout from '@/layouts/Teacher/Layout'
  import { CLASS_NAME as CLIENT_CLASS_NAME } from '@/components/Client'
  import { CLASS_NAME as TEACHER_CLASS_NAME } from '@/components/Teacher'
  import { CLASS_NAME as ADMIN_CLASS_NAME } from '@/components/Admin'
  import { SearchResults } from '@/components/Search'

  export default {
    data() {
      return {
        CLIENT_CLASS_NAME,
        TEACHER_CLASS_NAME,
        ADMIN_CLASS_NAME,
      }
    },

    components: { AdminLayout, TeacherLayout, ClientLayout, SearchResults },
    
    methods: {
      logout() {
        axios.get(apiUrl('logout'))
        this.$store.commit('setUser', null)
      }
    }
  }
</script>
