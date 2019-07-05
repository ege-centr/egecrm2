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

    <ToggleDrawer show v-if='!$store.state.drawer' />
      
  </div>
</template>

<script>

import Menu from './Menu'
import SearchBar from '@/components/Search/Bar'
import ToggleDrawer from '@/components/UI/ToggleDrawer'

export default {
  components: { Menu, SearchBar, ToggleDrawer },

  created() {
    this.updateCounters()
    this.pusher.on('CountersUpdated', this.updateCounters)
  },

  methods: {
    updateCounters() {
      axios.get(apiUrl('counters')).then(r => {
        this.$store.commit('setCounters', r.data)
      })
    },
  }
}
</script>
