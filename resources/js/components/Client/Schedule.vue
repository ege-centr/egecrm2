<template>
  <div>
    <div v-if='items !== null' class='mb-3'>
       <v-chip v-for="item in tabsWithData" class='pointer ml-0 mr-3'
          :class="{'primary white--text': item.id == selected_tab}"
          @click='selected_tab = item.id'
          :key='item.id'
        >
          {{ item.title }}
        </v-chip>
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
                'blue': item.lesson.status === LESSON_STATUS.PLANNED,
                'green': item.lesson.status === LESSON_STATUS.CONDUCTED,
                'grey': item.lesson.status === LESSON_STATUS.CANCELLED,
              }"></div>
              <span v-show='!excludeFromIndex(item)'>
                {{ getIndex(item) }}
              </span>
            </td>
            <td width='150'>
              {{ item.lesson.date + ' ' + item.lesson.time | date-time }}
            </td>
            <td width='150'>
              <router-link :to="{name: 'GroupShow', params: {id: item.group_id}}">
                Группа {{ item.lesson.group_id }}
              </router-link>
            </td>
            <td width='150'>
              <Cabinet :id='item.lesson.cabinet_id' />
            </td>
            <td width='150'>
              <SubjectGrade :item='item.lesson.group' />
            </td>
            <td width='250'>
              <span v-if='item.lesson.teacher_id'>
                {{ getData('teachers', item.lesson.teacher_id).names.abbreviation }}
              </span>
            </td>
            <td width='150'>
              <span v-if='item.status === LESSON_STATUS.CONDUCTED && item.price > 0'>
                {{ item.price }} руб.
              </span>
            </td>
            <td>
              <v-tooltip bottom v-if='item.lesson.status === LESSON_STATUS.CONDUCTED && item.comment'>
                <v-icon class='cursor-default' slot='activator'>comment</v-icon>
                <span>{{ item.comment }}</span>
              </v-tooltip>
            </td>
            <td>
              <span v-if='item.lesson.status !== LESSON_STATUS.CONDUCTED'>
                <span v-if='item.lesson.status === LESSON_STATUS.PLANNED'>
                  планируется
                </span>
                <span class='grey--text' v-else>
                  отменено
                </span>
              </span>
              <span v-else>
                <span v-if='item.is_absent'>
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
import { tabsWithData } from '@/other/functions'
import Cabinet from '@/components/UI/Cabinet'

export default {
  props: ['clientId'],

  components: { Cabinet },
  
  data() {
    return {
      items: null,
      selected_tab: null,
      LESSON_STATUS,
    }
  },
  
  mounted() {
    axios.get(apiUrl('schedule/client', this.clientId)).then(r => {
      this.items = r.data
      if (this.tabsWithData.length) {
          this.selected_tab = this.tabsWithData.slice(-1)[0].id
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
      return item.lesson.status === LESSON_STATUS.CANCELLED
    },
  },

  computed: {
    filteredItems() {
      return this.items.filter(e => e.lesson.group.year === this.selected_tab)
    },

    tabsWithData() {
      return this.$store.state.data.years.filter(d => {
        return this.items.findIndex(e => e.lesson.group.year === d.id) !== -1
      })
    }
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
