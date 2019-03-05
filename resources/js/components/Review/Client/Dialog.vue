<template>
  <v-layout row justify-center>
    <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay>
      <v-card>
        <v-toolbar dark color="primary">
          <v-btn icon dark @click.native="dialog = false">
            <v-icon>close</v-icon>
          </v-btn>
          <v-toolbar-title>{{ edit_mode ? 'Редактирование' : 'Добавление' }} отзыва</v-toolbar-title>
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
              <v-flex md6>
                <div class='mb-3'>
                  <div class='flex-items align-center'>
                    <span class='caption mr-3 input-label'>Оценка</span>
                    <v-rating dense clearable v-model="item.comments[0].rating"></v-rating>
                  </div>
                </div>
                <div>
                  <v-textarea hide-details label='Отзыв' v-model='item.comments[0].text'></v-textarea>
                </div>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-layout>
</template>

<script>

import { API_URL, MODEL_DEFAULTS } from '../'
import { DialogMixin } from '@/mixins'

export default {
  mixins: [ DialogMixin ],

  data() {
    return {
      API_URL,
      MODEL_DEFAULTS,
    }
  },

  methods: {
    getRatingColor(rating) {
      if (rating <= 3) {
        return 'red'
      }
      if (rating === 4) {
        return 'orange'
      }
      if (rating === 5) {
        return 'green'
      }
      return 'primary'
    }
  }
}
</script>