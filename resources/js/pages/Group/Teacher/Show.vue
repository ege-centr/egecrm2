<template>
  <div class='teacher-group-show'>
    <EmailDialog ref='EmailDialog' />
    <div class='headline mb-4'>
      Группа {{ $route.params.id }}
    </div>

    <v-card class='mb-4' :class='config.elevationClass'>
      <v-card-text class='relative card-with-loader'>
        <Loader v-if='loading'></Loader>
        <v-layout wrap v-if='item !== null'>
          <v-flex md12>
            <GroupInfo :item='item' :display-options='{edit: false}' />
          </v-flex>
          <v-flex md12 class='mt-5' v-if='item.clients.length'>
            <v-data-table
              class="full-width big-table-headers"
              hide-actions
              :headers="[{sortable: false}, {sortable: false}, {text: 'Ученики', sortable: false}, {text: 'Представители', sortable: false}]"
              :items='item.clients'
            >
              <template slot='items' slot-scope="props">
                <td width='300'>
                  <PersonName :item='props.item'/>
                </td>
                <td width='150'>
                  30%
                </td>
                <td width='300' class='relative'>
                  <div class='flex-items align-center' v-if='props.item.email.email'>
                    <v-checkbox class='checkbox-in-table' 
                      :value='emails.includes(props.item.email.email)' 
                      @change='selectEmail(props.item.email.email)' hide-details></v-checkbox>
                    <EmailShow :item='props.item.email' />
                  </div>
                </td>
                <td>
                  <div class='flex-items align-center' 
                    v-if='props.item.representative.email && props.item.representative.email.email'>
                    <v-checkbox class='checkbox-in-table' 
                      :value='emails.includes(props.item.representative.email.email)' 
                      @change='selectEmail(props.item.representative.email.email)' hide-details></v-checkbox>
                    <EmailShow :item='props.item.representative.email' />
                  </div>
                </td>
              </template>
            </v-data-table>
          </v-flex>
        </v-layout>
      </v-card-text>
    </v-card>

    <!-- v-ripple -->
    <v-snackbar
      :value='emails.length > 0'
      :timeout='0'
      color='primary'
      class='text-sm-center'
    >
      <v-btn
        dark
        flat
        @click='sendEmails'
      >
        ОТПРАВИТЬ
        <v-icon right dark>mail</v-icon>
      </v-btn>
    </v-snackbar>

    <div v-if='item !== null'>
      <v-tabs fixed-tabs v-model='tabs' class='mb-4'>
        <v-tab>
          Расписание
        </v-tab>
        <v-tab>
          Посещаемость
        </v-tab>
      </v-tabs>
      <v-tabs-items v-model="tabs">
        <v-tab-item>
          <v-card :class='config.elevationClass'>
            <v-card-text class='relative'>
              <GroupSchedule v-if='item !== null' :group='item' :readonly='true' />
            </v-card-text>
          </v-card>
        </v-tab-item>
        <v-tab-item>
          <GroupVisits :group='item' />
        </v-tab-item>
      </v-tabs-items>
    </div>
  </div>
</template>

<script>

import { 
  API_URL, 
  GROUP_CLIENTS_API_URL, 
  LEVELS, 
  CLASS_NAME,
} from '@/components/Group'

import GroupSchedule from '@/components/Group/Schedule/Schedule'
import GroupVisits from '@/components/Group/Visits'
import GroupInfo from '@/components/Group/Info'

import Comments from '@/components/Comments'
import { SUBJECT_STATUSES } from '@/components/Contract'
import EmailShow from '@/components/Email/Show'
import EmailDialog from '@/components/Email/Dialog'

export default {
  components: { 
    Comments, GroupInfo, EmailShow, EmailDialog,
    GroupSchedule, GroupVisits
  },

  data() {
    return {
      LEVELS,
      CLASS_NAME,
      SUBJECT_STATUSES,
      loading: true,
      item: null,
      tabs: null,
      emails: [],
    }
  },

  created() {
    this.loadData()
  },

  methods: {
    loadData() {
      axios.get(apiUrl(`${API_URL}/${this.$route.params.id}`)).then(r => {
        this.item = r.data
        this.loading = false
      })
    },

    openGroup(id) {
      this.$refs.GroupDialog.open(id)
    },

    removeClientFromGroup(client) {
      this.item.clients.splice(this.item.clients.findIndex(e => e.id === client.id), 1)
      axios.delete(apiUrl(GROUP_CLIENTS_API_URL + `?group_id=${this.item.id}&client_id=${client.id}`))
    },

    moveClient(client) {
      this.$refs.MoveClientDialog.open(this.item, client)
    },

    selectEmail(email) {
      if (this.emails.includes(email)) {
        this.emails.splice(this.emails.indexOf(email), 1)
      } else {
        this.emails.push(email)
      }
    },

    sendEmails() {
      this.$refs.EmailDialog.open(this.emails)
      this.waitForDialogClose(() => this.emails = [])
    },
  }
}
</script>

<style lang='scss' scoped>
  .teacher-group-show {
    & .v-snack__content {
      & button {
        margin: 0 auto !important;
      }
    }
  }
</style>
