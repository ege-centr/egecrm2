<template>
  <div>
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

    <TaskDialog ref='TaskDialog' @updated='loadData' />

    <v-container grid-list-xl fluid px-0>
      <v-layout wrap>
        <v-flex md12 v-for='item in items' :key='item.id'>
          <TaskItem :item='item' @edit='edit' />
        </v-flex>
      </v-layout>
    </v-container>
  </div>
</template>

<script>

import { TaskDialog, TaskItem, url } from '@/components/Task/data'


export default {
  components: { TaskDialog, TaskItem },

  data() {
    return {
      loading: false,
      items: []
    }
  },

  created() {
    this.loadData()
  },

  methods: {
    add() {
      this.$refs.TaskDialog.add()
    },
    edit(item) {
      this.$refs.TaskDialog.item = item
      this.$refs.TaskDialog.dialog = true
    },
    loadData() {
      this.loading = true
      axios.get(apiUrl(`${url}`)).then(response => {
        this.items = response.data
        this.loading = false
      })
    }
  }
}
</script>
