<template lang="html">
  <v-layout row justify-center>
    <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay>
      <v-card>
        <v-toolbar dark color="primary">
          <v-toolbar-title>{{ edit_mode ? 'Редактирование' : 'Добавление' }} заявки</v-toolbar-title>
          <v-spacer></v-spacer>
          <TitleCredentials :item='item'/>
          <v-toolbar-items>
            <v-btn dark icon v-if='edit_mode' @click.native="destroy" :loading='destroying' class='mr-5'>
              <v-icon>delete</v-icon>
            </v-btn>
            <v-btn dark icon @click.native="storeOrUpdate" :loading='saving'>
              <v-icon>{{ edit_mode ? 'save_alt' : 'add' }}</v-icon>
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
                    <v-select hide-details
                      v-model="item.status"
                      :items="REQUEST_STATUSES"
                      item-text='title'
                      item-value='id'
                      label="Статус"
                    ></v-select>
                  </div>
                  <div class='vertical-inputs__input'>
                    <AdminSelect v-model='item.responsible_admin_id' label='Ответственный' />
                  </div>
                  <div class='vertical-inputs__input'>
                    <ClearableSelect label='Класс'
                      :items='$store.state.data.grades'
                      v-model='item.grade_id'
                    />
                  </div>
                  <div class='vertical-inputs__input'>
                    <v-select multiple hide-details
                      v-model="item.subjects"
                      :items="$store.state.data.subjects"
                      item-value='id'
                      item-text='name'
                      label="Предмет"
                    ></v-select>
                  </div>
                  <div class='vertical-inputs__input'>
                    <v-select multiple hide-details
                      v-model="item.branches"
                      :items="$store.state.data.branches"
                      item-value='id'
                      item-text='full'
                      label="Филиалы"
                    ></v-select>
                  </div>
                  <div class='vertical-inputs__input'>
                    <v-text-field hide-details v-model="item.comment" label="Комментарий"></v-text-field>
                  </div>
                  <div>
                    <PhoneEdit :item='item' />
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

import { API_URL, MODEL_DEFAULTS, REQUEST_STATUSES } from './'
import { DialogMixin } from '@/mixins'
import PhoneEdit from '@/components/Phone/Edit'
import { AdminSelect } from '@/components/UI'

export default {
  components: { PhoneEdit, AdminSelect },

  mixins: [ DialogMixin ],

  data() {
    return {
      API_URL,
      MODEL_DEFAULTS,
      REQUEST_STATUSES,
    }
  },
}
</script>