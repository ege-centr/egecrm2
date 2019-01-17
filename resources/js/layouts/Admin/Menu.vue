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
      <v-list-group
        prepend-icon="settings"
        value="true"
      >
      <v-list-tile slot="activator">
        <v-list-tile-title>Настройки</v-list-tile-title>
      </v-list-tile>

      <v-list subgroup>
        <v-list-tile v-for='(m, index) in admin_menu' :key='index' @click="goTo(m.route)">
            <v-list-tile-action>
                <v-icon>{{ m.icon }}</v-icon>
            </v-list-tile-action>
            <v-list-tile-content>
                <v-list-tile-title>{{ m.label }}</v-list-tile-title>
            </v-list-tile-content>
        </v-list-tile>
      </v-list>
    </v-list-group>
    </v-list>
  </v-navigation-drawer>
</template>

<script>

export default {
  data: () => ({
    drawer: true,
    menu: [
      {
        icon: 'view_list',
        route: 'Requests',
        label: 'Заявки'
      },
      {
        icon: 'person',
        route: 'ClientIndex',
        label: 'Клиенты'
      },
      {
        icon: 'calendar_today',
        route: 'GroupIndex',
        label: 'Группы'
      },
      {
        icon: 'attach_money',
        route: 'PaymentIndex',
        label: 'Платежи'
      },
      {
        icon: 'people',
        route: 'TeacherIndex',
        label: 'Преподаватели'
      },
      {
        icon: 'edit',
        route: 'TestIndex',
        label: 'Тесты'
      },
    ],
    admin_menu: [
      {
        icon: 'multiline_chart',
        route: 'RecommendedPrices',
        label: 'Рекомендованные цены'
      },
      {
        icon: 'event',
        route: 'SpecialDates',
        label: 'Праздники и экзамены'
      },
      {
        icon: 'history',
        route: 'LogIndex',
        label: 'Логи'
      },
      {
        icon: 'people',
        route: 'Admins',
        label: 'Пользователи'
      },
      {
        icon: 'assignment_turned_in',
        route: 'TaskIndex',
        label: 'Задачи'
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
