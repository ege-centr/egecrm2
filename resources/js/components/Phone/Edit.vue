<template>
  <div class='vertical-inputs'>
    <v-slide-y-transition group>
      <div v-for="(phone, index) in item.phones" :key='index' class='flex-items align-center' style='width: 100%'>
        <div class='mr-3 vertical-input'>
          <v-text-field hide-details
            :disabled='!editable'
            placeholder='+7 (###) ###-##-##'
            v-mask="'+7 (###) ###-##-##'"
            v-model="phone.phone" :label="`Телефон ${index + 1}`"
          >
          </v-text-field>
        </div>
        <div class='mr-3 vertical-input'>
          <v-text-field v-model="phone.comment" hide-details
            :disabled='!editable'
            :label="`Комментарий`">
          </v-text-field>
        </div>
        <v-btn v-if='item.phones.length > 1' flat icon color="red" class='ma-0' @click='item.phones.splice(index, 1)'>
          <v-icon>remove</v-icon>
        </v-btn>
      </div>
    </v-slide-y-transition>
    <div v-if='editable && item.phones.length < max'>
      <AddBtn @click.native="add()"></AddBtn>
    </div>
  </div>
</template>

<script>

import { MODEL_DEFAULTS } from './'

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

  data() {
    return {
      MODEL_DEFAULTS,
    }
  },

  created() {
    if (this.item.phones.length === 0) {
      this.add()
    }
  },

  methods: {
    add() {
      this.item.phones.push(_.clone(MODEL_DEFAULTS))
    },
  },
}
</script>

