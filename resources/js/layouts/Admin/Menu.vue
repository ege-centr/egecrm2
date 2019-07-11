<template>
  <v-list class='main-menu' dense>
    
    <ToggleDrawer />
    <SearchBar />
    <SmsMessageDialog ref='SmsMessageDialog' :custom-input='true' />

    <MenuItem v-for='m in menu' :key='m.route' :item='m' />

    <div class='menu-separator'></div>

    <MenuItem v-for='m in allowedAdminMenu' :key='m.route' :item='m' />

    <v-list-tile @click="$refs.SmsMessageDialog.open('')">
      <v-list-tile-action>
        <v-icon>send</v-icon>
      </v-list-tile-action>
      <v-list-tile-content>
        <v-list-tile-title>SMS</v-list-tile-title>
      </v-list-tile-content>
    </v-list-tile> 

    <v-list-tile @click="syncStaging" v-if='isStaging'>
      <v-list-tile-action>
        <v-progress-circular v-if='syncingStaging' :size="20" color="#fff" indeterminate></v-progress-circular>
        <v-icon v-else>refresh</v-icon>
      </v-list-tile-action>
      <v-list-tile-content>
        <v-list-tile-title>Синхронизировать</v-list-tile-title>
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
import Settings from '@/other/settings'

export default {
  components: { SearchBar, MenuItem, SmsMessageDialog, ToggleDrawer },

  data: () => ({
    drawer: true,
    syncingStaging: false,
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
        // counter: 'clients',
      },
      {
        icon: 'calendar_today',
        route: 'GroupIndex',
        label: 'Группы',
        // counter: 'groups',
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
        icon: 'edit',
        route: 'TestAdminClients',
        label: 'Назначенные тесты'
      },
      {
        icon: 'import_contacts',
        route: 'ContractIndex',
        label: 'Договоры'
      },
      {
        icon: 'how_to_reg',
        route: 'VisitIndex',
        label: 'Посещаемость',
        counter: 'lessons'
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
    adminMenu: [
      {
        icon: 'insert_chart',
        route: 'StatIndex',
        label: 'Итоги',
        needsAccess: true,
      },
      {
        icon: 'attach_money',
        route: 'StatPaymentIndex',
        label: 'Итоги по платежам',
        needsAccess: true,
      },
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
        label: 'Логи',
        needsAccess: true,
      },
      {
        icon: 'print',
        route: 'MacroIndex',
        label: 'Макросы'
      },
      {
        icon: 'people',
        route: 'AdminIndex',
        label: 'Пользователи',
        needsAccess: true,
      },
      {
        icon: 'photo_camera',
        route: 'ClienttPhotos',
        label: 'Фото клиентов',
      },
      {
        icon: 'assignment_turned_in',
        route: 'TaskIndex',
        label: 'Задачи',
        needsAccess: true,
      },
    ],
  }),

  created() {
    if (this.isStaging) {
      Settings.get('is_syncing_staging').then(r => {
        this.syncingStaging = Number(r.data)
      })
      this.pusher.on('StagingSyncFinished', () => this.syncingStaging = false)
    }
  },

  methods: {
    syncStaging() {
      this.syncingStaging = true
      axios.get(apiUrl('sync-staging'))
    }
  },

  computed: {
    allowedAdminMenu() {
      return this.adminMenu.filter(e => {
        if (e.needsAccess) {
          return this.hasAccess
        }
        return true
      })
    },

    hasAccess() {
      return this.$store.state.user.rights.indexOf(101) !== -1
    },

    isStaging() {
      return isStaging()
      // return true
    }
  }
}
</script>