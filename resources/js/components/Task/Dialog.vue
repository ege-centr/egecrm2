<template lang="html">
  <v-layout row justify-center>
    <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay content-class='overflow-hidden'>
      <v-card>
        <v-toolbar dark color="primary">
          <v-btn icon dark @click.native="dialog = false">
            <v-icon>close</v-icon>
          </v-btn>
          <v-toolbar-title>{{ edit_mode ? 'Редактирование' : 'Добавление' }} задачи</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items>
            <v-btn dark flat v-if='edit_mode' @click.native="destroy" :loading='destroying'>Удалить</v-btn>
            <v-btn dark flat @click.native="storeOrUpdate" :loading='saving'>{{ edit_mode ? 'Сохранить' : 'Добавить' }}</v-btn>
          </v-toolbar-items>
        </v-toolbar>
        <v-card-text>
          <Loader v-if='loading' class='loader-wrapper_fullscreen-dialog' />
          <v-container grid-list-xl class="pa-0 ma-0" fluid v-else>
            <v-layout>
              <v-flex md12 class='relative'>
                <div class='custom-toolbar flex-items'>
                  <div class='mr-3'>
                    <AdminSelect v-model='item.responsible_admin_id' label='Ответственный' />
                  </div>
                  <div>
                    <v-select
                      hide-details
                      v-model="item.status"
                      :items="STATUSES"
                      item-text='title'
                      item-value='id'
                      label="Статус"
                    ></v-select>
                  </div>
                </div>
                <TextEditor v-model='item.text' style='height: 82vh !important' />
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>

          <div class='flex-items align-center mt-3'>
            <v-chip close v-for='(attachment, index) in item.attachments' :key='index' @input='remove(index)'>
              <span v-if="typeof(attachment) === 'object'">{{ attachment.original_name }}</span>
              <span v-else>{{ attachment }}</span>
            </v-chip>
            <v-chip v-if='uploading_file_name !== null'>
              <span class='grey--text darken-5'>
                загрузка {{ uploading_file_name }}...
              </span>
            </v-chip>
            <v-btn @click='attach' :loading='uploading_file_name !== null' flat fab small class='my-0'>
              <v-icon style='font-size: 20px'>attach_file</v-icon>
            </v-btn>
            <span v-if='uploading_error' class='error--text'>размер файла больше 20мб</span>
          </div>

      </v-card>
    </v-dialog>
  </v-layout>
</template>

<script>

import { API_URL, STATUSES, MODEL_DEFAULTS } from './'
import { DialogMixin } from '@/mixins'
import { AdminSelect, TextEditor } from '@/components/UI'


export default {
  mixins: [ DialogMixin ],

  components: { AdminSelect, TextEditor },

  data() {
    return {
      API_URL,
      MODEL_DEFAULTS,
      STATUSES,
      uploading_file_name: null,
      uploading_error: false,
    }
  },

  watch: {
    dialog(newVal) {
      if (newVal === true) {
        this.$upload.on('file', {
          extensions: false,
          maxSizePerFile: 1024 * 1024 * 20,
          url: apiUrl('upload'),
          onSuccess(e, response) {
            console.log(response.data)
            this.item.attachments.push(response.data)
          },
          onError(a, b) {
            this.uploading_file_name = null
            this.uploading_error = true
          },
          onSelect(fileList) {
            this.uploading_file_name = fileList[0].name
            this.uploading_error = false
          },
          onEnd() {
            this.uploading_file_name = null
          }
        })
      } else {
        this.$upload.off('file')
      }
    },
  },

  methods: {
    attach() {
      this.$upload.select('file')
    },

    remove(index) {
      this.item.attachments.splice(index, 1)
    },
  }
}
</script>

<style lang="scss" scoped>
  .custom-toolbar {
    position: absolute;
    right: 3px;
    top: 4px;
    & > div {
      margin-right: 10px;
      width: 250px;
    }
  }
</style>
