<template>
  <v-text-field
    v-model='text'
    flat
    solo-inverted
    hide-details
    prepend-inner-icon="search"
    @input='search'
    label="Поиск..."
    class="hidden-sm-and-down"
  ></v-text-field>
</template>

<script>

import { API_URL } from './'

export default {
  data() {
    return {
      text: ''
    }
  },
  
  methods: {
    search: _.debounce(function() {
      axios.get(apiUrl(API_URL) + queryString({ text: this.text }))
    }, 1000)
  },
}
</script>
