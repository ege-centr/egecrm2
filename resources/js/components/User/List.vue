<template>
  <div>
    <UserDialog ref='UserDialog' @saved='loadData' />
    <Loader v-if='loading' />
    <v-container grid-list-md fluid class="px-0" v-if='data !== null'>
      <v-layout row wrap class='relative'>
        <v-flex xs12>
          <v-data-table :items='data' item-key='id' hide-headers hide-actions>
            <template slot="items" slot-scope="{ item }">
              <td>
                  {{ item.name }}
              </td>
              <td class='text-md-right'>
                <v-btn flat icon color="black" class='ma-0' @click='show(item.id)'>
                  <v-icon>more_horiz</v-icon>
                </v-btn>
              </td>
            </template>
          </v-data-table>
        </v-flex>
        <!-- <v-flex xs12 v-for='item in data' :key='item.id'>
          <UserItem :item='item' @show='show' />
        </v-flex> -->
      </v-layout>
      <div class="text-xs-center mt-4">
        <v-pagination
          v-if='!items && collection.meta.last_page > 1'
          v-model="page"
          :length="collection.meta.last_page"
          :total-visible="7"
          circle
        ></v-pagination>
     </div>
    </v-container>
  </div>
</template>

<script>

import UserDialog from '@/components/User/Dialog'

export default {
  components: { UserDialog },

  data() {
    return {
      page: 1,
      loading: false,
      collection: null
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

  props: {
    items: null,
  },

  methods: {
    loadData() {
      this.loading = true
      axios.get(apiUrl(`admins?page=${this.page}`)).then(response => {
        this.collection = response.data
        this.loading = false
      })
    },
    show(id) {
      this.$refs.UserDialog.show(id)
    }
  },

  computed: {
    data() {
      return this.items || (this.collection !== null ? this.collection.data : null)
    }
  },
}
</script>
