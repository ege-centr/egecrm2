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
    
    <v-list-tile @click="logout">
      <v-list-tile-action>
        <v-icon>exit_to_app</v-icon>
      </v-list-tile-action>
      <v-list-tile-content>
        <v-list-tile-title>Выход</v-list-tile-title>
      </v-list-tile-content>
    </v-list-tile> 

    <v-list-tile v-if='PreviewMode.isActive()' class='preview-mode-info'>
      Преподаватель №{{ $store.state.user.id }}
      <br>
      {{ $store.state.user.last_name }}
      {{ $store.state.user.first_name }}
      {{ $store.state.user.middle_name }}
    </v-list-tile>
  </v-list>
</template>



<script>
import PreviewMode from '@/other/PreviewMode'

export default {
  data: () => ({
    PreviewMode,
    drawer: true,
    menu: [
      {
        icon: 'calendar_today',
        route: 'GroupIndex',
        label: 'Группы'
      },
      {
        icon: 'attach_money',
        route: 'BalanceIndex',
        label: 'Баланс'
      },
      {
        icon: 'rate_review',
        route: 'ReportIndex',
        label: 'Отчёты'
      },
    ],
  }),
  methods: {
    logout() {
      if (PreviewMode.isActive()) {
        PreviewMode.exit()
      } else {
        this.$store.dispatch('logout')
      }
    },
  }
}
</script>
