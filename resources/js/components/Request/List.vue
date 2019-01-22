<template>
  <div>
    <v-btn v-if='phones !== null' fab dark small color="red" @click='openDialog(null)'>
      <v-icon dark>add</v-icon>
    </v-btn>
    <RequestDialog ref='RequestDialog' :phones='phones' @saved='saved' />
    <ClientDialog ref='ClientDialog' />
    <Loader v-if='loading' />
    <Filters :items='FILTERS' @updated='loadData' v-if='!items' />
    <v-container grid-list-md fluid class="px-0" v-if='requests !== null'>
      <v-layout row wrap class='relative'>
        <v-flex xs12 v-for='request in requests' :key='request.id'>
          <RequestItem :item='request' @openDialog='openDialog' @openClientDialog='openClientDialog' />
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
import { ClientDialog } from '@/components/Client'
import Filters from '@/components/Filters'
import { FILTERS } from './'

export default {
  props: {
    items: null,
    phones: {
      type: Array,
      default: null,
      required: false
    }
  },

  components: { RequestItem, RequestDialog, Filters, ClientDialog },

  data() {
    return {
      page: 1,
      loading: false,
      collection: null,
      FILTERS
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
    loadData(FILTERS = '') {
      this.loading = true
      axios.get(apiUrl(`requests?page=${this.page}${FILTERS}`)).then(response => {
        this.collection = response.data
        this.loading = false
      })
    },

    openDialog(id = null) {
      this.$refs.RequestDialog.open(id)
    },

    openClientDialog(phones) {
      console.log('openning client dialog', phones)
      this.$refs.ClientDialog.open(null, { phones })
    },

    saved() {
      if (! this.items) {
        this.loadData()
      } else {
        this.$emit('updated')
      }
    },

    testyy() {
      console.log('testyy')
    }
  },

  computed: {
    requests() {
      return this.items || (this.collection !== null ? this.collection.data : null)
    }
  },
}
</script>
