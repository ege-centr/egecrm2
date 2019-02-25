<template>
  <div>
    <EmailHistory ref='EmailHistory' />

    <v-menu>
      <a slot='activator'>
        {{ item.email }}
      </a>
      <v-list dense>
        <v-list-tile @click='openDialog'>
          <v-list-tile-action>
            <v-icon>email</v-icon>
          </v-list-tile-action>
          <v-list-tile-title>написать</v-list-tile-title>
        </v-list-tile>
        <v-list-tile @click='$refs.EmailHistory.open(item.email)'>
          <v-list-tile-action>
            <v-icon>history</v-icon>
          </v-list-tile-action>
          <v-list-tile-title>история</v-list-tile-title>
        </v-list-tile>
      </v-list>
    </v-menu>

    <v-layout row justify-center>
      <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay>
        <v-card>
          <v-toolbar dark color="primary">
            <v-btn icon dark @click.native="dialog = false">
              <v-icon>close</v-icon>
            </v-btn>
            <v-toolbar-title>Отправка email</v-toolbar-title>
          </v-toolbar>
          <v-card-text>
            <div style='width: 100%'>
              <v-combobox
                v-model="emails"
                label="Адреса"
                chips
                multiple
              >
                <template slot="selection" slot-scope="data">
                  <v-chip
                    close
                    @input="removeEmail(data.item)"
                  >
                    <span>{{ data.item }}</span>
                  </v-chip>
                </template>
              </v-combobox>
              <v-text-field label="Тема сообщения" v-model='subject' :counter='100'></v-text-field>
              <v-textarea v-model='message' label='Сообщение' :counter='1000' ref='textarea'
                @keydown.enter='handleCmdEnter($event)'
              >
              </v-textarea>
              <div class='flex-items align-center mt-3'>
                <div style='width: calc(100% - 130px)'>
                  <LoadingChip v-for="(file, index) in $upload.files('file').all" :key='file.$id' :file='file' @remove='removeFile(index)' />
                  <v-btn @click='attach' flat fab small style='height: 34px; width: 34px; margin: 4px'>
                    <v-icon style='font-size: 20px'>attach_file</v-icon>
                  </v-btn>
                  <span v-if='uploading_error' class='ml-2 error--text d-inline-block'>
                    суммарный размер файлов больше 20Мб
                  </span>
                </div>
                <v-spacer></v-spacer>
                <v-btn flat color='primary' :loading='sending' @click='send' style='align-self: flex-start'>отправить</v-btn>
              </div>
            </div>
          </v-card-text>
        </v-card>
      </v-dialog>
    </v-layout>
  </div>
</template>

<script>

import LoadingChip from '@/components/UI/LoadingChip'
import { API_URL } from './'
import EmailHistory from './History'

export default {
  props: ['item'],

  components: { LoadingChip, EmailHistory },

  data() {
    return {
      dialog: false,
      sending: false,
      message: '',
      subject: '',
      files: [],
      emails: [],
      uploading_error: false,
    }
  },

  watch: {
    dialog(newVal) {
      if (newVal === true) {
        this.emails = [this.item.email]
        this.$upload.on('file', {
          extensions: false,
          multiple: true,
          // 100mb, но ограничение на самом деле 20
          maxSizePerFile: 1024 * 1024 * 100,
          maxFilesSelect: 20,
          url: apiUrl('upload'),
          onSuccess(e, response) {
            this.files.push(response.data)
          },
          // onError(a, b) {
          //   this.uploading_error = true
          // },
          onBeforeSelect(fileList) {
            this.uploading_error = false
            let size = 0
            this.$upload.files('file').all.forEach(file => size += file.size)
            _.each(fileList, file => size += file.size)
            if (size / 1024 / 1024 >= 20) {
              this.uploading_error = true
              return false
            }
            return true
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

    openDialog() {
      this.message = ''
      this.subject = ''
      this.dialog = true
      // Vue.nextTick(() => this.$refs.textarea.focus())
    },

    send() {
      this.sending = true
      axios.post(apiUrl(API_URL), {
        subject: this.subject,
        message: this.message,
        emails: this.emails,
        files: this.files,
      }).then(r => {
        this.message = ''
        this.subject = ''
        this.files = []
        this.$upload.reset('file')
        this.dialog = false
        this.emails = []
        this.waitForDialogClose(() => this.sending = false)
      })
    },

    handleCmdEnter(event) {
      if (event.metaKey || event.ctrlKey) {
        this.send()
      }
    },

    removeFile(index) {
      this.files.splice(index, 1)
    },

    removeEmail(email) {
      this.emails.splice(this.emails.indexOf(email), 1)
    },

    cancelUpload() {
      this.$upload.reset('file')
      this.uploading_file_name = null
    },
  }
}
</script>

<style lang='scss'>
  .email {
    & .v-input__append-inner {
      align-self: flex-end !important;
      & i {
        font-size: 24px;
        margin-bottom: 18px;
      }
    }
  }
</style>