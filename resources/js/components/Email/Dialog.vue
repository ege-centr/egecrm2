<template>
  <v-layout row justify-center>
    <AddDialog ref='AddDialog' @added='(email) => emails.push(email)' />
    <v-snackbar v-model="uploading_error"
      :bottom="true"
      :timeout="6000"
      color='red'
    >
      общий размер файлов больше 20мб
    </v-snackbar>
    <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay content-class='email-dialog'>
      <v-card>
        <v-toolbar dark color="primary">
          <v-btn icon dark @click.native="dialog = false">
            <v-icon>close</v-icon>
          </v-btn>
          <v-toolbar-title>Отправка email</v-toolbar-title>
            <v-spacer></v-spacer>
          <v-toolbar-items>
            <v-btn dark flat :loading='sending' @click='send'>
              <!-- <v-icon>send</v-icon> -->
              отправить
            </v-btn>
          </v-toolbar-items>
        </v-toolbar>
        <v-card-text class='px-0' style='padding-top: 64px'>
          <v-form>
            <v-combobox
              v-model="emails"
              placeholder="Адреса"
              chips
              box
              multiple
              hide-details
              readonly
            >
              <template slot="selection" slot-scope="data">
                <v-chip
                  close
                  @input="removeEmail(data.item)"
                >
                  <span>{{ data.item }}</span>
                </v-chip>
              </template>
              <v-btn @click='$refs.AddDialog.open()' slot='append' flat fab small style='height: 32px; width: 32px; margin: 0'>
                <v-icon style='font-size: 20px'>add</v-icon>
              </v-btn>
            </v-combobox>
            <v-divider></v-divider>
            <v-text-field
              label="Тема"
              single-line
              full-width
              hide-details
              v-model='subject'
            ></v-text-field>
            <v-divider></v-divider>
            <v-textarea
              label="Сообщение"
              counter
              maxlength="1000"
              full-width
              auto-grow
              single-line
              @keydown.enter='handleCmdEnter($event)'
              ref='textarea'
              v-model='message' 
            ></v-textarea>
          </v-form>
          <hr class="v-divider theme--light">
          <div class='px-2 mt-2' style='padding-left: 12px !important'>
            <LoadingChip v-for="(file, index) in $upload.files('file').all" :key='file.$id' :file='file' @remove='removeFile(index)' />
            <v-btn @click='attach' flat fab small style='height: 32px; width: 32px; margin: 4px 0'>
              <v-icon style='font-size: 20px'>attach_file</v-icon>
            </v-btn>
          </div>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-layout>
</template>



<script>
import LoadingChip from '@/components/UI/LoadingChip'
import AddDialog from './AddDialog'
import { API_URL } from '@/components/Email'

export default {
  components: { LoadingChip, AddDialog },

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

    open(emails = []) {
      this.message = ''
      this.subject = ''
      this.emails = emails
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
  .email-dialog {
    & .v-textarea.v-text-field--enclosed.v-text-field--single-line .v-label {
      top: 8px !important;
    }
    & .v-input__slot {
      background: white !important;
      &:before, &:after {
        content: none !important;
      }
      & .v-select__selections {
        min-height: inherit !important;
        padding: 0 !important;
      }
    }

    & .v-select__slot {
      & .v-select__selections {
        flex: initial;
        & input {
          display: none;
        }
      }
      & .v-input__append-inner {
        align-self: center;
        margin: 0;
        padding: 0;
      }
    }

    & .v-chip {
      margin-left: 0;
    }
  }
</style>