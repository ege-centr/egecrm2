<template>
  <div>
    <ResultsDialog :item='testPageOptions' />
    <Loader v-if='loading' />
    <DataTable v-else :items='items'>
      <tr slot-scope='{ item }'>
        <td>
          {{ item.test.title }}
        </td>
        <td>
          <span v-if='item.test.subject_id'>
            {{ getData('subjects', item.test.subject_id).three_letters }}
          </span>
        </td>
        <td>
          <span v-if='item.test.grade_id'>
            {{ getData('grades', item.test.grade_id).title }}
          </span>
        </td>
        <td>
          {{ item.test.problems_count  }} вопросов
        </td>
        <td class='text-md-right'>
          <router-link v-if='item.results === null' :to="{ name: 'TestClientStart', params: { id: item.test.id} }">
            <v-btn small color='primary'>начать</v-btn>
          </router-link>
          <a @click='testPageOptions = {clientId: clientId, testId: item.test.id}' v-else>
            результат: <b>{{ item.results.score }}</b> из {{ item.results.max_score }}
          </a>
        </td>
      </tr>
    </DataTable>
  </div>
</template>

<script>
import { API_URL, CLIENT_TESTS_API_URL } from '@/components/Test'
import ResultsDialog from '@/components/Test/ResultsDialog'

export default {
  props: {
    clientId: {
      type: Number,
      required: true,
    },
  },

  components: { ResultsDialog },

  data() {
    return {
      loading: false,
      items: [],
      testPageOptions: null,
    }
  },

  created() {
    this.loadData()
  },

  methods: {
    loadData() {
      this.loading = true
      axios.get(apiUrl(CLIENT_TESTS_API_URL) + queryString({client_id: this.clientId, includeTest: true})).then(r => {
        this.items = r.data
        this.loading = false
      })
    },
  },
}
</script>