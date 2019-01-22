<template lang="html">
  <v-layout row justify-center>
    <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay>
      <v-card>
        <v-toolbar dark color="primary">
          <v-btn icon dark @click.native="dialog = false">
            <v-icon>close</v-icon>
          </v-btn>
          <v-toolbar-title>{{ edit_mode ? 'Редактирование' : 'Добавление' }} задачи</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items>
            <v-btn dark flat v-if='edit_mode' @click.native="destroy" :loading='destroying'>Удалить</v-btn>
            <v-btn dark flat @click.native="storeOrUpdate" :loading='saving'>{{ edit_mode ? 'Сохранить' : 'Добавить' }}</v-btn>
          </v-toolbar-items>
        </v-toolbar>
        <v-card-text>
          <Loader v-if='loading' class='loader-wrapper_fullscreen-dialog' />
          <v-container grid-list-xl class="pa-0 ma-0" fluid v-else>
            <v-layout>
              <v-flex md12>
                <VueEditor style='height: 500px' class='mb-5'
                    :editorOptions="editorSettings"
                    v-model='item.text'
                  />
                  <v-container grid-list-xl class="pa-0 ma-0">
                    <v-layout pt-3>
                      <v-flex md12>
                        <div class='vertical-inputs'>
                          <div class='vertical-inputs__input'>
                            <v-select
                              hide-details
                              v-model="item.responsible_admin_id"
                              :items="withNullOption($store.state.data.admins, 'id', 'name')"
                              item-value='id'
                              item-text='name'
                              label="Ответственный"
                            ></v-select>
                          </div>
                          <div class='vertical-inputs__input'>
                            <v-select
                              hide-details
                              v-model="item.status"
                              :items="STATUSES"
                              label="Статус"
                            ></v-select>
                          </div>
                        </div>
                      </v-flex>
                    </v-layout>
                  </v-container>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-layout>
</template>

<script>

import { API_URL, STATUSES, MODEL_DEFAULTS } from './'
import { VueEditor, Quill } from 'vue2-editor'
import { ImageDrop } from 'quill-image-drop-module'
import { DialogMixin } from '@/mixins'

export default {
  mixins: [ DialogMixin ],

  components: { VueEditor },

  data() {
    return {
      API_URL,
      MODEL_DEFAULTS,
      STATUSES,
      editorSettings: {
        modules: {
          imageDrop: true
        }
      }
    }
  },
}
</script>