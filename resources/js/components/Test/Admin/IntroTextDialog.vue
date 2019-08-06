<template>
   <v-layout row justify-center>
    <v-dialog persistent v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay content-class="overflow-hidden">
      <v-card>
        <v-toolbar dark color="primary">
          <v-toolbar-title>Редактировать вступительный текст</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items>
            <v-btn dark icon @click.native="save" :loading='saving'>
              <v-icon>save_alt</v-icon>
            </v-btn>
            <v-btn icon dark @click.native="dialog = false">
              <v-icon>close</v-icon>
            </v-btn>
          </v-toolbar-items>
        </v-toolbar>
        <v-card-text class='relative'>
          <Loader v-if='loading' class='loader-wrapper_fullscreen-dialog' />
          <v-container grid-list-xl class="pa-0 ma-0" fluid v-else>
            <v-flex md12>
              <TextEditor v-model='text' />
            </v-flex>
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>
   </v-layout>
</template>

<script>

import Settings from '@/other/settings'
import { TextEditor } from '@/components/UI'
import { SETTINGS_KEY } from '@/components/Test'

export default {
  components: { TextEditor },
  
  data() {
    return {
      dialog: false,
      loading: false,
      saving: false,
      text: '',
    }
  },

  methods: {
    open() {
      this.dialog = true
      this.loadData()
    },

    loadData() {
      this.loading = true
      Settings.get(SETTINGS_KEY).then(r => {
        this.text = r.data
        this.loading = false
      })
    },

    async save() {
      this.saving = true
      await Settings.set(SETTINGS_KEY, this.text)
      this.dialog = false
      setTimeout(() => this.saving = false, 300)
    }
  },
}
</script>
