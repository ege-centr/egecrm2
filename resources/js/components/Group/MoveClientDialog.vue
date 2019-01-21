<template>
  <v-layout row justify-center>
    <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay>
      <v-card v-if='client !== null'>
        <v-toolbar dark color="primary">
          <v-btn icon dark @click.native="dialog = false">
            <v-icon>close</v-icon>
          </v-btn>
          <v-toolbar-title>Перенос клиента {{ client.names.short }} в другую группу</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items>
            <v-btn dark flat @click.native="move" :disabled='selected_group_id === null' :loading='saving'>Перенести</v-btn>
          </v-toolbar-items>
        </v-toolbar>
        <v-card-text class='relative'>
          <Filters v-if='dialog' :items='filters' :pre-installed='pre_installed_filters' @updated='loadData' />
          <Loader v-if='groups === null || loading' class='loader-wrapper_fullscreen-dialog' />
          <v-container v-else grid-list-xl class="pa-0 ma-0 mt-3" fluid>
            <v-layout wrap>
              <v-flex md12>
                <v-data-table
                  :class='config.elevationClass'
                  hide-actions
                  hide-headers
                  :items='groups'
                >
                <template slot='items' slot-scope="{ item }">
                  <td width='100'>
                    <v-radio-group v-model="selected_group_id" hide-details>
                      <v-radio :value='item.id' class='ma-0'></v-radio>
                    </v-radio-group>
                  </td>
                  <td width='200'>
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
                    {{ item.lessons_count }} занятий
                  </td>
                  <td>
                    {{ item.teacher_name }}
                  </td>
                </template>
              </v-data-table>

              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-layout>
</template>

<script>

import { API_URL, GROUP_CLIENTS_API_URL } from '@/components/Group'
import Filters from '@/components/Filters'

export default {
  components: { Filters },

  data() {
    return {
      // TODO: дубль из pages/Group/Index
      filters: [
        {label: 'Год', field: 'year', type: 'multiple', options: this.$store.state.data.years, valueField: 'id', textField: 'text'},
        {label: 'Преподаватель', field: 'teacher_id', type: 'multiple', options: this.$store.state.data.teachers, valueField: 'id', textField: 'names.abbreviation'},
        {label: 'Предмет', field: 'subject_id', type: 'multiple', options: this.$store.state.data.subjects, valueField: 'id', textField: 'name'},
        {label: 'Класс', field: 'grade_id', type: 'multiple', options: this.$store.state.data.grades, valueField: 'id', textField: 'title'},
        {label: 'Филиал', field: 'branch_id', type: 'multiple', options: this.$store.state.data.branches, valueField: 'id', textField: 'full'},
      ],
      pre_installed_filters: [],
      saving: false,
      groups: null,
      dialog: false,
      edit_mode: true,
      client: null,
      group: null,
      selected_group_id: null,
      loading: false,
    }
  },

  methods: {
    open(group, client) {
      this.pre_installed_filters = []
      this.groups = null
      this.selected_group_id = null
      this.client = client
      this.group = clone(group)
      if (group.grade_id) {
        this.pre_installed_filters.push({item: this.filters[3], value: [group.grade_id]})
      }
      if (group.subject_id) {
        this.pre_installed_filters.push({item: this.filters[2], value: [group.subject_id]})
      }
      if (group.year) {
        this.pre_installed_filters.push({item: this.filters[0], value: [group.year]})
      }
      this.dialog = true
    },

    loadData(filters = {}) {
      this.loading = true
      if (this.group_id) {
        filters.group_id = this.group_id
      }
      axios.get(apiUrl(API_URL) + queryString(filters)).then(r => {
        this.groups = r.data.data
        this.loading = false
      })
    },

    async move() {
      this.$emit('moved', this.client)
      this.saving = true
      await axios.post(apiUrl(GROUP_CLIENTS_API_URL), {
        group_id: this.selected_group_id,
        client_id: this.client.id,
      })
      this.saving = false
      this.dialog = false
    }
  }
}
</script>
