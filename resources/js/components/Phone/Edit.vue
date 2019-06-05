<template>
  <div class='vertical-inputs'>
    <div>
      <div v-for="(phone, index) in item.phones" :key='index' class='flex-items align-flex-start' style='width: 100%'>
        <div class='mr-3 vertical-input'>
          <v-text-field 
            :hide-details='getErrorMessage(phone) === null'
            :error-messages='getErrorMessage(phone)'
            :disabled='!editable'
            :loading='phone.loading'
            placeholder='Телефон'
            v-mask="'+7 (###) ###-##-##'"
            v-model="phone.phone"
            @keyup="checkDuplicate(phone)"
          >
          </v-text-field>
        </div>
        <div class='mr-3 vertical-input'>
          <v-text-field v-model="phone.comment" hide-details
            :disabled='!editable'
            placeholder="Комментарий">
          </v-text-field>
        </div>
        <v-btn v-if='item.phones.length > 1' flat icon color="red" class='ma-0' @click='item.phones.splice(index, 1)' style='top: 16px'>
          <v-icon>remove</v-icon>
        </v-btn>
      </div>
    </div>
    <div v-if='editable && item.phones.length < max'>
      <AddBtn @click.native="add()"></AddBtn>
    </div>
  </div>
</template>

<script>

import { MODEL_DEFAULTS } from './'
import { ENTITY_TYPE_TITLE } from '@/other/user-types'

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
      ENTITY_TYPE_TITLE,
    }
  },

  created() {
    if (this.item.phones.length === 0) {
      this.add()
    } else {
      this.item.phones.forEach(phone => this.checkDuplicate(phone))
    }
  },

  methods: {
    add() {
      this.item.phones.push(_.clone(MODEL_DEFAULTS))
    },

    getErrorMessage(phone) {
      if (phone.phone.length === 18 && phone.error) {
        return [ ENTITY_TYPE_TITLE[phone.error.entity_type] + ' №' + phone.error.entity_id ]
      }
      return null
    },

    checkDuplicate(phone) {
      if (phone.phone.length === 18) {
        phone.error = null
        phone.loading = true
        this.$forceUpdate()
        axios.post(apiUrl('phones/check-duplicate'), phone).then(r => {
          phone.error = r.data.data
          phone.loading = false
          this.$forceUpdate()
        })
      }
    },
  },
}
</script>

