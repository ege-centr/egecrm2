<template>
  <v-layout row wrap>
    <Loader v-if='loading' />
    <Filters class='mb-3' :items='FILTERS' @updated='loadData' />
    <v-flex md12 class='relative'>
      <Dialog ref='Dialog' v-if='entityId'
        :item='item'
        :entity-id='entityId'
        :class-name='className'
        @updated='updated'
        @stored='stored' />
      <v-data-table v-if='getItems && getItems.length'
        :class='config.elevationClass'
        hide-actions
        hide-headers
        :items='getItems'
      >
        <template slot='items' slot-scope="{ item, index }">
          <td>
            {{ ENUMS.types.find(e => e.value == item.type).text }}
          </td>
          <td>
            {{ ENUMS.methods.find(e => e.value == item.method).text }}
          </td>
          <td>
            {{ ENUMS.categories.find(e => e.value == item.category).text }}
          </td>
          <td>
            {{ item.sum }} руб.
          </td>
          <td>
            {{ item.date | date }}
          </td>
          <td :class="{'text-md-right': !entityId}">
            <span v-if='item.id'>
              {{ getData('admins', item.created_admin_id).name }}
              {{ item.created_at | date-time }}
            </span>
          </td>
          <td class='text-md-right' v-if='entityId'>
            <v-menu left>
              <v-btn slot='activator' flat icon color="black" class='ma-0'>
                <v-icon>more_horiz</v-icon>
              </v-btn>
              <v-list dense>
                <v-list-tile @click='openDialog(item)'>
                    <v-list-tile-action>
                      <v-icon>edit</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                      <v-list-tile-title>Редактировать</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile @click='destroy(item)'>
                    <v-list-tile-action>
                      <v-icon>close</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                      <v-list-tile-title>Удалить</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
              </v-list>
            </v-menu>
          </td>
        </template>
      </v-data-table>
      <v-flex md12 px-0 mt-3 v-if='entityId'>
        <v-btn fab dark small color="red" @click='openDialog(null)'>
          <v-icon dark>add</v-icon>
        </v-btn>
      </v-flex>
      <v-layout row class='mt-4' align-center v-if='items === null && collection !== null'>
        <v-flex md4>
        </v-flex>
        <v-flex md4>
          <v-pagination
            v-if='collection.meta.last_page > 1'
            v-model="page"
            :length="collection.meta.last_page"
            :total-visible="7"
            circle
          ></v-pagination>
        </v-flex>
        <v-flex class='text-md-right'>
          <ShowBy :value='show_by' @changed='showByChanged' />
        </v-flex>
     </v-layout>
   </v-flex>
 </v-layout>
</template>
<script>

import { API_URL, ENUMS, FILTERS } from './data'
import Dialog from './Dialog'
import ShowBy from '@/components/UI/ShowBy'
import Filters from '@/components/Filters'

export default {
  props: {
    items: {
      type: Array,
      default: null,
      required: false
    },
    className: {
      type: String,
      required: false
    },
    entityId: {
      type: Number,
      required: false,
      default: null
    }
  },

  components: { Dialog, ShowBy, Filters },

  data() {
    return {
      ENUMS,
      FILTERS,
      page: 1,
      loading: false,
      collection: null,
      item: null,
      show_by: 30,
    }
  },

  created() {
    if (! this.items) {
      this.loadData()
    }
  },

  watch: {
    page() {
        this.loadData()
    }
  },

  methods: {
    openDialog(item) {
      this.$refs.Dialog.open(item)
    },
    destroy(item) {
      const index = this.items.findIndex(e => e.id == item.id)
      this.items.splice(index, 1)
      axios.delete(apiUrl(API_URL, item.id))
    },
    updated(item) {
      const index = this.items.findIndex(e => e.id == item.id)
      Vue.set(this.items, index, item)
    },
    stored(item) {
      this.items.push(item)
    },
    loadData(filters = '') {
      this.loading = true
      axios.get(apiUrl(`${API_URL}?page=${this.page}&show_by=${this.show_by}${filters}`)).then(response => {
        this.collection = response.data
        this.loading = false
      })
    },
    showByChanged(option) {
      this.show_by = option
      this.loadData()
    },
  },

  computed: {
    getItems() {
      return this.items || (this.collection !== null ? this.collection.data : null)
    }
  },
}
</script>
