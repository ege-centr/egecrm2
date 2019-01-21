<template lang="html">
  <div>
    <Loader v-if='loading' />

    <div class='text-md-right mb-2'>
      <slot name='buttons'></slot>
    </div>

    <Filters v-if='filters !== null' :items='filters' :pre-installed='preInstalledFilters' @updated='loadData' />

    <v-container grid-list-md fluid class="px-0" v-if='collection !== null'>
      <v-layout row wrap class='relative'>
        <v-flex xs12>
          <div v-if='sort !== undefined' class='grey--text darken-3 mb-3 text-md-right caption flex-items justify-end'>
            cортировка: 
            <v-menu class='mx-1'>
              <span slot='activator' class='sort-label'>{{ selectedSort.label }}</span>
              <v-list dense>
                <v-list-tile v-for='(s, index) in sort' :key='index' @click='setSort(index)'>
                  <v-list-tile-title>{{ s.label }}</v-list-tile-title>
                </v-list-tile>
              </v-list>
            </v-menu>
            <v-icon class='pr-3' small @click='toggleSortType'>
              {{ sort.find(e => e.selected).type === 'asc' ? 'arrow_upward' : 'arrow_downward' }}
            </v-icon>
          </div>
          <slot name='items' :items='collection.data'></slot>
          
          <v-card :class='config.elevationClass' v-if='pagination'>
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

    <slot name='buttons-bottom'></slot>

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
    pagination: {
      type: Boolean,
      required: false,
      default: true,
    },
    invisibleFilters: {
      type: Object,
      required: false,
    },
    preInstalledFilters: {
      type: Array,
      required: false,
    },
    sort: {
      type: Array,
      required: false,
    },
  },

  components: { Filters },

  data() {
    return {
      page: 1,
      show_by: this.pagination ? 30 : '',
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
    if (this.preInstalledFilters === undefined) {
      this.loadData()
    }
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
        ...this.invisibleFilters,
        ...this.getSort(),
      })).then(response => {
        this.collection = response.data
        this.loading = false
      })
    },

    getSort() {
      if (this.sort !== undefined) {
        return {
          sort_by: this.selectedSort.field,
          sort_type: this.selectedSort.type,
        }
      }
      return {}
    },

    toggleSortType() {
      this.selectedSort.type = this.selectedSort.type === 'asc' ? 'desc' : 'asc'
      this.loadData()
    },

    setSort(index) {
      this.selectedSort.selected = false
      this.sort[index].selected = true
      this.loadData()
    },
  },

  computed: {
    selectedSort() {
      return this.sort.find(e => e.selected)
    },
  }
}
</script>

<style lang="scss" scoped>
  .sort-label {
    border-bottom: 1px dotted grey;
    cursor: pointer;
  }
</style>
