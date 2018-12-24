<template>
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
</template>

<script>

import ShowBy from '@/components/UI/ShowBy'

export default {
  components: { ShowBy },

  data() {
    return {
      page: 1,
      show_by: 30,
    }
  },

  watch: {
    page() {
      this.loadData()
    }
  },

   methods: {
    loadData(filters = '') {
      this.loading = true
      axios.get(apiUrl(`${this.apiUrl}?page=${this.page}&show_by=${this.show_by}${filters}`)).then(response => {
        this.collection = response.data
        this.loading = false
      })
    },
}
</script>



