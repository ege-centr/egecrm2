<template>
  <div>
    <v-data-table v-if='getItems && getItems.length'
      class="elevation-3"
      hide-actions
      hide-headers
      :items='getItems'
    >
      <template slot='items' slot-scope="{ item }">
        <td>
          <router-link :to="{ name: 'TeacherShow', params: {id: item.id}}">
            Teacher {{ item.id }}
          </router-link>
        </td>
        <td class='text-md-right'>
          <router-link :to="{name: 'TeacherEdit', params: { id: item.id }}">
            <v-btn flat icon color="black" class='ma-0'>
              <v-icon>more_horiz</v-icon>
            </v-btn>
          </router-link>
        </td>
      </template>
    </v-data-table>
    <div class="text-xs-center mt-4" v-if='items === null'>
      <v-pagination
        v-if='collection.meta.last_page > 1'
        v-model="page"
        :length="collection.meta.last_page"
        :total-visible="7"
        circle
      ></v-pagination>
   </div>
  </div>
</template>
<script>

import { url } from './data'

export default {
  props: {
    items: {
      type: Array,
      default: null,
      required: false
    }
  },
  data() {
    return {
      page: 1,
      loading: false,
      collection: null,
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
