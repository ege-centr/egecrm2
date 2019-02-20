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
      <v-data-table hide-headers hide-actions :items='data' class='mt-3' :class='config.elevationClass'>
        <template slot='items' slot-scope="{ item }">
          <td>
            {{ item.title }}
          </td>
          <td>
            <span v-if='item.subject_id'>
              {{ getData('subjects', item.subject_id).three_letters }}
            </span>
          </td>
          <td>
            <span v-if='item.grade_id'>
              {{ getData('grades', item.grade_id).title }}
            </span>
          </td>
          <td>
            {{ item.problems_count  }} вопросов
          </td>
          <td>
            <!-- <router-link :to="{ name: 'TestResults', params: {id: item.id, clientId: client.id} }" v-if='getClientTest(item) !== undefined && getClientTest(item).results !== null'> -->
              <a 
                v-if='getClientTest(item) !== undefined && getClientTest(item).results !== null'
                @click='testPageOptions = {clientId: client.id, testId: item.id}'
              >
                результат: <b>{{ getClientTest(item).results.score }}</b> из {{ getClientTest(item).results.max_score }}
              </a>
          </td>
          <td class='text-md-right'>
            <v-btn small color='primary' @click='addTest(item)' :loading='adding_test_id === item.id' v-if='getClientTest(item) === undefined'>добавить</v-btn>
            <v-btn v-else :loading='destroying_test_id === item.id' small @click='destroyTest(item)'>
              сбросить
            </v-btn>
          </td>
        </template>
      </v-data-table>
    </div>

    <div v-if='testPageOptions !== null' class='relative' style='margin-top: 100px'>
      <TestClientStartPage :options='testPageOptions' />
    </div>
  </div>
</template>

<script>
import { API_URL, CLIENT_TESTS_API_URL } from '@/components/Test'

import TestClientStartPage from '@/pages/Test/Client/Start'

export default {
  props: {
    client: {
      type: Object,
      default: null,
    },
  },

  components: { TestClientStartPage },
  
  data() {
    return {
      loading: false,
      items: null,
      client_tests: null,
      adding_test_id: false,
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
        this.selected_tab = this.tabsWithData.slice(-1)[0].id
        this.loading = false
      })
      // axios.get(apiUrl(CLIENT_TESTS_API_URL) + queryString({client_id: this.client.id}))
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
      axios.delete(apiUrl(CLIENT_TESTS_API_URL, this.client.tests[index].id)).then(r => {
        this.client.tests.splice(index, 1)
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