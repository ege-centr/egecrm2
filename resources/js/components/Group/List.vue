<template>
  <div>
    <v-data-table v-if='getItems && getItems.length'
      class="elevation-1"
      hide-actions
      hide-headers
      :items='getItems'
    >
    <template slot='items' slot-scope="{ item }">
      <td>
        Группа {{ item.id }}
      </td>
    </template>
  </v-data-table>
  </div>
</template>
<script>

import { url } from './data'

export default {
  props: {
    items: {
      type: Array,
      default: null,
      required: false
    }
  },
  data() {
    return {
      page: 1,
      loading: false,
      collection: null,
    }
  },
  created() {
    if (! this.items) {
      this.loadData()
    }
  },
  watch: {
    page() {
        this.loadData()
    }
  },
  methods: {
    loadData() {
      this.loading = true
      axios.get(apiUrl(`${url}?page=${this.page}`)).then(response => {
        this.collection = response.data
        this.loading = false
      })
    },
  },
  computed: {
    getItems() {
      return this.items || (this.collection !== null ? this.collection.data : null)
    }
  },
}
</script>
