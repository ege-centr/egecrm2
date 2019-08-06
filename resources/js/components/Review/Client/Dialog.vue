<template>
  <v-layout row justify-center>
    <v-dialog persistent v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay>
      <v-card>
        <v-toolbar dark color="primary">
          <v-toolbar-title>{{ edit_mode ? 'Редактирование' : 'Добавление' }} отзыва</v-toolbar-title>
          <v-spacer></v-spacer>
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
              <v-flex md12>
                <v-layout wrap>
                  <v-flex md12 class='pb-0'>
                    <div class='flex-items mb-2'>
                      <div class='title font-weight-bold mr-2'>
                        Отзыв ученика 
                      </div>
                    </div>
                    <div class='flex-items align-center'>
                      <span class='mr-1 subheading'>Оценка</span>
                      <v-menu>
                        <v-btn class='v-btn_xs' small fab flat slot='activator' 
                          :dark="getComment(COMMENT_TYPE.client).rating > 0"
                          :class="getColorClass(getComment(COMMENT_TYPE.client).rating)">
                          <span v-if="getComment(COMMENT_TYPE.client).rating > 0">
                            {{ getComment(COMMENT_TYPE.client).rating }}
                          </span>
                          <v-icon v-else>edit</v-icon>
                        </v-btn>
                        <v-list dense>
                          <v-list-tile @click='setRating(null, COMMENT_TYPE.client)'>
                            <v-list-tile-title class='grey--text'>
                              не установлено
                            </v-list-tile-title>
                          </v-list-tile>
                          <v-list-tile v-for='rating in [1, 2, 3, 4, 5]' :key='rating' @click='setRating(rating, COMMENT_TYPE.client)'>
                              <v-list-tile-title>
                                {{ rating }}
                              </v-list-tile-title>
                          </v-list-tile>
                        </v-list>
                      </v-menu>
                    </div>
                  </v-flex>
                  <v-flex md12 class='pa-0 relative'>
                    <div>
                      <v-textarea 
                        full-width
                        auto-grow
                        class='textarea-padding-fix'
                        label='Комментарий' 
                        v-model="getComment(COMMENT_TYPE.client).text"></v-textarea>
                    </div>
                    <hr class="v-divider theme--light">
                  </v-flex>
                </v-layout>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-layout>
</template>

<script>

import { API_URL, MODEL_DEFAULTS, COMMENT_TYPE } from '../'
import { DialogMixin } from '@/mixins'
import { getColorClass } from '@/components/Report'

export default {
  mixins: [ DialogMixin ],

  data() {
    return {
      API_URL,
      MODEL_DEFAULTS,
      COMMENT_TYPE,
    }
  },

  methods: {
   getColorClass,

   getComment(type) {
      const index = this.item.comments.findIndex(e => e.type === type)
      if (index === -1) {
        this.item.comments.push({
          rating: null,
          text: '',
          type,
        })
      }
      return this.item.comments.find(e => e.type === type)
    },

    setRating(rating, type) {
      this.getComment(type).rating = rating
    },
  }
}
</script>