<template>
  <div>
    <div class='flex-items'>
      <v-spacer></v-spacer>
      <AddBtn class='mr-2' @click.native='$refs.TestIntroTextDialog.open()' label='редактировать вступительный текст' icon='format_align_left' />
      <AddBtn @click.native='$refs.TestDialog.open()' animated label='добавить тест' />
    </div>

    <TestDialog ref='TestDialog' @updated='loadData' />
    <TestIntroTextDialog ref='TestIntroTextDialog' />

    <Loader v-if='loading' />
    <div v-else>
      <v-data-table v-if='items.length > 0' hide-headers hide-actions :items='items' class='mt-3' :class="config.elevationClass">
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
      <NoData 
        :add='() => $refs.TestDialog.open(null)'
        box
        v-else />
    </div>
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