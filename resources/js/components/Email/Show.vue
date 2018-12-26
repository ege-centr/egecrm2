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
          <v-card-text class='messages'>
            <Loader v-if='messages === null' />
            <div v-else>
              <div v-for='message in messages' :key='message.id' class="mb-3 display-flex">
              <Avatar :photo='message.createdAdmin ? message.createdAdmin.photo : null' :size='50' class='mr-3' />
              <v-card class='messages__item elevate-3 grey lighten-4'>
                <v-card-text class='py-2 px-3'>
                  <div class='display-flex align-center'>
                    <span class='font-weight-medium'>{{ message.createdAdmin ? message.createdAdmin.name : 'Неизвестный отправитель' }}</span>
                    <span class='ml-2 caption grey--text'>{{ message.created_at | date-time }}</span>
                  </div>
                  {{ message.message }}
                  <div v-if='message.attachments.length' class='mt-2 grey--text small caption flex-items '>
                    <a v-for='(attachment, index) in message.attachments' :key='index' class='mr-2 flex-items align-center' target="_blank" :href="`/storage/img/upload/${attachment}`">
                      <v-icon style='font-size: 14px' class='mr-1'>attach_file</v-icon>
                      <span class='grey--text'>Вложение {{ index + 1 }}</span>
                    </a>
                  </div>
                </v-card-text>
              </v-card>
            </div>
            </div>
          </v-card-text>
          <v-card-actions class='v-card-actions--normal-padding'>
            <div style='width: 100%'>
              <v-text-field label="Тема сообщения" v-model='subject'></v-text-field>
              <v-textarea v-model='message' label='Сообщение' :counter='true' ref='textarea' :loading='sending'
                @keydown.enter.prevent='send'
                @click:append='send'
                append-icon='send'>
              </v-textarea>
              <div class='flex-items align-center'>
                <v-chip close v-for='(attachment, index) in attachments' :key='attachment.filename' @input='remove(index)'>{{ attachment.original_name }}</v-chip>
                <v-btn @click='attach' :loading='uploading' flat fab small>
                  <v-icon style='font-size: 20px'>attach_file</v-icon>
                </v-btn>
                <!-- <span v-if='attachments.length' class='grey--text ml-2'>({{ attachments.length }} вложений)</span> -->
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

export default {
  props: ['item'],

  data() {
    return {
      dialog: false,
      sending: false,
      message: '',
      subject: '',
      attachments: [],
      messages: null,
      uploading: false,
    }
  },

  created() {
     this.$upload.on('file', {
       extensions: false,
       url: apiUrl('upload'),
       onSuccess(e, response) {
         console.log(response.data)
         this.attachments.push(response.data)
        //  this.$emit('photoChanged', null)
        //  Vue.nextTick(() => {
        //    this.$emit('photoChanged', response.data)
        //    this.dialog = true
        //  })
       },
       onError(a, b) {
         this.uploading = false
       },
       onStart() {
         this.uploading = true
       },
       onEnd() {
         this.uploading = false
       }
    })
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
      axios.post(apiUrl(API_URL), {
        subject: this.subject,
        message: this.message,
        email: this.item.email,
        attachments: _.map(this.attachments, e => e.filename),
      }).then(r => {
        this.sending = false
        this.messages.unshift(r.data)
        this.message = ''
        this.subject = ''
        this.attachments = []
      })
    },

    remove(index) {
      this.attachments.splice(index, 1)
    },
  }
}
</script>

<style lang='scss' scoped>
  .v-input__append-inner {
    align-self: flex-end !important;
    & i {
      font-size: 24px;
      margin-bottom: 18px;
    }
  }
  .messages {
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