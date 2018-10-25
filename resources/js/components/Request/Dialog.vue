<template>
  <v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="1000px">
      <v-card>
        <v-card-title>
          <span class="headline">{{ dialog_model.id ? 'Редактирование' : 'Добавление' }} заявки</span>
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
                  v-model="dialog_model.responsible_admin_id"
                  :items="$store.state.data.admins"
                  item-value='id'
                  item-text='name'
                  label="Ответственный"
                ></v-select>
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
                <v-select multiple
                  v-model="dialog_model.subjects"
                  :items="$store.state.data.subjects"
                  item-value='id'
                  item-text='name'
                  label="Предмет"
                ></v-select>
              </v-flex>
              <v-flex md4>
                <v-select multiple
                  v-model="dialog_model.branches"
                  :items="$store.state.data.branches"
                  item-value='id'
                  item-text='full'
                  label="Филиалы"
                ></v-select>
              </v-flex>
              <Phones :item='dialog_model' />
              <v-flex md12>
                <v-textarea v-model="dialog_model.comment" label="Комментарий"></v-textarea>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-btn color="red darken-1" flat @click.native="dialog = false" v-if='dialog_model.id'>Удалить</v-btn>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" flat @click.native="dialog = false">Отмена</v-btn>
          <v-btn color="blue darken-1" flat @click.native="storeOrUpdate" :loading='loading'>{{ dialog_model.id ? 'Сохранить' : 'Добавить' }}</v-btn>
        </v-card-actions>
      </v-card> 
    </v-dialog>
  </v-layout>
</template>

<script>

import { request_statuses, model_defaults } from './data'
import Phones from '@/components/Phones'

export default {
  components: { Phones },

  data() {
    return {
      dialog: false,
      dialog_model: {},
      loading: false,
      request_statuses
    }
  },

  methods: {
    add() {
      this.dialog = true
      this.dialog_model = _.clone(model_defaults)
    },
    async storeOrUpdate() {
      this.loading = true
      if (this.dialog_model.id) {
        await axios.put(apiUrl(`requests/${this.dialog_model.id}`), this.dialog_model)
      } else {
        await axios.post(apiUrl('requests'), this.dialog_model)
      }
      this.$emit('saved')
      this.loading = false
      this.dialog = false
    },
    show(id) {
      axios.get(apiUrl(`requests/${id}`)).then(r => {
        this.dialog_model = r.data
        this.dialog = true
      })
    },
  }
}
</script>
