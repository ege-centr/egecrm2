<template lang="html">
  <div>
    <Loader v-if='teachers === null' />
    <v-container grid-list-md fluid class="px-0" v-else>
      <v-layout row wrap class='relative'>
        <v-flex xs12>
          <Filters :items='FILTERS' @updated='loadData' class="mb-3" />
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

import { API_URL, FILTERS } from '@/components/Teacher'
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
