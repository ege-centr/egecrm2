<template lang="html">
  <div>
    <Loader v-if='loading' />

    <div class='flex-items align-center'>
      <div v-if='yearTabs'>
         <v-chip v-for="year in yearsWithData" class='pointer ml-0 mr-3'
            :class="{'primary white--text': year.id == selected_year}"
            @click='selected_year = year.id'
            :key='year.id'
          >
            {{ year.title }}
          </v-chip>
      </div>
      <AllFilter v-if='filters !== null' :items='filters' :pre-installed='preInstalledFilters' @updated='filtersUpdated' />
      <v-spacer></v-spacer>
      <slot name='buttons'></slot>
    </div>

    <v-container grid-list-md fluid class="px-0" :class="{'invisible': loading}">
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

          <slot name='items' :items='items' v-if='items.length > 0'></slot>

          <infinite-loading v-if='pagination'
            @infinite='loadData' ref='InfiniteLoading' :distance='2000' spinner='spiral' class='mt-3'>
            <div slot='no-more'></div>
            <div slot='no-results'></div>
          </infinite-loading>
        </v-flex>
      </v-layout>
    </v-container>

    <div v-if='items.length === 0' class='text-md-center grey--text darken-3 font-weight-medium' style='margin: 150px 0' :class="{'invisible': loading}">
      нет данных
    </div>

    <slot name='buttons-bottom'></slot>
  </div>
</template>

<script>

import { AllFilter } from '@/components/Filter'
import InfiniteLoading from 'vue-infinite-loading'

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
    showBy: {
      type: Number,
      required: false,
      default: 50,
    },
    yearTabs: {
      type: Boolean,
      default: false,
    }
  },

  components: { AllFilter, InfiniteLoading },

  data() {
    return {
      page: 1,
      loading: true,
      data: [],
      selected_year: this.$store.state.data.academic_year,
    }
  },

  created() {
    if (! this.pagination) {
      this.loadData()
    }
  },

  methods: {
    loadData(state) {
      axios.get(apiUrl(this.apiUrl) + queryString({
        page: this.page,
        show_by: this.pagination ? this.showBy : '',
        ...this.current_filters,
        ...this.invisibleFilters,
        ...this.getSort(),
      })).then(response => {
        if (this.page === 1 || !this.pagination) {
          this.data = response.data.data
        } else {
          this.data.push(...response.data.data)
        }
        if (this.yearTabs) {
          this.selected_year = this.yearsWithData.slice(-1)[0].id
        }
        // if (response.data.meta.current_page === response.data.meta.last_page) {
        //   state.complete()
        // } else {
        //   state.loaded()
        // }
        if (this.pagination) {
          if (response.data.meta.current_page >= response.data.meta.last_page) {
            this.$refs.InfiniteLoading.stateChanger.complete()
          } else {
            this.$refs.InfiniteLoading.stateChanger.loaded()
          }
          this.page++
        }
        this.loading = false
      })
    },

    reloadData() {
      this.loading = true
      this.page = 1
      this.loadData()
      // this.$refs.InfiniteLoading.$emit('infinite', this.$refs.InfiniteLoading.stateChanger)
      // this.$refs.InfiniteLoading.attemptLoad()
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
      this.loading = true
      this.selectedSort.type = this.selectedSort.type === 'asc' ? 'desc' : 'asc'
      this.loadData()
    },

    setSort(index) {
      this.loading = true
      this.selectedSort.selected = false
      this.sort[index].selected = true
      this.loadData()
    },

    filtersUpdated(filters, initial_set) {
      this.current_filters = filters
      if (!initial_set) {
        this.reloadData()
      }
    },
  },

  computed: {
    selectedSort() {
      if (this.sort) {
        return this.sort.find(e => e.selected)
      }
    },

    items() {
      return this.yearTabs ?
        this.data.filter(e => e.year === this.selected_year) :
        this.data
    },

    yearsWithData() {
      return this.$store.state.data.years.filter(year => {
        return this.data.findIndex(e => e.year === year.id) !== -1
      })
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
