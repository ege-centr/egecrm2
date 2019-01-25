<template>
  <div>
    <IndexPage :api-url='API_URL' :filters='FILTERS' :pagination='false'>
      <template slot='items' slot-scope='{ items }'>
        <v-data-table :items='items' item-key='id' hide-headers hide-actions>
            <template slot="items" slot-scope="{ item }">
              <td width='400'>
                <router-link :to="{ name: 'TeacherShow', params: { id: item.id }}">
                  {{ item.names.full }}
                </router-link>
              </td>
              <td>
                <span v-for='(subject_id, index) in item.subjects_ec' :key='subject_id'>{{ getData('subjects', subject_id).three_letters }}{{ index === item.subjects_ec.length - 1 ? '' : '+' }}</span>
              </td>
            </template>
          </v-data-table>
      </template>
    </IndexPage>
  </div>
</template>

<script>

import { IndexPage } from '@/components/UI'
import { API_URL, FILTERS } from '@/components/Teacher'

export default {
  components: { IndexPage },

  data() {
    return {
      API_URL,
      FILTERS,
    }
  },
}
</script>
