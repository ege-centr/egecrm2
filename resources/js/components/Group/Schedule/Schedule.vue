<template>
  <div style='min-height: 200px'>
    <LessonDialog ref='LessonDialog' @updated='loadData' />
    <ConductDialog ref='ConductDialog' @updated='loadData' />
    <TopicDialog ref='TopicDialog'  @updated='loadData' />
    <Loader v-if='items === null' />
    <div v-else>
      <v-container grid-list-xl class="pa-0 ma-0" fluid>
        <v-layout>
          <v-flex md3 style='margin-right: 70px'>
            <Calendar :year='group.year' :lessons='items' :with-special-dates='true' :group='this.group' />
          </v-flex>
          <v-flex class='relative' style='align-self: baseline'>
            <Loader v-if='loading' />
            <v-data-table hide-actions hide-headers :items='items' class='mt-3 v-table_no-overflow' v-if='items.length > 0'>
              <template slot='items' slot-scope="{ index, item }">
                <td width='24' class='px-2 relative'>
                  <LessonStatusCircles :item='item'  />
                </td>
                <td width='10' class='px-0'>
                  <span class='grey--text'
                    v-if="item.status !== LESSON_STATUS.CANCELLED">{{ indexSkippingCancelledLessons(index, items) }}</span>
                </td>
                <td>
                  {{ item.date | date }}
                </td>
                <td>
                  {{ item.time }}
                </td>
                <td>
                  <span v-if='item.teacher_id'>
                    {{ getData('teachers', item.teacher_id).default_name }}
                  </span>
                </td>
                <td>
                  <v-tooltip bottom v-if='item.topic'>
                    <v-icon class='cursor-default' slot='activator'>chrome_reader_mode</v-icon>
                    <span>
                      <h4>Тема занятия</h4>
                      {{ item.topic }}
                    </span>
                  </v-tooltip>
                </td>
                <td>
                  <span v-if='!cantSee(item) && item.price > 0'>
                    {{ item.price }}
                    <span v-if='item.bonus > 0'>
                      + {{ item.bonus }}
                    </span>
                    руб.
                  </span>
                </td>
                <td class='text-md-right' width='100'>
                  <v-checkbox 
                    class='td-checkbox'
                    v-if="selectedMassSelectMode !== null"
                    hide-details
                    :disabled='selectedMassSelectMode === massSelectMode.clone && !isClonable(item)'
                    v-model="selectedLessonIds" 
                    :value='item.id'></v-checkbox>
                  <div v-else>
                    <!-- меню отображается только в случае, если админ || либо если препод работает со своими занятиями -->
                    <v-menu v-if='isAdmin() || $store.state.user.id === item.teacher_id'>
                      <v-btn slot='activator' flat icon color="black" class='ma-0'>
                        <v-icon>more_horiz</v-icon>
                      </v-btn>
                      <v-list dense>
                        <v-list-tile @click='$refs.LessonDialog.open(item.id)' v-if='isAdmin()'>
                            <v-list-tile-action>
                              <v-icon>edit</v-icon>
                            </v-list-tile-action>
                            <v-list-tile-content>
                              <v-list-tile-title>Редактировать</v-list-tile-title>
                            </v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile @click='$refs.TopicDialog.open(item.id)' v-if='item.status !== LESSON_STATUS.CANCELLED'>
                            <v-list-tile-action>
                              <v-icon>chrome_reader_mode</v-icon>
                            </v-list-tile-action>
                            <v-list-tile-content>
                              <v-list-tile-title>Установить тему</v-list-tile-title>
                            </v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile @click='$refs.ConductDialog.open(item.id)'>
                            <v-list-tile-action>
                              <v-icon>assignment_turned_in</v-icon>
                            </v-list-tile-action>
                            <v-list-tile-content>
                              <v-list-tile-title>Провести</v-list-tile-title>
                            </v-list-tile-content>
                        </v-list-tile>
                    </v-list>
                    </v-menu>
                    <!-- если препод видит чужие занятия, то кнопка сразу переводящая в диалог проводки/просмотра проведенного -->
                    <v-btn v-else flat icon color="black" class='ma-0' @click='$refs.ConductDialog.open(item.id)'>
                      <v-icon>more_horiz</v-icon>
                    </v-btn>
                  </div>
                </td>
              </template>
              <template slot='footer' v-if='isAdmin() && items.length > 0'>
                <tr>
                  <td colspan='10' class='text-md-right'>
                    <div v-if='selectedMassSelectMode === null'>
                      <v-menu>
                        <v-btn slot='activator' flat icon color='primary' class='mx-0'>
                          <v-icon>add</v-icon>
                        </v-btn>
                        <v-list dense>
                          <v-list-tile @click='addLesson()'>
                            <v-list-tile-action>
                              <v-icon>add</v-icon>
                            </v-list-tile-action>
                            <v-list-tile-title>
                              добавить 1 занятие
                            </v-list-tile-title>
                          </v-list-tile>
                          <v-list-tile @click="selectedMassSelectMode = massSelectMode.clone">
                            <v-list-tile-action>
                              <v-icon>file_copy</v-icon>
                            </v-list-tile-action>
                            <v-list-tile-title>
                              массовое добавление
                            </v-list-tile-title>
                          </v-list-tile>
                          <v-list-tile @click="selectedMassSelectMode = massSelectMode.destroy">
                            <v-list-tile-action>
                              <v-icon>delete</v-icon>
                            </v-list-tile-action>
                            <v-list-tile-title>
                              массовое удаление
                            </v-list-tile-title>
                          </v-list-tile>
                        </v-list>
                      </v-menu>
                    </div>
                    <div v-else>
                      <v-btn 
                        v-if='selectedLessonIds.length === 0'
                        @click='quitMassSelectMode' 
                        flat 
                        icon 
                        color='primary' 
                        class='mx-0'>
                        <v-icon>close</v-icon>
                      </v-btn>
                      <v-btn 
                        v-else
                        @click='massSelectAction' 
                        flat 
                        icon 
                        color='primary' 
                        class='mx-0'>
                        <v-icon>
                          {{ selectedMassSelectMode === massSelectMode.clone ? 'file_copy' : 'delete' }}
                        </v-icon>
                      </v-btn>
                    </div>
                  </td>
                </tr>
              </template>
            </v-data-table>
            <NoData v-else
                  title='Место для расписания'
                  text='В выборке отсутствуют элементы. Вы можете добавить их, чтобы изменить ситуацию'
                  :add='isAdmin() ? addLesson : undefined' />
          </v-flex>
        </v-layout>
      </v-container>
    </div>
  </div>
