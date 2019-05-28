<template>
  <v-layout row justify-center>
    <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay>
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
                      <div class='grey--text' style='position: relative; top: 1px'>
                        заполняется учеником из его ЛК
                      </div>
                    </div>
                    <div class='flex-items align-center'>
                      <span class='mr-1 subheading'>Оценка</span>
                      <v-menu>
                        <v-btn class='v-btn_xs' small fab flat slot='activator' 
                          :dark="getComment(COMMENT_TYPE.client).rating > 0"
                          :class="getColorClass(getComment(COMMENT_TYPE.client).rating)"
                        >
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
                    <div class='absolute-credentials grey--text' v-if='getComment(COMMENT_TYPE.client) && getComment(COMMENT_TYPE.client).createdUser'>
                      {{ getComment(COMMENT_TYPE.client).createdUser.default_name }}
                      {{ getComment(COMMENT_TYPE.client).created_at | date-time }}
                    </div>
                    <hr class="v-divider theme--light">
                  </v-flex>



                  <v-flex md12 class='pb-0'>
                    <div class='flex-items mb-2'>
                      <div class='title font-weight-bold mr-2'>
                        Предварительная оценка
                      </div>
                      <div class='grey--text' style='position: relative; top: 1px'>
                        заполняется администратором после 1–2 занятий
                      </div>
                    </div>
                    <div class='flex-items align-center'>
                      <span class='mr-1 subheading'>Оценка</span>
                      <v-menu>
                        <v-btn class='v-btn_xs' small fab flat slot='activator' 
                          :dark="getComment(COMMENT_TYPE.admin).rating > 0"
                          :class="getColorClass(getComment(COMMENT_TYPE.admin).rating)">
                          <span v-if="getComment(COMMENT_TYPE.admin).rating > 0">
                            {{ getComment(COMMENT_TYPE.admin).rating }}
                          </span>
                          <v-icon v-else-if='getComment(COMMENT_TYPE.admin).rating === -1'>remove</v-icon>
                          <v-icon v-else>edit</v-icon>
                        </v-btn>
                        <v-list dense>
                          <v-list-tile v-for='rating in [null, -1, 1, 2, 3, 4, 5]' :key='rating' @click='setRating(rating, COMMENT_TYPE.admin)'>
                              <v-list-tile-title>
                                <span v-if='rating === null' class='grey--text'>
                                  не установлено
                                </span>
                                <span v-else-if='rating === -1'>
                                  не собирать
                                </span>
                                <span v-else>
                                  {{ rating }}
                                </span>
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
                        v-model="getComment(COMMENT_TYPE.admin).text"></v-textarea>
                    </div>
                    <div class='absolute-credentials grey--text' v-if='getComment(COMMENT_TYPE.admin) && getComment(COMMENT_TYPE.admin).createdUser'>
                      {{ getComment(COMMENT_TYPE.admin).createdUser.default_name }}
                      {{ getComment(COMMENT_TYPE.admin).created_at | date-time }}
                    </div>
                    <hr class="v-divider theme--light">
                  </v-flex>

                  
                   <v-flex md12 class='pb-0'>
                    <div class='flex-items mb-2'>
                      <div class='title font-weight-bold mr-2'>
                        Финальная оценка
                      </div>
                      <div class='grey--text' style='position: relative; top: 1px'>
                        заполняется администратором после окончания всех занятий
                      </div>
                    </div>
                    <div class='flex-items align-center'>
                      <span class='mr-1 subheading'>Оценка</span>
                      <v-menu>
                        <v-btn class='v-btn_xs' small fab flat slot='activator' 
                          :dark="getComment(COMMENT_TYPE.final).rating > 0"
                          :class="getColorClass(getComment(COMMENT_TYPE.final).rating)">
                          <span v-if="getComment(COMMENT_TYPE.final).rating > 0">
                            {{ getComment(COMMENT_TYPE.final).rating }}
                          </span>
                          <v-icon v-else-if='getComment(COMMENT_TYPE.final).rating === -1'>remove</v-icon>
                          <v-icon v-else>edit</v-icon>
                        </v-btn>
                        <v-list dense>
                          <v-list-tile v-for='rating in [null, -1, 1, 2, 3, 4, 5]' :key='rating' @click='setRating(rating, COMMENT_TYPE.final)'>
                              <v-list-tile-title>
                                <span v-if='rating === null' class='grey--text'>
                                  не установлено
                                </span>
                                <span v-else-if='rating === -1'>
                                  не собирать
                                </span>
                                <span v-else>
                                  {{ rating }}
                                </span>
                              </v-list-tile-title>
                          </v-list-tile>
                        </v-list>
                      </v-menu>
                    </div>
                  </v-flex>
                  <v-flex md12 class='pa-0 relative'>
                    <div>
                      <v-textarea 
                        class='textarea-padding-fix'
                        full-width
                        auto-grow
                        hide-details
                        label='Текст отзыва' 
                        v-model="getComment(COMMENT_TYPE.final).text"></v-textarea>
                    </div>
                    <div>
                      <v-text-field 
                        hide-details
                        full-width 
                        v-model='item.signature' 
                        label='Подпись'
                      ></v-text-field>
                    </div>
                    <div>
                      <v-text-field 
                        hide-details
                        full-width 
                        v-model='item.expressive_title' 
                        label='Экспрессивный заголовок'
                      ></v-text-field>
                    </div>
                    <div class='no-border-input mt-0'>
                      <div class='flex-items align-center inline-inputs'>
                        <v-text-field style='width: 128px' hide-details v-model='item.score' label='Набранный балл' />
                        <span class='grey--text two-inputs-separator'>/</span>
                        <v-text-field style='width: 200px' hide-details v-model='item.max_score' label='Максимальный балл' />
                      </div>
                    </div>
                    <div class='no-border-input'>
                      <AdminSelect v-model='item.reviewer_admin_id' label='Ответственный' />
                    </div>
                  </v-flex>
                  <v-flex md12 class='pb-0 mt-2'>
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
                  <v-flex md12 class='pa-0 mb-5 mt-3 relative'>
                     <div class='absolute-credentials grey--text' v-if='getComment(COMMENT_TYPE.final) && getComment(COMMENT_TYPE.final).createdUser'>
                      {{ getComment(COMMENT_TYPE.final).createdUser.default_name }}
                      {{ getComment(COMMENT_TYPE.final).created_at | date-time }}
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
import { AdminSelect } from '@/components/UI'
import { getColorClass } from '@/components/Report'

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
  },
}
</script>