<template>
  <v-layout row justify-center>
    <!-- <v-dialog v-model="dialog" transition="dialog-bottom-transition" content-class='v-dialog--fullscreen halfscreen-dialog'> -->
    <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay>
      <v-card>
        <v-toolbar dark color="primary">
          <v-toolbar-title>{{ edit_mode ? 'Редактирование' : 'Добавление' }} группы</v-toolbar-title>
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
        <v-card-text class='relative'>
          <Loader v-if='loading' class='loader-wrapper_fullscreen-dialog' />
          <v-container grid-list-xl class="pa-0 ma-0" fluid v-else>
            <v-layout wrap>
              <v-flex md12>
                <div class='vertical-inputs'>
                  <div class='vertical-inputs__input'>
                    <TeacherSelect v-model='item.teacher_id' only-active />
                  </div>
                  <div class='vertical-inputs__input'>
                    <TeacherSelect v-model='item.head_teacher_id' label="Классный руководитель" only-active />
                  </div>
                  <div class='vertical-inputs__input'>
                    <DataSelect type='years' v-model="item.year" :error-messages='errorMessages.year' />
                  </div>
                  <div class='vertical-inputs__input'>
                    <DataSelect type='grades' v-model="item.grade_id" :error-messages='errorMessages.grade_id' />
                  </div>
                  <div class='vertical-inputs__input'>
                    <DataSelect v-model='item.subject_id' type='subjects' :error-messages='errorMessages.subject_id' />
                  </div>
                  <div class='vertical-inputs__input' v-if='lessons.length > 0'>
                    <ClearableSelect label='Критическая дата старта' v-model="item.latest_start_lesson_id" 
                      :item-text='(e) => $options.filters.date(e.date)' 
                      :items='lessons' />
                  </div>
                  <div class='vertical-inputs__input'>
                    <v-switch class='mt-0'
                      label="Готова к запуску"
                      color="success"
                      hide-details
                      v-model='item.is_ready_to_start'
                    ></v-switch>
                  </div>
                  <div class='vertical-inputs__input'>
                    <v-switch class='mt-0'
                      label="Договор заключен"
                      color="success"
                      hide-details
                      v-model='item.is_contract_signed'
                    ></v-switch>
                  </div>
                  <div class='vertical-inputs__input'>
                    <v-switch class='mt-0'
                      label="Заархивирована"
                      color="error"
                      hide-details
                      v-model='item.is_archived'
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
import { MODEL_DEFAULTS, API_URL } from '@/components/Group'
import { TeacherSelect, DataSelect } from '@/components/UI'
import { DialogMixin } from '@/mixins'

export default {
  components: { DataSelect, TeacherSelect },

  mixins: [ DialogMixin ],

  data() {
    return {
      API_URL,
      MODEL_DEFAULTS,
      lessons: [],
      redirectAfterStore: 'GroupShow',
      redirectAfterDestroy: 'GroupIndex',
    }
  },
  
  methods: {
    beforeOpen(item_id) {
      if (item_id !== null) {
        axios.get(apiUrl('lessons') + queryString({group_id: item_id})).then(r => {
          this.lessons = r.data
        })
      }
    },
  },
}
</script>