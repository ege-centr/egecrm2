<template>
  <div>
    <div v-if='items !== null && tabs.length' class='mb-3'>
      <v-chip v-for="(tab, index) in tabs" class='pointer ml-0 mr-3'
        :class="{'primary white--text': selected_tab_index === index}"
        @click='selected_tab_index = selected_tab_index === index ? null : index'
        :key='index'
      >
      {{ getData('subjects', tab.subject_id).three_letters }}–{{ tab.client_grade_id }}
    </v-chip>
    </div>
    <Loader class='loader-wrapper_transparent' v-if='items === null' />
    <v-card v-else>
      <v-card-text>
        <div v-if='items.length > 0'>
            <v-data-table 
              hide-actions 
              hide-headers 
              :items='filteredItems'
            >
              <template slot='items' slot-scope='{ item }'>
                <td width='65' class='pr-0 grey--text'>
                  <div class='lesson-status' :class="{
                    'blue': item.status === LESSON_STATUS.PLANNED,
                    'green': item.status === LESSON_STATUS.CONDUCTED,
                    'grey': item.status === LESSON_STATUS.CANCELLED,
                  }"></div>
                  <span v-show='item.status !== LESSON_STATUS.CANCELLED'>
                    {{ indexSkippingCancelledLessons(item) }}
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
                  {{ getData('subjects', item.subject_id).three_letters }}–{{ item.client_grade_id }}
                </td>
                <td width='300'>
                  <span v-if='item.teacher_id'>
                    {{ getData('teachers', item.teacher_id).names.abbreviation }}
                  </span>
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
              </template>
            </v-data-table>
        </div>
        <NoData v-else />
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
import { CLIENT_LESSONS_API_URL } from '@/components/Lesson'
import { ROLES } from '@/config'
import { LESSON_STATUS, indexSkippingCancelledLessons } from '@/components/Lesson'
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
    axios.get(apiUrl(CLIENT_LESSONS_API_URL) + queryString({
      entity_id: this.clientId
    })).then(r => this.items = r.data)
  },

  methods: {
    indexSkippingCancelledLessons(item) {
      const index = this.filteredItems.findIndex(e => e === item)
      const cancelled_lessons_count = _.chain(this.filteredItems).sortBy('date').take(index + 1).filter(e => e.status === LESSON_STATUS.CANCELLED).value().length
      return index + 1 - cancelled_lessons_count
    }
  },

  computed: {
    filteredItems() {
      if (this.selected_tab_index === null) {
        return this.items
      }
      const tab = this.tabs[this.selected_tab_index]
      return this.items.filter(e => e.subject_id === tab.subject_id && e.client_grade_id === tab.client_grade_id)
    },

    tabs() {
      const tabs = []
      this.items.forEach(item => {
        let tab = _.pick(item, ['subject_id', 'client_grade_id'])
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
    left: 6px;
    top: 20px;
  }

  .headline {
    text-transform: capitalize;
  }
</style>
