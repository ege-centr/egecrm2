<template>
  <div class='search-bar'>
    <v-text-field
      v-model='text'
      flat
      prepend-inner-icon="search"
      solo-inverted
      hide-details
      @input='search'
      :loading='loading'
      label="Поиск..."
      class="hidden-sm-and-down"
    ></v-text-field>
  </div>
</template>

<script>

import { API_URL } from './'

export default {
  data() {
    return {
      loading: false,
      text: ''
    }
  },
  
  methods: {
    search: _.debounce(function() {
      if (this.text.length <= 3) {
        this.$store.commit('set', {field: 'search', payload: null})
        return
      }
      this.loading = true
      axios.get(apiUrl(API_URL) + queryString({ text: this.text })).then(r => {
        this.$store.commit('set', {field: 'search', payload: r.data})
        this.loading = false
      })
    }, 300),
    
    //
    // clear() {
    //   this.text = ''
    //   this.$store.commit('set', {field: 'search', payload: null})
    // }
  },
}
</script>

<style lang="scss">
  .search-bar {
    padding: 0 17px;
    & input, label {
      font-size: 13px;
    }
    & .v-input__control {
      min-height: 40px !important;
    }
    margin-bottom: 12px;
  }
</style>
