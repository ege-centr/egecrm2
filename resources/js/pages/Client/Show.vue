<template>
  <div>
    <ClientMap ref='ClientMap' v-if='client !== null' :items='client.markers' />
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
          <ContractList :items='client.contracts' />
        </v-tab-item>
        <v-tab-item>
          <GroupList :items='client.groups' />
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

import RequestList from '@/components/Request/List'
import Avatar from '@/components/UI/Avatar'
import { subject_statuses } from '@/components/Contract/data'
import Comments from '@/components/Comments'
import ContractList from '@/components/Contract/List'
import GroupList from '@/components/Group/List'
import ClientMap from '@/components/Client/Map'

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

  components: { RequestList, Avatar, Comments, ContractList, ClientMap, GroupList },

  created() {
    this.loadData()
  },

  methods: {
    openMap() {
      this.$refs.ClientMap.openMap()
    },
    loadData() {
      axios.get(apiUrl(`clients/${this.$route.params.id}`)).then(r => {
        this.client = r.data
        this.loading = false
      })
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
