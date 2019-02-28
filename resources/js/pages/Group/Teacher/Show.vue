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
              class="full-width"
              hide-actions
              hide-headers
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

     <v-layout row justify-center>
        <v-dialog v-model="emailDialog" max-width="800">
          <v-card>
            <v-card-text>
              <v-container class="pa-0 ma-0" fluid>
                <v-layout wrap>
                  <v-flex md12>
                     <v-data-table
                        class="full-width"
                        hide-actions
                        :headers="[{text: 'Ученики', sortable: false}, {text: 'Представители', sortable: false}]"
                        :items='item.clients'
                      >
                        <template slot='items' slot-scope="props">
                          <tr>
                            <td width='300' class='relative'>
                              <div class='flex-items align-center' v-if='props.item.email.email'>
                                <v-checkbox class='checkbox-in-table' 
                                  @change='selectEmail(props.item.email.email)'
                                  :value='emails.includes(props.item.email.email)' 
                                  hide-details></v-checkbox>
                                <EmailShow :item='props.item.email' />
                              </div>
                            </td>
                            <td>
                              <div class='flex-items align-center' 
                                v-if='props.item.representative.email && props.item.representative.email.email'>
                                <v-checkbox 
                                  @change='selectEmail(props.item.representative.email.email)' 
                                  :value='emails.includes(props.item.representative.email.email)' 
                                  class='checkbox-in-table' hide-details></v-checkbox>
                                <EmailShow :item='props.item.representative.email' />
                              </div>
                            </td>
                          </tr>
                        </template>
                        <template slot='footer'>
                          <td>
                            <div class='flex-items align-center'>
                              <v-checkbox class='checkbox-in-table' @change="(e) => selectAllEmails(e, 'client')" hide-details></v-checkbox>
                              <span>выделить все</span>
                            </div>
                          </td>
                          <td>
                            <div class='flex-items align-center'>
                              <v-checkbox class='checkbox-in-table' @change="(e) => selectAllEmails(e, 'representative')" hide-details></v-checkbox>
                              <span>выделить все</span>
                            </div>
                          </td>
                        </template>
                     </v-data-table>
                  </v-flex>
                </v-layout>
              </v-container>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" flat 
                @click='sendEmails'
                :disabled='emails.length === 0'>отправить email на выбранные адреса</v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-layout>
    
    <!-- <div v-if='item !== null'>
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
          <v-card :class='config.elevationClass' v-if='item.lessons.length > 0'>
            <v-card-text class='relative'>
              <Visits :group='item' />
            </v-card-text>
          </v-card>
          <NoData v-else />
        </v-tab-item>
      </v-tabs-items>
    </div> -->
  </div>
</template>

<script>

import { 
  API_URL, 
  GROUP_CLIENTS_API_URL, 
  LEVELS, 
  CLASS_NAME,
} from '@/components/Group'

import Comments from '@/components/Comments'
import { SUBJECT_STATUSES } from '@/components/Contract'
import GroupInfo from '@/components/Group/Info'
import EmailShow from '@/components/Email/Show'
import EmailDialog from '@/components/Email/Dialog'

export default {
  components: { 
    Comments, GroupInfo, EmailShow, EmailDialog
  },

  data() {
    return {
      LEVELS,
      CLASS_NAME,
      SUBJECT_STATUSES,
      loading: true,
      item: null,
      tabs: null,
      emailDialog: false,
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

    /**
     * type null | true выделить все | false снять выделение
     */
    selectEmail(email, type = null) {
      if (this.emails.includes(email)) {
        if (type !== true) {
          this.emails.splice(this.emails.indexOf(email), 1)
        }
      } else {
        if (type !== false) {
          this.emails.push(email)
          if (!this.emailDialog) {
            this.emailDialog = true
          }
        }
      }
    },

    selectAllEmails(selected, type) {
      this.item.clients.forEach(client => {
        const item = type === 'client' ? client : client.representative
        if (item.email && item.email.email) {
          this.selectEmail(item.email.email, selected)
        }
      })
    },

    sendEmails() {
      this.emailDialog = false
      this.$refs.EmailDialog.open(this.emails)
    },
  }
}
</script>

<style lang='scss'>

</style>
