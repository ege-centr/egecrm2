<template>
  <v-layout row justify-center>
    <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay>
      <v-card>
        <v-toolbar dark color="primary">
          <v-btn icon dark @click.native="dialog = false">
            <v-icon>close</v-icon>
          </v-btn>
          <v-toolbar-title>{{ edit_mode ? 'Редактирование' : 'Добавление' }} пробного ЕГЭ</v-toolbar-title>
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
              <v-flex md12>
                <div class='vertical-inputs'>
                  <div class='vertical-inputs__input'>
                    <v-text-field hide-details v-model='item.sum' label='Цена' />
                  </div>
                  <div class='vertical-inputs__input'>
                    <DataSelect type='subjects' v-model='item.subject_id' />
                  </div>
                  <div class='vertical-inputs__input'>
                    <DataSelect type='grades' v-model='item.grade_id' />
                  </div>
                  <div class='vertical-inputs__input'>
                    <DataSelect type='years' v-model='item.year' />
                  </div>
                  <div class='vertical-inputs__input'>
                    <DatePicker v-model='item.date' label='Дата' />
                  </div>
                  <div class='vertical-inputs__input'>
                    <div class='flex-items align-center ege-trail-score'>
                      <v-text-field hide-details v-model='item.score' label='Балл' />
                      <span class='grey--text ege-trail-score__separator'>/</span>
                      <v-text-field hide-details v-model='item.max_score' label='Максимальный' />
                    </div>
                  </div>
                  <div class='vertical-inputs__input'>
                    <v-text-field hide-details v-model='item.description' label='Описание' />
                  </div>
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

import { API_URL, MODEL_DEFAULTS } from './'
import { DialogMixin } from '@/mixins'
import { DataSelect, DatePicker } from '@/components/UI'

export default {
  mixins: [ DialogMixin ],

  components: { DataSelect, DatePicker },

  data() {
    return {
      API_URL,
      MODEL_DEFAULTS,
    }
  },

  methods: {

  }
}
</script>



<style lang="scss">
.ege-trail-score {
  & .v-input {
    width: 135px;
  }

  &__separator {
    margin: 0 10px;
    font-size: 16px;
    top: 14px;
    position: relative;
  }
}
</style>
