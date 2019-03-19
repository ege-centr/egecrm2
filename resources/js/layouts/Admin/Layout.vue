<template>
  <div>
    <v-navigation-drawer
      clipped
      fixed
      :value="$store.state.drawer"
      :width="250"
      app
      dark
    >
      <div class='menu-logo'>
        <img src='/img/svg/logo-full.svg' />
      </div>
      <Menu />
    </v-navigation-drawer>
    <v-btn class='btn-show-drawer' small float fab v-if='!$store.state.drawer' @click="$store.commit('toggleDrawer', true)">
      <v-icon>arrow_forward_ios</v-icon>
    </v-btn>
  </div>
</template>

<script>

import Menu from './Menu'
import SearchBar from '@/components/Search/Bar'

export default {
  components: { Menu, SearchBar },

  created() {
    axios.get(apiUrl('counters')).then(r => {
      this.$store.commit('setCounters', r.data)
    })
  },
}
</script>

