<template>
  <v-layout row justify-center>
    <v-dialog v-model="dialog" transition="dialog-bottom-transition" content-class='v-dialog--fullscreen halfscreen-dialog'>
      <v-card>
        <v-toolbar dark color="primary">
          <v-btn icon dark @click.native="dialog = false">
            <v-icon>close</v-icon>
          </v-btn>
          <v-toolbar-title>Печать договора</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items>
            <v-btn dark flat @click.native="print">Печать</v-btn>
          </v-toolbar-items>
        </v-toolbar>
        <v-card-text class='relative'>
          <Loader v-if='loading' class='loader-wrapper_fullscreen-dialog' />
          <v-container grid-list-xl class="pa-0 ma-0" fluid v-else>
            <v-layout wrap>
              <v-flex md12>
                <VueEditor style='height: 500px' class='mb-5' v-model='text' />
              </v-flex>
              <div id='print-block' v-html='text'></div>
            </v-layout>
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-layout>
</template>

<script>
import { VueEditor } from 'vue2-editor'
import printJS from 'print-js'

export default {
  components: { VueEditor },

  data() {
    return {
      dialog: false,
      loading: true,
      text: null
    }
  },

  methods: {
    open(item_id) {
      this.loading = true
      this.dialog = true
      axios.get(apiUrl(`print?type=contract&id=${item_id}`)).then(r => {
        this.text = r.data
        this.loading = false
      })
    },

    print() {
      printJS('print-block', 'html')
      this.dialog = false
    },
  }
}
</script>
