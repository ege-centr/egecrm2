<template>
  <div>
    <div v-for="(phone, index) in item.phones" :key='index' class='flex-items align-center' style='width: 100%'>
      <div class='mr-3 vertical-input'>
        <v-text-field hide-details
          :disabled='!editable'
          placeholder='+7 (###) ###-##-##'
          v-mask="'+7 (###) ###-##-##'"
          v-model="item.phones[index].phone" :label="`Телефон ${index + 1}`"
        >
        </v-text-field>
      </div>
      <div class='mr-3 vertical-input'>
        <v-text-field v-model="item.phones[index].comment" hide-details
          :disabled='!editable'
          :label="`Комментарий`">
        </v-text-field>
      </div>
      <v-menu left>
        <v-btn slot='activator' flat icon color="black" class='ma-0' v-if='editable'>
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
          <v-list-tile @click=''>
              <v-list-tile-action>
                <v-icon>bar_chart</v-icon>
              </v-list-tile-action>
              <v-list-tile-content>
                <v-list-tile-title>Детализация</v-list-tile-title>
              </v-list-tile-content>
          </v-list-tile>
          <v-list-tile @click='item.phones.splice(index, 1)'>
              <v-list-tile-action>
                <v-icon>close</v-icon>
              </v-list-tile-action>
              <v-list-tile-content>
                <v-list-tile-title>Удалить</v-list-tile-title>
              </v-list-tile-content>
          </v-list-tile>
        </v-list>
      </v-menu>
    </div>
    <div v-if='editable'>
      <v-btn fab dark small color="red" @click="item.phones.push({phone: '', comment: ''})">
        <v-icon dark>add</v-icon>
      </v-btn>
    </div>
    <Sms ref='Sms' />
  </div>
</template>

<script>

import Sms from './Sms'

export default {
  props: {
    item: {},
    editable: {
      type: Boolean,
      required: false,
      default: true
    },
  },

  components: { Sms },

  methods: {
    sms(phone) {
      this.$refs.Sms.init(phone.phone)
    }
  }
}
</script>

