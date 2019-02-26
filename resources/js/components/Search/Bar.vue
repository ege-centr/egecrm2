<template>
  <v-list-tile @click='start()'>
    <div class='search-bar' v-show='show'>
      <v-text-field
        ref='search'
        v-model='text'
        flat
        prepend-inner-icon="search"
        solo-inverted
        hide-details
        @input='search'
        :loading='loading'
        label="поиск"
        class="hidden-sm-and-down"
      ></v-text-field>
    </div>
  </v-list-tile>
</template>

<script>

import { API_URL } from './'

export default {
  data() {
    return {
      loading: false,
      text: '',
      show: true,
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
    
    start() {
      this.show = true
      console.log(this.$refs.search.$el.querySelector('input'))
      this.$refs.search.$el.querySelector('input').focus()
    },
    //
    // clear() {
    //   this.text = ''
    //   this.$store.commit('set', {field: 'search', payload: null})
    // }
  },
}
</script>

<style lang="scss">
  @import '~sass/_variables';

  .search-bar {
    position: absolute;
    width: 100%;
    margin-left: -16px;
    z-index: 1;
    & input, label {
      font-size: 13px;
    }
    & .v-input__control {
      min-height: 40px !important;
    }
    & .v-input__slot {
      padding: 0 15px !important;
      border-radius: 0 !important;
      background: $sidebar-color !important;
      cursor: pointer !important;
      &:hover {
        background: rgba(255, 255, 255, 0.01) !important;
      }
      & .v-label  {
        left: 9px !important;
        color: white !important;
      }
      & input {
        padding-left: 9px;
        cursor: pointer;
      }
    }
    & .v-input--is-focused {
      & .v-input__slot {
        background: white !important;
        color: black !important;
        & .v-label  {
          color: #9e9e9e !important;
        }
      }
    }
    margin-bottom: 12px;
  }
</style>
