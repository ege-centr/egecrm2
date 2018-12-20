<template lang="html">
  <div>
    <Loader v-if='loading' />
    <v-layout>
      <v-flex xs12 class="text-xs-right">
        <router-link :to="{ name: 'ClientCreate' }" class='black-link'>
          <v-btn small fab color="primary">
            <v-icon dark>add</v-icon>
          </v-btn>
          добавить клиента
        </router-link>
      </v-flex>
    </v-layout>
    
    <Filters :items='FILTERS' @updated='loadData' />

    <v-container grid-list-md fluid class="px-0" v-if='collection !== null'>
      <v-layout row wrap class='relative'>
        <v-flex xs12>
          <v-data-table :items='collection.data' item-key='id' hide-headers hide-actions>
            <template slot="items" slot-scope="{ item }">
              <td>
                <router-link :to="{ name: 'ClientShow', params: { id: item.id }}">
                  {{ item.names.short }}
                </router-link>
              </td>
              <td class='text-md-right'>
                <router-link :to="{ name: 'ClientEdit', params: { id: item.id }}">
                  <v-btn flat icon color="black" class='ma-0'>
                      <v-icon>more_horiz</v-icon>
                  </v-btn>
                </router-link>
              </td>
            </template>
          </v-data-table>
        </v-flex>
      </v-layout>
      <v-layout row class='mt-4' align-center>
        <v-flex md4>
        </v-flex>
        <v-flex md4>
          <v-pagination
            v-if='collection.meta.last_page > 1'
            v-model="page"
            :length="collection.meta.last_page"
            :total-visible="7"
            circle
          ></v-pagination>
        </v-flex>
        <v-flex class='text-md-right'>
          <ShowBy :value='show_by' @changed='showByChanged' />
        </v-flex>
     </v-layout>
    </v-container>
  </div>
</template>

<script>

import { API_URL, FILTERS } from '@/components/Client/data'
import ShowBy from '@/components/UI/ShowBy'
import Filters from '@/components/Filters'

export default {
  components: { Filters, ShowBy },

  data() {
    return {
      FILTERS,
      page: 1,
      show_by: 30,
      loading: false,
      collection: null
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
      axios.get(apiUrl(`${API_URL}?page=${this.page}&show_by=${this.show_by}${filters}`)).then(response => {
        this.collection = response.data
        this.loading = false
      })
    },

    showByChanged(option) {
      this.show_by = option
      this.loadData()
    },
  }
}
</script>
