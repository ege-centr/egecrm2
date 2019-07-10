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
          {{ item.started_at | date-time }}
        </td>
        <td>
          {{ item.test.problems_count  }} вопросов
        </td>
        <td class='text-md-right'>
          <a v-if='item.is_finished' @click='testPageOptions = {clientId: clientId, testId: item.test.id}'>
            результат: <b>{{ item.results.score }}</b> из {{ item.results.max_score }}
          </a>
          <router-link v-else :to="{ name: 'TestClientStart', params: { id: item.test.id} }">
            <v-btn small color='primary'>
              {{ item.is_in_progress ? 'продолжить' : 'начать' }}
            </v-btn>
          </router-link>
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
      axios.get(apiUrl(CLIENT_TESTS_API_URL), {
        params: {
          client_id: this.clientId,
          includeTest: true
        }
      }).then(r => {
        this.items = r.data.data
        this.loading = false
      })
    },
  },
}
</script>