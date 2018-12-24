<template>
  <span>
    <span v-for='(phone, index) in items' :key='index'>
      <v-menu>
        <a slot='activator'>{{ phone.phone }}</a>
        <v-list dense>
          <v-list-tile @click='call(phone)'>
            <v-list-tile-action>
              <v-icon>phone</v-icon>
            </v-list-tile-action>
            <v-list-tile-title>Позвонить</v-list-tile-title>
          </v-list-tile>
          <v-list-tile @click='sms(phone)'>
            <v-list-tile-action>
              <v-icon>textsms</v-icon>
            </v-list-tile-action>
            <v-list-tile-title>СМС</v-list-tile-title>
          </v-list-tile>
          <v-list-tile @click=''>
            <v-list-tile-action>
              <v-icon>play_arrow</v-icon>
            </v-list-tile-action>
            <v-list-tile-title>Прослушивание</v-list-tile-title>
          </v-list-tile>
        </v-list>
      </v-menu>
      {{ index === items.length - 1 ? '' : ', ' }}
    </span>
    <Sms ref='Sms' />
  </span>
</template>

<script>

import Sms from './Sms'

export default {
  props: ['items'],

  components: { Sms },

  methods: {
    call(phone) {
      window.location = `tel:${phone.phone_clean}`
    },

    sms(phone) {
      this.$refs.Sms.init(phone.phone)
    },
  }
}
</script>
