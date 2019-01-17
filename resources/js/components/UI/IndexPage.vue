<template lang="html">
  <div>
    <Loader v-if='loading' />

    <div class='text-md-right mb-2'>
      <slot name='buttons'></slot>
    </div>

    <Filters v-if='filters !== null' :items='filters' @updated='loadData' />

    <v-container grid-list-md fluid class="px-0" v-if='collection !== null'>
      <v-layout row wrap class='relative'>
        <v-flex xs12>
          <slot name='items' :items='collection.data'></slot>
          
          <v-card :class='config.elevationClass'>
            <v-card-text>
              <div class='flex-items align-center'>
                <v-spacer></v-spacer>
                
                <div class='flex-items align-center'>
                  <div>
                    <span>отображать по: </span>
                  </div>
                  <v-select style='width: 75px; margin: 0 0 0 20px; padding: 0' hide-details
                    v-model="show_by"
                    :items="show_by_options"
                  ></v-select>
                </div>

                <div class='mx-3'>
                  {{ page }} из {{ collection.meta.last_page }}
                </div>
                <v-icon style='margin: 0 10px' small @click='page -= 1' v-if='collection.meta.last_page > 1' :disabled='page == 1' color='black'>arrow_back_ios</v-icon>
                <v-icon small @click='page += 1' v-if='collection.meta.last_page > 1' :disabled='page == collection.meta.last_page'  color='black'>arrow_forward_ios</v-icon>
              </div>
            </v-card-text>
          </v-card>
        </v-flex>
      </v-layout>
    </v-container>
  </div>
</template>

<script>

import Filters from '@/components/Filters'

export default {
  props: {
    apiUrl: {
      type: String,
      required: true,
    },
    filters: {
      type: Array,
      required: false,
      default: null,
    },
  },

  components: { Filters },

  data() {
    return {
      page: 1,
      show_by: 30,
      show_by_options: [
        {text: '10', value: 10},
        {text: '30', value: 30},
        {text: '50', value: 50},
        {text: 'все', value: ''},
      ],
      loading: false,
      collection: null
    }
  },

  created() {
    this.loadData()
  },

  watch: {
    page() {
      this.loadData()
    },

    show_by() {
      this.loadData()
    },
  },

  methods: {
    loadData(filters = {}) {
      this.loading = true
      // axios.get(apiUrl(`${this.apiUrl}?page=${this.page}&show_by=${this.show_by}${filters}`)).then(response => {
      axios.get(apiUrl(this.apiUrl) + queryString({
        page: this.page,
        show_by: this.show_by,
        ...filters,
      })).then(response => {
        this.collection = response.data
        this.loading = false
      })
    },
  }
}
</script>
