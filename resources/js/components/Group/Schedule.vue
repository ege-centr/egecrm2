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
                <td width='10' class='pr-0 grey--text relative' :class="{'purple lighten-5': item.is_unplanned}">
                  <div class='lesson-status' :class="{
                    'blue': item.is_planned,
                    'green': item.is_conducted,
                    'grey': item.is_cancelled,
                  }"></div>
                  <span v-if='!item.is_cancelled'>{{ indexSkippingCancelledLessons(index) }}</span>
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
        <v-dialog v-model="dialog" persistent max-width="400px">
          <v-card>
            <v-card-text>
              <v-container class="pa-0 ma-0" fluid>
                <v-layout wrap>
                  <v-flex md12>
                    <v-menu
                    ref="date"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    :return-value.sync="dialog_item.date"
                    lazy
                    transition="scale-transition"
                    offset-y
                    full-width
                    min-width="290px"
                    >
                    <v-text-field
                    slot="activator"
                    v-model="dialog_item.date"
                    label="Дата занятия"
                    prepend-icon="event"
                    readonly
                    ></v-text-field>
                    <v-date-picker
                    v-model="dialog_item.date"
                    @input="$refs.date.save(dialog_item.date)">
                  </v-date-picker>
                </v-menu>
              </v-flex>
              <v-flex md12>
                <v-text-field v-model='dialog_item.time' label='Время занятия' v-mask="'##:##'"></v-text-field>
              </v-flex>
              <v-flex md12>
                <v-select clearable
                v-model="dialog_item.cabinet_id"
                :items="cabinets"
                item-value='id'
                label="Кабинет"
                ></v-select>
              </v-flex>
              <v-flex md12>
                <v-select clearable
                v-model="dialog_item.teacher_id"
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
                v-model='dialog_item.is_cancelled'
                ></v-switch>
              </v-flex>
              <v-flex md12>
                <v-switch
                label="Незапланировано"
                hide-details
                v-model='dialog_item.is_unplanned'
                ></v-switch>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
            <v-card-actions>
              <v-btn color="red darken-1" flat @click.native="destroy" v-show='dialog_item.id' :loading='destroying'>Удалить</v-btn>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" flat @click.native="dialog = false">Отмена</v-btn>
              <v-btn color="blue darken-1" flat @click.native='storeOrUpdate' :loading='saving'>{{ dialog_item.id ? 'Сохранить' : 'Добавить' }}</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-layout>
    </div>
  </div>
</template>

<script>

import Calendar from '@/components/Calendar/Calendar'

const API_URL = 'lessons'

export default {
  components: { Calendar },

  props: {
    group: {
      required: true
    }
  },

  data() {
    return {
      dialog: false,
      items: null,
      saving: false,
      destroying: false,
      filling: false,
      dialog_item: {},
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
        // await axios.post(apiUrl(API_URL), this.dialog_item).then(r => {
        //   this.items.push(r.data)
        // })
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
      const cancelled_lessons_count = _.chain(this.items).sortBy('date').take(index + 1).filter(e => e.is_cancelled).value().length
      return index + 1 - cancelled_lessons_count
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
    height: 10px;
    width: 10px;
    position: absolute;
    left: 5px;
    top: 18px;
  }
</style>