</template>

<script>

import Calendar from '@/components/Calendar/Calendar'
import { LESSON_STATUS, API_URL } from '@/components/Lesson'
import LessonStatusCircles from '@/components/Lesson/StatusCircles'
import LessonDialog from './LessonDialog'
import ConductDialog from './ConductDialog'
import TopicDialog from './TopicDialog'
import { ROLES } from '@/config'

export default {
  components: { 
    Calendar, LessonStatusCircles, LessonDialog, ConductDialog, TopicDialog
  },

  props: {
    group: {
      required: true
    },
  },

  data() {
    return {
      LESSON_STATUS,
      dialog: false,
      items: null,
      loading: false,

      selectedLessonIds: [],
      massSelectMode: {
        clone: 'clone',
        destroy: 'destroy',
      },
      selectedMassSelectMode: null, // clone | destroy
    }
  },

  created() {
    this.loadData()
  },

  methods: {
    async loadData() {
      this.loading = true
      await axios.get(apiUrl(API_URL), {
        params: {
          group_id: this.group.id,
        }
      }).then(r => {
        this.items = r.data
      })
      this.loading = false
    },

    addLesson() {
      let params = {
        group_id: this.group.id,
        year: this.group.year,
      }
      if (this.lastClonableLesson !== null) {
        params = {
          ...params,
          ..._.pick(this.lastClonableLesson, ['date', 'time', 'teacher_id', 'price', 'cabinet_id', 'duration'])
        }
      }
      this.$refs.LessonDialog.open(null, params)
    },

    indexSkippingCancelledLessons(index) {
      const cancelled_lessons_count = _.chain(this.items).sortBy('date').take(index + 1).filter(e => e.status === LESSON_STATUS.CANCELLED).value().length
      return index + 1 - cancelled_lessons_count
    },

    // Учитель не может эт видеть
    cantSee(item) {
      return this.$store.state.user.class === ROLES.TEACHER
        && item.teacher_id !== this.$store.state.user.id
    },

    quitMassSelectMode() {
      this.selectedMassSelectMode = null
      this.selectedLessonIds = []
    },

    // занятие можно копировать
    isClonable(item) {
      return item.status !== LESSON_STATUS.CANCELLED && !item.is_unplanned
    },

    async massSelectAction() {
      const ids = _.clone(this.selectedLessonIds)
      const action = 'mass-' + this.selectedMassSelectMode
      this.loading = true
      this.quitMassSelectMode()
      await axios.post(apiUrl(API_URL, action), { ids })
      await this.loadData()
    },
  },

  computed: {
    lastPlannedLesson() {
      const plannedLessons = this.items.filter(e => e.status === LESSON_STATUS.PLANNED)
      if (plannedLessons.length > 0) {
        return _.sortBy(plannedLessons, 'date').reverse()[0]
      }
      return null
    },

    // последнее занятие, с которого можно взяьть данные для дублирования
    // не внеплановое и не отмененное
    lastClonableLesson() {
      const lessons = this.items.filter(e => this.isClonable(e))
      if (lessons.length > 0) {
        return _.sortBy(lessons, 'date').reverse()[0]
      }
      return null
    },
  }
}
</script>