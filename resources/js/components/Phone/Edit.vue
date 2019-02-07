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
    </div>
    <div v-if='editable && item.phones.length < max'>
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
    max: {
      type: Number,
      default: 10,
      required: false,
    }
  },

  components: { Sms },

  methods: {
    sms(phone) {
      this.$refs.Sms.init(phone.phone)
    }
  }
}
</script>

