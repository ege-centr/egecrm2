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

        <v-layout wrap>
          <v-flex md12 class='headline mt-3'>
            Состав группы:
          </v-flex>
          <v-flex md12>
            <v-slide-x-transition :group='true'>
              <div v-for="(client, index) in item.clients" :key="client.id" class='flex-items align-center'>
                <v-btn flat icon color="red" class='ma-0 mr-3' @click='item.clients.splice(index, 1)'>
                  <v-icon>remove</v-icon>
                </v-btn>
                <div>
                  {{ client.names.short }}
                </div>
              </div>
            </v-slide-x-transition>
          </v-flex>
          <v-flex md3 class='py-0'>
            <v-select
              :items='clients'
              item-value='id'
              item-text='names.short'
              label='Добавить ученика'
              v-model='selected_client'
              @change='selectClient'
            />
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
                <v-btn color="red darken-1" flat @click.native="edit_lesson_dialog = false">Удалить</v-btn>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" flat @click.native="edit_lesson_dialog = false">Отмена</v-btn>
                <v-btn color="blue darken-1" flat @click.native='saveLesson'>Сохранить</v-btn>
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
                  <v-flex md7>
                    <v-date-picker class='mr-3'
                      v-model="dates"
                      full-width
                      landscape
                      multiple
                    ></v-date-picker>
                  </v-flex>
                  <v-flex md5>
                    <div class="headline">{{ item.lessons.length }} занятий</div>
                    <v-data-table hide-actions hide-headers :items='item.lessons' class='mt-3'>
                      <template slot='items' slot-scope="{ index, item }">
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
                          <v-btn flat icon color="black" class='ma-0' @click='editLesson(index)'>
                            <v-icon>more_horiz</v-icon>
                          </v-btn>
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

import { model_defaults, url } from '@/components/Group/data'

export default {
  data() {
    return {
      item: model_defaults,
      selected_client: null,
      teachers: [],
      clients: [],
      cabinets: [],
      loading: true,
      saving: false,
      schedule_dialog: false,
      edit_lesson_dialog: false,
      dates: [],
      lesson: {},
      editing_lesson_index: false
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
      await axios.get(apiUrl('clients?get_all=1')).then(r => {
        this.clients = r.data
      })
      await axios.get(apiUrl('cabinets')).then(r => {
        this.cabinets = r.data
      })
      if (this.$route.params.id) {
        await axios.get(apiUrl(`${url}/${this.$route.params.id}`)).then(r => {
          this.dates = r.data.lessons.map(e => e.lesson_date)
          Vue.nextTick(() => this.item = r.data)
        })
      }
      this.loading = false
    },
    selectClient(client_id) {
      if (client_id && !this.item.clients.find(e => e.id == client_id)) {
        this.item.clients.push(this.clients.find(e => e.id == client_id))
      }
      Vue.nextTick(() => {
        this.selected_client = null
      })
    },
    async storeOrUpdate() {
      this.saving = true
      if (this.item.id) {
        await axios.put(apiUrl(`${url}/${this.item.id}`), this.item).then(r => {
          this.item = r.data
        })
      } else {
        await axios.post(apiUrl(url), this.item).then(r => {
          this.$router.push({ name: 'GroupEdit', params: { id: r.data }})
        })
      }
      this.saving = false
    },
    editLesson(index) {
      this.edit_lesson_dialog = true
      this.editing_lesson_index = index
      this.lesson = clone(this.item.lessons[index])
    },
    saveLesson() {
      this.edit_lesson_dialog = false
      this.item.lessons.splice(this.editing_lesson_index, 1, this.lesson)
    }
  },
  watch: {
    dates(newVal, oldVal) {
      if (this.item) {
        if (newVal.length > oldVal.length) {
          const added_date = _.difference(newVal, oldVal)[0]
          this.item.lessons.push({lesson_date: added_date})
        } else {
          const deleted_date = _.difference(oldVal, newVal)[0]
          this.item.lessons = this.item.lessons.filter(e => e.lesson_date != deleted_date)
        }
      }
    }
  }
}
</script>
