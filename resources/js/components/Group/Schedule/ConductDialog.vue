<template>
  <v-layout row justify-center>
    <AddClientDialog ref='AddClientDialog' @added='handleClientAdded' />
    <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay content-class='conduct-dialog'>
      <v-card>
        <v-toolbar dark color="primary">
          <v-toolbar-title>
            Проводка занятия
          </v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items v-if='item !== null'>
            <v-btn dark icon @click.native="conduct" 
              v-if="item.status === LESSON_STATUS.PLANNED"
              :loading='conducting'>
              <v-icon>assignment</v-icon>
            </v-btn>
            <v-btn dark icon 
              v-if="item.status === LESSON_STATUS.CONDUCTED && item.topic"
              @click.native="storeOrUpdate" 
              :disabled='cantEdit'
              :loading='saving'>
                <v-icon>save_alt</v-icon>
            </v-btn>
            <v-btn icon dark @click.native="dialog = false">
              <v-icon>close</v-icon>
            </v-btn>
          </v-toolbar-items>
        </v-toolbar>
        <v-card-text class='px-0'>
          <Loader v-if='loading' class='loader-wrapper_fullscreen-dialog' />
          <div v-else-if='item.topic' class='relative'>
            <DivBlocker v-if='cantEdit' />
            <v-alert
              :value="true"
              type="info"
              outline
              class='mx-3 mb-4'
            >
              При проводке занятия отправляются СМС родителям отсутствующих и опоздавших учеников, а также начисляется оплата и бонусы
            </v-alert>
            <!-- ЗАНЯТИЕ ПРОВЕДЕНО -->
            <div wrap v-if="item.status === LESSON_STATUS.CONDUCTED">
              <v-data-table 
                hide-actions
                :headers="headers"
                :items='clientLessons'
              >
                <template slot="items" slot-scope="{ item }">
                  <td width='200'>
                    {{ item.client ? item.client.names.short : 'ученик не указан' }}
                  </td>
                  <td width='150'>
                    <v-switch color='red' v-model="item.is_absent" hide-details></v-switch>
                  </td>
                  <td width='150'>
                    <v-edit-dialog
                      :return-value.sync="item.late"
                      lazy
                    > 
                      <v-btn small flat v-if="!item.late" fab class='edit-inside-table'>
                        <v-icon>edit</v-icon>
                      </v-btn>
                      <span v-else>{{ item.late }}</span>
                      <v-text-field
                        slot="input"
                        v-model="item.late"
                        label="Опоздание"
                        single-line
                        v-mask="'##'"
                      ></v-text-field>
                    </v-edit-dialog>
                  </td>
                  <td width='150' v-if='$store.state.user.class === ROLES.ADMIN'>
                    <v-edit-dialog
                      :return-value.sync="item.price"
                      lazy
                    > 
                      <v-btn small flat v-if="!item.price" fab class='edit-inside-table'>
                        <v-icon>edit</v-icon>
                      </v-btn>
                      <span v-else>{{ item.price }}</span>
                      <v-text-field
                        slot="input"
                        v-model="item.price"
                        label="Цена"
                        single-line
                        v-mask="'#####'"
                      ></v-text-field>
                    </v-edit-dialog>
                  </td>
                  <td>
                    <v-edit-dialog
                      :return-value.sync="item.comment"
                      lazy
                    > 
                      <v-btn small flat v-if="!item.comment" fab class='edit-inside-table'>
                        <v-icon>edit</v-icon>
                      </v-btn>
                      <span v-else>{{ item.comment }}</span>
                      <v-text-field
                        slot="input"
                        v-model="item.comment"
                        single-line
                        label="Комментарий"
                      ></v-text-field>
                    </v-edit-dialog>
                  </td>
                  <td width='30' class='text-md-right' v-if='$store.state.user.class === ROLES.ADMIN'>
                    <v-btn flat icon color="red" class='ma-0' @click='destroyClientLesson(item.id)'>
                      <v-icon>remove</v-icon>
                    </v-btn>
                  </td>
                </template>
                <template slot='footer' v-if='$store.state.user.class === ROLES.ADMIN'>
                  <tr>
                    <td colspan='7' class='text-md-right'>
                      <v-btn slot='activator' flat icon color='primary' class='mx-0'
                        @click='$refs.AddClientDialog.open()'
                      >
                        <v-icon>add</v-icon>
                      </v-btn>
                    </td>
                  </tr>
                </template>
              </v-data-table>
            </div>
            
            <!-- Планируемое занятие -->
            <div wrap v-else>
              <v-flex md12>
                <v-data-table v-if='item.groupClients.length'
                  hide-actions
                  :headers="[
                    { text: 'Ученик', sortable: false },
                    { text: 'Отсутствовал', sortable: false }, 
                    { text: 'Опоздание', sortable: false },
                    { text: 'Комментарий', sortable: false },
                  ]"
                  :items='item.groupClients'
                >
                  <template slot="items" slot-scope="{ item }">
                    <td width='200'>
                      <PersonName :item='item' />
                    </td>
                    <td width='150'>
                      <v-switch color='red' v-model="item.is_absent" hide-details></v-switch>
                    </td>
                    <td width='150'>
                      <v-edit-dialog
                        :return-value.sync="item.late"
                        lazy
                      > 
                        <v-btn small flat v-if="!item.late" fab class='edit-inside-table'>
                          <v-icon>edit</v-icon>
                        </v-btn>
                        <span v-else>{{ item.late }}</span>
                        <v-text-field
                          slot="input"
                          v-model="item.late"
                          single-line
                          label="Опоздание"
                          v-mask="'##'"
                        ></v-text-field>
                      </v-edit-dialog>
                    </td>
                    <td>
                      <v-edit-dialog
                        :return-value.sync="item.comment"
                        lazy
                      > 
                        <v-btn small flat v-if="!item.comment" fab class='edit-inside-table'>
                          <v-icon>edit</v-icon>
                        </v-btn>
                        <span v-else>{{ item.comment }}</span>
                        <v-text-field
                          slot="input"
                          v-model="item.comment"
                          single-line
                          label="Комментарий"
                        ></v-text-field>
                      </v-edit-dialog>
                    </td>
                  </template>
                </v-data-table>
              </v-flex>
            </div>
          </div>
          <div v-else class='full-height-vh flex-items align-center justify-center grey--text' style='flex-direction: column'>
            <div style='opacity: .2'>
              <v-icon size='100'>chrome_reader_mode</v-icon>
            </div>
            <div class='subheading'>
              Установите тему занятия
            </div>
          </div>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-layout>
