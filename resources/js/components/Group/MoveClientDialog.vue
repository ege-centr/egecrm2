<template>
  <v-layout row justify-center>
    <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay>
      <v-card v-if='clientId !== null' class='grey-background'>
        <v-toolbar dark color="primary">
          <v-toolbar-title>Перенос клиента в другую группу</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items>
            <v-btn dark icon @click.native="move" :disabled='selected_group_id === null' :loading='saving'>
              <v-icon>compare_arrows</v-icon>
            </v-btn>
            <v-btn icon dark @click.native="dialog = false">
              <v-icon>close</v-icon>
            </v-btn>
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

import { API_URL, GROUP_CLIENTS_API_URL, FILTERS } from '@/components/Group'
import GroupList from '@/components/Group/List'
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
      clientId: null,
      group: null,
      selected_group_id: null,
      loading: false,
    }
  },

  methods: {
    open(group, clientId) {
      this.pre_installed_filters = {}
      this.groups = null
      this.selected_group_id = null
      this.clientId = clientId
      this.group = clone(group);
      // TODO: переделать в DisplayData (может тогда уж переименовать DisplayData?)
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
      this.saving = true
      await axios.post(apiUrl(GROUP_CLIENTS_API_URL), {
        group_id: this.selected_group_id,
        client_id: this.clientId,
      })
      .then(r => {
        this.$emit('moved', this.clientId)
      })
      .catch(e => {
        this.$store.commit('message', { text: 'Ученик уже присутствует в группе' })
      })
      this.saving = false
      this.dialog = false
    }
  }
}
</script>
