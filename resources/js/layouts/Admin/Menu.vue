<template>
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

    
    <SearchBar />
    
    <v-list dense>
      <v-list-tile v-for='(m, index) in menu' :key='index' @click="goTo(m.route)">
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
      <v-list-tile @click="$store.dispatch('logout')">
        <v-list-tile-action>
          <v-icon>exit_to_app</v-icon>
        </v-list-tile-action>
        <v-list-tile-content>
          <v-list-tile-title>Выход</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile> 
    </v-list>
  </v-navigation-drawer>
</template>

<script>

import SearchBar from '@/components/Search/Bar'

export default {
  components: { SearchBar },

  data: () => ({
    drawer: true,
    menu: [
      {
        icon: 'view_list',
        route: 'RequestIndex',
        label: 'Заявки',
        counter: 'requests',
      },
      {
        icon: 'person',
        route: 'ClientIndex',
        label: 'Клиенты',
        counter: 'clients',
      },
      {
        icon: 'calendar_today',
        route: 'GroupIndex',
        label: 'Группы',
        counter: 'groups',
      },
      {
        icon: 'calendar_today',
        route: 'AbstractGroupIndex',
        label: 'Болота',
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
        icon: 'print',
        route: 'MacroIndex',
        label: 'Макросы'
      },
      {
        icon: 'people',
        route: 'AdminIndex',
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

<style lang='scss' scoped>

.v-navigation-drawer {
  background: #213846;
}

.menu-logo {
  text-align: center;
  padding: 20px 0 34px;
  & img {
    width: 40%;
  }
}

.v-list__tile__title {
  text-transform: lowercase;
}

</style>
