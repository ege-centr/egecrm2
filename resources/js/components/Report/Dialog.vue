<template>
  <v-layout row justify-center>
    <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay>
      <v-card>
        <v-toolbar dark color="primary">
          <v-toolbar-title>{{ edit_mode ? (readOnly ? 'Просмотр' : 'Редактирование') : 'Добавление' }} отчёта</v-toolbar-title>
          <v-spacer></v-spacer>
          <TitleCredentials v-if='!readOnly' :item='item'/>
          <v-toolbar-items>
            <v-btn v-if="!readOnly" dark icon @click.native="destroy" :loading='destroying' class='mr-5'>
              <v-icon>delete</v-icon>
            </v-btn>
            <v-btn v-if="!readOnly" dark icon @click.native="storeOrUpdate" :loading='saving'>
              <v-icon>save_alt</v-icon>
            </v-btn>
            <v-btn icon dark @click.native="dialog = false">
              <v-icon>close</v-icon>
            </v-btn>
          </v-toolbar-items>
        </v-toolbar>
        <v-card-text class='px-0'>
          
          <Loader v-if='loading' class='loader-wrapper_fullscreen-dialog' />
          <v-container grid-list-xl class="pa-0 ma-0 relative" fluid v-else>
            <DivBlocker v-if='readOnly' />
            <Loader block v-if='clientLessons === null' />
            <v-layout wrap class='mb-4 px-3' v-else>
              <v-flex md12 class='pb-0 subheading'>
                <div class='title font-weight-bold mb-3'>Данные для отчета</div>
                <div>
                  Составитель отчета: 
                  {{ teacher.names.full }},
                  преподаватель по 
                  {{ teacher.subjects_ec.map(subject_id => getData('subjects', subject_id).dative).join(' и ') }}
                </div>
                <div>
                  Ученик: 
                  <span v-if='client !== null'>{{ client.names.short }}</span>
                </div>
                <div class='mb-3'>
                  Предмет: {{ getData('subjects', defaults.subject_id).name }}
                </div>
                <div v-for='clientLesson in clientLessons'>
                  {{ clientLesson.lesson.date | date }} – 
                  <span v-if='clientLesson.is_absent' class='red--text'>
                    не был
                  </span>
                  <span v-else>
                    <span v-if='clientLessons.late > 0'>опоздал на {{ clientLesson.late }} мин.</span>
                    <span v-else>был</span>
                  </span>
                  <span v-show='clientLesson.lesson.topic'>
                    ({{ clientLesson.lesson.topic }})
                  </span>
                </div>
              </v-flex>
              <v-flex md12 class='pa-0 mt-3'>
                <hr class="v-divider theme--light">
              </v-flex>
            </v-layout>

            <v-layout wrap v-for='categoryName in CATEGORY' :key='categoryName' class='mb-4 px-3'>
              <v-flex md12 class='pb-0'>
                <div class='title font-weight-bold mb-2'>
                  {{ getCategoryTitle(categoryName) }}
                </div>
                <div class='mb-1'>
                  <div class='flex-items align-center'>
                    <span class='mr-1 subheading'>Оценка</span>
                    <v-menu>
                      <v-btn class='v-btn_xs' small fab  flat slot='activator' 
                        :dark="item[categoryName + '_score'] > 0"
                        :class="getColorClass(item[categoryName + '_score'])">
                        <span v-if="item[categoryName + '_score'] > 0">
                          {{ item[categoryName + '_score'] }}
                        </span>
                        <v-icon v-else>edit</v-icon>
                      </v-btn>
                       <v-list dense>
                         <v-list-tile @click='setScore(null, categoryName)'>
                          <v-list-tile-title class='grey--text'>
                            не установлено
                          </v-list-tile-title>
                        </v-list-tile>
                         <v-list-tile v-for='score in [1, 2, 3, 4, 5]' :key='score' @click='setScore(score, categoryName)'>
                            <v-list-tile-title>
                              {{ score }}
                            </v-list-tile-title>
                         </v-list-tile>
                       </v-list>
                    </v-menu>
                  </div>
                </div>
              </v-flex>
              <v-flex md12 class='pa-0'>
                <div>
                  <v-textarea 
                    class='textarea-padding-fix'
                    full-width
                    auto-grow
                    counter 
                    label='Комментарий' 
                    maxlength="1000"
                    v-model="item[categoryName + '_comment']"></v-textarea>
                </div>
                <hr class="v-divider theme--light">
              </v-flex>
            </v-layout>


              <v-layout wrap class='mb-4 px-3'>
                <v-flex md12>
                  <div class='title font-weight-bold mb-2'>
                    Рекомендации родителям
                  </div>
                </v-flex>
                 <v-flex md12 class='pa-0'>
                  <div>
                    <v-textarea 
                      class='textarea-padding-fix'
                      full-width
                      auto-grow
                      maxlength="1000"
                      counter 
                      label='Комментарий' 
                      v-model="item.recommendation"></v-textarea>
                  </div>
                  <hr class="v-divider theme--light">
                </v-flex>
              </v-layout>

              <v-layout wrap class='px-3'>
                <v-flex md12>
                  <div class='vertical-inputs'>
                    <div class='vertical-inputs__input' v-if='$store.state.user.class === ROLES.ADMIN'>
                      <v-switch color='red' v-model="item.is_not_moderated" hide-details label="модерация не пройдена"></v-switch>
                    </div>
                    <div class='vertical-inputs__input' v-if='$store.state.user.class === ROLES.ADMIN'>
                      <v-switch color='green' v-model="item.is_available_for_parents" hide-details 
                        :disabled='client === null || client.representative.email === null'
                        label="сделать отчет доступным для родителя"></v-switch>
                    </div>
                    <div class='vertical-inputs__input' v-if='$store.state.user.class === ROLES.ADMIN'>
                      <v-text-field
                        hide-details
                        label='Цена'
                        v-model='item.price'
                      />
                    </div>
                    <div class='vertical-inputs__input'>
                      <DatePicker v-model='item.date' label='Дата создания отчёта' />
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

import { API_URL, MODEL_DEFAULTS, CATEGORY, getCategoryTitle, getCategoryDescription, getColorClass } from './'
import { DialogMixin } from '@/mixins'
import { DatePicker } from '@/components/UI'
import { API_URL as CLIENT_API_URL } from '@/components/Client'
import { ROLES } from '@/config'

export default {
  mixins: [ DialogMixin ],

  components: { DatePicker },

  data() {
    return {
      API_URL,
      CLIENT_API_URL,
      MODEL_DEFAULTS,
      CATEGORY,
      ROLES,
      defaults: null,
      client: null,
      clientLessons: null,
    }
  },

  methods: {
    getCategoryTitle,
    getCategoryDescription,
    getColorClass,

    beforeOpen(item_id, defaults, options) {
      this.clientLessons = null
      this.client = null
      this.defaults = defaults
      axios.get(apiUrl(CLIENT_API_URL, defaults.client_id)).then(r => {
        this.client = r.data
      })
    },

    afterOpen(item_id, defaults, options) {
      axios.post(apiUrl(API_URL, 'client-lessons'), defaults).then(r => {
        this.clientLessons = r.data
      })
      console.log(item_id)
      if (item_id === null) {
        this.item.date = moment().format('YYYY-MM-DD')  
      }
    },

    setScore(score, categoryName) {
      this.item[categoryName + '_score'] = score
    },
  },

  computed: {
    readOnly() {
      return 'readOnly' in this.options
    },

    teacher() {
      return this.getData('teachers', this.edit_mode ? this.item.teacher.id : this.defaults.teacher_id)
    },
  },
}
</script>