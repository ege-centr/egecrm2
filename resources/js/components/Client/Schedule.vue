<template>
  <div>
    <Loader class='loader-wrapper_transparent' v-if='items === null' />
    <div v-else>
      <div v-if='items.length > 0'>
        <div v-for='(lessons, yearMonth) in byMonths' :key='yearMonth'>
          <div class='headline mb-2'>{{ yearMonth + '-01' | dateFormat('MMMM YYYY') }}</div>
          <v-data-table 
            class='mb-5'
            :class='config.elevationClass' 
            hide-actions 
            hide-headers 
            :items='lessons'
          >
            <template slot='items' slot-scope='{ item, index }'>
              <td width='25'>
                {{ indexSkippingCancelledLessons(index, lessons) }}
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
      </div>
      <NoData v-else />
    </div>
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
      LESSON_STATUS,
    }
  },
  
  mounted() {
    axios.get(apiUrl(CLIENT_LESSONS_API_URL) + queryString({
      entity_id: this.clientId
    })).then(r => this.items = r.data)
  },

  methods: {
    indexSkippingCancelledLessons,
  },

  computed: {
    byMonths() {
      const data = {}
      this.items.forEach(item => {
        let yearMonth = moment(item.date).format('YYYY-MM')
        if (!(yearMonth in data)) {
          data[yearMonth] = []
        }
        data[yearMonth].push(item)
      })
      return data
    },
  },
}
</script>

<style lang='scss' scoped>
  .headline {
    text-transform: capitalize;
  }
</style>
