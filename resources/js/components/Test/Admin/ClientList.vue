<template>
  <div>
    <TestDialog ref='TestDialog'/>

    <Loader v-if='loading' />
    <v-data-table v-else hide-headers hide-actions :items='items' class='mt-3' :class='config.elevationClass'>
      <template slot='items' slot-scope="{ item }">
        <td>
          <a @click='$refs.TestDialog.open(item.id)'>{{ item.title }}</a>
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
          <div v-if='getClientTest(item) !== undefined && getClientTest(item).results !== null'>
            результат: <b>{{ getClientTest(item).results.score }}</b> из {{ getClientTest(item).results.max_score }}
          </div>
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
</template>

<script>
import { API_URL, CLIENT_TESTS_API_URL } from '@/components/Test'

// TODO: почему это не работает?
// import { API_URL, CLIENT_TESTS_API_URL, TestDialog } from '@/components/Test'
import TestDialog from '@/components/Test/Admin/Dialog'

export default {
  props: {
    client: {
      type: Object,
      default: null,
    },
  },

  components: { TestDialog },
  
  data() {
    return {
      loading: false,
      items: null,
      client_tests: null,
      adding_test_id: false,
      destroying_test_id: false,
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
}
</script>