<template lang="html">
  <div>
    <v-layout>
      <v-flex xs12 class="text-xs-right">
          <v-btn small fab color="primary" @click='add'>
            <v-icon dark>add</v-icon>
          </v-btn>
      </v-flex>
    </v-layout>
    <RequestList ref='RequestList' /></RequestList>
  </div>
</template>

<script>

import RequestList from '@/components/Request/List'

export default {
  components: { RequestList },

  methods: {
    add() {
      this.$refs.RequestList.$refs.RequestDialog.open(null)
    }
  }
}
</script>
