<template lang="html">
  <v-layout row justify-center>
    <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay content-class='overflow-hidden'>
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
              <v-flex md12 class='relative'>
                <div class='custom-toolbar flex-items'>
                  <div class='mr-3'>
                    <AdminSelect v-model='item.responsible_admin_id' label='Ответственный' />
                  </div>
                  <div>
                    <v-select
                      hide-details
                      v-model="item.status"
                      :items="STATUSES"
                      item-text='title'
                      item-value='id'
                      label="Статус"
                    ></v-select>
                  </div>
                </div>
                <TextEditor v-model='item.text' />
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
import { DialogMixin } from '@/mixins'
import { AdminSelect, TextEditor } from '@/components/UI'


export default {
  mixins: [ DialogMixin ],

  components: { AdminSelect, TextEditor },

  data() {
    return {
      API_URL,
      MODEL_DEFAULTS,
      STATUSES,
    }
  },
}
</script>

<style lang="scss" scoped>
  .custom-toolbar {
    position: absolute;
    right: 3px;
    top: 4px;
    & > div {
      margin-right: 10px;
      width: 250px;
    }
  }
</style>
