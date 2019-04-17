<template lang="html">
  <v-layout row justify-center>
    <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay>
      <v-card>
        <v-toolbar dark color="primary">
          <v-toolbar-title>{{ edit_mode ? 'Редактирование' : 'Добавление' }} свободного времени</v-toolbar-title>
          <v-spacer></v-spacer>
          <TitleCredentials :item='item'/>
          <v-toolbar-items>
            <v-btn dark icon v-if='edit_mode' @click.native="destroy" :loading='destroying' class='mr-5'>
              <v-icon>delete</v-icon>
            </v-btn>
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
            <v-layout>
              <v-flex md12>
                <div class='vertical-inputs'>
                  <div class='vertical-inputs__input'>
                    <v-select 
                      hide-details
                      :items='days'
                      v-model='item.weekday'
                    ></v-select>
                  </div>
                  <div class='vertical-inputs__input'>
                    <v-text-field 
                      hide-details 
                      v-model='item.from' 
                      label='Время от' 
                      v-mask="'##:##'"
                    ></v-text-field>
                  </div>
                  <div class='vertical-inputs__input'>
                    <v-text-field 
                      hide-details 
                      v-model='item.to' 
                      label='Время до' 
                      v-mask="'##:##'"
                    ></v-text-field>
                  </div>
                </div>
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
import { DialogMixin } from '@/mixins'

export default {
  mixins: [ DialogMixin ],

  data() {
    return {
      API_URL,
      MODEL_DEFAULTS,
      days: [
        {value: 1, text: 'ПН'},
        {value: 2, text: 'ВТ'},
        {value: 3, text: 'СР'},
        {value: 4, text: 'ЧТ'},
        {value: 5, text: 'ПТ'},
        {value: 6, text: 'СБ'},
        {value: 0, text: 'ВС'},
      ]
    }
  },
}
</script>