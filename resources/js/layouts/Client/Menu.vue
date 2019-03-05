<template>
  <v-list dense>
    <v-list-tile v-for='m in menu' :key='m.route' @click="$router.push({name: m.route})">
        <v-list-tile-action>
            <v-icon>{{ m.icon }}</v-icon>
        </v-list-tile-action>
        <v-list-tile-content>
            <v-list-tile-title>
              {{ m.label }}
              <span class='menu-counter' v-if="m.hasOwnProperty('counter')">{{ $store.state.counters[m.counter] || '' }}</span>
            </v-list-tile-title>
        </v-list-tile-content>
    </v-list-tile>

    <div class='menu-separator'></div>

    <v-list-tile @click="$router.push({name: 'ProfileIndex'})">
      <v-list-tile-action>
        <v-icon>person</v-icon>
      </v-list-tile-action>
      <v-list-tile-content>
        <v-list-tile-title>Профиль</v-list-tile-title>
      </v-list-tile-content>
    </v-list-tile> 
    
    <v-list-tile @click="$store.dispatch('logout')">
      <v-list-tile-action>
        <v-icon>exit_to_app</v-icon>
      </v-list-tile-action>
      <v-list-tile-content>
        <v-list-tile-title>Выход</v-list-tile-title>
      </v-list-tile-content>
    </v-list-tile> 
  </v-list>
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
        icon: 'list',
        route: 'ScheduleIndex',
        label: 'Расписание'
      },
      {
        icon: 'attach_money',
        route: 'BalanceIndex',
        label: 'Баланс'
      },
      {
        icon: 'edit',
        route: 'TestIndex',
        label: 'Тесты'
      },
      {
        icon: 'chat',
        route: 'ReviewIndex',
        label: 'Отзывы'
      },
      {
        icon: 'rate_review',
        route: 'ReportIndex',
        label: 'Отчёты'
      },
    ],
  }),
  methods: {
    goTo(route) {
      console.log('Going to ', route)
      this.$router.push({name: route})
    }
  }
}
</script>
