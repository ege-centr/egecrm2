<template>
  <div>
    <v-navigation-drawer
      clipped
      fixed
      :value="true"
      :width="250"
      app
      dark
    >
      <div class='menu-logo'>
        <img src='/img/svg/logo-full.svg' />
      </div>
      <SearchBar />
      <Menu />
    </v-navigation-drawer>
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

