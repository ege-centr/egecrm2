<template>
  <ClearableSelect 
    :value='value'
    @input="value => $emit('input', value)"
    :items="item.items"
    :item-text='item.text'
    :label="item.label"
  />
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
        text: 'text',
      },
    }
  },

  created() {
    this.item.items = this.$store.state.data[this.type]
    switch(this.type) {
      case 'subjects':
        return this.item = {...this.item, ...{
          text: 'name',
          label: 'Предмет',
        }}
      case 'grades':
        return this.item = {...this.item, ...{
          text: 'title',
          label: 'Класс',
        }}
      case 'years':
        return this.item.label = 'Год'
      case 'teachers':
         return this.item = {...this.item, ...{
          text: 'names.abbreviation',
          label: 'Учитель',
        }}
    }
  },
}
</script>

