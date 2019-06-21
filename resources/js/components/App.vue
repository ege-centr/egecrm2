<template>
  <div>
    <AppLayout v-if="$store.state.user"></AppLayout> 
    <LoginLayout v-else></LoginLayout>
  </div>
</template>

<script>
  import AppLayout from '@/layouts/AppLayout'
  import LoginLayout from '@/layouts/LoginLayout'

  export default {
    components: { AppLayout, LoginLayout },
    
    created() {
      // global error handler
      // axios.interceptors.response.use(
      //   (response) => response, 
      //   // (error) => this.$store.commit('message', {text: error.response.data.message})
      //   (error) => {
      //     switch(error.response.status) {
      //       case 401: return this.$store.commit('setUser', null)
      //       case 500: return this.$store.commit('message', {text: 'ошибка сервера'})
      //     }
      //   }
      // )

      // типа Drawer сохраняется в LocalStorage
      if (localStorage.hasOwnProperty('drawer')) {
        this.$store.commit('toggleDrawer', localStorage.getItem('drawer') === 'true')
      }
    }
  }
</script>