</template>


<script>
import { DialogMixin } from '@/mixins'
import { ROLES } from '@/config'
import { LESSON_STATUS, MODEL_DEFAULTS, API_URL } from '@/components/Lesson'
import AddClientDialog from './AddClientDialog'

export default {
  mixins: [ DialogMixin ],

  components: {  },
  
  data() {
    return {
      API_URL,
      ROLES,
      LESSON_STATUS,
      MODEL_DEFAULTS,
      // FIXME:
      recompute_client_lessons: 0,
      conducting: false,
    }
  },

  components: { AddClientDialog },
  
  methods: {
    destroyClientLesson(lesson_id) {
      const index = this.item.clientLessons.findIndex(e => e.id === lesson_id)
      console.log('INDEX', index, this.item.clientLessons[index])
      this.item.clientLessons[index].to_be_deleted = true
      this.recompute_client_lessons++
    },

    handleClientAdded(client) {
      this.item.clientLessons.push(client)
    },

    conduct() {
      this.conducting = true
      axios.post(apiUrl(API_URL, 'conduct', this.item.id), {
        topic: this.item.topic,
        clients: this.item.groupClients
      }).then(r => {
        this.conducting = false
        this.item = r.data
      })
    },
  },

  computed: {
    clientLessons() {
      this.recompute_client_lessons
      return this.item.clientLessons.filter(e => e.to_be_deleted !== true)
    },

    headers() {
      const headers = [
        { text: 'Ученик', sortable: false, width: 200 },
        { text: 'Отсутствовал', sortable: false, width: 150 }, 
        { text: 'Опоздание', sortable: false, width: 150 },
        { text: 'Комментарий', sortable: false },
      ]
      if (this.$store.state.user.class === ROLES.ADMIN) {
        headers.splice(3, 0, { text: 'Цена', sortable: false, width: 150 })
        headers.splice(5, 0, { text: '', sortable: false })
      }
      return headers
    },

    // учитель не может редактировать чужое занятие
    cantEdit() {
      return this.$store.state.user.class === ROLES.TEACHER
        && this.item.teacher_id !== this.$store.state.user.id
    }
  }
}
</script>

<style lang="scss">
.conduct-dialog {
  & .v-datatable {
    & tr {
      & td {
        position: relative;
      }
    }
  }
}
</style>