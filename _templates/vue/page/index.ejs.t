---
to: resources/js/pages/<%= Name %>/Index.vue
---
<template lang="html">
  <div>
    <v-layout>
      <v-flex xs12 class="text-xs-right">
        <router-link :to="{ name: '<%= Name %>Create' }" class='black-link'>
          <v-btn small fab color="primary">
            <v-icon dark>add</v-icon>
          </v-btn>
          добавить
        </router-link>
      </v-flex>
    </v-layout>
    <v-container grid-list-md fluid class="px-0" v-if='collection !== null'>
      <v-layout row wrap class='relative'>
        <Loader v-if='loading' />
        <v-flex xs12>
          <v-data-table :items='collection.data' item-key='id' hide-headers hide-actions>
            <template slot="items" slot-scope="{ item }">
              <td>
                <router-link :to="{ name: '<%= Name %>Show', params: { id: item.id }}">
                  {{ item.id }}
                </router-link>
              </td>
              <td class='text-md-right'>
                <router-link :to="{ name: '<%= Name %>Edit', params: { id: item.id }}">
                  <v-btn flat icon color="black" class='ma-0'>
                      <v-icon>more_horiz</v-icon>
                  </v-btn>
                </router-link>
              </td>
            </template>
          </v-data-table>
        </v-flex>
      </v-layout>
      <div class="text-xs-center mt-4">
        <v-pagination
          v-if='collection.meta.last_page > 1'
          v-model="page"
          :length="collection.meta.last_page"
          :total-visible="7"
          circle
        ></v-pagination>
     </div>
    </v-container>
  </div>
</template>

<script>

import { API_URL } from '@/components/<%= Name %>/data'

export default {
  data() {
    return {
      page: 1,
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
    loadData() {
      this.loading = true
      axios.get(apiUrl(`${API_URL}?page=${this.page}`)).then(response => {
        this.collection = response.data
        this.loading = false
      })
    }
  }
}
</script>
