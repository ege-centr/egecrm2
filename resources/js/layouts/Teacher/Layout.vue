<template>
  <div>
    <v-navigation-drawer
      clipped
      fixed
      :value="true"
      app
      dark
    >
      <div class='menu-logo'>
        <img src='/img/svg/logo-full.svg' />
      </div>
      <Menu />
    </v-navigation-drawer>
    <PreviewMode />
  </div>
</template>

<script>

import Menu from './Menu'
import PreviewMode from '@/components/UI/PreviewMode'

export default {
  components: { Menu, PreviewMode },
}
</script>

