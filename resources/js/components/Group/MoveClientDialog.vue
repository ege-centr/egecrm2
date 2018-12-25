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
            <v-btn dark flat @click.native="storeOrUpdate" :disabled='selected_group_id === null' :loading='false'>Перенести</v-btn>
          </v-toolbar-items>
        </v-toolbar>
        <v-card-text class='relative'>
          <Loader v-if='groups === null' class='loader-wrapper_fullscreen-dialog' />
          <v-container v-else grid-list-xl class="pa-0 ma-0" fluid>
            <Filters :items='FILTERS' />
            <v-layout wrap>
              <v-flex md12>
                <v-data-table
                  class="elevation-3"
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

import { API_URL, FILTERS } from '@/components/Group'
import Filters from '@/components/Filters'

export default {
  components: { Filters },

  data() {
    return {
      FILTERS,
      saving: false,
      groups: null,
      dialog: false,
      edit_mode: true,
      client: null,
      group: null,
      selected_group_id: null,
    }
  },

  methods: {
    open(group, client) {
      this.groups = null
      this.selected_group_id = null
      this.client = client
      this.group = clone(group)
      this.dialog = true
      this.loadData()
    },

    loadData(filters = '') {
      axios.get(apiUrl(API_URL) + `?group_id=${this.group.id}&subject_id=${this.group.subject_id}&grade_id=${this.group.grade_id}&year=${this.group.year}`).then(r => {
      // axios.get(apiUrl(API_URL) + `?group_id=${this.group.id}${filters}`).then(r => {
        this.groups = r.data
      })
    },

    photoChanged(new_photo) {
      this.item.photo = new_photo
    },

    async storeOrUpdate() {
      // this.saving = true
      // if (this.item.id) {
      //   await axios.put(apiUrl(API_URL, this.item.id), this.item).then(r => {
      //     this.item = r.data
      //   })
      // } else {
 
      // }
      this.saving = false
      this.dialog = false
    }
  }
}
</script>
