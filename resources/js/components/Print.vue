<template>
  <v-layout row justify-center>
    <v-dialog persistent v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay content-class='overflow-hidden'>
      <v-card>
        <v-toolbar dark color="primary">
          <v-toolbar-title>{{ title }}</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items>
            <v-btn dark icon @click.native="print">
              <v-icon>print</v-icon>
            </v-btn>
            <v-btn icon dark @click.native="dialog = false">
              <v-icon>close</v-icon>
            </v-btn>
          </v-toolbar-items>
        </v-toolbar>
        <v-card-text class='relative' style='padding-top: 76px'>
          <Loader v-if='loading' class='loader-wrapper_fullscreen-dialog' />
          <v-container grid-list-xl class="pa-0 ma-0" fluid v-else>
            <v-layout wrap>
              <v-flex md12 class='py-0'>
                <Editor v-model='text' :api-key='tinyMceApiKey' :init='tinyMceInit' style='padding-top: 10px' />
                <!-- <TextEditor v-model='text' /> -->
                <!-- <codemirror style='height: 1000px' :options="cmOptions" v-model='text' /> -->
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
// import VueFroala from 'vue-froala-wysiwyg'
import Editor from '@tinymce/tinymce-vue';
import { TextEditor } from '@/components/UI'
// import 'codemirror/mode/htmlmixed/htmlmixed.js'
// import { codemirror } from 'vue-codemirror'
import printJS from 'print-js'

const API_URL = 'print'

export default {
  props: {
    params: {
      type: Object,
      required: false,
    },
    title: {
      type: String,
      default: 'Печать',
      required: false,
    },
  },

  components: { Editor, TextEditor },

  data() {
    return {
      tinyMceApiKey: process.env.MIX_TINYMCE_API_KEY,
      dialog: false,
      loading: true,
      text: '',
      tinyMceInit: {
        inline: true,
      },
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
    open(params = {}) {
      this.loading = true
      this.dialog = true
      axios.get(apiUrl(API_URL) + queryString({...this.params, ...params})).then(r => {
        this.text = r.data
        this.loading = false
      })
    },

    print() {
      print()
      this.dialog = false
    },
  }
}
</script>
