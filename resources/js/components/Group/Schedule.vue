<template>
  <div style='min-height: 200px'>
    <Loader v-if='items === null' />
    <div v-else>
      <v-container grid-list-xl class="pa-0 ma-0" fluid>
        <v-layout>
          <v-flex>
            <Calendar :year='group.year' :lessons='items' :with-special-dates='true' :group='this.group' />
          </v-flex>
          <v-spacer></v-spacer>
          <v-flex>
            <v-data-table hide-actions hide-headers :items='items' :pagination.sync="sortingOptions" class='mt-3'>
              <template slot='items' slot-scope="{ index, item }">
                <td width='10' class='pr-0 grey--text' :class="{'purple lighten-5': item.is_unplanned}">
                  <div class='lesson-status' :class="{
                    'blue': item.status === LESSON_STATUS.PLANNED,
                    'green': item.status === LESSON_STATUS.CONDUCTED,
                    'grey': item.status === LESSON_STATUS.CANCELLED,
                  }"></div>
                  <span v-if="item.status !== LESSON_STATUS.CANCELLED">{{ indexSkippingCancelledLessons(index) }}</span>
                </td>
                <td :class="{'purple lighten-5': item.is_unplanned}">
                  {{ item.date | date }}
                </td>
                <td :class="{'purple lighten-5': item.is_unplanned}">
                  {{ item.time }}
                </td>
                <td :class="{'purple lighten-5': item.is_unplanned}">
                  <span v-if='item.cabinet_id'>
                    {{ cabinets.find(e => e.id == item.cabinet_id).text }}
                  </span>
                </td>
                <td :class="{'purple lighten-5': item.is_unplanned}">
                  <span v-if='item.teacher_id'>
                    {{ teachers.find(e => e.id == item.teacher_id).names.abbreviation }}
                  </span>
                </td>
                <td class='grey--text' :class="{'purple lighten-5': item.is_unplanned}">
                  <span v-if='item.createdAdmin'>{{ item.createdAdmin.name }} {{ item.created_at | date-time }}</span>
                </td>
                <td class='text-md-right' :class="{'purple lighten-5': item.is_unplanned}">
                  <v-btn flat icon color="black" class='ma-0' @click='edit(item)'>
                    <v-icon>more_horiz</v-icon>
                  </v-btn>
                </td>
              </template>
            </v-data-table>
            <div class='mt-3'>
              <v-btn color='primary' small class='ma-0 mr-3' @click='add'>
                <v-icon class="mr-1">add</v-icon>
                добавить занятие
              </v-btn>
              <v-btn color='primary' small flat @click='fillSchedule' :loading='filling' v-if='items.length > 0'>
                проставить до 1 июня текущего года
              </v-btn>
              <!-- <a @click='fillSchedule' v-if='items.length > 0'>проставить до 1 июня текущего года</a> -->
            </div>
          </v-flex>
        </v-layout>
      </v-container>

      
      <v-layout row justify-center>
        <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay>
          <v-card>
            <v-toolbar dark color="primary">
              <v-btn icon dark @click.native="dialog = false">
                <v-icon>close</v-icon>
              </v-btn>
              <v-toolbar-title>{{ dialog_item.id ? 'Редактирование' : 'Добавление' }} занятия</v-toolbar-title>
              <v-spacer></v-spacer>
              <v-toolbar-items>
                <v-btn dark flat @click.native="storeOrUpdate" :loading='saving'>{{ dialog_item.id ? 'Сохранить' : 'Добавить' }}</v-btn>
              </v-toolbar-items>
            </v-toolbar>
            <v-card-text class='relative'>
              <Loader v-if='loading' class='loader-wrapper_fullscreen-dialog' />
              <v-container grid-list-xl class="pa-0 ma-0" fluid v-else>
                <v-layout wrap>
                  <v-flex md12>
                    <div class='vertical-inputs'>
                      <div class='vertical-inputs__input'>
                        <DatePicker label="Дата занятия" :item='dialog_item.date' />
                      </div>
                      <div class='vertical-inputs__input'>
                        <v-text-field hide-details v-model='dialog_item.time' label='Время занятия' v-mask="'##:##'"></v-text-field>
                      </div>
                      <div class='vertical-inputs__input'>
                        <ClearableSelect v-model="dialog_item.cabinet_id"
                          label="Кабинет"
                          :items='cabinets' 
                          item-text='text'
                        />
                      </div>
                      <div class='vertical-inputs__input'>
                        <ClearableSelect v-model="dialog_item.teacher_id"
                          label="Учитель"
                          :items='teachers' 
                          item-text='names.abbreviation'
                        />
                      </div>
                      <div class='vertical-inputs__input' v-if="dialog_item.status === LESSON_STATUS.CONDUCTED">
                        <v-text-field v-model='dialog_item.price' label='Цена' hide-details></v-text-field>
                      </div>
                      <div class="vertical-inputs__input" v-if="dialog_item.status !== LESSON_STATUS.CONDUCTED">
                        <v-switch class='ma-0'
                          label="Отменено"
                          hide-details
                          :input-value="dialog_item.status === LESSON_STATUS.CANCELLED"
                          @change='toggleCancelled'
                        ></v-switch>
                      </div>
                      <div class="vertical-inputs__input">
                         <v-switch class='ma-0'
                          label="Незапланировано"
                          hide-details
                          v-model='dialog_item.is_unplanned'
                          ></v-switch>
                      </div>
                    </div>
                  </v-flex>
                </v-layout>
                <v-layout wrap v-if="dialog_item.status === LESSON_STATUS.CONDUCTED">
                  <v-flex md12 class='headline'>
                    Занятие
                  </v-flex>
                  <v-flex md12>
                    <v-data-table v-if='items.length'
                      hide-actions
                      :headers="[
                        { text: 'Ученик', sortable: false },
                        { text: 'Отсутствовал', sortable: false }, 
                        { text: 'Опоздание', sortable: false },
                        { text: 'Цена', sortable: false },
                        { text: 'Комментарий', sortable: false },
                      ]"
                      :items='dialog_item.clientLessons'
                    >
                      <template slot="items" slot-scope="{ item }">
                        <td width='200'>
                          {{ item.client.names.short }}
                        </td>
                        <td width='150'>
                          <v-switch color='red' v-model="item.is_absent" hide-details></v-switch>
                        </td>
                        <td width='150'>
                          <v-icon small v-if="!item.late" class='client-edit-icon'>edit</v-icon>
                          <v-edit-dialog
                            :return-value.sync="item.late"
                            lazy
                          > {{ item.late }}
                            <v-text-field
                              slot="input"
                              v-model="item.late"
                              label="Опоздание"
                              single-line
                              v-mask="'##'"
                            ></v-text-field>
                          </v-edit-dialog>
                        </td>
                        <td width='150'>
                          <v-icon small v-if="!item.price" class='client-edit-icon'>edit</v-icon>
                          <v-edit-dialog
                            :return-value.sync="item.price"
                            lazy
                          > {{ item.price }}
                            <v-text-field
                              slot="input"
                              v-model="item.price"
                              label="Цена"
                              single-line
                              v-mask="'#####'"
                            ></v-text-field>
                          </v-edit-dialog>
                        </td>
                        <td>
                          <v-icon small v-if="!item.comment" class='client-edit-icon'>edit</v-icon>
                          <v-edit-dialog
                            :return-value.sync="item.comment"
                            lazy
                          > {{ item.comment }}
                            <v-text-field
                              slot="input"
                              v-model="item.comment"
                              single-line
                              label="Комментарий"
                            ></v-text-field>
                          </v-edit-dialog>
                        </td>
                      </template>
                    </v-data-table>
                  </v-flex>
                </v-layout>
              </v-container>
            </v-card-text>
          </v-card>
        </v-dialog>
      </v-layout>

    </div>
  </div>
