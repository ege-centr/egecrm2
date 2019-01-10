<template>
  <div>
    <TestDialog ref='TestDialog'/>

    <Loader v-if='loading' />
    <v-data-table v-else hide-headers hide-actions :items='items' class='mt-3'>
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
        <td class='text-md-right'>
          <v-btn small color='primary' @click='addClientTest(item)' :loading='adding_test_id === item.id'>добавить</v-btn>
        </td>
      </template>
    </v-data-table>
  </div>
</template>

<script>
import { API_URL, CLIENT_TESTS_API_URL, TestDialog } from '@/components/Test'

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
      adding_test_id: false,
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
    },

    addClientTest(test) {
      this.adding_test_id = test.id
      axios.post(apiUrl(CLIENT_TESTS_API_URL), {
        client_id: this.client.id,
        test_id: test.id,
      }).then(r => {
        this.adding_test_id = false
      })
    },
  },
}
</script>