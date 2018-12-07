<template>
  <div>
    <Loader v-if='loading' />
    <div v-if='!loading && item !== null'>
      <v-container grid-list-xl class="pa-0 ma-0" fluid>
        <v-layout wrap>
          <v-flex md12 class='headline'>
            {{ item.id ? `Редактирование группы №${item.id}` : 'Добавление группы' }}
          </v-flex>
          <v-flex md3>
            <v-select clearable
              v-model="item.teacher_id"
              :items="teachers"
              label="Учитель"
              item-value='id'
              item-text='names.abbreviation'
            ></v-select>
          </v-flex>
          <v-flex md3>
            <v-select clearable
              v-model="item.head_teacher_id"
              :items="teachers"
              label="Классный руководитель"
              item-value='id'
              item-text='names.abbreviation'
            ></v-select>
          </v-flex>
          <v-flex md3>
            <v-select clearable
              v-model="item.grade_id"
              :items="$store.state.data.grades"
              item-value='id'
              item-text='title'
              label="Класс"
            ></v-select>
          </v-flex>
          <v-flex md3>
            <v-select clearable
              v-model="item.year"
              :items="$store.state.data.years"
              label="Год"
            ></v-select>
          </v-flex>
          <v-flex md3>
            <v-select clearable
              v-model="item.subject_id"
              :items="$store.state.data.subjects"
              item-value='id'
              item-text='name'
              label="Предмет"
            ></v-select>
          </v-flex>
          <v-flex md3>
            <v-select clearable
              v-model="item.cabinet_id"
              :items="cabinets"
              item-value='id'
              label="Кабинет"
            ></v-select>
          </v-flex>
          <v-flex md3>
            <v-text-field v-model="item.teacher_price" label="Цена за занятие, руб."></v-text-field>
          </v-flex>
          <v-flex>
            <v-text-field v-model="item.duration" label="Длительность занятия, мин."></v-text-field>
          </v-flex>
        </v-layout>

        <v-layout>
          <v-flex pt-0>
            <a @click='schedule_dialog = true'>расписание ({{ item.lessons.length }})</a>
          </v-flex>
        </v-layout>

        <v-layout>
          <v-switch class='ml-3 mt-0'
            label="Готова к запуску"
            color="success"
            hide-details
            v-model='item.is_ready_to_start'
          ></v-switch>
        </v-layout>

        <v-layout>
          <v-switch class='ml-3'
            label="Заархивирована"
            color="error"
            hide-details
            v-model='item.is_archived'
          ></v-switch>
        </v-layout>

        <v-layout wrap v-if='item.clients.length'>
          <v-flex md12 class='headline mt-3'>
            Состав группы:
          </v-flex>
          <v-flex md12>
            <div v-for="(client, index) in item.clients" :key="client.id" class='flex-items align-center'>
              <div>
                {{ client.names.short }}
              </div>
            </div>
          </v-flex>
        </v-layout>

        <v-layout>
          <v-flex md12 justify-center class='text-md-center'>
            <v-btn @click='storeOrUpdate' :loading='saving'>
              {{ item.id ? 'сохранить' : 'добавить' }}
            </v-btn>
          </v-flex>
        </v-layout>
      </v-container>

      <v-layout row justify-center>
        <v-layout row justify-center>
          <v-dialog v-model="edit_lesson_dialog" persistent max-width="400px">
            <v-card>
              <v-card-text>
                <v-container class="pa-0 ma-0" fluid>
                  <v-layout wrap>
                    <v-flex md12>
                      <v-menu
                        ref="date"
                        :close-on-content-click="false"
                        :nudge-right="40"
                        :return-value.sync="lesson.lesson_date"
                        lazy
                        transition="scale-transition"
                        offset-y
                        full-width
                        min-width="290px"
                      >
                        <v-text-field
                          slot="activator"
                          v-model="lesson.lesson_date"
                          label="Дата занятия"
                          prepend-icon="event"
                          readonly
                        ></v-text-field>
                        <v-date-picker
                          v-model="lesson.lesson_date"
                          @input="$refs.date.save(lesson.lesson_date)">
                        </v-date-picker>
                      </v-menu>
                    </v-flex>
                    <v-flex md12>
                      <v-text-field v-model='lesson.lesson_time' label='Время занятия' v-mask="'##:##'"></v-text-field>
                    </v-flex>
                    <v-flex md12>
                      <v-select clearable
                        v-model="lesson.cabinet_id"
                        :items="cabinets"
                        item-value='id'
                        label="Кабинет"
                      ></v-select>
                    </v-flex>
                    <v-flex md12>
                      <v-select clearable
                        v-model="lesson.teacher_id"
                        :items="teachers"
                        label="Учитель"
                        item-value='id'
                        item-text='names.abbreviation'
                      ></v-select>
                    </v-flex>
                    <v-flex md12>
                      <v-switch
                        label="Отменено"
                        hide-details
                        v-model='lesson.is_cancelled'
                      ></v-switch>
                    </v-flex>
                    <v-flex md12>
                      <v-switch
                        label="Незапланировано"
                        hide-details
                        v-model='lesson.is_unplanned'
                      ></v-switch>
                    </v-flex>
                  </v-layout>
                </v-container>
              </v-card-text>
              <v-card-actions>
                <v-btn color="red darken-1" flat @click.native="deleteLesson" v-show='editing_lesson_index !== null'>Удалить</v-btn>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" flat @click.native="edit_lesson_dialog = false">Отмена</v-btn>
                <v-btn color="blue darken-1" flat @click.native='saveLesson'>{{ editing_lesson_index === null ? 'Добавить' : 'Сохранить' }}</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-layout>

        <v-dialog v-model="schedule_dialog" fullscreen hide-overlay transition="dialog-bottom-transition">
          <v-card>
            <v-toolbar dark color="primary">
              <v-toolbar-title>Расписание группы</v-toolbar-title>
              <v-spacer></v-spacer>
              <v-btn icon dark @click.native="schedule_dialog = false">
                <v-icon>close</v-icon>
              </v-btn>
            </v-toolbar>
            <v-card-text>
              <v-container grid-list-xl class="pa-0 ma-0" fluid>
                <v-layout>
                  <v-flex md5>
                    <Calendar :year='item.year' :lessons='item.lessons' />
                  </v-flex>
                  <v-spacer></v-spacer>
                  <v-flex md6>
                    <v-data-table hide-actions hide-headers :items='item.lessons' :pagination.sync="lessonSortingOptions" class='mt-3'>
                      <template slot='items' slot-scope="{ index, item }">
                        <td width='10' class='pr-0 grey--text'>
                          <span v-if='!item.is_cancelled'>{{ indexSkippingCancelledLessons(index) }}</span>
                        </td>
                        <td>
                          {{ item.lesson_date | date }}
                        </td>
                        <td>
                          {{ item.lesson_time }}
                        </td>
                        <td>
                          <span v-if='item.cabinet_id'>
                            {{ cabinets.find(e => e.id == item.cabinet_id).text }}
                          </span>
                        </td>
                        <td>
                          <span v-if='item.teacher_id'>
                            {{ teachers.find(e => e.id == item.teacher_id).names.abbreviation }}
                          </span>
                        </td>
                        <td class='text-md-right'>
                          <v-btn flat icon color="black" class='ma-0' @click='editLesson(item)'>
                            <v-icon>more_horiz</v-icon>
                          </v-btn>
                        </td>
                      </template>
                    </v-data-table>
                    <div class='mt-3'>
                      <v-btn color='primary' small class='ma-0 mr-3' @click='addLesson'>
                        <v-icon class="mr-1">add</v-icon>
                        добавить занятие
                      </v-btn>
                      <a @click='fillSchedule' v-if='item.lessons.length > 0'>проставить до 1 июня текущего года</a>
                    </div>
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

