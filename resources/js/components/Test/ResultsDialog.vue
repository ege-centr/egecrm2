<template>
  <v-layout row justify-center>
    <v-dialog :value="item !== null" transition="dialog-bottom-transition" fullscreen hide-overlay>
      <v-card>
        <v-toolbar dark color="primary">
          <v-btn icon dark @click.native="item = null">
            <v-icon>close</v-icon>
          </v-btn>
          <v-toolbar-title>Результаты теста</v-toolbar-title>
        </v-toolbar>
        <v-card-text class='test-results-dialog'>
          <TestClientStartPage :options='item' :key='JSON.stringify(item)' />
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-layout>
</template>

<script>
import TestClientStartPage from '@/pages/Test/Client/Start'

export default {
  props: {
    item: {
      type: Object,
      default() {
        return null
      }
    }
  },

  components: { TestClientStartPage },
  
}
</script>

