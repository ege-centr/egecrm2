<template>
  <div>
    <a @click='openDialog'>
      {{ item.email }}
    </a>

    <v-layout row justify-center>
      <v-dialog v-model="dialog" max-width="1000px" scrollable>
        <v-card>
          <v-card-title class='title justify-center'>
            {{ item.email }}
          </v-card-title>
          <v-card-text class='email-messages'>
            <Loader v-if='messages === null' />
            <div v-else>
              <div v-for='message in messages' :key='message.id' class="mb-3 display-flex">
              <Avatar :photo='message.createdAdmin ? message.createdAdmin.photo : null' :size='50' class='mr-3' />
              <v-card class='email-messages__item grey lighten-4' :class='config.elevationClass'>
                <v-card-text class='py-2 px-3'>
                  <div class='display-flex align-center'>
                    <span class='font-weight-medium'>{{ message.createdAdmin ? message.createdAdmin.name : 'Неизвестный отправитель' }}</span>
                    <span class='ml-2 caption grey--text'>{{ message.created_at | date-time }}</span>
                  </div>
                  <div v-if='message.subject' class='font-weight-medium'>
                    {{ message.subject }}
                  </div>
                  <div>
                    {{ message.message }}
                  </div>
                  <div v-if='message.files.length' class='mt-2 grey--text small caption flex-items '>
                    <a v-for='file in message.files' :key='file.name' class='mr-2 flex-items align-center' target="_blank" :href="`/storage/img/upload/${file.name}`">
                      <v-icon style='font-size: 14px' class='mr-1'>attach_file</v-icon>
                      <span class='grey--text'>{{ file.original_name }}</span>
                    </a>
                  </div>
                </v-card-text>
              </v-card>
            </div>
            </div>
          </v-card-text>
          <v-card-actions class='v-card-actions--normal-padding email'>
            <div style='width: 100%'>
              <v-text-field label="Тема сообщения" v-model='subject' :counter='100'></v-text-field>
              <v-textarea v-model='message' label='Сообщение' :counter='1000' ref='textarea'
                @keydown.enter='handleCmdEnter($event)'
              >
              </v-textarea>
              <div class='flex-items align-center mt-3'>
                <div style='width: calc(100% - 130px)'>
                  <LoadingChip v-for="(file, index) in $upload.files('file').all" :key='file.$id' :file='file' @remove='remove(index)' />
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
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-layout>
  </div>
</template>

<script>

const API_URL = 'email-messages'

import LoadingChip from '@/components/UI/LoadingChip'

export default {
  props: ['item'],

  components: { LoadingChip },

  data() {
    return {
      dialog: false,
      sending: false,
      message: '',
      subject: '',
      files: [],
      messages: null,
      uploading_error: false,
    }
  },

  watch: {
    dialog(newVal) {
      if (newVal === true) {
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
      this.messages = null
      this.message = ''
      this.subject = ''
      this.dialog = true
      // Vue.nextTick(() => this.$refs.textarea.focus())
      axios.get(apiUrl(`${API_URL}?email=${this.item.email}`)).then(r => {
        this.messages = r.data
      })
    },

    send() {
      this.sending = true
      console.log('files', this.files)
      axios.post(apiUrl(API_URL), {
        subject: this.subject,
        message: this.message,
        email: this.item.email,
        files: this.files,
      }).then(r => {
        this.sending = false
        this.messages.unshift(r.data)
        this.message = ''
        this.subject = ''
        this.files = []
        this.$upload.reset('file')
      })
    },

    handleCmdEnter(event) {
      if (event.metaKey || event.ctrlKey) {
        this.send()
      }
    },

    remove(index) {
      this.files.splice(index, 1)
    },

    cancelUpload() {
      this.$upload.reset('file')
      this.uploading_file_name = null
    }
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
  .email-messages {
    height: 500px;
    position: relative;
    &__item {
      display: inline-block;
      min-width: 300px;
      max-width: 90%;
    }
    & .v-icon {
      cursor: default;
    }
  }
</style>