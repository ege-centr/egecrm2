<template>
  <div>
    <v-layout row justify-center v-if='client !== null'>
      <v-dialog v-model="map_dialog" persistent max-width="1000px">
        <v-card>
          <v-card-text>
            <GmapMap ref='map' @click='mapClick'
                :center="{lat: 55.7387, lng: 37.6032}"
                :zoom="12"
                :options="{
                  disableDefaultUI: true
                }"
                style="width: 100%; height: 600px"
              >
              <GmapMarker
                v-for="(m, index) in client.markers"
                :key="index"
                :position="{lat: m.lat, lng: m.lng}"
              />
            </GmapMap>
          </v-card-text>
          <v-card-actions class='justify-center'>
            <v-btn color="blue darken-1" flat @click.native="map_dialog = false">Закрыть</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-layout>

    <div class='headline mb-4'>
      Клиент {{ $route.params.id }}
    </div>
    <v-card class='elevation-3 mb-4'>
      <v-card-text class='relative card-with-loader'>
        <Loader v-if='loading'></Loader>
        <v-layout wrap v-if='client !== null'>
          <v-flex>
            <div class='flex-items'>
              <Avatar :size='100' class='mr-4' :photo='client.photo' />
              <div>
                <div class='grey--text text--darken-2 font-weight-medium caption'>Ученик</div>
                <div class='font-weight-bold'>
                  {{ client.first_name }}
                  {{ client.last_name }}
                  {{ client.middle_name }}
                  ({{ getData('grades', client.grade).title }})
                </div>
                <div class='grey--text text--darken-2 font-weight-medium caption mt-3'>Представитель</div>
                <div class='font-weight-bold'>
                  {{ client.passport.first_name }}
                  {{ client.passport.last_name }}
                  {{ client.passport.middle_name }}
                </div>
              </div>
            </div>
          </v-flex>
          <v-flex>
            <div v-for='(phone, index) in client.phones' :key='index'>
              <div class='flex-items'>
                <div class='d-inline-block mr-2'>
                  {{ phone.phone }}
                </div>
                <div class='d-inline-block grey--text text--darken-2 caption'>
                  {{ phone.comment }}
                </div>
              </div>
            </div>
            <div v-if='client.email'>
              {{ client.email.email }}
            </div>
            <div v-if='client.markers.length'>
              <a @click='openMap'>метки ({{ client.markers.length }})</a>
            </div>
          </v-flex>
          <v-spacer></v-spacer>
          <v-flex class='text-md-right' align-end d-flex>
            <div>
              <router-link :to="{ name: 'ClientEdit', params: { id: client.id }}">
                <v-btn flat icon color="black" class='ma-0'>
                  <v-icon>more_horiz</v-icon>
                </v-btn>
              </router-link>
            </div>
          </v-flex>
        </v-layout>
      </v-card-text>
    </v-card>

    <div v-if='client !== null'>
      <v-tabs fixed-tabs v-model='tabs' class='mb-4'>
        <v-tab>
          Заявки
        </v-tab>
        <v-tab>
          Договоры
        </v-tab>
        <v-tab>
          Группы
        </v-tab>
        <v-tab>
          Посещаемость
        </v-tab>
        <v-tab>
          Комментарии
        </v-tab>
        <v-tab>
          Баланс счёта
        </v-tab>
      </v-tabs>

      <v-tabs-items v-model="tabs">
        <v-tab-item>
            <RequestList :items='client.requests' />
        </v-tab-item>
        <v-tab-item>
          <v-layout wrap>
            <v-flex md12 v-if='client.contracts.length'>
              <v-data-table
                :items="client.contracts"
                class="elevation-1"
                hide-actions
                hide-headers
              >
                <template slot='items' slot-scope="{ item }">
                  <td>
                    <span v-if='item.id'>
                      №{{ item.number }} версия {{ item.version }}
                    </span>
                  </td>
                  <td>
                    от {{ item.date | date }}
                  </td>
                  <td>
                    {{ item.sum }} руб.
                  </td>
                  <td>
                    <span v-if='item.payments.length'>
                      {{ item.payments.length }} платежа
                    </span>
                    <span v-else>
                      платежей нет
                    </span>
                  </td>
                  <td>
                    {{ getData('grades', item.grade).title }}
                  </td>
                  <td>
                    <span v-for='subject in item.subjects' :class="{
                      'error--text': subject.status == subject_statuses[2],
                      'orange--text': subject.status == subject_statuses[1]
                    }">
                      {{ getData('subjects', subject.subject_id).three_letters }}
                      <span class='grey--text'>{{ subject.lessons }}</span>
                    </span>
                  </td>
                  <td class='text-md-right'>
                    <span v-if='item.id'>
                      {{ getData('admins', item.created_admin_id).name }}
                      {{ item.created_at | date-time }}
                    </span>
                  </td>
                </template>
              </v-data-table>
            </v-flex>
          </v-layout>
        </v-tab-item>
        <v-tab-item>
          <div class='headline'>
            Группы
          </div>
        </v-tab-item>
        <v-tab-item>
          <div class='headline'>
            Посещаемость
          </div>
        </v-tab-item>
        <v-tab-item>
          <Comments class-name='Client\Client' :entity-id='client.id' />
        </v-tab-item>
        <v-tab-item>
          <div class='headline'>
            Баланс счёта
          </div>
        </v-tab-item>
      </v-tabs-items>
    </div>


  </div>
</template>

<script>

import RequestList from '@/components/Request/RequestList'
import Avatar from '@/components/UI/Avatar'
import { subject_statuses } from '@/components/Contract/data'
import Comments from '@/components/Comments'

export default {
  data() {
    return {
      tabs: null,
      loading: true,
      client: null,
      map_dialog: false,
      subject_statuses
    }
  },

  components: { RequestList, Avatar, Comments },

  created() {
    this.loadData()
  },

  methods: {
    openMap() {
      this.map_dialog = true
      Vue.nextTick(() => {
        this.$refs.map.resize()
      })
    },

    loadData() {
      axios.get(apiUrl(`clients/${this.$route.params.id}`)).then(r => {
        this.client = r.data
        this.loading = false
      })
    },
    loadTabs() {

    },
  }
}
</script>

<style scoped lang='scss'>
  .v-tabs__items {
    width: calc(100% + 20px);
    padding: 10px;
    margin-left: -10px;
  }
</style>
