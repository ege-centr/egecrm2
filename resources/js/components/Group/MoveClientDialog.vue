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
          <AllFilter v-if='dialog' :items='FILTERS' :pre-installed='pre_installed_filters' @updated='loadData' />
          <Loader v-if='groups === null || loading' class='loader-wrapper_fullscreen-dialog' />
          <v-container v-else grid-list-xl class="pa-0 ma-0 mt-3" fluid>
            <v-layout wrap>
              <v-flex md12>
                <GroupList :items='groups' :selectable='true' :selected_group_id.sync='selected_group_id' />
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-layout>
</template>

<script>

import { API_URL, GROUP_CLIENTS_API_URL, FILTERS, GroupList } from '@/components/Group'
import { AllFilter } from '@/components/Filter'

export default {
  components: { AllFilter, GroupList },

  data() {
    return {
      FILTERS,
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
      this.pre_installed_filters = {}
      this.groups = null
      this.selected_group_id = null
      this.client = client
      this.group = clone(group);
      // TODO: переделать в IndexPage (может тогда уж переименовать IndexPage?)
      ['grade_id', 'subject_id', 'year'].forEach(field => {
        if (group[field]) {
          this.pre_installed_filters[field] = [group[field]]
        }
      })
      console.log(this.pre_installed_filters)
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
