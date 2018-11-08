<template>
  <div>
    <v-btn v-if='phones !== null' color='primary' small class='ma-0' @click='add'>
      <v-icon class="mr-1">add</v-icon>
      добавить
    </v-btn>
    <RequestDialog ref='RequestDialog' :phones='phones' @saved='saved' />
    <Loader v-if='loading' />
    <Filters :items='filters' @updated='loadData' v-if='!items' />
    <v-container grid-list-md fluid class="px-0" v-if='requests !== null'>
      <v-layout row wrap class='relative'>
        <v-flex xs12 v-for='request in requests' :key='request.id'>
          <RequestItem :item='request' @show='show' />
        </v-flex>
      </v-layout>
      <div class="text-xs-center mt-4">
        <v-pagination
          v-if='!items && collection.meta.last_page > 1'
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

import RequestItem from '@/components/Request/Item'
import RequestDialog from '@/components/Request/Dialog'
import Filters from '@/components/Filters'
import { filters } from './data'

export default {
  props: {
    items: null,
    phones: {
      type: Array,
      default: null,
      required: false
    }
  },

  components: { RequestItem, RequestDialog, Filters },

  data() {
    return {
      page: 1,
      loading: false,
      collection: null,
      filters
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
    loadData(filters = '') {
      this.loading = true
      axios.get(apiUrl(`requests?page=${this.page}${filters}`)).then(response => {
        this.collection = response.data
        this.loading = false
      })
    },

    show(id) {
      this.$refs.RequestDialog.show(id)
    },

    add() {
      this.$refs.RequestDialog.add()
    },

    saved() {
      if (! this.items) {
        this.loadData()
      } else {
        this.$emit('updated')
      }
    },
  },

  computed: {
    requests() {
      return this.items || (this.collection !== null ? this.collection.data : null)
    }
  },
}
</script>
