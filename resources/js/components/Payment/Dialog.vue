<template lang="html">
  <v-layout row justify-center>
    <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay>
      <v-card>
        <v-toolbar dark color="primary">
          <v-toolbar-title>{{ edit_mode ? 'Редактирование' : 'Добавление' }} платежа</v-toolbar-title>
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
                    <ClearableSelect v-model="item.method"
                      :error-messages='errorMessages.method'
                      :items='ENUMS.methods'
                      label="Cпособ оплаты" />
                    <div class='vertical-inputs__input__message' style='top: 0'>
                      <div class='vertical-inputs__input' v-if="item.method === 'card'">
                        <v-text-field hide-details v-model='item.card_number' 
                          placeholder='**** **** **** ****'
                          label='Номер карты' 
                          mask="#*** **** **** ####"></v-text-field>
                          <div class='vertical-inputs__input__message' v-if='item.card_number && item.card_number.length > 0'>
                            <div class='payment-system' :class="'payment-system_' + item.card_number[0]"></div>
                          </div>
                      </div>
                      <div class='vertical-inputs__input' v-if="item.id && showPko">
                        <v-text-field hide-details v-model='item.bill_number' 
                          label='Номер ПКО' 
                          v-mask="'#####'"></v-text-field>
                      </div>
                      <div v-if="!item.id && showPko" class='relative grey--text' style='top: 20px'>
                        будет присвоен номер ПКО
                      </div>
                    </div>
                  </div>
                  <div class='vertical-inputs__input'>
                    <ClearableSelect v-model="item.type"
                      :error-messages='errorMessages.type'
                      :items='ENUMS.types'
                      label="Тип" />
                  </div>
                  <div class='vertical-inputs__input'>
                    <ClearableSelect v-model="item.category"
                      :error-messages='errorMessages.category'
                      :items='ENUMS.categories'
                      label="Категория" />
                  </div>
                  <div class='vertical-inputs__input'>
                    <ClearableSelect v-model="item.year"
                      :error-messages='errorMessages.year'
                      :items='$store.state.data.years'
                      label="Год" />
                  </div>
                  <div class='vertical-inputs__input'>
                    <v-text-field 
                      :hide-details='errorMessages.sum === undefined'
                      :error-messages='errorMessages.sum'
                      v-model='item.sum' label='Сумма'></v-text-field>
                  </div>
                  <div class='vertical-inputs__input'>
                    <DatePicker label="Дата" v-model="item.date" :error-messages="errorMessages.date"  />
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
import { DialogMixin } from '@/mixins'

export default {
  components: { DatePicker },

  mixins: [ DialogMixin ],

  data() {
    return {
      ENUMS,
      API_URL,
      MODEL_DEFAULTS,
    }
  },

  methods: {
  },

  computed: {
    showPko() {
      return this.item.method === 'cash' && 
        this.item.type === 'payment' && 
        this.item.entity_type === this.userTypes.client
    }
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

