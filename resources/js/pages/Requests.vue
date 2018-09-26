<template lang="html">
  <div>
    <v-layout row justify-center>
      <v-dialog v-model="dialog" persistent max-width="1000px">
        <v-card>
          <v-card-title>
            <span class="headline">Добавление заявки</span>
          </v-card-title>
          <v-card-text>
            <v-container grid-list-md class="pa-0">
              <v-layout wrap>
                <v-flex md4>
                  <v-text-field v-model="dialog_model.name" label="Имя"></v-text-field>
                </v-flex>
                <v-flex md4>
                  <v-select
                    v-model="dialog_model.status"
                    :items="request_statuses"
                    label="Статус"
                  ></v-select>
                </v-flex>
                <v-flex md4>
                  <v-select clearable
                    v-model="dialog_model.responsible_user_id"
                    :items="$store.state.users"
                    item-value='id'
                    item-text='login'
                    label="Ответственный"
                  ></v-select>
                </v-flex>
                <v-flex md4>
                  <v-select clearable
                    v-model="dialog_model.grade"
                    :items="$store.state.data.grade"
                    item-value='id'
                    item-text='title'
                    label="Класс"
                  ></v-select>
                </v-flex>
                <v-flex md4>
                  <v-select multiple
                    v-model="dialog_model.subjects"
                    :items="$store.state.data.subject"
                    item-value='id'
                    item-text='title'
                    label="Предмет"
                  ></v-select>
                </v-flex>
                <v-flex md4>
                  <v-select multiple
                    v-model="dialog_model.branches"
                    :items="$store.state.data.branch"
                    item-value='id'
                    item-text='title'
                    label="Филиал"
                  ></v-select>
                </v-flex>
                <v-flex md12>
                  <v-textarea v-model="dialog_model.comment" label="Комментарий"></v-textarea>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" flat @click.native="dialog = false">Отмена</v-btn>
            <v-btn color="blue darken-1" flat @click.native="addModel" :loading='loading'>Добавить</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-layout>
    <v-layout>
      <v-flex xs12 class="text-xs-right">
        <a class='black-link' @click='openDialog'>
          <v-btn small fab color="primary">
            <v-icon dark>add</v-icon>
          </v-btn>
          добавить заявку
        </a>
      </v-flex>
    </v-layout>

    <!-- <div>
      <v-chip small close>
        Ответственный: Кристина Богатырёва
      </v-chip>
      <v-chip small close>
        11 класс
      </v-chip>
      <v-chip small close>
        невыполненные
      </v-chip>
      <v-btn small round flat>добавить фильтр</v-btn>
    </div> -->

    <v-container grid-list-md fluid class="px-0">
      <v-layout row wrap>
        <v-flex xs12 v-for='request in requests' :key='request.id'>
          <v-card class='elevation-3'>
            <v-card-text>
              <v-layout row>
                <v-flex>
                  <div class='request-info'>
                    <v-avatar
                      :size="60"
                      color="grey lighten-4"
                    >
                      <img src="http://placekitten.com/g/200/200">
                    </v-avatar>
                    <div>
                      <div>
                        <b class='inline'>
                          {{ request.created_user_id ? $store.state.users.find(e => e.id == request.created_user_id).login : 'system' }}
                          | заявка {{ request.id }}
                        </b>
                        <span class='grey--text lighten-2'>{{ request.created_at | date-time }}</span>
                      </div>
                      <div>
                        <span v-for='branch_id in request.branches' :key='branch_id'>
                          {{ $store.state.data.branch.find(e => e.id == branch_id).short }},
                        </span>
                        <span v-if='request.grade'>
                          {{ $store.state.data.grade.find(e => e.id == request.grade).title }}
                        </span>
                      </div>
                    </div>
                  </div>
                </v-flex>
                <v-flex>

                </v-flex>
              </v-layout>
            </v-card-text>
          </v-card>
        </v-flex>
      </v-layout>
    </v-container>
    <div class="text-xs-center">
      <v-pagination
         v-model="page"
         :length="4"
         circle
       ></v-pagination>
   </div>
  </div>
</template>

<script>
const MODEL_DEFAULTS = {
  status: 'new'
}

export default {
  data() {
    return {
      page: 1,
      dialog: false,
      dialog_model: {},
      loading: false,
      request_statuses: [
        {text: 'Новые', value: 'new'},
        {text: 'Ожидающиеся', value: 'awaiting'},
        {text: 'Выполненные', value: 'finished'},
      ],
      requests: null
    }
  },

  created() {
    this.loadData()
  },

  methods: {
    openDialog() {
      this.dialog = true
      this.dialog_model = _.clone(MODEL_DEFAULTS)
    },

    async addModel() {
      this.loading = true
      await axios.post(apiUrl('requests'), this.dialog_model)
      this.loadData()
      this.loading = false
      this.dialog = false
    },

    loadData() {
      this.$store.commit('loading', true)
      axios.get(apiUrl('requests')).then(response => {
        this.requests = response.data
        this.$store.commit('loading', false)
      })
    }
  }
}
</script>


<style scoped lang='scss'>
  .request-info {
    display: flex;
    & > div {
      margin-right: 14px;
    }
  }
</style>
