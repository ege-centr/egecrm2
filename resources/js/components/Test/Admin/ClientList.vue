<template>
  <div>

    <Loader transparent v-if='loading' />
    
    <div v-else>
      <div>
         <v-chip v-for="item in tabsWithData" class='pointer ml-0 mr-3'
            :class="{'primary white--text': item.id == selected_tab}"
            @click='selected_tab = item.id'
            :key='item.id'
          >
            {{ item.title }}
          </v-chip>
      </div>
      <v-data-table v-if='data.length > 0' hide-headers hide-actions :items='data' class='mt-3' :class='config.elevationClass'>
        <template slot='items' slot-scope="{ item }">
          <td width='200'>
            {{ item.title }}
          </td>
          <td width='150'>
            <span v-if='item.subject_id'>
              {{ getData('subjects', item.subject_id).three_letters }}
            </span>
          </td>
          <td width='150'>
            <span v-if='item.grade_id'>
              {{ getData('grades', item.grade_id).title }}
            </span>
          </td>
          <td width='200'>
            {{ item.problems_count  }} вопросов
          </td>
          <td width='200'>
            {{ item.minutes  }} минут
          </td>
          <td width='200'>
            <span v-if='getClientTest(item) !== undefined && getClientTest(item).started_at !== null'>
              {{ getClientTest(item).started_at | date-time }}
            </span>
          </td>
          <td width='300'>
            <span v-if='getClientTest(item) !== undefined'>
              <a 
                v-if='getClientTest(item).is_finished'
                @click='testPageOptions = {clientId: client.id, testId: item.id}'
              >
                результат: <b>{{ getClientTest(item).results.score }}</b> из {{ getClientTest(item).results.max_score }}
              </a>
              <span v-else>в процессе</span>
            </span>
          </td>
          <td class='pa-0' width='100'>
            <v-btn small color='primary' class='btn-td' flat
              @click='addTest(item)' 
              :loading='adding_test_id === item.id' 
              v-if='getClientTest(item) === undefined'>добавить</v-btn>
            <div v-else>
              <span v-if='getClientTest(item) && getClientTest(item).is_in_progress' >
                <v-progress-circular v-if='reloading_test_id === item.id' :size="20" color='primary' indeterminate></v-progress-circular>
                <span v-else class='grey--text flex-items align-center justify-end pr-3'>
                  <v-icon class='mr-1'>access_time</v-icon>
                  <TestCountDown
                    :minutes='item.minutes'
                    :from='getClientTest(item).started_at' 
                    @end='reloadClientTest(item)' 
                  />
                </span>
              </span>
              <v-btn v-else flat
                :loading='destroying_test_id === item.id' small @click='destroyTest(item)' class='btn-td'>
                сбросить
              </v-btn>
            </div>
          </td>
        </template>
      </v-data-table>
      <NoData v-else square :class='config.elevationClass' />
    </div>

    <ResultsDialog :item='testPageOptions' :show-all-answers='true' />
  </div>
</template>

<script>
import { API_URL, CLIENT_TESTS_API_URL } from '@/components/Test'

import TestCountDown from '@/components/Test/CountDown'
import ResultsDialog from '@/components/Test/ResultsDialog'

export default {
  props: {
    client: {
      type: Object,
      default: null,
    },
  },

  components: { TestCountDown, ResultsDialog },
  
  data() {
    return {
      loading: false,
      items: null,
      client_tests: null,
      adding_test_id: false,
      reloading_test_id: false,
      destroying_test_id: false,
      selected_tab: null,
      testPageOptions: null,
    }
  },

  created() {
    this.loadData()
  },

  methods: {
    loadData() {
      this.loading = true
      axios.get(apiUrl(API_URL)).then(r => {
        this.items = r.data
        if (this.tabsWithData.length > 0) {
          this.selected_tab = this.tabsWithData.slice(-1)[0].id
        }
        this.loading = false
      })
      // axios.get(apiUrl(CLIENT_TESTS_API_URL) + queryString({client_id: this.client.id}))
    },

    reloadClientTest(test) {
      this.reloading_test_id = test.id
      axios.get(apiUrl(CLIENT_TESTS_API_URL, test.id) + queryString({
        client_id: this.client.id
      })).then(r => {
        const index = this.client.tests.findIndex(e => e.test_id === test.id)
        this.client.tests.splice(index, 1, r.data)
        this.reloading_test_id = false
      })
    },

    addTest(test) {
      this.adding_test_id = test.id
      axios.post(apiUrl(CLIENT_TESTS_API_URL), {
        client_id: this.client.id,
        test_id: test.id,
      }).then(r => {
        this.client.tests.push(r.data)
        this.adding_test_id = false
      })
    },

    destroyTest(test) {
      const index = this.client.tests.findIndex(e => e.test_id === test.id)
      this.destroying_test_id = test.id
      // если открыта страница просмотра результатов, закрываем её
      if (this.testPageOptions !== null && this.testPageOptions.testId === test.id) {
        this.testPageOptions = null
      }
      axios.delete(apiUrl(CLIENT_TESTS_API_URL, this.client.tests[index].id)).then(r => {
        this.client.tests.splice(index, 1)
        this.destroying_test_id = false
      }).catch(e => {
        this.client.tests.splice(index, 1, e.response.data)
        this.destroying_test_id = false
      })
    },

    getClientTest(test) {
      return this.client.tests.find(e => e.test_id === test.id)
    }
  },

  computed: {
    data() {
      return this.items.filter(item => item.grade_id == this.selected_tab)
    },

    tabsWithData() {
      return this.$store.state.data.grades.filter(grade => {
        return this.items.findIndex(item => item.grade_id === grade.id) !== -1
      })
    },
  }
}
</script>