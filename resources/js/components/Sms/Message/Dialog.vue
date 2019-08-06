<template>
  <v-layout row justify-center>
    <v-dialog persistent v-model="dialog" scrollable transition="dialog-bottom-transition" fullscreen hide-overlay content-class='email-dialog'>
      <v-card>
        <v-toolbar dark color="primary">
          
          <v-toolbar-title>Отправка смс</v-toolbar-title>
            <v-spacer></v-spacer>
          <v-toolbar-items>
            <v-btn icon dark  :loading='sending' @click='send'>
              <v-icon>send</v-icon>
            </v-btn>
            <v-btn icon dark @click.native="dialog = false">
              <v-icon>close</v-icon>
            </v-btn>
          </v-toolbar-items>
        </v-toolbar>
        <v-card-text class='px-0' style='padding-top: 70px'>
          <v-form class='sms-message-form'>
            <v-text-field 
              :readonly='!customInput'
              v-mask="'+7 (###) ###-##-##'"
              single-line
              full-width
              v-model="phone"
              :error-messages='errorMessages.phone'
              :hide-details="!errorMessages.hasOwnProperty('phone')"
              label='Телефон'
            >
            </v-text-field>
            <v-divider></v-divider>
            <v-textarea
              label="Сообщение"
              full-width
              auto-grow
              :error-messages='errorMessages.text'
              :hide-details="!errorMessages.hasOwnProperty('text')"
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
            
            <div v-if='templates !== null' class='ml-2'>
              <v-chip 
                v-for='template in templates' :key='template.id'  
                @click='text = template.text'
                class='pointer'>
                {{ template.title }}
              </v-chip>
            </div>
          </v-form>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-layout>
</template>

<script>

import { API_URL } from './'
import { API_URL as TEMPLATES_API_URL } from '@/components/Sms/Template'

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
      errorMessages: {},
      sending: false,
      phone: '',
      text: '',
      templates: null,
    }
  },

  methods: {
    open(phone) {
      this.loadTemplates()
      this.phone = phone
      this.text = ''
      this.dialog = true
      // Vue.nextTick(() => this.$refs.textarea.focus())
    },

    send() {
      this.sending = true
      this.errorMessages = {}
      axios.post(apiUrl(API_URL, 'send'), {
        text: this.text,
        phone: this.phone
      })
      .then(r => {
        this.text = ''
        this.dialog = false
      })
      .catch(e => {
        this.errorMessages = e.response.data.errors
      })
      .then(() => this.sending = false)
    },

    loadTemplates() {
      axios.get(apiUrl(TEMPLATES_API_URL), {
        params: {
          type: 'manual'
        }
      }).then(r => {
        this.templates = r.data.data
      })
    }
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