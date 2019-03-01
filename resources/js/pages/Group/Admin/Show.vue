<template>
  <div>
    <GroupDialog ref='GroupDialog' @updated='loadData' />
    <GroupActDialog v-if='$refs.GroupActPage' ref='GroupActDialog' @updated='$refs.GroupActPage.loadData()' />
    <MoveClientDialog ref='MoveClientDialog' @moved='removeClientFromGroup' />
    
    <div class='headline mb-4'>
      Группа {{ $route.params.id }}
    </div>

    <v-card class='mb-4' :class='config.elevationClass'>
      <v-card-text class='relative card-with-loader'>
        <Loader v-if='loading'></Loader>
        <v-layout wrap v-if='item !== null'>
          <v-flex md12>
            <GroupInfo :item='item' @open='openGroup' />
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
                  <router-link :to="{name: 'ClientShow', params: { id: props.item.id }}" 
                    :class="{
                      'orange--text': props.item.subject_status === SUBJECT_STATUSES.SUBJECT_STATUS_TO_BE_TERMINATED,
                      'red--text': props.item.subject_status === SUBJECT_STATUSES.SUBJECT_STATUS_TERMINATED,
                    }"
                  >
                    <PersonName :item='props.item'/>
                  </router-link>
                </td>
                <td width='200'>
                  30%
                </td>
                <td width='200'>
                  смс отправлено
                </td>
                <td width='200'>
                  <BranchList :items='props.item.branches' />
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
         <v-tab>
          Комментарии
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
          <DisplayData ref='GroupActPage' :api-url='GROUP_ACTS_API_URL' :invisible-filters='{group_id: item.id}' container-class='py-0'>
            <template slot='items' slot-scope='{ items }'>
              <GroupActList :items='items' @updated='$refs.GroupActPage.loadData()' />
            </template>
            <template slot='buttons-bottom'>
              <AddBtn class='mt-3' @click.native='$refs.GroupActDialog.open(null, {group_id: item.id})' />
            </template>
          </DisplayData>
          <!-- <GroupActList :group-id='item.id' /> -->
        </v-tab-item>
        <v-tab-item>
          <v-card :class='config.elevationClass'>
            <v-card-text>
              <Comments :class-name='CLASS_NAME' :entity-id='item.id' />
            </v-card-text>
          </v-card>
        </v-tab-item>
      </v-tabs-items>
    </div>
  </div>
</template>

<script>

import { 
  API_URL, 
  GROUP_CLIENTS_API_URL, 
  LEVELS, 
  CLASS_NAME,
} from '@/components/Group'

import GroupSchedule from '@/components/Group/Schedule/Schedule'
import MoveClientDialog from '@/components/Group/MoveClientDialog'
import GroupDialog from '@/components/Group/Dialog'

import Bars from '@/components/Group/Bars'
import Visits from '@/components/Group/Visits'
import { DisplayData } from '@/components/UI'
import { API_URL as GROUP_ACTS_API_URL } from '@/components/Group/Act'
import GroupActDialog from '@/components/Group/Act/Dialog'
import GroupActList from '@/components/Group/Act/List'
import Comments from '@/components/Comments'
import { SUBJECT_STATUSES } from '@/components/Contract'
import BranchList from '@/components/UI/BranchList'
import GroupInfo from '@/components/Group/Info'


export default {
  components: { 
    DisplayData, GroupSchedule, Bars, Visits, GroupDialog, MoveClientDialog, GroupActList, 
    GroupActDialog, Comments, BranchList, GroupInfo 
  },

  data() {
    return {
      LEVELS,
      CLASS_NAME,
      GROUP_ACTS_API_URL,
      SUBJECT_STATUSES,
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