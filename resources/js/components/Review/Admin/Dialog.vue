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
              <v-flex md5>
                <v-layout wrap>
                  <v-flex md12 class='mb-4'>
                    <div class='font-weight-medium'>
                      Оценка и отзыв ученика (заполняется учеником из его личного кабинета)
                    </div>
                    <div class='caption grey--text' v-if='getComment(COMMENT_TYPE.client)'>
                      {{ getComment(COMMENT_TYPE.client).createdUser.default_name }}
                      {{ getComment(COMMENT_TYPE.client).created_at | date-time }}
                    </div>
                    <div class='mb-3 mt-2'>
                      <div class='flex-items align-center'>
                        <span class='caption mr-3 input-label'>Оценка</span>
                        <v-rating dense clearable v-model="getComment(COMMENT_TYPE.client).rating"></v-rating>
                      </div>
                    </div>
                    <div>
                      <v-textarea auto-grow hide-details label='Отзыв' v-model='getComment(COMMENT_TYPE.client).text'></v-textarea>
                    </div>
                  </v-flex>
                  <v-flex md12 class='mb-4'>
                    <div class='font-weight-medium'>
                      Предварительная оценка и отзыв ученика (заполняется администратором)
                    </div>
                    <div class='caption grey--text' v-if='getComment(COMMENT_TYPE.admin)'>
                      {{ getComment(COMMENT_TYPE.admin).createdUser.default_name }}
                      {{ getComment(COMMENT_TYPE.admin).created_at | date-time }}
                    </div>
                    <div class='mb-3 mt-2'>
                      <div class='flex-items align-center'>
                        <span class='caption mr-3 input-label'>Оценка</span>
                        <div class='v-rating v-rating--dense'>
                          <v-icon class='cursor-default' v-if='getComment(COMMENT_TYPE.admin).rating === -1'>star</v-icon>
                          <v-icon class='pointer' 
                            v-if='getComment(COMMENT_TYPE.admin).rating !== -1'
                            @click='getComment(COMMENT_TYPE.admin).rating = -1'>star_border</v-icon>
                        </div>
                        <v-rating dense clearable v-model="getComment(COMMENT_TYPE.admin).rating"></v-rating>
                      </div>
                    </div>
                    <div>
                      <v-textarea auto-grow hide-details label='Отзыв' v-model='getComment(COMMENT_TYPE.admin).text'></v-textarea>
                    </div>
                  </v-flex>
                  <v-flex md12 class='mb-4'>
                    <div class='font-weight-medium'>
                      Оценка и отзыв ученика по окончании занятий (заполняется администратором)
                    </div>
                    <div class='caption grey--text' v-if='getComment(COMMENT_TYPE.final)'>
                      {{ getComment(COMMENT_TYPE.final).createdUser.default_name }}
                      {{ getComment(COMMENT_TYPE.final).created_at | date-time }}
                    </div>
                    <div class='mb-3 mt-2'>
                      <div class='flex-items align-center'>
                        <span class='caption mr-3 input-label'>Оценка</span>
                        <div class='v-rating v-rating--dense'>
                          <v-icon class='cursor-default' v-if='getComment(COMMENT_TYPE.final).rating === -1'>star</v-icon>
                          <v-icon class='pointer' 
                            v-if='getComment(COMMENT_TYPE.final).rating !== -1'
                            @click='getComment(COMMENT_TYPE.final).rating = -1'>star_border</v-icon>
                        </div>
                        <v-rating dense clearable v-model="getComment(COMMENT_TYPE.final).rating"></v-rating>
                      </div>
                    </div>
                    <div>
                      <v-textarea auto-grow hide-details label='Отзыв' v-model='getComment(COMMENT_TYPE.final).text'></v-textarea>
                    </div>
                  </v-flex>
                </v-layout>
              </v-flex>
              <v-flex md6 offset-md1>
                <v-layout wrap>
                  <v-flex md12>
                    <v-textarea label='Экспрессивный заголовок' hide-details auto-grow v-model='item.expressive_title'></v-textarea>
                  </v-flex>
                  <v-flex md12>
                    <v-text-field hide-details v-model='item.signature' label='Подпись'></v-text-field>
                  </v-flex>
                  <v-flex md12>
                    <div class='flex-items align-center'>
                      <v-text-field hide-details v-model='item.score' label='Балл' />
                      <span class='grey--text two-inputs-separator'>/</span>
                      <v-text-field hide-details v-model='item.max_score' label='Максимальный' />
                    </div>
                  </v-flex>
                  <v-flex md12>
                    <AdminSelect v-model='item.reviewer_admin_id' label='Ответственный' />
                  </v-flex>
                  <v-flex md12>
                     <v-switch class='ma-0'
                      label="Опубликован"
                      hide-details
                      v-model='item.is_published'
                      ></v-switch>
                  </v-flex>
                  <v-flex md12>
                     <v-switch class='ma-0'
                      label="Подтвержден"
                      hide-details
                      v-model='item.is_approved'
                      ></v-switch>
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
import { AdminSelect } from '@/components/UI'

export default {
  mixins: [ DialogMixin ],

  components: { AdminSelect },

  data() {
    return {
      API_URL,
      MODEL_DEFAULTS,
      COMMENT_TYPE,
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
    },

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
    }
  },
}
</script>