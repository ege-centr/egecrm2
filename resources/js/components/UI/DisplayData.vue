<template lang="html">
  <div>
    <Loader v-if='loading && !loadingNew' />

    <div class='flex-items align-center'>
      <YearTabs v-if='tabs' :items='tabsWithData' :selected-year.sync='selectedTab' />
      <div v-else-if='customTabs !== null'>
        <v-chip v-for="(label, id) in tabsWithData" class='pointer ml-0 mr-3'
          :class="{'primary white--text': id == selectedTab}"
          @click='selectedTab = id'
          :key='id'
        >
          {{ label }}
        </v-chip>
      </div>
      <AllFilter 
        v-if='filters !== null' 
        :items='filters' 
        :pre-installed='preInstalledFilters' 
        :sort='sort' 
        :facets='facets'
        :used-filter-facets='usedFilterFacets'
        @updated='filtersUpdated'
        @usedFilterClick='(filter) => loadData(null, filter)'
      />
      <v-spacer></v-spacer>
      <slot name='buttons' v-if='items.length > 0'></slot>
    </div>

    <!-- <NoData v-if='items.length === 0' :class="{'invisible': loading}" /> -->

    <v-container grid-list-md fluid :class="`px-0 pb-0 ${containerClass} ${loading ? 'invisible' : ''}`">
      <v-layout row wrap class='relative'>
        <v-flex xs12>
          <!-- <div v-if='sort !== undefined' class='grey--text darken-3 mb-3 text-md-right caption flex-items justify-end'>
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
          </div> -->

          <slot name='items' :items='items'></slot>

          <infinite-loading 
            ref='InfiniteLoading' 
            v-if='paginate && infiniteLoadingComponent'
            @infinite='loadData' 
            :distance='600' 
            spinner='spiral' 
            class='mt-3'
          >
            <div slot='no-more'></div>
            <div slot='no-results'></div>
          </infinite-loading>
        </v-flex>
      </v-layout>
    </v-container>

    <slot name='buttons-bottom'></slot>
  </div>
</template>

<script>

import { AllFilter } from '@/components/Filter'
import { tabsWithData } from '@/other/functions'
import InfiniteLoading from 'vue-infinite-loading'
import EVENT_TYPE from '@/other/event-types'

