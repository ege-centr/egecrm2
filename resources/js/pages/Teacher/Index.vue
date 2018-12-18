<template lang="html">
  <div>
    <v-container grid-list-md fluid class="px-0" v-if='collection !== null'>
      <v-layout row wrap class='relative'>
        <Loader v-if='loading' />
        <v-flex xs12 v-else>
          <Filters :items='FILTERS' @updated='loadData' />
          <v-data-table :items='teachers' item-key='id' hide-headers hide-actions>
            <template slot="items" slot-scope="{ item }">
              <td>
                <router-link :to="{ name: 'TeacherShow', params: { id: item.id }}">
                  {{ item.names.full }}
                </router-link>
              </td>
            </template>
          </v-data-table>
        </v-flex>
      </v-layout>
    </v-container>
  </div>
</template>

<script>

import { API_URL, FILTERS } from '@/components/Teacher/data'
import Filters from '@/components/Filters'

export default {
  components: { Filters },
  
  data() {
    return {
      FILTERS,
      page: 1,
      loading: false,
      teachers: null
    }
  },

  created() {
    this.loadData()
  },

  watch: {
    page() {
        this.loadData()
    }
  },

  methods: {
    loadData(filters = '') {
      this.loading = true
      axios.get(apiUrl(`${API_URL}?page=${this.page}${filters}`)).then(response => {
        this.teachers = response.data
        this.loading = false
      })
    }
  }
}
</script>
