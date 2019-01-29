<template>
  <div>
    <div class='headline mb-4'>
      Болото
    </div>

    <v-card class='mb-4' :class='config.elevationClass'>
      <v-card-text class='relative card-with-loader'>
        <Loader v-if='loading'></Loader>
        <v-layout wrap v-else>
          <v-flex md12>
            <div class='flex-items'>
              <v-avatar size='100' class='mr-4'>
                <img src="/img/no-profile-img.jpg">
              </v-avatar>
              <div class='mr-5 pr-5'>
                <div class='item-label'>Преподаватель</div>
                {{ 'Не установлен' }}
              </div>
              <div class='mr-5 pr-5'>
                <div class='item-label'>Предмет и класс</div>
                <span>
                  {{ getData('subjects', $route.params.subject_id).name }}, 
                  {{ getData('grades', $route.params.grade_id).title }}
                </span>
              </div>
              <div class='mr-5 pr-5'>
                <div class='item-label'>Учебный год</div>
                <span>{{ getData('years', $route.params.year).title }}</span>
              </div>
              <div>
                <div class='item-label'>Статус</div>
                <span>Болото</span>
                <!-- <div class='mt-3 item-label'>Уровень</div>
                <span v-if='item.level' class='text-capitalize'>{{ LEVELS.find(e => e.value == item.level).text }}</span>
                <span v-else>Не установлен</span> -->
              </div>
            </div>
          </v-flex>
          <v-flex md12 class='mt-5' v-if='clients.length'>
            <v-data-table
              class="full-width"
              hide-actions
              hide-headers
              :items='clients'
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
                <td class='text-md-right' style='padding-right: 16px'>
                  <v-menu>
                    <v-btn slot='activator' flat icon color="black" class='ma-0'>
                      <v-icon>more_horiz</v-icon>
                    </v-btn>
                     <v-list dense>
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
    <MoveClientDialog ref='MoveClientDialog' @moved='removeClientFromGroup' />
  </div>
</template>

<script>

import { API_URL } from '@/components/AbstractGroup'
import { MoveClientDialog } from '@/components/Group'

export default {
  components: { MoveClientDialog },
  
  data() {
    return {
      loading: true,
      clients: null,
      tabs: null,
    }
  },

  created() {
    this.loadData()
  },

  methods: {
    loadData() {
      axios.get(apiUrl(API_URL) + queryString(this.abstractGroup)).then(r => {
        this.clients = r.data
        this.loading = false
      })
    },

    removeClientFromGroup(client) {
      this.clients.splice(this.clients.findIndex(e => e.id === client.id), 1)
    },

    moveClient(client) {
      this.$refs.MoveClientDialog.open(this.abstractGroup, client)
    },
  },

  computed: {
    abstractGroup() {
      return {
        year: parseInt(this.$route.params.year),
        subject_id: parseInt(this.$route.params.subject_id),
        grade_id: parseInt(this.$route.params.grade_id),
      }
    }
  }
}
</script>