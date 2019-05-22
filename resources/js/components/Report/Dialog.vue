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
            <v-layout wrap v-for='categoryName in CATEGORY' :key='categoryName' class='mb-4 px-3'>
              <v-flex md12 class='pb-0'>
                <div class='title font-weight-bold mb-2'>
                  {{ getCategoryTitle(categoryName) }}
                </div>
                <div class='mb-1'>
                  <div class='flex-items align-center'>
                    <span class='mr-1'>Оценка</span>
                    <v-menu>
                      <v-btn class='v-btn_xs' small fab dark flat slot='activator' :class="getColorClass(item[categoryName + '_score'])">
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
                    <!-- <v-rating dense clearable v-model="item[categoryName + '_score']"></v-rating> -->
                  </div>
                </div>
              </v-flex>
              <v-flex md12 class='pa-0'>
                <div>
                  <v-textarea 
                    full-width
                    auto-grow
                    single-line
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
                      full-width
                      auto-grow
                      single-line
                      maxlength="1000"
                      counter 
                      label='Комментарий' 
                      v-model="item.recommendation"></v-textarea>
                  </div>
                  <hr class="v-divider theme--light">
                </v-flex>
              </v-layout>


              <v-layout wrap class='mb-4 pink lighten-5'>
                <v-flex md5 style='padding-left: 28px'>    
                  <div class='title font-weight-bold mb-2'>
                    Прогноз баллов на экзамене 
                    <!-- (информация доступна только администраторам) -->
                  </div>
                  <v-layout>
                    <v-flex md4>
                      <v-text-field hide-details v-model='item.expected_score_from' label='От'></v-text-field>
                    </v-flex>
                    <v-flex md4>
                      <v-text-field hide-details v-model='item.expected_score_to' label='До'></v-text-field>
                    </v-flex>
                    <v-flex md4>
                      <v-text-field hide-details v-model='item.expected_score_max' label='Из возможных'></v-text-field>
                    </v-flex>
                  </v-layout>
                </v-flex>
                  <v-flex offset-md1 md5>
                  <span class='font-weight-bold red--text'>ВНИМАНИЕ:</span>
                  информация по прогнозу баллов будет доступна только администраторам. Родитель или ученик ни в какой форме не будут иметь к ней доступа. 
                  Укажите в этом поле наиболее вероятный балл на ЕГЭ или ОГЭ, который по вашему мнению получит этот ученик. 
                  Нередко очень сложно давать какие-либо прогнозы, однако в данном отчете это сделать нужно обязательно.
                </v-flex>
              </v-layout>


              <v-layout wrap class='px-3'>
                <v-flex md5 class='pb-0'>
                  <DatePicker v-model='item.date' label='Дата создания отчёта' />
                </v-flex>
                  <v-flex offset-md1 md5 class='pb-0' v-if='$store.state.user.class === ROLES.ADMIN'>
                  <v-switch color='green' v-model="item.is_available_for_parents" label="сделать отчет доступным для родителя"></v-switch>
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
import { ROLES } from '@/config'

export default {
  mixins: [ DialogMixin ],

  components: { DatePicker },

  data() {
    return {
      API_URL,
      MODEL_DEFAULTS,
      CATEGORY,
      ROLES,
    }
  },

  methods: {
    getCategoryTitle,
    getCategoryDescription,
    getColorClass,

    setScore(score, categoryName) {
      this.item[categoryName + '_score'] = score
    },
  },

  computed: {
    readOnly() {
      return 'readOnly' in this.options
    }
  },
}
</script>