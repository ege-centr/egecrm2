<template>
  <v-layout row wrap>
    <Loader v-if='loading' />
    <v-flex md12 class='relative'>
      <Dialog ref='Dialog' :item='item' @saved='saved' />
      <v-data-table v-if='getItems && getItems.length'
        class="elevation-1"
        hide-actions
        hide-headers
        :items='getItems'
      >
        <template slot='items' slot-scope="{ item, index }">
          <td>
            {{ enums.types.find(e => e.value == item.type).text }}
          </td>
          <td>
            {{ enums.methods.find(e => e.value == item.method).text }}
          </td>
          <td>
            {{ enums.categories.find(e => e.value == item.category).text }}
          </td>
          <td>
            {{ item.sum }} руб.
          </td>
          <td>
            {{ item.date | date }}
          </td>
          <td :class="{'text-md-right': !editable}">
            <span v-if='item.id'>
              {{ getData('admins', item.created_admin_id).name }}
              {{ item.created_at | date-time }}
            </span>
          </td>
          <td class='text-md-right' v-if='editable'>
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
                <v-list-tile @click='getItems.splice(index, 1)'>
                    <v-list-tile-action>
                      <v-icon>remove</v-icon>
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
      <v-flex md12 px-0 v-if='editable'>
        <v-btn color='blue white--text darken-1' small class='ma-0' @click='openDialog'>
          <v-icon class="mr-1">add</v-icon>
          добавить
        </v-btn>
      </v-flex>
      <div class="text-xs-center mt-4" v-if='items === null && collection !== null'>
        <v-pagination
          v-if='collection.meta.last_page > 1'
          v-model="page"
          :length="collection.meta.last_page"
          :total-visible="7"
          circle
        ></v-pagination>
     </div>
   </v-flex>
 </v-layout>
</template>
<script>

import { url, enums } from './data'
import Dialog from './Dialog'

export default {
  props: {
    items: {
      type: Array,
      default: null,
      required: false
    },
    editable: {
      type: Boolean,
      default: false
    }
  },

  components: { Dialog },

  data() {
    return {
      page: 1,
      loading: false,
      collection: null,
      item: null,
      enums
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
    remove(index) {
      console.log('remove', index)
    },
    saved(item) {
      if (item.id) {
        const index = this.items.findIndex(e => e.id == item.id)
        Vue.set(this.items, index, item)
      } else {
        this.items.push(item)
      }
    },
    loadData() {
      this.loading = true
      axios.get(apiUrl(`${url}?page=${this.page}`)).then(response => {
        this.collection = response.data
        this.loading = false
      })
    },
  },

  computed: {
    getItems() {
      return this.items || (this.collection !== null ? this.collection.data : null)
    }
  },
}
</script>
