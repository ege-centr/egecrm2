<template>
  <v-menu offset-y style='font-size: 12px'>
    <div slot='activator' class='grey--text flex-items align-center'>
      <span>отображать {{ selected ? `по ${selected}` : 'все' }} </span>
      <v-icon>keyboard_arrow_down</v-icon>
    </div>
    <v-list small>
      <v-list-tile v-for='option in options' :key='option' @click='select(option)'>
        <v-list-tile-title>{{ option ? `по ${option}` : 'все' }}</v-list-tile-title>
      </v-list-tile>
    </v-list>
  </v-menu>
</template>

<script>
export default {
  props: ['value'],

  data() {
    return {
      selected: null,
      options: [
        10,
        30,
        50,
        0,
      ]
    }
  },

  created() {
    this.selected = this.value
  },
  
  methods: {
    select(option) {
      this.selected = option
      this.$emit('changed', option)
    }
  }
}
</script>
