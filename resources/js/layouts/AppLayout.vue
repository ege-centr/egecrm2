<template>
  <v-app id="inspire">
    <Menu></Menu>
    <v-toolbar class='toolbar' app fixed clipped-left dark>
      <v-toolbar-side-icon @click.stop="$store.commit('toggleDrawer')"></v-toolbar-side-icon>
      <v-avatar tile>
          <img src='/img/svg/logo.svg'>
      </v-avatar>
      <v-spacer></v-spacer>
      <v-text-field
        flat
        solo-inverted
        hide-details
        prepend-inner-icon="search"
        label="Поиск..."
        class="hidden-sm-and-down"
      ></v-text-field>
      <v-spacer></v-spacer>
      <v-menu left>
          <Avatar slot='activator' :photo='$store.state.user.photo' :version='true' :size='50' />
          <v-list dense>
            <v-list-tile @click='editProfile'>
                <v-list-tile-action>
                  <v-icon>edit</v-icon>
                </v-list-tile-action>
                <v-list-tile-content>
                  <v-list-tile-title>Редактировать</v-list-tile-title>
                </v-list-tile-content>
            </v-list-tile>
            <v-list-tile @click=''>
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
    <!-- <UserDialog ref='UserDialog'></UserDialog> -->
  </v-app>
</template>

<script>
  import Menu from '@/components/UI/Menu'
  import Avatar from '@/components/UI/Avatar'
  import ListenToLogout from '@/components/ListenToLogout'
  import UserDialog from '@/components/User/Dialog'

  export default {
    async created() {
      await this.$store.dispatch('loadInitial')
    },
    components: { Menu, ListenToLogout, UserDialog, Avatar },
    computed: {
      initialDataLoaded() {
        return this.$store.state.data.users !== null
      }
    },
    methods: {
      editProfile() {
        this.$refs.UserDialog.show(this.$store.state.user.id)
      }
    }
  }
</script>

<style lang='scss' scoped>
    @import '~sass/_variables';

    nav {
        background: $blue !important;
    }

    .fade-enter-active, .fade-leave-active {
      transition-property: opacity;
      transition-duration: .2s;
    }

    .fade-enter-active {
      transition-delay: .2s;
    }

    .fade-enter, .fade-leave-active {
      opacity: 0
    }
</style>
