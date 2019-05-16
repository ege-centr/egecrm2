<template>
  <v-list dense>
    
    <ToggleDrawer />
    <SearchBar />
    <SmsMessageDialog ref='SmsMessageDialog' :custom-input='true' />

    <MenuItem v-for='m in menu' :key='m.route' :item='m' />

    <div class='menu-separator'></div>

    <MenuItem v-for='m in admin_menu' :key='m.route' :item='m' />

    <v-list-tile @click="$refs.SmsMessageDialog.open('')">
      <v-list-tile-action>
        <v-icon>send</v-icon>
      </v-list-tile-action>
      <v-list-tile-content>
        <v-list-tile-title>SMS</v-list-tile-title>
      </v-list-tile-content>
    </v-list-tile> 
    
    <v-list-tile @click="$store.dispatch('logout')">
      <v-list-tile-action>
        <v-icon>exit_to_app</v-icon>
      </v-list-tile-action>
      <v-list-tile-content>
        <v-list-tile-title>
          Выход
        </v-list-tile-title>
      </v-list-tile-content>
    </v-list-tile> 
  </v-list>
</template>



<script>
import SearchBar from '@/components/Search/Bar'
import MenuItem from '@/components/UI/MenuItem'
import SmsMessageDialog from '@/components/Sms/Message/Dialog'
import ToggleDrawer from '@/components/UI/ToggleDrawer'

export default {
  components: { SearchBar, MenuItem, SmsMessageDialog, ToggleDrawer },

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
    admin_menu: [
      {
        icon: 'event',
        route: 'DatesAndPrices',
        label: 'Экзамены и цены'
      },
      {
        icon: 'textsms',
        route: 'SmsTemplateIndex',
        label: 'Шаблоны'
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