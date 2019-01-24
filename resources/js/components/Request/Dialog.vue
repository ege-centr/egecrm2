<template>
  <v-layout row justify-center>
    <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay>
      <v-card>
        <v-toolbar dark color="primary">
          <v-btn icon dark @click.native="dialog = false">
            <v-icon>close</v-icon>
          </v-btn>
          <v-toolbar-title>{{ edit_mode ? 'Редактирование' : 'Добавление' }} заявки</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items>
            <v-btn dark flat @click.native="storeOrUpdate" :loading='saving'>{{ edit_mode ? 'Сохранить' : 'Добавить' }}</v-btn>
          </v-toolbar-items>
        </v-toolbar>
        <v-card-text class='relative'>
          <Loader v-if='loading' class='loader-wrapper_fullscreen-dialog' />
          <v-container grid-list-xl class="pa-0 ma-0" fluid v-else>
            <v-layout wrap>
              <v-flex md12>
                <div class='vertical-inputs'>
                  <!-- <div class='vertical-inputs__input'>
                    <v-text-field v-model="item.name" label="Имя" hide-details></v-text-field>
                  </div> -->
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

import { REQUEST_STATUSES, MODEL_DEFAULTS } from './'
import PhoneEdit from '@/components/Phone/Edit'
import { AdminSelect } from '@/components/UI'

export default {
  props: {
    phones: {
      type: Array,
      default: null,
      required: false
    }
  },

  components: { PhoneEdit, AdminSelect },

  data() {
    return {
      MODEL_DEFAULTS,
      REQUEST_STATUSES,
      loading: true,
      saving: false,
      item: null,
      dialog: false,
      edit_mode: true,
    }
  },

  created() {
    if (this.phones) {
      this.MODEL_DEFAULTS = {...MODEL_DEFAULTS, phones: [this.phones[0]]}
    }
  },

  methods: {
    open(item_id = null) {
      this.dialog = true
      if (item_id === null) {
        this.edit_mode = false
        this.item = clone(this.MODEL_DEFAULTS)
        this.loading = false
      } else {
        this.edit_mode = true
        this.loadData(item_id)
      }
    },

    async storeOrUpdate() {
      this.loading = true
      if (this.item.id) {
        await axios.put(apiUrl(`requests/${this.item.id}`), this.item)
      } else {
        await axios.post(apiUrl('requests'), this.item)
      }
      this.$emit('saved')
      this.loading = false
      this.dialog = false
    },

    loadData(item_id) {
      this.loading = true
      axios.get(apiUrl('requests', item_id)).then(r => {
        this.item = r.data
        this.loading = false
      })
    },

  }
}
</script>
