<template>
  <v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="1200px">
      <v-card v-if='item !== null'>
        <v-card-title>
          <span class="headline">{{ item.id ? 'Редактирование' : 'Добавление' }}</span>
        </v-card-title>
        <v-card-text>
          <v-container grid-list-xl class="pa-0 ma-0">
            <v-layout>

            </v-layout>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-btn color="red darken-1" flat @click.native="destroy" v-if='item.id' :loading='destroying'>Удалить</v-btn>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" flat @click.native="dialog = false">Отмена</v-btn>
          <v-btn color="blue darken-1" flat @click.native="storeOrUpdate" :loading='saving'>{{ item.id ? 'Сохранить' : 'Добавить' }}</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-layout>
</template>

<script>

import { API_URL, MODEL_DEFAULTS } from './data'

export default {
  data() {
    return {
      dialog: false,
      saving: false,
      destroying: false,
      item: null
    }
  },
  methods: {
    add() {
      this.item = MODEL_DEFAULTS
      this.dialog = true
    },
    async storeOrUpdate() {
      this.saving = true
      if (this.item.id) {
        await axios.put(apiUrl(`${API_URL}/${this.item.id}`), this.item)
      } else {
        await axios.post(apiUrl(API_URL), this.item)
      }
      this.$emit('updated')
      this.dialog = false
      setTimeout(() => this.saving = false, 500)
    },
    async destroy() {
      this.destroying = true
      await axios.delete(apiUrl(`${API_URL}/${this.item.id}`))
      this.$emit('updated')
      this.dialog = false
      setTimeout(() => this.destroying = false, 500)
    }
  }
}
</script>
