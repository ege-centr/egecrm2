<template>
  <div>
    <Loader v-if='loading' />
    <v-data-table v-else hide-headers hide-actions :items='items' class='mt-3' :class='config.elevationClass'>
      <template slot='items' slot-scope="{ item }">
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
          <div v-if='item.results === null'>
            <router-link :to="{ name: 'TestClientStart', params: { id: item.test.id} }">
              <v-btn small color='primary'>начать</v-btn>
            </router-link>
          </div>
          <div v-else>
            результат: <b>{{ item.results.score }}</b> из {{ item.results.max_score }}
          </div>
        </td>
      </template>
    </v-data-table>
  </div>
</template>

<script>
import { API_URL, CLIENT_TESTS_API_URL } from '@/components/Test'

export default {
  props: {
    clientId: {
      type: Number,
      required: true,
    },
  },

  data() {
    return {
      loading: false,
      items: [],
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