<template>
  <div>
    <!-- <div class='flex-items'>
      <router-link :to="{name: 'TestIndex'}">
        <v-chip class='pointer'>тесты</v-chip>
      </router-link>
      <v-chip class='primary white--text'>ученики</v-chip>
    </div> -->

    <ResultsDialog :item='testPageOptions' />
    <TestDialog ref='TestDialog' />
    <DisplayData 
      :invisible-filters='{includeTest: 1}'
      :filters='FILTERS'
      :api-url='CLIENT_TESTS_API_URL' 
      :paginate='30'
    >
      <template slot='items' slot-scope="{ items }">
        <DataTable v-if='items.length > 0' :items='items'>
          <tr slot-scope='{ item }'>
            <td>
              <router-link :to="{name: 'ClientShow', params: {id: item.client_id}}">
                {{ item.client.default_name }}
              </router-link>
            </td>
            <td>
              <a @click='$refs.TestDialog.open(item.test.id)'>{{ item.test.title }}</a>
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
              <a v-if='item.is_finished' @click='testPageOptions = {clientId: item.client_id, testId: item.test.id}'>
                результат: <b>{{ item.results.score }}</b> из {{ item.results.max_score }}
              </a>
              <span v-if='item.is_in_progress'>
                в процессе
              </span>
            </td>
          </tr>
        </DataTable>
        <NoData 
          fullscreen
          v-else />
      </template>
    </DisplayData>
  </div>
</template>

<script>

import DisplayData from '@/components/UI/DisplayData'
import { CLIENT_TESTS_API_URL, FILTERS, TestDialog } from '@/components/Test'
import ResultsDialog from '@/components/Test/ResultsDialog'

export default {
  components: { DisplayData, ResultsDialog, TestDialog },
  
  data() {
    return {
      FILTERS,
      CLIENT_TESTS_API_URL,
      testPageOptions: null,
    }
  },
}
</script>
