<template>
  <v-layout row justify-center>
    <v-dialog persistent v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay>
      <v-card>
        <v-toolbar dark color="primary">
          <v-toolbar-title>{{ edit_mode ? 'Редактирование' : 'Добавление' }} шаблона</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items>
            <v-btn dark icon @click.native="storeOrUpdate" :loading='saving'>
              <v-icon>save_alt</v-icon>
            </v-btn>
            <v-btn icon dark @click.native="dialog = false">
              <v-icon>close</v-icon>
            </v-btn>
          </v-toolbar-items>
        </v-toolbar>
        <v-card-text>
          <Loader v-if='loading' class='loader-wrapper_fullscreen-dialog' />
          <v-container grid-list-xl class="pa-0 ma-0" fluid v-else>
            <v-layout wrap>
              <v-flex md4>
                <div class='vertical-inputs'>
                  <div class='vertical-inputs__input vertical-inputs__input_wide'>
                    <v-text-field hide-details v-model='item.title' label='Название'></v-text-field>
                  </div>
                  <div class='vertical-inputs__input vertical-inputs__input_wide'>
                    <v-textarea auto-grow hide-details v-model='item.text' label='Текст'></v-textarea>
                  </div>
                </div>
              </v-flex>
              <v-flex md4 offset-md1>
                <p>
                  Тип: {{ TYPE[item.type] }}
                </p>
                <div class='sms-template-description' v-html='item.description'></div>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-layout>
</template>

<script>

import { API_URL, MODEL_DEFAULTS, TYPE } from './'
import { DialogMixin } from '@/mixins'

export default {
  mixins: [ DialogMixin ],

  data() {
    return {
      API_URL,
      MODEL_DEFAULTS,
      TYPE,
    }
  },

  methods: {

  }
}
</script>


<style lang='scss'>
  .sms-template-description {
    & pre {
      background: #eff0f1;
      font-family: 'Ubuntu Mono', monospace;
      display: inline-block;
    }
  }
</style>