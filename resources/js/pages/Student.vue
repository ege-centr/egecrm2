<template>
  <div>
    <div class='headline mb-4'>
      Клиент {{ $route.params.id }}
    </div>
    <v-card class='elevation-3 mb-4'>
      <v-card-text class='relative card-with-loader'>
        <Loader v-if='loading'></Loader>
        <v-layout wrap v-if='student !== null'>
          <v-flex>
            <div class='flex-items'>
              <v-avatar :size="100" class='mr-4'>
                <img src="http://placekitten.com/g/530/530">
              </v-avatar>
              <div>
                <div class='grey--text text--darken-2 font-weight-medium caption'>Ученик</div>
                <div class='font-weight-bold'>
                  {{ student.full_name }} ({{ getData('grades', student.grade).title }})
                </div>
              </div>
            </div>
          </v-flex>
          <v-flex>
            <div v-for='(phone, index) in student.phones' :key='index'>
              <div class='flex-items'>
                <div class='d-inline-block mr-2'>
                  {{ phone.phone }}
                </div>
                <div class='d-inline-block grey--text text--darken-2 caption'>
                  {{ phone.comment }}
                </div>
              </div>
            </div>
          </v-flex>
          <v-spacer></v-spacer>
          <v-flex class='text-md-right' align-end d-flex>
            <div>
              <v-btn flat icon color="black" class='ma-0' @click='showModel(request.id)'>
                <v-icon>more_horiz</v-icon>
              </v-btn>
            </div>
          </v-flex>
        </v-layout>
      </v-card-text>
    </v-card>

    <div v-if='student !== null'>
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
            <RequestList :items='student.requests' />
        </v-tab-item>
        <v-tab-item>
          <div class='headline'>
            Договоры
          </div>
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
          <div class='headline'>
            Комментарии
          </div>
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

export default {
  data() {
    return {
      tabs: null,
      loading: true,
      student: null
    }
  },

  components: { RequestList },

  created() {
    this.loadData()
  },

  methods: {
    loadData() {
      axios.get(apiUrl(`students/${this.$route.params.id}`)).then(r => {
        this.student = r.data
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
    padding: 0 10px;
    margin-left: -10px;
  }
</style>
