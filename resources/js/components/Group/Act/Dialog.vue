<template>
  <v-layout row justify-center>
    <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay>
      <v-card>
        <v-toolbar dark color="primary">
          <v-toolbar-title>{{ edit_mode ? 'Редактирование' : 'Добавление' }} акта</v-toolbar-title>
          <TitleCredentials :item='item'/>
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
                <div class='vertical-inputs'>
                  <div class='vertical-inputs__input'>
                    <TeacherSelect v-model="item.teacher_id" :error-messages='errorMessages.teacher_id' />
                  </div>
                  <div class='vertical-inputs__input'>
                    <v-text-field v-model='item.lesson_count' label='Количество занятий' 
                      :error-messages="errorMessages.lesson_count"
                      :hide-details="errorMessages.lesson_count === undefined"
                     v-mask="'####'"></v-text-field>
                  </div>
                  <div class='vertical-inputs__input'>
                    <v-text-field v-model='item.sum' label='Сумма' 
                      :error-messages="errorMessages.sum"
                      :hide-details="errorMessages.sum === undefined"
                    v-mask="'######'"></v-text-field>
                  </div>
                  <div class='vertical-inputs__input'>
                    <DatePicker label="Дата в акте" v-model='item.date' :error-messages='errorMessages.date' />
                  </div>
                  <div class='vertical-inputs__input'>
                    <DatePicker label="Дата от" v-model='item.date_from' :error-messages='errorMessages.date_from' />
                  </div>
                  <div class='vertical-inputs__input'>
                    <DatePicker label="Дата до" v-model='item.date_to' :error-messages='errorMessages.teacher_id' />
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
import { DatePicker, TeacherSelect } from '@/components/UI'

export default {
  mixins: [ DialogMixin ],

  components: { DatePicker, TeacherSelect },

  data() {
    return {
      API_URL,
      MODEL_DEFAULTS,
    }
  },
}
</script>