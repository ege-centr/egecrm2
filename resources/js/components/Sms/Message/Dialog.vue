<template>
  <v-layout row justify-center>
    <v-dialog v-model="dialog" scrollable transition="dialog-bottom-transition" fullscreen hide-overlay content-class='email-dialog'>
      <v-card>
        <v-toolbar dark color="primary">
          
          <v-toolbar-title>Отправка смс <span v-if='!customInput'>на {{ phone }}</span></v-toolbar-title>
            <v-spacer></v-spacer>
          <v-toolbar-items>
            <v-btn icon dark  :loading='sending' @click='send' :disabled="phone.length !== 18 || text.length === 0">
              <v-icon>send</v-icon>
            </v-btn>
            <v-btn icon dark @click.native="dialog = false">
              <v-icon>close</v-icon>
            </v-btn>
          </v-toolbar-items>
        </v-toolbar>
        <v-card-text class='px-0' style='padding-top: 70px'>
          <v-form class='sms-message-form'>
            <v-text-field hide-details
              v-if='customInput'
              v-mask="'+7 (###) ###-##-##'"
              single-line
              full-width
              v-model="phone"
              label='Телефон'
            >
            </v-text-field>
            <v-divider v-if='customInput'></v-divider>
            <v-textarea
              label="Сообщение"
              full-width
              auto-grow
              hide-details
              single-line
              ref='textarea'
              v-model='text' 
            >
              <template v-slot:append>
                <div class='v-counter sms-counter'>
                  <div class='d-inline-block text-sm-center' style='width: 40px'>
                    {{ counter.messages }} SMS
                  </div>
                </div>
              </template>
            </v-textarea>
            <v-divider></v-divider>
          </v-form>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-layout>
</template>

<script>

import { API_URL } from './'

export default {
  props: {
    customInput: {
      type: Boolean,
      default: false,
    }
  },

  data() {
    return {
      dialog: false,
      sending: false,
      phone: '',
      text: '',
    }
  },

  methods: {
    open(phone) {
      this.phone = phone
      this.text = ''
      this.dialog = true
      // Vue.nextTick(() => this.$refs.textarea.focus())
    },

    send() {
      this.sending = true
      axios.post(apiUrl(API_URL, 'send'), {
        text: this.text,
        phone: this.phone
      }).then(r => {
        this.sending = false
        this.text = ''
        this.dialog = false
      })
    },    
  },

  computed: {
    counter() {
      return SmsCounter.count(this.text)
    }
  }
}
</script>

<style lang='scss'>
.sms-message-form {
  & .v-textarea {
    & .v-label {
      top: 8px !important;
    }
  }
}

.sms-counter {
    color: rgba(0, 0, 0, 0.54);
    position: absolute;
    bottom: 6px;
    margin: 0 !important;
    right: 12px;
}
</style>