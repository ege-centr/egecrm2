<template>
  <div>
    <div class='flex-items justify-end'>
      <div class='mr-5'>
        <v-btn fab dark small color="red" @click='$refs.TestIntroTextDialog.open()'>
          <v-icon dark>format_align_left</v-icon>
        </v-btn>
        <span>редактировать вступительный текст</span>
      </div>
      <div>
        <v-btn fab dark small color="red" @click='$refs.TestDialog.open()'>
          <v-icon dark>add</v-icon>
        </v-btn>
        <span>добавить тест</span>
      </div>
    </div>

    <TestDialog ref='TestDialog' @updated='loadData' />
    <TestIntroTextDialog ref='TestIntroTextDialog' />

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
      </template>
    </v-data-table>
  </div>
</template>

<script>

import { API_URL, TestDialog, TestIntroTextDialog } from '@/components/Test'

export default {

  components: { TestDialog, TestIntroTextDialog },
  
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
      axios.get(apiUrl(API_URL)).then(r => {
        this.items = r.data
        this.loading = false
      })
    },
  },
}
</script>