<template>
  <v-layout row justify-center>
    <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay>
      <v-card>
        <v-toolbar dark color="primary">
          <v-btn icon dark @click.native="dialog = false">
            <v-icon>close</v-icon>
          </v-btn>
          <v-toolbar-title>История переписки с {{ phone }}</v-toolbar-title>
        </v-toolbar>
        <v-card-text class='email-messages'>
            <Loader transparent v-if='items === null' />
            <div v-else>
              <div v-if='items.length > 0'>
                <div v-for='item in items' :key='item.id' class="mb-3 display-flex">
                  <Avatar :photo='item.createdUser ? item.createdUser.photo : null' :size='50' class='mr-3' />
                  <v-card class='email-messages__item elevation-0 mb-4'>
                    <v-card-text class='py-0 px-3'>
                      <div class='display-flex align-center'>
                        <span class='font-weight-medium'>{{ item.createdUser ? item.createdUser.default_name : 'Неизвестный отправитель' }}</span>
                        <span class='ml-2 caption grey--text'>{{ item.created_at | date-time }}</span>
                        <v-tooltip bottom class='ml-2 cursor-default'>
                          <v-icon small slot="activator" :class="{
                            'green--text': item.status == 1,
                            'red--text': item.status < -1 || item.status > 2
                          }">
                            {{ item.status == 1 ? 'done_all' : ((item.status < -1 || item.status > 2) ? 'error' : 'done') }}
                          </v-icon>
                          <span>{{ item.status_name }}</span>
                        </v-tooltip>
                      </div>
                      <div>
                        {{ item.message }}
                      </div>
                    </v-card-text>
                  </v-card>
                </div>
              </div>
              <div v-else class='full-height-vh flex-items align-center justify-center grey--text' style='flex-direction: column'>
                <div style='opacity: .2'>
                  <v-icon size='100'>mail_outline</v-icon>
                </div>
                <div class='subheading'>
                  история смс пуста
                </div>
              </div>
          </div>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-layout>
</template>



<script>

import { API_URL } from './'

export default {
  data() {
    return {
      dialog: false,
      items: null,
      phone: null,
    }
  },

  methods: {
    open(phone) {
      this.items = null
      this.dialog = true
      this.phone = phone
      // Vue.nextTick(() => this.$refs.textarea.focus())
      axios.get(apiUrl(`${API_URL}?phone=${phone}`)).then(r => {
        this.items = r.data
      })
    },
  }
}
</script>