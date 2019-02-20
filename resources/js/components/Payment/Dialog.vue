<template lang="html">
  <v-layout row justify-center>
    <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay>
      <v-card>
        <v-toolbar dark color="primary">
          <v-btn icon dark @click.native="dialog = false">
            <v-icon>close</v-icon>
          </v-btn>
          <v-toolbar-title>{{ edit_mode ? 'Редактирование' : 'Добавление' }} платежа</v-toolbar-title>
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
                <div class='vertical-inputs'>
                  <div class='vertical-inputs__input'>
                    <ClearableSelect v-model="item.method"
                      :items='ENUMS.methods'
                      label="Cпособ оплаты" />
                  </div>
                  <div class='vertical-inputs__input' v-if="item.method === 'card'">
                    <v-text-field hide-details v-model='item.card_number' 
                      placeholder='**** **** **** ****'
                      label='Номер карты' 
                      mask="#*** **** **** ####"></v-text-field>
                      <div class='vertical-inputs__input__message' v-if='item.card_number && item.card_number.length > 0'>
                        <div class='payment-system' :class="'payment-system_' + item.card_number[0]"></div>
                      </div>
                  </div>
                  <div class='vertical-inputs__input'>
                    <ClearableSelect v-model="item.type"
                      :items='ENUMS.types'
                      label="Тип" />
                  </div>
                  <div class='vertical-inputs__input'>
                    <ClearableSelect v-model="item.category"
                      :items='ENUMS.categories'
                      label="Категория" />
                  </div>
                  <div class='vertical-inputs__input'>
                    <ClearableSelect v-model="item.year"
                      :items='$store.state.data.years'
                      label="Год" />
                  </div>
                  <div class='vertical-inputs__input'>
                    <v-text-field hide-details v-model='item.sum' label='Сумма'></v-text-field>
                  </div>
                  <div class='vertical-inputs__input'>
                    <DatePicker label="Дата" v-model="item.date"  />
                  </div>
                  <div class='vertical-inputs__input'>
                    <v-switch color='green' v-model="item.is_confirmed" label="подтвержден"></v-switch>
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

import { API_URL, ENUMS, MODEL_DEFAULTS } from './'
import { DatePicker } from '@/components/UI'

export default {
  components: { DatePicker },

  data() {
    return {
      ENUMS,
      dialog: false,
      saving: false,
      item: null,
      edit_mode: true,
      loading: true,
      destroying: false,
    }
  },

  methods: {
    open(item_id = null, defaults = {}) {
      this.dialog = true
      if (item_id !== null) {
        this.edit_mode = true
        this.loadData(item_id)
      } else {
        this.edit_mode = false
        this.item = {...MODEL_DEFAULTS, ...defaults }
        this.loading = false
      }
    },

    loadData(item_id) {
      this.loading = true
      axios.get(apiUrl(API_URL, item_id)).then(r => {
        this.item = r.data
        this.loading = false
      })
    },

    destroy() {
      this.destroying = true
      axios.delete(apiUrl(API_URL, this.item.id)).then(r => {
        this.$emit('updated')
        this.dialog = false
        this.waitForDialogClose(() => this.destroying = false)
      })
    },

    async storeOrUpdate() {
      this.saving = true
      if (this.item.id) {
        await axios.put(apiUrl(`${API_URL}/${this.item.id}`), this.item).then(r => {
          this.$emit('updated')
          // this.item = r.data
          // this.$emit('updated', this.item)
        })
      } else {
        await axios.post(apiUrl(API_URL), this.item).then(r => {
          this.$emit('updated')
          // this.item = r.data
          // this.$emit('stored', this.item)
        })
      }
      this.dialog = false
      this.waitForDialogClose(() => this.saving = false)
    },
  }
}
</script>

<style lang="scss">
  .payment-system {
    height: 22px;
    background-size: contain;
    background-repeat: no-repeat;
    &_2 {
      height: 14px;
      background-image: url('/img/svg/payment/mir.svg')
    }
    &_4 {
      background-image: url('/img/svg/payment/visa.svg')
    }
    &_3, &_5, &_6 {
      background-image: url('/img/svg/payment/mastercard.svg')
    }
  }
</style>

