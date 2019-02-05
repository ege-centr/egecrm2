<template>
  <div>
    <div class='headline mb-4'>
      Группа {{ $route.params.id }}
    </div>

    <v-card class='mb-4' :class='config.elevationClass'>
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
                <span v-if='item.subject_id' class='text-capitalize'>{{ getData('subjects', item.subject_id).name }}</span>
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
                <span v-if='item.level' class='text-capitalize'>{{ LEVELS.find(e => e.value == item.level).text }}</span>
                <span v-else>Не установлен</span>
              </div>
              <div class='f-1 text-md-right align-center d-flex'>
                <div>
                  <v-btn @click='openGroup(item.id)' flat icon color="black" class='ma-0'>
                    <v-icon>more_horiz</v-icon>
                  </v-btn>
                </div>
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
                  <router-link :to="{name: 'ClientShow', params: { id: props.item.id }}">
                    {{ props.item.names.short }}
                  </router-link>
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
                  <v-menu>
                    <v-btn slot='activator' flat icon color="black" class='ma-0'>
                      <v-icon>more_horiz</v-icon>
                    </v-btn>
                     <v-list dense>
                        <v-list-tile @click='removeClientFromGroup(props.item)'>
                          <v-list-tile-action>
                            <v-icon>close</v-icon>
                          </v-list-tile-action>
                          <v-list-tile-title>Удалить из группы</v-list-tile-title>
                        </v-list-tile>
                        <v-list-tile @click='moveClient(props.item)'>
                          <v-list-tile-action>
                            <v-icon>repeat</v-icon>
                          </v-list-tile-action>
                          <v-list-tile-title>Переместить в группу</v-list-tile-title>
                        </v-list-tile>
                     </v-list>
                  </v-menu>
                </td>
              </template>
            </v-data-table>
          </v-flex>
        </v-layout>
      </v-card-text>
    </v-card>
    <div v-if='item !== null'>
      <v-tabs fixed-tabs v-model='tabs' class='mb-4'>
        <v-tab>
          Расписание
        </v-tab>
        <v-tab>
          Посещаемость
        </v-tab>
         <v-tab>
          Акты
        </v-tab>
      </v-tabs>
      <v-tabs-items v-model="tabs">
        <v-tab-item>
          <v-card :class='config.elevationClass'>
            <v-card-text class='relative'>
              <GroupSchedule v-if='item !== null' :group='item' />
            </v-card-text>
          </v-card>
        </v-tab-item>
        <v-tab-item>
          <v-card :class='config.elevationClass' v-if='item.lessons.length > 0'>
            <v-card-text class='relative'>
              <Visits :group='item' />
            </v-card-text>
          </v-card>
          <NoData v-else />
        </v-tab-item>
        <v-tab-item>
          <DisplayData :api-url='GROUP_ACTS_API_URL' :invisible-filters='{group_id: item.id}'>
            <template slot='items' slot-scope='{ items }'>
              <GroupActList :items='items' />
            </template>
            <template slot='buttons-bottom'>
              <AddBtn @click.native='$refs.GroupActDialog.open(null, {group_id: item.id})' />
            </template>
          </DisplayData>
          <!-- <GroupActList :group-id='item.id' /> -->
        </v-tab-item>
      </v-tabs-items>
    </div>
    <GroupDialog ref='GroupDialog' />
    <GroupActDialog ref='GroupActDialog' />
    <MoveClientDialog ref='MoveClientDialog' @moved='removeClientFromGroup' />
  </div>
</template>

<script>

import { 
  API_URL, 
  GROUP_CLIENTS_API_URL, 
  LEVELS, 
  GroupSchedule, 
  MoveClientDialog
} from '@/components/Group'
import Bars from '@/components/Group/Bars'
import Visits from '@/components/Group/Visits'
import GroupDialog from '@/components/Group/Dialog'
import { DisplayData } from '@/components/UI'
import { 
  API_URL as GROUP_ACTS_API_URL,
  GroupActDialog
} from '@/components/Group/Act'
import GroupActList from '@/components/Group/Act/List'


export default {
  components: { DisplayData, GroupSchedule, Bars, Visits, GroupDialog, MoveClientDialog, GroupActList, GroupActDialog },

  data() {
    return {
      LEVELS,
      GROUP_ACTS_API_URL,
      loading: true,
      item: null,
      tabs: null,
    }
  },

  created() {
    this.loadData()
  },

  methods: {
    loadData() {
      axios.get(apiUrl(`${API_URL}/${this.$route.params.id}`)).then(r => {
        this.item = r.data
        this.loading = false
      })
    },

    openGroup(id) {
      this.$refs.GroupDialog.open(id)
    },

    removeClientFromGroup(client) {
      this.item.clients.splice(this.item.clients.findIndex(e => e.id === client.id), 1)
      axios.delete(apiUrl(GROUP_CLIENTS_API_URL + `?group_id=${this.item.id}&client_id=${client.id}`))
    },

    moveClient(client) {
      this.$refs.MoveClientDialog.open(this.item, client)
    },
  }
}
</script>