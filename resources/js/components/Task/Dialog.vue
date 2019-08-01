<template>
  <v-layout row justify-center>
    <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay>
      <v-card>
        <v-toolbar dark color="primary">
          <v-toolbar-title>{{ edit_mode ? 'Редактирование' : 'Добавление' }} задачи</v-toolbar-title>
          <v-spacer></v-spacer>
          <TitleCredentials :item='item'/>
          <v-toolbar-items>
            <v-btn dark icon v-if='edit_mode' @click.native="destroy" :loading='destroying' class='mr-5'>
              <v-icon>delete</v-icon>
            </v-btn>
            <v-btn dark icon @click.native="storeOrUpdate" :loading='saving'>
              <v-icon>save_alt</v-icon>
            </v-btn>
            <v-btn icon dark @click.native="dialog = false">
              <v-icon>close</v-icon>
            </v-btn>
          </v-toolbar-items>
        </v-toolbar>
        <v-card-text>
          <Loader v-if='loading' class='loader-wrapper_fullscreen-dialog' />
          <v-container grid-list-xl class="pa-0 ma-0" fluid v-else>
            <v-layout>
              <v-flex md12 class='relative pa-0 mt-2'>
                <div class='custom-toolbar flex-items'>
                  <div class='mr-3'>
                    <AdminSelect v-model='item.responsible_admin_id' label='Ответственный' class-name='hide-bottom-border' />
                  </div>
                  <div>
                    <v-select
                      hide-details
                      v-model="item.status"
                      :items="STATUSES"
                      item-text='title'
                      item-value='id'
                      label="Статус"
                      class='hide-bottom-border'
                    ></v-select>
                  </div>
                </div>
                <TextEditor v-if='dialog' v-model='item.text' class='task-text-editor' />
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>

          <div v-if='!loading' class='flex-items align-center task-attachments'>
            <FileUploader v-if='dialog' :files.sync='item.files' :init='item.files' />
          </div>
      </v-card>
    </v-dialog>
  </v-layout>
</template>

<script>

import { API_URL, STATUSES, MODEL_DEFAULTS } from './'
import { DialogMixin } from '@/mixins'
import { AdminSelect, TextEditor } from '@/components/UI'
import FileUploader from '@/components/FileUploader'


export default {
  mixins: [ DialogMixin ],

  components: { AdminSelect, TextEditor, FileUploader },

  data() {
    return {
      API_URL,
      MODEL_DEFAULTS,
      STATUSES,
      uploading_file_name: null,
      uploading_error: false,
      maxFiles: 30,
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
}
</script>

<style lang="scss">
  .custom-toolbar {
    position: absolute;
    right: 3px;
    top: -4px;
    & > div {
      margin-right: 10px;
      width: 250px;
    }
  }

  .task-text-editor {
    & .quillWrapper  {
      height: initial !important;
    }
    & .ql-container {
        min-height: 500px !important;
        border-bottom: 1px solid #ccc !important;     
    }
  }

  .task-attachments {
    padding: 0 8px 20px;
  }
</style>
