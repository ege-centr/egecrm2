<template>
  <div>
    <div v-if='items !== null' class='mb-3 flex-items justify-space-between align-center'>
      <div>
        <v-chip v-for="item in yearTabs" class='pointer ml-0 mr-3'
            :class="{'primary white--text': item.id == selected_year_tab}"
            @click='selected_year_tab = item.id'
            :key='item.id'
          >
            {{ item.title }}
        </v-chip>
      </div>
      <div>
        <v-chip v-for="item in subjectTabs" class='pointer mr-0 ml-3'
            :class="{'primary white--text': item.id == selected_subject_tab}"
            @click='selected_subject_tab = selected_subject_tab === item.id ? null : item.id'
            :key='item.id'
          >
            {{ item.three_letters }}
        </v-chip>
      </div>
    </div>
    <Loader class='loader-wrapper_transparent' v-if='items === null' />
    <v-card v-else class='elevation-0'>
      <v-card-text class='pa-0'>
        <data-table 
          hide-actions 
          hide-headers 
          :items='filteredItems'
        >
          <tr slot-scope='{ item }'>
            <!-- ЗАНЯТИЕ -->
            <td width='65' class='pr-0 grey--text'>
              <div class='lesson-status' :class="{
                'blue': item.status === LESSON_STATUS.PLANNED,
                'green': item.status === LESSON_STATUS.CONDUCTED,
                'grey': item.status === LESSON_STATUS.CANCELLED,
              }"></div>
              <span v-show='!excludeFromIndex(item)'>
                {{ getIndex(item) }}
              </span>
            </td>
            <td width='150'>
              {{ item.date + ' ' + item.time | date-time }}
            </td>
            <td width='150'>
              <router-link :to="{name: 'GroupShow', params: {id: item.group_id}}">
                Группа {{ item.group_id }}
              </router-link>
            </td>
            <td width='150'>
              <Cabinet :id='item.cabinet_id' />
            </td>
            <td width='150'>
              <SubjectGrade :item='item.group' />
            </td>
            <td width='250'>
              <span v-if='item.teacher_id'>
                {{ getData('teachers', item.teacher_id).default_name }}
              </span>
            </td>
            <td width='150'>
              <span v-if='item.status === LESSON_STATUS.CONDUCTED && item.clientLesson.price > 0'>
                {{ item.clientLesson.price }} руб.
              </span>
            </td>
            <td>
              <v-tooltip bottom v-if='item.status === LESSON_STATUS.CONDUCTED && item.clientLesson.comment'>
                <v-icon class='cursor-default' slot='activator'>comment</v-icon>
                <span>{{ item.clientLesson.comment }}</span>
              </v-tooltip>
            </td>
            <td>
              <span v-if='item.status !== LESSON_STATUS.CONDUCTED'>
                <span v-if='item.status === LESSON_STATUS.PLANNED'>
                  планируется
                </span>
                <span class='grey--text' v-else>
                  отменено
                </span>
              </span>
              <span v-else>
                <span v-if='item.clientLesson.is_absent'>
                  не был
                </span>
                <span v-else>
                  был
                </span>
              </span>
            </td>
          </tr>
        </data-table>
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
import { ROLES } from '@/config'
import { LESSON_STATUS } from '@/components/Lesson'
import Cabinet from '@/components/UI/Cabinet'

export default {
  props: ['clientId'],

  components: { Cabinet },
  
  data() {
    return {
      items: null,
      selected_year_tab: null,
      selected_subject_tab: null,
      LESSON_STATUS,
    }
  },
  
  mounted() {
    axios.get(apiUrl('schedule/client', this.clientId)).then(r => {
      this.items = r.data
      if (this.yearTabs.length) {
          this.selected_year_tab = this.yearTabs.slice(-1)[0].id
        }
    })
  },

  methods: {
    getIndex(item) {
      const index = this.filteredItems.findIndex(e => e === item)
      const exclude_count = _.chain(this.filteredItems).sortBy('date').take(index + 1).filter(e => this.excludeFromIndex(e)).value().length
      return index + 1 - exclude_count
    },
    
    // какие позиции не нумеровать?
    excludeFromIndex(item) {
      return item.status === LESSON_STATUS.CANCELLED
    },
  },

  computed: {
    filteredItems() {
      if (this.selected_subject_tab !== null) {
        return this.filteredByYear.filter(e => e.group.subject_id === this.selected_subject_tab)
      }
      return this.filteredByYear
    },

    filteredByYear() {
      return this.items.filter(e => e.group.year === this.selected_year_tab)
    },

    yearTabs() {
      return this.$store.state.data.years.filter(d => {
        return this.items.findIndex(e => e.group.year === d.id) !== -1
      })
    },

    subjectTabs() {
      return this.$store.state.data.subjects.filter(subject => {
        return this.filteredByYear.findIndex(e => e.group.subject_id === subject.id) !== -1
      })
    },
  },
}
</script>

<style lang='scss' scoped>
   .v-datatable {
    & tr {
      & td {
        position: relative;
      }
    }
  }

  .lesson-status {
    border-radius: 50%;
    height: 8px;
    width: 8px;
    position: absolute;
    left: 10px;
    top: 20px;
  }

  .headline {
    text-transform: capitalize;
  }
</style>
