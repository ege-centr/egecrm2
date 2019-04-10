<template>
  <v-layout row justify-center>
    <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay>
      <v-card>
        <v-toolbar dark color="primary">
          <v-btn icon dark @click.native="dialog = false">
            <v-icon>close</v-icon>
          </v-btn>
          <v-toolbar-title>{{ edit_mode ? 'Редактирование' : 'Добавление' }} занятия</v-toolbar-title>
          <v-spacer></v-spacer>
          <TitleCredentials v-if='item && item.status === LESSON_STATUS.CONDUCTED' :item='item' user-field='conductedUser' time-field='conducted_at' />
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
                    <DatePicker v-if='dialog' label="Дата занятия" v-model='item.date' />
                  </div>
                  <div class='vertical-inputs__input'>
                    <v-text-field hide-details v-model='item.time'  label='Время занятия' v-mask="'##:##'"></v-text-field>
                  </div>
                  <div class='vertical-inputs__input'>
                    <DataSelect type='cabinets' v-model='item.cabinet_id' />
                  </div>
                  <div class='vertical-inputs__input'>
                    <TeacherSelect v-model="item.teacher_id" />
                  </div>
                  <div class='vertical-inputs__input relative' v-if='item.id'>
                    <!-- <DivBlocker v-if="item.status === LESSON_STATUS.PLANNED" /> -->
                    <v-text-field v-model='item.price' label='Цена' hide-details></v-text-field>
                  </div>
                  <div class='vertical-inputs__input relative' v-if='item.id'>
                    <!-- <DivBlocker v-if="item.status === LESSON_STATUS.PLANNED" /> -->
                    <v-text-field v-model='item.bonus' label='Бонус' hide-details></v-text-field>
                  </div>
                  <div class="vertical-inputs__input" v-if="item.status !== LESSON_STATUS.CONDUCTED">
                    <v-switch class='ma-0'
                      label="Отменено"
                      hide-details
                      :input-value="item.status === LESSON_STATUS.CANCELLED"
                      @change='toggleCancelled'
                    ></v-switch>
                  </div>
                  <div class="vertical-inputs__input">
                      <v-switch class='ma-0'
                        label="Незапланировано"
                        hide-details
                        v-model='item.is_unplanned'
                      ></v-switch>
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
import { DialogMixin } from '@/mixins'
import { DatePicker, DataSelect, TeacherSelect } from '@/components/UI'
import { LESSON_STATUS, MODEL_DEFAULTS, API_URL } from '@/components/Lesson'

export default {
  mixins: [ DialogMixin ],

  components: { DatePicker, DataSelect, TeacherSelect },
  
  data() {
    return {
      API_URL,
      LESSON_STATUS,
      MODEL_DEFAULTS,
    }
  },
  
  methods: {
    toggleCancelled(isCancelled) {
      this.item.status = isCancelled ? LESSON_STATUS.CANCELLED : LESSON_STATUS.PLANNED
    },
  }
}
</script>
