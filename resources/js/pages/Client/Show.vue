<template>
  <div>
    <div class='headline mb-4'>
      Клиент {{ clientId || $route.params.id }}
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
                  {{ client.last_name }}
                  {{ client.first_name }}
                  {{ client.middle_name }}
                  <span v-if='client.grade_id !== null'>({{ getData('grades', client.grade_id).title }})</span>
                </div>
                <div class='grey--text text--darken-2 font-weight-medium caption mt-3'>Представитель</div>
                <div class='font-weight-bold'>
                  {{ client.representative.last_name }}
                  {{ client.representative.first_name }}
                  {{ client.representative.middle_name }}
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
          Платежи
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
            <RequestList :items='client.requests' :phones='client.phones' @updated='loadData' />
        </v-tab-item>
        <v-tab-item>
          <ContractList
            :items='client.contracts'
            :client-id='client.id'
          />
        </v-tab-item>
        <v-tab-item>
          <GroupList :items='client.groups' />
          <GroupNotAssignedList :groups='client.groups' :contracts='client.contracts' @assigned='loadData' />
        </v-tab-item>
        <v-tab-item>
          <PaymentList
            :items='client.payments'
            :entity-id='client.id'
            :class-name='CLASS_NAME'
          />
        </v-tab-item>
        <v-tab-item>
          <Comments :class-name='CLASS_NAME' :entity-id='client.id' />
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
import Comments from '@/components/Comments'
import ContractList from '@/components/Contract/List'
import GroupList from '@/components/Group/List'
import PaymentList from '@/components/Payment/List'
import { API_URL, CLASS_NAME, ClientMap, GroupNotAssignedList } from '@/components/Client/data'

export default {
  props: ['clientId'],

  data() {
    return {
      CLASS_NAME,
      tabs: null,
      loading: true,
      client: null,
      map_dialog: false,
    }
  },

  components: { RequestList, Comments, ContractList, ClientMap, GroupList, GroupNotAssignedList, PaymentList },

  created() {
    this.loadData()
  },

  methods: {
    openMap() {
      this.$refs.ClientMap.openMap()
    },
    loadData() {
      axios.get(apiUrl(`${API_URL}/${this.clientId || this.$route.params.id}`)).then(r => {
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