export default {
  props: {
    loadingNew: {
      type: Boolean,
      default: false,
    },
    
    apiUrl: {
      type: String,
      required: true,
    },
    filters: {
      type: Array,
      required: false,
      default: null,
    },
    paginate: {
      type: Number,
      required: false,
      default: null,
    },
    invisibleFilters: {
      type: Object,
      required: false,
    },
    preInstalledFilters: {
      type: Object,
      required: false,
    },
    sort: {
      type: Array,
      required: false,
    },
    tabs: {
      type: Boolean,
      default: false,
    },
    customTabs: {
      type: Object,
      default: null,
      required: false,
    },

    containerClass: {
      type: String,
      default: '',
    },

    options: {
      type: Object,
      default() {
        return {}
      },
    }
  },

  components: { AllFilter, InfiniteLoading },

  data() {
    return {
      page: 1,
      // изначальная загрузка (спиннер на весь экран + блокировка)
      loading: true,
      // загрузка страницы (infinite-scroll внизу)
      pageLoading: true,
      // для пересоздания компонента
      infiniteLoadingComponent: true,
      data: [],
      selectedTab: null,
      facets: null,
      usedFilterFacets: null,
      currentFilters: {},
    }
  },

  created() {
    this.setLoading(true)
    if (this.paginate === null) {
      this.loadData()
    }
  },

  methods: {
    // usedFilter если установлен, то перезагружать данные не надо,
    // а только recount фасетов сделать без учета этого фильтрас
    loadData(state, usedFilter = null) {
      this.pageLoading = true
      let filters = this.currentFilters
      if (usedFilter !== null) {
        this.usedFilterFacets = null
        filters = _.omitBy(filters, (e, key) => key === usedFilter)
      }
      axios.get(apiUrl(this.apiUrl) + queryString({
        page: this.page,
        paginate: this.paginate || '',
        ...this.options,
        ...filters,
        ...this.invisibleFilters,
        // ...this.getSort(),
      })).then(response => {
        this.pageLoading = false
        this.setLoading(false)
        const facets = 'facets' in response.data ? response.data.facets : null
        if (usedFilter !== null) {
          this.usedFilterFacets = facets[usedFilter]
        } else {
          this.facets = facets
          if (this.page === 1 || this.paginate === null) {
            this.data = response.data.data
            if (this.tabs && this.tabsWithData.length > 0) {
              this.selectedTab = this.tabsWithData.slice(-1)[0].id
            }
            if (this.customTabs !== null && Object.keys(this.tabsWithData).length > 0) {
              this.selectedTab = Object.keys(this.tabsWithData)[0]
            }
          } else {
            this.data.push(...response.data.data)
          }
          if (this.paginate !== null) {
            if (response.data.meta.current_page === response.data.meta.last_page) {
              colorLog('COMPLETE', 'Turquoise')
              state.complete()
            } else {
              colorLog('LOADED', 'PaleVioletRed')
              state.loaded()
            }
            this.page++
          }
          // } else {
          //   colorLog('COMPLETE2', 'Turquoise')
          //   state.complete()
          // }
          // if (this.paginate !== null) {
          //   if (response.data.meta.current_page >= response.data.meta.last_page) {
          //     colorLog('COMPLETE', 'Turquoise')
          //     this.$refs.InfiniteLoading.stateChanger.complete()
          //   } else {
          //     colorLog('LOADED', 'PaleVioletRed')
          //     this.$refs.InfiniteLoading.stateChanger.loaded()
          //   }
          //   this.page++
          // }
        }
      })
    },

    reloadData() {
      colorLog('Reloading data', 'Turquoise')
      this.setLoading(true)
      this.page = 1
      this.data = []
      if (this.paginate === null) {
        this.loadData()
      } else {
        this.infiniteLoadingComponent = false
        Vue.nextTick(() => this.infiniteLoadingComponent = true)
      }
      // this.loadData()
      // this.$refs.InfiniteLoading.$emit('infinite', this.$refs.InfiniteLoading.stateChanger)
      // Vue.nextTick(() => {
      //   this.$refs.InfiniteLoading.attemptLoad()
      // })
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
      this.setLoading(true)
      this.selectedSort.type = this.selectedSort.type === 'asc' ? 'desc' : 'asc'
      this.reloadData()
    },

    setSort(index) {
      this.setLoading(true)
      this.selectedSort.selected = false
      this.sort[index].selected = true
      this.reloadData()
    },

    filtersUpdated(filters, initial_set) {
      this.currentFilters = filters
      this.$emit('update:current-filters', filters)
      if (!initial_set) {
        this.reloadData()
      }
    },

    setLoading(value) {
      this.loading = value
      if (this.loadingNew) {
        this.$store.commit('loading', value)
      }
    },

    updateItem({ event, item }) {
      console.log("HERE", event, item)
      const index = this.data.findIndex(e => e.id === item.id)
      switch(event) {
        case EVENT_TYPE.updated: {
          this.data.splice(index, 1, item)
          break
        }

        case EVENT_TYPE.created: {
          this.data.splice(0, 0, item)
          break
        }

        case EVENT_TYPE.destroyed: {
          this.data.splice(index, 1)
          break
        }
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
      if (this.tabs) {
        return this.data.filter(e => e.year === this.selectedTab)
      } else if (this.customTabs !== null) {
        return this.data.filter(e => e[this.customTabs.field] === this.selectedTab)
      }
      return this.data
    },

    tabsWithData() {
      if (this.tabs) {
        return tabsWithData(this.data)
      } else if (this.customTabs !== null) { 
        return _.omitBy(this.customTabs.data, (label, key) => {
          return this.data.findIndex(e => e[this.customTabs.field] === key) === -1
        })
      }
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
