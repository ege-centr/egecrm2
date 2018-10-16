<template>
  <div>
    <v-data-table v-if='getItems && getItems.length'
      class="elevation-1"
      hide-actions
      hide-headers
      :items='getItems'
    >
    <template slot='items' slot-scope="{ item }">
      <td>
        Группа {{ item.id }}
      </td>
      <td>
        {{ getData('subjects', item.subject_id).three_letters }}–{{ item.grade_id }}
      </td>
      <td>
        <span v-if="item.cabinet">
          {{ item.cabinet.text }}
        </span>
      </td>
      <td>
        <span v-if='item.clients_count'>
          {{ item.clients_count }} ученика
        </span>
        <span v-else>нет учеников</span>
      </td>
      <td>
        {{ item.teacher_name }}
      </td>
      <td class='text-md-right'>
        <router-link :to="{name: 'GroupEdit', params: { id: item.id }}">
          <v-btn flat icon color="black" class='ma-0'>
            <v-icon>more_horiz</v-icon>
          </v-btn>
        </router-link>
      </td>
    </template>
  </v-data-table>
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
