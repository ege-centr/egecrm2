<template>
  <v-select hide-details
    :value="value"
    @input="admin_id => $emit('input', admin_id)"
    :items="withNullOption($store.state.data.admins, 'id', 'name')"
    :label="label"
    item-value='id'
    item-text='name'
  >
    <template slot='item' slot-scope='{ item }' @click='value = item.id'>
      <div :class="{'grey--text': item.is_banned}">{{ item.name }}</div>
    </template>
  </v-select>
</template>

<script>
export default {
  props: {
    label: {
      type: String,
      default: 'Пользователь',
      required: false,
    },
    value: {
      required: true
    },
  },
}
</script>
