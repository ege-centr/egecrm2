<template>
  <v-navigation-drawer
    clipped
    fixed
    :value="$store.state.drawer"
    app
    dark
  >
    <v-list dense>
      <v-list-tile @click="goTo('Requests')">
        <v-list-tile-action>
          <v-icon>view_list</v-icon>
        </v-list-tile-action>
        <v-list-tile-content>
          <v-list-tile-title>Заявки</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>
      <v-list-tile @click="goTo('Clients')">
          <v-list-tile-action>
              <v-icon>person</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
              <v-list-tile-title>Клиенты</v-list-tile-title>
          </v-list-tile-content>
      </v-list-tile>
      <v-list-tile @click="">
          <v-list-tile-action>
              <v-icon>mail</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
              <v-list-tile-title>SMS</v-list-tile-title>
          </v-list-tile-content>
      </v-list-tile>
      <v-list-tile @click="">
          <v-list-tile-action>
              <v-icon>folder</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
              <v-list-tile-title>Версии договоров</v-list-tile-title>
          </v-list-tile-content>
      </v-list-tile>
      <v-list-tile @click="">
          <v-list-tile-action>
              <v-icon>people</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
              <v-list-tile-title>Группы</v-list-tile-title>
          </v-list-tile-content>
      </v-list-tile>
    </v-list>
  </v-navigation-drawer>
</template>

<script>

export default {
  data: () => ({
    drawer: true,
  }),
  methods: {
    goTo(route) {
      this.$router.push({name: route})
    }
  }
}
</script>
