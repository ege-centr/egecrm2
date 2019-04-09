<template>
  <div style='min-height: 200px'>
    <LessonDialog ref='LessonDialog' />
    <ConductDialog ref='ConductDialog' />
    <Loader v-if='items === null' />
    <div v-else>
      <v-container grid-list-xl class="pa-0 ma-0" fluid>
        <v-layout>
          <v-flex>
            <Calendar :year='group.year' :lessons='items' :with-special-dates='true' :group='this.group' />
          </v-flex>
          <v-spacer></v-spacer>
          <v-flex>
            <v-data-table hide-actions hide-headers :items='items' class='mt-3'>
              <template slot='items' slot-scope="{ index, item }">
                <td width='24' class='px-2'>
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
                  <span v-if='item.cabinet_id'>
                    {{ getData('cabinets', item.cabinet_id).title }}
                  </span>
                </td>
                <td>
                  <span v-if='item.teacher_id'>
                    {{ getData('teachers', item.teacher_id).default_name }}
                  </span>
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
                <td class='text-md-right'>
                  <div v-if='readonly'>
                    <v-btn slot='activator' flat icon small color="black" class='ma-0' 
                      v-if='item.status !== LESSON_STATUS.CANCELLED'
                      @click='$refs.ConductDialog.open(item.id)'>
                      <v-icon>more_horiz</v-icon>
                    </v-btn>
                  </div>
                  <v-menu v-else>
                    <v-btn slot='activator' flat icon small color="black" class='ma-0'>
                      <v-icon>more_horiz</v-icon>
                    </v-btn>
                    <v-list dense>
                      <v-list-tile @click='$refs.LessonDialog.open(item.id)'>
                          <v-list-tile-action>
                            <v-icon>edit</v-icon>
                          </v-list-tile-action>
                          <v-list-tile-content>
                            <v-list-tile-title>Редактировать</v-list-tile-title>
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
                </td>
              </template>
              <template slot='footer' v-if='!readonly'>
                <tr>
                  <td colspan='10' class='pa-0 text-md-center'>
                    <v-btn slot='activator' small flat color='primary' class='btn-tr' 
                      @click='$refs.LessonDialog.open(null, {
                          group_id: group.id,
                          year: group.year,
                      })'
                      v-if='lastPlannedLesson === null' >
                        <v-icon class="mr-1">add</v-icon>
                        добавить
                    </v-btn>
                    <v-menu style='width: 100%' v-else>
                      <v-btn slot='activator' small flat color='primary' class='btn-tr' :loading='filling'>
                        <v-icon class="mr-1">add</v-icon>
                        добавить
                      </v-btn>
                      <v-list dense>
                        <v-list-tile @click='$refs.LessonDialog.open(null, {
                          group_id: group.id,
                          year: group.year,
                        })'>
                          <v-list-tile-title>
                            добавить 1 занятие
                          </v-list-tile-title>
                        </v-list-tile>
                        <v-list-tile @click='fillSchedule'>
                          <v-list-tile-title>
                            проставить занятия до 1 июня текущего года
                          </v-list-tile-title>
                        </v-list-tile>
                      </v-list>
                    </v-menu>
                  </td>
                </tr>
              </template>
              <template slot='no-data'>
                <NoData />
              </template>
            </v-data-table>
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
import { ROLES } from '@/config'

export default {
  components: { 
    Calendar, LessonStatusCircles, LessonDialog, ConductDialog 
  },

  props: {
    group: {
      required: true
    },

    readonly: {
      type: Boolean,
      required: false,
      default: false,
    }
  },

  data() {
    return {
      LESSON_STATUS,
      dialog: false,
      items: null,
      filling: false,
    }
  },

  created() {
    this.loadData()
  },

  methods: {
    async loadData() {
      await axios.get(apiUrl(API_URL), {
        params: {
          group_id: this.group.id,
        }
      }).then(r => {
        this.items = r.data
      })
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

    // Учитель не может эт видеть
    cantSee(item) {
      return this.$store.state.user.class === ROLES.TEACHER
        && item.teacher_id !== this.$store.state.user.id
    },

    async fillSchedule() {
      if (this.lastPlannedLesson !== null) {
        this.filling = true
        let lastPlannedLesson = clone(this.lastPlannedLesson)
        let date = lastPlannedLesson.date
        while (true) {
          date = moment(date).add(1, 'week').format('YYYY-MM-DD')
          if (moment(date).format('M') == 6) {
            this.filling = false
            return
          }
          await this.store({...lastPlannedLesson, date})
        }
      }
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
  }
}
</script>
