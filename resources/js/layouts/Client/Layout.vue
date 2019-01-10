<template>
  <div>
    <Menu></Menu>
    <v-toolbar class='toolbar' app fixed clipped-left dark>
      <v-toolbar-side-icon @click.stop="$store.commit('toggleDrawer')"></v-toolbar-side-icon>
      <v-avatar tile>
        <img src='/img/svg/logo.svg'>
      </v-avatar>
      <v-spacer></v-spacer>
      <v-menu left>
          <Avatar slot='activator' :photo='$store.state.user.photo' :version='true' :size='50' />
          <v-list dense>
            <v-list-tile>
                <v-list-tile-action>
                  <v-icon>edit</v-icon>
                </v-list-tile-action>
                <v-list-tile-content>
                  <v-list-tile-title>Редактировать</v-list-tile-title>
                </v-list-tile-content>
            </v-list-tile>
            <v-list-tile @click="$emit('logout')">
                <v-list-tile-action>
                  <v-icon>exit_to_app</v-icon>
                </v-list-tile-action>
                <v-list-tile-content>
                  <v-list-tile-title>Выход</v-list-tile-title>
                </v-list-tile-content>
            </v-list-tile>
          </v-list>
      </v-menu>
    </v-toolbar>
    <!-- <ListenToLogout></ListenToLogout> -->
    <!-- <UserDialog ref='UserDialog'></UserDialog> -->
  </div>
</template>

<script>

import Menu from './Menu'
import Avatar from '@/components/UI/Avatar'
import ListenToLogout from '@/components/ListenToLogout'

export default {
  components: { Menu, Avatar },
}
</script>

