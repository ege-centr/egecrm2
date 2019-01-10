<template>
  <v-navigation-drawer
    clipped
    fixed
    :value="$store.state.drawer"
    app
    dark
  >
    <v-list dense>
      <v-list-tile v-for='(m, index) in menu' :key='index' @click="goTo(m.route)">
          <v-list-tile-action>
              <v-icon>{{ m.icon }}</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
              <v-list-tile-title>{{ m.label }}</v-list-tile-title>
          </v-list-tile-content>
      </v-list-tile>
    </v-list>
  </v-navigation-drawer>
</template>

<script>

export default {
  data: () => ({
    drawer: true,
    menu: [
      {
        icon: 'calendar_today',
        route: 'GroupIndex',
        label: 'Группы'
      },
      {
        icon: 'edit',
        route: 'TestIndex',
        label: 'Тесты'
      },
    ],
  }),
  methods: {
    goTo(route) {
      this.$router.push({name: route})
    }
  }
}
</script>
