<template lang="html">
  <div>
    <v-layout row justify-center>
      <v-dialog v-model="dialog" persistent max-width="1000px">
        <v-card>
          <v-card-title>
            <span class="headline">{{ dialog_model.id ? 'Редактирование' : 'Добавление' }} клиента</span>
          </v-card-title>
          <v-card-text>
            <v-container grid-list-md class="pa-0">
              <v-layout wrap>
                <v-flex md4>
                  <v-text-field v-model="dialog_model.first_name" label="Имя"></v-text-field>
                </v-flex>
                <v-flex md4>
                  <v-text-field v-model="dialog_model.last_name" label="Фамилия"></v-text-field>
                </v-flex>
                <v-flex md4>
                  <v-text-field v-model="dialog_model.middle_name" label="Отчество"></v-text-field>
                </v-flex>
                <v-flex md4>
                  <v-select clearable
                    v-model="dialog_model.grade"
                    :items="$store.state.data.grades"
                    item-value='id'
                    item-text='title'
                    label="Класс"
                  ></v-select>
                </v-flex>
                <v-flex md4>
                  <v-select clearable
                    v-model="dialog_model.year"
                    :items="$store.state.data.years"
                    label="Год"
                  ></v-select>
                </v-flex>
                <v-flex md4>
                  <v-select multiple
                    v-model="dialog_model.branches"
                    :items="$store.state.data.branches"
                    item-value='id'
                    item-text='title'
                    label="Филиалы"
                  ></v-select>
                </v-flex>
                <v-flex md12 v-for="(phone, index) in dialog_model.phones" :key='index'>
                  <v-layout>
                    <v-flex md4>
                      <v-text-field
                        placeholder='+7 (###) ###-##-##'
                        v-mask="'+7 (###) ###-##-##'"
                        v-model="dialog_model.phones[index].phone" :label="`Телефон ${index + 1}`"
                      >
                      </v-text-field>
                    </v-flex>
                    <v-flex md4>
                      <v-text-field v-model="dialog_model.phones[index].comment"
                        :label="`Комментарий к телефону ${index + 1}`">
                      </v-text-field>
                    </v-flex>
                  </v-layout>
                </v-flex>
                <v-flex md12>
                  <v-btn color="blue darken-1" class="ma-0 pl-1" flat @click="dialog_model.phones.push({phone: '', comment: ''})">
                    <v-icon class="mr-1">add</v-icon>
                    добавить телефон
                  </v-btn>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-btn color="red darken-1" flat @click.native="dialog = false" v-if="dialog_model.id">Удалить</v-btn>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" flat @click.native="dialog = false">Отмена</v-btn>
            <v-btn color="blue darken-1" flat @click.native="storeOrUpdateModel" :loading='loading.dialog'>{{ dialog_model.id ? 'Сохранить' : 'Добавить' }}</v-btn>
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
          добавить клиента
        </a>
      </v-flex>
    </v-layout>

    <v-container grid-list-md fluid class="px-0" v-if='collection !== null'>
      <v-layout row wrap class='relative'>
        <Loader v-if='loading.pagination' />
        <v-flex xs12>
          <v-data-table :items='collection.data' :item-key='id' hide-headers hide-actions>
            <template slot="items" slot-scope="{ item }">
              <td>
                <router-link :to="{ name: 'Student', params: { id: item.id }}">
                  {{ item.name }}
                </router-link>
              </td>
              <td class='text-md-right'>
                <v-btn flat icon color="black" class='ma-0' @click='showModel(item.id)'>
                  <v-icon>more_horiz</v-icon>
                </v-btn>
              </td>
            </template>
          </v-data-table>
        </v-flex>
      </v-layout>
      <div class="text-xs-center mt-4">
        <v-pagination
          v-if='collection.meta.last_page > 1'
          v-model="page"
          :length="collection.meta.last_page"
          :total-visible="7"
          circle
        ></v-pagination>
     </div>
    </v-container>
  </div>
</template>

<script>

const MODEL_DEFAULTS = {
  phones: [{phone: '', comment: ''}]
}

export default {
  data() {
    return {
      page: 1,
      dialog: false,
      dialog_model: {},
      loading: {
        dialog: false,
        pagination: false
      },
      collection: null
    }
  },

  created() {
    this.loadData()
  },

  watch: {
    page() {
        this.loadData()
    }
  },

  methods: {
    openDialog() {
      this.dialog = true
      this.dialog_model = _.clone(MODEL_DEFAULTS)
    },
    async storeOrUpdateModel() {
      this.loading.dialog = true
      if (this.dialog_model.id) {
        await axios.put(apiUrl(`students/${this.dialog_model.id}`), this.dialog_model)
      } else {
        await axios.post(apiUrl('students'), this.dialog_model)
      }
      this.loadData()
      this.loading.dialog = false
      this.dialog = false
    },
    showModel(id) {
      axios.get(apiUrl(`students/${id}`)).then(r => {
        this.dialog_model = r.data
        this.dialog = true
      })
    },
    loadData() {
      // this.$store.commit('loading', true)
      this.loading.pagination = true
      axios.get(apiUrl(`students?page=${this.page}`)).then(response => {
        this.collection = response.data
        this.loading.pagination = false
        // this.$store.commit('loading', false)
      })
    }
  }
}
</script>

<style lang="css">
</style>
