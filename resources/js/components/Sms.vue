<template>
  <v-layout row justify-center>
    <v-dialog v-model="dialog" max-width="1000px" scrollable>
      <v-card>
        <v-card-title class='title justify-center'>
          {{ phone }}
        </v-card-title>
        <v-card-text class='messages'>
          <Loader v-if='messages === null' />
          <div v-else>
            <div v-for='(message, index) in messages' :key='index' class="mb-3 display-flex">
              <Avatar :photo='message.createdAdmin.photo' :size='50' class='mr-3' />
              <v-card class='messages__item elevate-3 grey lighten-4'>
                <v-card-text class='py-2 px-3'>
                  <div class='display-flex align-center'>
                    <span class='font-weight-medium'>{{ message.createdAdmin.name }}</span>
                    <span class='ml-2 caption grey--text'>{{ message.created_at | date-time }}</span>
                  </div>
                  {{ message.text }}
                </v-card-text>
              </v-card>
              <v-tooltip bottom style='align-self: center' class='ml-2'>
                <v-icon small slot="activator">done</v-icon>
                <span>в пути</span>
              </v-tooltip>
            </div>
          </div>
        </v-card-text>
        <v-card-actions class='v-card-actions--normal-padding'>
          <v-textarea v-model='text' label='Сообщение' :counter='true' ref='textarea' :loading='sending'
            @keydown.enter.prevent='send'
            @click:append='send'
            append-icon='send'>
          </v-textarea>
        </v-card-actions>
        <!-- <v-card-actions class='justify-center'>
          <v-btn color="blue darken-1" flat @click.native="dialog = false">Закрыть</v-btn>
        </v-card-actions> -->
      </v-card>
    </v-dialog>
  </v-layout>
</template>

<script>

const url = 'sms'

export default {
  data() {
    return {
      dialog: false,
      sending: false,
      phone: '',
      text: '',
      messages: null
    }
  },
  methods: {
    init(phone) {
      this.phone = phone
      this.messages = null
      this.text = ''
      this.dialog = true
      Vue.nextTick(() => this.$refs.textarea.focus())
      axios.get(apiUrl(`${url}?phone=${this.phone}`)).then(r => {
        this.messages = r.data
      })
    },
    send() {
      this.sending = true
      axios.post(apiUrl(url, 'send'), {
        text: this.text,
        phone: this.phone
      }).then(r => {
        console.log(r.data)
        this.sending = false
        this.messages.push(r.data)
        this.text = ''
      })
    }
  }
}
</script>

<style lang='scss'>
  .v-input__append-inner {
    align-self: flex-end !important;
    & i {
      font-size: 34px;
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
