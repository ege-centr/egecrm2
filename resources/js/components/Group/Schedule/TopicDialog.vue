<template>
  <v-layout row justify-center>
    <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay>
      <v-card>
        <v-toolbar dark color="primary">
          <v-toolbar-title>
            Тема занятия
          </v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items v-if='item !== null'>
            <v-btn dark icon 
              @click.native="storeOrUpdate" 
              :loading='saving'>
                <v-icon>save_alt</v-icon>
            </v-btn>
            <v-btn icon dark @click.native="dialog = false">
              <v-icon>close</v-icon>
            </v-btn>
          </v-toolbar-items>
        </v-toolbar>
        <v-card-text class='px-0'>
          <Loader v-if='loading' class='loader-wrapper_fullscreen-dialog' />
          <v-layout v-else wrap>
            <v-flex md12 class='pa-0'>
              <v-textarea 
                class='textarea-padding-fix'
                full-width
                auto-grow
                counter 
                label='Тема занятия' 
                maxlength="1000"
                rows='15'
                :error-messages='errorMessages.topic'
                @keydown.enter='(event) => event.preventDefault()'
                v-model='item.topic'>
              </v-textarea>
              <hr class="v-divider theme--light">
            </v-flex>
          </v-layout>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-layout>
</template>


<script>
import { DialogMixin } from '@/mixins'
import { LESSON_STATUS, MODEL_DEFAULTS, API_URL } from '@/components/Lesson'

export default {
  mixins: [ DialogMixin ],

  components: {  },
  
  data() {
    return {
      API_URL,
      LESSON_STATUS,
      MODEL_DEFAULTS,
    }
  },
}
</script>