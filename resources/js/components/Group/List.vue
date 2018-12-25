<template>
  <div>
    <Loader v-if='loading' />

    <div class='mb-3'>
      <v-chip v-for="year in $store.state.data.years" class='pointer ml-0 mr-3'
        :class="{'primary white--text': year.value == selected_year}"
        @click='selected_year = year.value'
        :key='year.value'>{{ year.text }}</v-chip>
    </div>

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

import { API_URL } from '@/components/Group'

export default {
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
      page: 1,
      loading: false,
      server_items: null,
      // TODO
      selected_year: 2018,
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
    },

    selected_year() {
      this.loadData()
    },
  },

  methods: {
    loadData() {
      this.loading = true
      axios.get(apiUrl(API_URL + '?year=' + this.selected_year)).then(response => {
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
