<template>
  <div>
    <div class='headline mb-4'>
      Группа {{ $route.params.id }}
    </div>

    <v-card class='elevation-3 mb-4'>
      <v-card-text class='relative card-with-loader'>
        <Loader v-if='loading'></Loader>
        <v-layout wrap v-if='item !== null'>
          <v-flex md12>
            <div class='flex-items'>
              <v-avatar v-if='item.teacher' :size='100' class='bg-avatar mr-4' :style="{backgroundImage: `url(${item.teacher.photo_url})`}"></v-avatar>
              <v-avatar v-else size='100' class='mr-4'>
                <img src="/img/no-profile-img.jpg">
              </v-avatar>
              <div class='mr-5 pr-5'>
                <div class='item-label'>Преподаватель</div>
                {{ item.teacher ? item.teacher.names.full : 'Не установлен' }}
              </div>
              <div class='mr-5 pr-5'>
                <div class='item-label'>Предмет и класс</div>
                <span class='text-capitalize'>{{ getData('subjects', item.subject_id).name }}</span>
                <span v-if='item.grade_id'>, {{ getData('grades', item.grade_id).title }}</span>
                <div class='mt-3 item-label'>Расписание</div>
                <span>{{ item.schedule.label }}</span>
              </div>
              <div class='mr-5 pr-5'>
                <div class='item-label'>Учебный год</div>
                <span>{{ item.year }}–{{ item.year + 1 }}</span>
                <div class='mt-3 item-label'>Всего уроков</div>
                <span>{{ item.lessons.length }}</span>
              </div>
              <div>
                <div class='item-label'>Статус</div>
                <span>{{ item.is_archived ? 'Заархивирована' : 'Активная' }}</span>
                <div class='mt-3 item-label'>Уровень</div>
                <span v-if='item.level' class='text-capitalize'>{{ levels.find(e => e.value == item.level).text }}</span>
                <span v-else>Не установлен</span>
              </div>
              <div class='f-1 text-md-right align-center d-flex'>
                <router-link :to="{name: 'GroupEdit', params: { id: item.id }}">
                  <v-btn flat icon color="black" class='ma-0'>
                    <v-icon>more_horiz</v-icon>
                  </v-btn>
                </router-link>
              </div>
            </div>
          </v-flex>
          <v-flex md12 class='mt-5' v-if='item.clients.length'>
            <v-data-table
              class="full-width"
              hide-actions
              hide-headers
              :items='item.clients'
            >
              <template slot='items' slot-scope="props">
                <td width='200'>
                  <a @click='openClient(props.item.id)'>
                    {{ props.item.names.short }}
                  </a>
                </td>
                <td width='200'>
                  30%
                </td>
                <td width='200'>
                  смс отправлено
                </td>
                <td width='200'>
                  ТУР
                </td>
                <td>
                  <Bars :group-bars='item.schedule.bars' :client-bars='props.item.bars' />
                </td>
                <td class='text-md-right' style='padding-right: 16px'>
                  <v-btn flat icon color="black" class='ma-0'>
                    <v-icon>more_horiz</v-icon>
                  </v-btn>
                </td>
              </template>
            </v-data-table>
          </v-flex>
        </v-layout>
      </v-card-text>
    </v-card>
    <ClientShow v-for='client_id in opened_clients' :client-id='client_id' :key='client_id' />
  </div>
</template>

<script>

import { url, levels } from '@/components/Group/data'
import ClientShow from '@/pages/Client/Show'
import Bars from '@/components/Group/Bars'

export default {
  components: { ClientShow, Bars },

  data() {
    return {
      loading: true,
      item: null,
      levels,
      opened_clients: []
    }
  },

  created() {
    this.loadData()
  },

  methods: {
    loadData() {
      axios.get(apiUrl(`${url}/${this.$route.params.id}`)).then(r => {
        this.item = r.data
        this.loading = false
      })
    },
    openClient(client_id) {
      if (this.opened_clients.indexOf(client_id) === -1) {
        this.opened_clients.push(client_id)
        Vue.nextTick(() => window.scrollTo(0,document.body.scrollHeight))
      }
    }
  }
}
</script>
