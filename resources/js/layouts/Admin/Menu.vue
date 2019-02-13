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

    <v-list-tile v-for='m in admin_menu' :key='m.route' @click="$router.push({name: m.route})">
        <v-list-tile-action>
            <v-icon>{{ m.icon }}</v-icon>
        </v-list-tile-action>
        <v-list-tile-content>
            <v-list-tile-title>
              {{ m.label }}
            </v-list-tile-title>
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
      {
        icon: 'import_contacts',
        route: 'ContractIndex',
        label: 'Договоры'
      },
      {
        icon: 'how_to_reg',
        route: 'VisitIndex',
        label: 'Посещаемость'
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
}
</script>
