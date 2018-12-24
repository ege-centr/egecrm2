<template>
  <div>
    <Loader v-if='loading' />
    <Filters class='mb-3' :items='FILTERS' @updated='loadData' />
    <v-data-table v-if='getItems && getItems.length'
      class="elevation-3"
      hide-actions
      hide-headers
      :items='getItems'
    >
    <template slot='items' slot-scope="{ item }">
      <td width='200'>
        <router-link :to="{ name: 'GroupShow', params: {id: item.id}}">
          Группа {{ item.id }}
        </router-link>
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
        {{ item.lessons_count }} занятий
      </td>
      <td>
        {{ item.teacher_name }}
      </td>
      <td class='text-md-right' v-if='editable'>
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

import { API_URL, FILTERS } from './data'
import Filters from '@/components/Filters'

export default {
  components: { Filters },

  props: {
    items: {
      type: Array,
      default: null,
      required: false
    },
    editable: {
      type: Boolean,
      default: false,
      required: false
    }
  },

  data() {
    return {
      FILTERS,
      page: 1,
      loading: false,
      server_items: null,
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
    loadData(filters = '') {
      this.loading = true
      axios.get(apiUrl(API_URL + '?a=1' + filters)).then(response => {
        this.server_items = response.data
        this.loading = false
      })
    },
  },

  computed: {
    getItems() {
      return this.items || this.server_items
    }
  }
}
</script>
