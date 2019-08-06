<template>
  <v-layout row justify-center>
    <v-dialog persistent :value="item !== null" transition="dialog-bottom-transition" fullscreen hide-overlay>
      <v-card>
        <v-toolbar dark color="primary">
          <v-toolbar-title>Результаты теста</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-btn icon dark @click.native="item = null">
            <v-icon>close</v-icon>
          </v-btn>
        </v-toolbar>
        <v-card-text class='test-results-dialog'>
          <TestClientStartPage v-if='item !== null' :options='item' :show-all-answers='showAllAnswers' :key='JSON.stringify(item)' />
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
    },

    showAllAnswers: {
      type: Boolean,
      default: false,
    }
  },

  components: { TestClientStartPage },
  
}
</script>

