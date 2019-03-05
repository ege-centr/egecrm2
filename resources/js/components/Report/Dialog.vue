<template>
  <v-layout row justify-center>
    <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay>
      <v-card class='grey-background'>
        <v-toolbar dark color="primary">
          <v-btn icon dark @click.native="dialog = false">
            <v-icon>close</v-icon>
          </v-btn>
          <v-toolbar-title>{{ edit_mode ? 'Редактирование' : 'Добавление' }} отчёта</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items>
            <v-btn dark flat v-if='edit_mode' @click.native="destroy" :loading='destroying'>Удалить</v-btn>
            <v-btn dark flat @click.native="storeOrUpdate" :loading='saving'>{{ edit_mode ? 'Сохранить' : 'Добавить' }}</v-btn>
          </v-toolbar-items>
        </v-toolbar>
        <v-card-text>
          <Loader v-if='loading' class='loader-wrapper_fullscreen-dialog' />
          <v-container grid-list-xl class="pa-0 ma-0" fluid v-else>
              <v-card v-for='categoryName in CATEGORY' :key='categoryName' class='mb-4'>
                <v-card-text>
                  <v-layout wrap>
                    <v-flex md5>
                      <div class='font-weight-medium mb-2'>
                        {{ getCategoryTitle(categoryName) }}
                      </div>
                      <div class='mb-3'>
                        <div class='flex-items align-center'>
                          <span class='caption mr-3 input-label'>Оценка</span>
                          <v-rating dense clearable v-model="item[categoryName + '_score']"></v-rating>
                        </div>
                      </div>
                      <div>
                        <v-textarea counter auto-grow label='Комментарий' v-model="item[categoryName + '_comment']"></v-textarea>
                      </div>
                    </v-flex>
                    <v-flex offset-md1 md5>
                      <span class='font-weight-bold red--text' v-if='categoryName !== CATEGORY.behavior'>ПИШИТЕ ПОДРОБНЕЕ.</span>
                      {{ getCategoryDescription(categoryName) }}
                    </v-flex>
                  </v-layout>
                </v-card-text>
              </v-card>

              <v-card class='mb-4'>
                <v-card-text>
                  <v-layout wrap>
                    <v-flex md5>
                      <div class='font-weight-medium mb-2'>
                        Рекомендации родителям
                      </div>
                      <v-textarea counter auto-grow label='Комментарий' v-model="item.recommendation"></v-textarea>
                    </v-flex>
                     <v-flex offset-md1 md5>
                      <span class='font-weight-bold red--text'>ПИШИТЕ ПОДРОБНЕЕ.</span>
                      Например: какой-либо необходимости контроля или воздействия со стороны родителей не вижу, так как процесс идет отлично
                    </v-flex>
                  </v-layout>
                </v-card-text>
              </v-card>


              <v-card class='mb-4 pink lighten-5'>
                <v-card-text>
                  <v-layout wrap>
                    <v-flex md5>
                      <div class='font-weight-medium mb-2'>
                        Прогноз баллов на экзамене (информация доступна только администраторам)
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
                </v-card-text>
              </v-card>


              <v-card>
                <v-card-text>
                  <v-layout wrap>
                    <v-flex md5 class='pb-0'>
                      <DatePicker v-model='item.date' label='Дата создания отчёта' />
                    </v-flex>
                     <v-flex offset-md1 md5 class='pb-0'>
                      <v-switch color='green' v-model="item.is_available_for_parents" label="сделать отчет доступным для родителя"></v-switch>
                    </v-flex>
                  </v-layout>
                </v-card-text>
              </v-card>
              <div>
                
              </div>
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-layout>
</template>

<script>

import { API_URL, MODEL_DEFAULTS, CATEGORY, getCategoryTitle, getCategoryDescription } from './'
import { DialogMixin } from '@/mixins'
import { DatePicker } from '@/components/UI'

export default {
  mixins: [ DialogMixin ],

  components: { DatePicker },

  data() {
    return {
      API_URL,
      MODEL_DEFAULTS,
      CATEGORY,
    }
  },

  methods: {
    getCategoryTitle,
    getCategoryDescription,
  }
}
</script>