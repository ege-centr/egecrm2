<template>
  <div>
    <Loader v-if='loading' />
    <v-layout>
      <v-flex xs12 class="text-xs-right">
        <a class='black-link' @click='add'>
          <v-btn small fab color="primary">
            <v-icon dark>add</v-icon>
          </v-btn>
          добавить задачу
        </a>
      </v-flex>
    </v-layout>

    <Filters :items='filters' @updated='loadData' />

    <TaskDialog ref='TaskDialog' @updated='loadData' />

    <v-container grid-list-xl fluid px-0  v-if='collection !== null'>
      <v-layout wrap>
        <v-flex md12 v-for='item in collection.data' :key='item.id'>
          <TaskItem :item='item' @edit='edit' />
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

import { TaskDialog, TaskItem, url, filters } from '@/components/Task/data'
import Filters from '@/components/Filters'

export default {
  components: { TaskDialog, TaskItem, Filters },

  data() {
    return {
      page: 1,
      loading: false,
      collection: null,
      filters
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
    add() {
      this.$refs.TaskDialog.add()
    },
    edit(item) {
      this.$refs.TaskDialog.item = item
      this.$refs.TaskDialog.dialog = true
    },
    loadData(filters = '') {
      this.loading = true
      axios.get(apiUrl(`${url}?page=${this.page}${filters}`)).then(response => {
        this.collection = response.data
        this.loading = false
      })
    }
  }
}
</script>
