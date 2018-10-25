<template>
  <div>
    <div v-for="(phone, index) in item.phones" :key='index' class='flex-items align-center' style='width: 100%'>
      <div class='mr-3' style='width: 200px'>
        <v-text-field
          placeholder='+7 (###) ###-##-##'
          v-mask="'+7 (###) ###-##-##'"
          v-model="item.phones[index].phone" :label="`Телефон ${index + 1}`"
        >
        </v-text-field>
      </div>
      <div class='mr-3' style='width: 200px'>
        <v-text-field v-model="item.phones[index].comment"
          :label="`Комментарий`">
        </v-text-field>
      </div>
      <v-menu left>
        <v-btn slot='activator' flat icon color="black" class='ma-0'>
          <v-icon>more_horiz</v-icon>
        </v-btn>
        <v-list dense>
          <v-list-tile @click='sms(phone)'>
              <v-list-tile-action>
                <v-icon>send</v-icon>
              </v-list-tile-action>
              <v-list-tile-content>
                <v-list-tile-title>Написать</v-list-tile-title>
              </v-list-tile-content>
          </v-list-tile>
          <v-list-tile @click='addContractVersion(item)'>
              <v-list-tile-action>
                <v-icon>bar_chart</v-icon>
              </v-list-tile-action>
              <v-list-tile-content>
                <v-list-tile-title>Детализация</v-list-tile-title>
              </v-list-tile-content>
          </v-list-tile>
          <v-list-tile @click='item.phones.splice(index, 1)'>
              <v-list-tile-action>
                <v-icon>remove</v-icon>
              </v-list-tile-action>
              <v-list-tile-content>
                <v-list-tile-title>Удалить</v-list-tile-title>
              </v-list-tile-content>
          </v-list-tile>
        </v-list>
      </v-menu>
    </div>
    <div>
      <v-btn color='blue white--text darken-1' small class='ma-0' @click="item.phones.push({phone: '', comment: ''})">
        <v-icon class="mr-1">add</v-icon>
        добавить телефон
      </v-btn>
    </div>
    <Sms ref='Sms' />
  </div>
</template>

<script>

import Sms from './Sms'

export default {
  components: { Sms },
  props: ['item'],
  methods: {
    sms(phone) {
      this.$refs.Sms.init(phone.phone)
    }
  }
}
</script>
