<template lang="html">
  <v-layout row justify-center>
    <v-dialog v-model="dialog" max-width="1200px">
      <v-card>
        <v-card-text>
          <VueEditor style='height: 500px' class='mb-5'
            :editorOptions="editorSettings"
            v-model='item.text'
          />
          <v-container grid-list-xl class="pa-0 ma-0">
            <v-layout pt-3>
              <v-flex md6>
                <v-select clearable
                  hide-details
                  v-model="item.responsible_admin_id"
                  :items="$store.state.data.admins"
                  item-value='id'
                  item-text='name'
                  label="Ответственный"
                ></v-select>
              </v-flex>
              <v-flex md6>
                <v-select
                  hide-details
                  v-model="item.status"
                  :items="statuses"
                  label="Статус"
                ></v-select>
              </v-flex>
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

import { url, statuses, model_defaults } from './data'
import { VueEditor, Quill } from 'vue2-editor'
import { ImageDrop } from 'quill-image-drop-module'

Quill.register('modules/imageDrop', ImageDrop)

export default {
  components: { VueEditor },
  data() {
    return {
      dialog: false,
      saving: false,
      destroying: false,
      item: model_defaults,
      statuses,
      editorSettings: {
        modules: {
          imageDrop: true
        }
      }
    }
  },
  methods: {
    add() {
      this.item = model_defaults
      this.dialog = true
    },
    async storeOrUpdate() {
      this.saving = true
      if (this.item.id) {
        await axios.put(apiUrl(`${url}/${this.item.id}`), this.item)
      } else {
        await axios.post(apiUrl(url), this.item)
      }
      this.$emit('updated')
      this.dialog = false
      setTimeout(() => this.saving = false, 500)
    },
    async destroy() {
      this.destroying = true
      await axios.delete(apiUrl(`${url}/${this.item.id}`))
      this.$emit('updated')
      this.dialog = false
      setTimeout(() => this.destroying = false, 500)
    }
  }
}
</script>
