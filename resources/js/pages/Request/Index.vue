<template lang="html">
  <div>
    <IndexPage :api-url='API_URL' :filters='FILTERS'>
      <template slot='items' slot-scope='{ items }'>
        <v-layout row wrap class='relative'>
          <v-flex xs12 v-for='item in items' :key='item.id'>
            <RequestItem :item='item' @openDialog='$refs.RequestDialog.open' @openClientDialog='$refs.ClientDialog.open' />
          </v-flex>
        </v-layout>
      </template>
    </IndexPage>
    <RequestDialog ref='RequestDialog' />
    <ClientDialog ref='ClientDialog' />
  </div>
</template>

<script>

import { API_URL, FILTERS } from '@/components/Request'
import RequestItem from '@/components/Request/Item'
import RequestDialog from '@/components/Request/Dialog'
import { IndexPage } from '@/components/UI'
import { ClientDialog } from '@/components/Client'

export default {
  components: { RequestItem, RequestDialog, IndexPage, ClientDialog },

  data() {
    return {
      API_URL,
      FILTERS,
    }
  },

  methods: {
    add() {
      this.$refs.RequestList.$refs.RequestDialog.open(null)
    },
  }
}
</script>
