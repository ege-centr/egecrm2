<template>
  <v-select clearable hide-details
    :value='value'
    @input="value => $emit('input', value)"
    :items="item.items"
    :item-value='item.value'
    :item-text='item.text'
    :label="item.label"
  ></v-select>
</template>

<script>
export default {
  props: {
    type: {
      type: String,
      required: true,
    },
    value: {
      required: true
    },
  },

  data() {
    return {
      item: {
        value: 'value',
        text: 'text',
      },
    }
  },

  created() {
    this.item.items = this.$store.state.data[this.type]
    // console.log(this.type, this.$store.state.data[this.type])
    switch(this.type) {
      case 'subjects':
        return this.item = {...this.item, ...{
          text: 'name',
          value: 'id',
          label: 'Предмет',
        }}
      case 'grades':
        return this.item = {...this.item, ...{
          text: 'title',
          value: 'id',
          label: 'Класс',
        }}
      case 'years':
        return this.item.label = 'Год'
    }
  },
}
</script>

