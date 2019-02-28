<template>
  <div>
    <div v-if='items !== null && tabs.length' class='mb-3'>
      <v-chip v-for="(tab, index) in tabs" class='pointer ml-0 mr-3'
        :class="{'primary white--text': selected_tab_index === index}"
        @click='selected_tab_index = selected_tab_index === index ? null : index'
        :key='index'
      >
        <SubjectGrade :item='tab' />
      </v-chip>
      <v-chip class='pointer ml-0 mr-3'
        v-if='items.findIndex(e => isEgeTrial(e)) !== -1'
        :class="{'primary white--text': selected_tab_index === -1}"
        @click='selected_tab_index = selected_tab_index === -1 ? null : -1'
        :key='-1'
      >
        ПРОБНЫЙ ЕГЭ
      </v-chip>
    </div>
    <Loader class='loader-wrapper_transparent' v-if='items === null' />
    <v-card v-else>
      <v-card-text class='pa-0'>
        <div v-if='items.length > 0'>
            <v-data-table 
              hide-actions 
              hide-headers 
              :items='filteredItems'
            >
              <template slot='items' slot-scope='{ item }'>
                <!-- ПРОБНЫЙ ЕГЭ -->
                <tr v-if="isEgeTrial(item)">
                  <td width='48'>
                  </td>
                  <td width='150'>
                    {{ item.date | date }}
                  </td>
                  <td width='150'>
                    Пробный ЕГЭ
                  </td>
                  <td width='150'>
                    {{ item.sum }} руб.
                  </td>
                  <td width='150'>
                    <SubjectGrade :item='item' />
                  </td>
                  <td colspan='4'>{{ item.description }}</td>
                </tr>

                <!-- ЗАНЯТИЕ -->
                <tr v-else>
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
                    <SubjectGrade :item='item' />
                  </td>
                  <td width='250'>
                    <span v-if='item.teacher_id'>
                      {{ getData('teachers', item.teacher_id).names.abbreviation }}
                    </span>
                  </td>
                  <td width='150'>
                    <span v-if='item.status === LESSON_STATUS.CONDUCTED && item.price > 0'>
                      {{ item.price }} руб.
                    </span>
                  </td>
                  <td>
                    <v-tooltip bottom v-if='item.status === LESSON_STATUS.CONDUCTED && item.comment'>
                      <v-icon class='cursor-default' slot='activator'>comment</v-icon>
                      <span>{{ item.comment }}</span>
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
                      <span v-if='item.is_absent'>
                        не был
                      </span>
                      <span v-else>
                        был
                      </span>
                    </span>
                  </td>
                </tr>
              </template>
            </v-data-table>
        </div>
        <NoData v-else />
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
      selected_tab_index: null,
      LESSON_STATUS,
    }
  },
  
  mounted() {
    axios.get(apiUrl('schedule/client', this.clientId)).then(r => this.items = r.data)
  },

  methods: {
    getIndex(item) {
      const index = this.filteredItems.findIndex(e => e === item)
      const exclude_count = _.chain(this.filteredItems).sortBy('date').take(index + 1).filter(e => this.excludeFromIndex(e)).value().length
      return index + 1 - exclude_count
    },
    
    // какие позиции не нумеровать?
    excludeFromIndex(item) {
      return item.status === LESSON_STATUS.CANCELLED || this.isEgeTrial(item)
    },

    isEgeTrial(item) {
      return 'score' in item
    }
  },

  computed: {
    filteredItems() {
      if (this.selected_tab_index === null) {
        return this.items
      }
      if (this.selected_tab_index === -1) {
        return this.items.filter(e => this.isEgeTrial(e))
      }
      const tab = this.tabs[this.selected_tab_index]
      return this.items.filter(e => !this.isEgeTrial(e) && e.subject_id === tab.subject_id && e.grade_id === tab.grade_id)
    },

    tabs() {
      const tabs = []
      this.items.forEach(item => {
        let tab = _.pick(item, ['subject_id', 'grade_id'])
        if (tabs.findIndex(e => _.isEqual(e, tab)) === -1) {
          tabs.push(tab)
        }
      })
      return tabs
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
