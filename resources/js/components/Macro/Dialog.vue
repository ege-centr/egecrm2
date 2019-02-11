<template>
  <v-layout row justify-center>
    <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay content-class='overflow-hidden'>
      <v-card>
        <v-toolbar dark color="primary">
          <v-btn icon dark @click.native="dialog = false">
            <v-icon>close</v-icon>
          </v-btn>
          <v-toolbar-title>Редактировать макрос «{{ filename }}»</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items>
            <v-btn dark flat @click.native="save" :loading='saving'>Сохранить</v-btn>
          </v-toolbar-items>
        </v-toolbar>
        <v-card-text style='padding-top: 76px'>
          <Loader v-if='loading' class='loader-wrapper_fullscreen-dialog' />
          <v-container grid-list-xl class="pa-0 ma-0" fluid v-else>
            <v-layout>
              <v-flex md12 class='py-0'>
                <codemirror style='height: 1000px' :options="cmOptions" v-model='text' />
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-layout>
</template>

<script>

import { API_URL, MODEL_DEFAULTS } from './'
import 'codemirror/mode/htmlmixed/htmlmixed.js'
import { codemirror } from 'vue-codemirror'

export default {
  components: { codemirror },
  
  data() {
    return {
      API_URL,
      MODEL_DEFAULTS,
      dialog: false,
      filename: null,
      loading: true,
      saving: false,
      text: '',
      cmOptions: {
        tabSize: 4,
        mode: 'text/html',
        lineNumbers: false,
        lineWrapping: true,
        line: true,
      }
    }
  },

  methods: {
    open(filename) {
      this.dialog = true
      this.filename = filename
      this.loadData()
    },

    loadData() {
      this.loading = true
      axios.get(apiUrl(API_URL, this.filename)).then(r => {
        this.text = r.data
        this.loading = false
      })
    },
  }
}
</script>