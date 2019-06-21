<template>
  <v-select ref='select'
    :error-messages='errorMessages'
    :hide-details='errorMessages === undefined'
    :value='value'
    @input="value => $emit('input', value)"
    :items="items"
    :label="label"
    item-value='id'
    item-text='default_name'
    :readonly="readonly"
  >
    <v-list-tile slot='prepend-item' @click='clear'>
      <v-list-tile-title class='grey--text'>
        не установлено
      </v-list-tile-title>
    </v-list-tile>
    <template slot='item' slot-scope='{ item }' @click='value = item.id'>
      <div :class="{'grey--text': item.in_egecentr !== 2}">{{ item.default_name }}</div>
    </template>
  </v-select>
</template>

<script>
export default {
  props: {
    label: {
      type: String,
      required: false,
      default: 'Учитель',
    },
    value: {
      required: true,
    },
    readonly: {
      type: Boolean,
      default: false,
      required: false,
    },

    onlyActive: Boolean,

    errorMessages: {
      default: undefined,
    },
  },

  methods: {
    clear() {
      this.$refs.select.isMenuActive = false
      this.$emit('input', null)
      this.$refs.select.blur()
    },
  },

  computed: {
    items() {
      if (this.onlyActive) {
        return this.$store.state.data.teachers.filter(e => e.in_egecentr === 2 || e.id === this.value)
      }
      return this.$store.state.data.teachers
    }
  }
}
</script>