import { model_defaults, API_URL } from '@/components/Group/data'
import Calendar from '@/components/Calendar/Calendar'

export default {
  components: { Calendar },

  data() {
    return {
      item: model_defaults,
      teachers: [],
      cabinets: [],
      loading: true,
      saving: false,
      schedule_dialog: false,
      edit_lesson_dialog: false,
      dates: [],
      lesson: {},
      editing_lesson_index: null,
      lessonSortingOptions: {
        rowsPerPage: -1,
        sortBy: 'lesson_date'
      }
    }
  },
  async created() {
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
      if (this.$route.params.id) {
        await axios.get(apiUrl(`${API_URL}/${this.$route.params.id}`)).then(r => {
          this.dates = r.data.lessons.map(e => e.lesson_date)
          Vue.nextTick(() => this.item = r.data)
        })
      }
      this.loading = false
    },

    async storeOrUpdate() {
      this.saving = true
      if (this.item.id) {
        await axios.put(apiUrl(`${API_URL}/${this.item.id}`), this.item).then(r => {
          this.item = r.data
        })
      } else {
        await axios.post(apiUrl(API_URL), this.item).then(r => {
          this.$router.push({ name: 'GroupEdit', params: { id: r.data }})
        })
      }
      this.saving = false
    },

    addLesson() {
      this.lesson = {}
      this.editing_lesson_index = null
      this.edit_lesson_dialog = true
    },

    editLesson(lesson) {
      this.edit_lesson_dialog = true
      this.editing_lesson_index = this.item.lessons.findIndex(e => e === lesson)
      this.lesson = clone(lesson)
    },

    deleteLesson() {
      this.item.lessons.splice(this.editing_lesson_index, 1)
      this.edit_lesson_dialog = false
    },

    saveLesson() {
      this.edit_lesson_dialog = false
      if (this.editing_lesson_index === null) {
        this.item.lessons.push(this.lesson)
      } else {
        this.item.lessons.splice(this.editing_lesson_index, 1, this.lesson)
      }
    },

    indexSkippingCancelledLessons(index) {
      const cancelled_lessons_count = _.chain(this.item.lessons).sortBy('lesson_date').take(index + 1).filter({is_cancelled: 1}).value().length
      return index + 1 - cancelled_lessons_count
    },

    fillSchedule() {
      const last_lesson = _.sortBy(this.item.lessons, 'lesson_date').reverse()[0]
      let lesson_date = last_lesson.lesson_date
      while (true) {
        lesson_date = moment(lesson_date).add(1, 'week').format('YYYY-MM-DD')
        if (moment(lesson_date).format('M') == 6) {
          return
        }
        this.item.lessons.push({...last_lesson, lesson_date})
      }
    },
  },
}
</script>

<style lang='scss'>
  .v-picker--landscape {
    & .v-date-picker-header {
      & button {
        display: none;
      }
    }
  }
</style>