</template>

<script>

import Calendar from '@/components/Calendar/Calendar'
import { LESSON_STATUS } from '@/components/Lesson'
import { DatePicker } from '@/components/UI'

const API_URL = 'lessons'

export default {
  components: { Calendar, DatePicker },

  props: {
    group: {
      required: true
    }
  },

  data() {
    return {
      LESSON_STATUS,
      edit_lesson_tab: true,
      dialog: false,
      items: null,
      saving: false,
      destroying: false,
      filling: false,
      dialog_item: {},
      loading: false,
      sortingOptions: {
        rowsPerPage: -1,
        sortBy: 'date'
      },
    }
  },

  created() {
    this.loadData()
  },

  methods: {
    async loadData() {
      await axios.get(apiUrl('teachers')).then(r => {
        this.teachers = r.data
      })
      await axios.get(apiUrl('cabinets')).then(r => {
        this.cabinets = r.data
      })
      await axios.get(apiUrl(API_URL), {
        params: {
          group_id: this.group.id,
        }
      }).then(r => {
        this.items = r.data
      })
    },

    add() {
      this.dialog_item = {
        group_id: this.group.id,
        year: this.group.year,
      }
      this.dialog = true
    },

    edit(lesson) {
      this.edit_lesson_tab = true
      this.dialog = true
      this.dialog_item = clone(lesson)
    },

    async destroy() {
      this.destroying = true
      await axios.delete(apiUrl(API_URL, this.dialog_item.id))
      this.items.splice(this.items.findIndex(e => e.id === this.dialog_item.id), 1)
      this.dialog = false
      this.destroying = false
    },

    async storeOrUpdate() {
      this.saving = true
      if (this.dialog_item.id) {
        await axios.put(apiUrl(API_URL, this.dialog_item.id), this.dialog_item).then(r => {
          this.items.splice(this.items.findIndex(e => e.id === this.dialog_item.id), 1, r.data)
        })
      } else {
        await this.store(this.dialog_item)
      }
      this.dialog = false
      this.saving = false
    },

    async store(item) {
      await axios.post(apiUrl(API_URL), item).then(r => {
        this.items.push(r.data)
      })
    },

    indexSkippingCancelledLessons(index) {
      const cancelled_lessons_count = _.chain(this.items).sortBy('date').take(index + 1).filter(e => e.status === LESSON_STATUS.CANCELLED).value().length
      return index + 1 - cancelled_lessons_count
    },

    toggleCancelled(isCancelled) {
      this.dialog_item.status = isCancelled ? LESSON_STATUS.CANCELLED : LESSON_STATUS.PLANNED
    },

    async fillSchedule() {
      this.filling = true
      const last_lesson = _.sortBy(this.items, 'date').reverse()[0]
      let date = last_lesson.date
      while (true) {
        date = moment(date).add(1, 'week').format('YYYY-MM-DD')
        if (moment(date).format('M') == 6) {
          this.filling = false
          return
        }
        await this.store({...last_lesson, date})
      }
    },
  }
}
</script>

<style lang="scss">
  .lesson-status {
    border-radius: 50%;
    height: 8px;
    width: 8px;
    position: absolute;
    left: 6px;
    top: 20px;
  }

  .v-datatable {
    & tr {
      & td {
        position: relative;
      }
    }
  }

  .v-small-dialog__content {
    background: white;
  }

  .client-edit-icon {
    position: absolute;
    top: 15px;
  }
</style>
