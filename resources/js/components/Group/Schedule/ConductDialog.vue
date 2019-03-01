<template>
  <v-layout row justify-center>
    <AddClientDialog ref='AddClientDialog' @added='handleClientAdded' />
    <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay content-class='conduct-dialog'>
      <v-card>
        <v-toolbar dark color="primary">
          <v-btn icon dark @click.native="dialog = false">
            <v-icon>close</v-icon>
          </v-btn>
          <v-toolbar-title>
            Проводка занятия
          </v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items v-if='item !== null'>
            <!-- <v-btn dark flat v-if='edit_mode' @click.native="destroy" :loading='destroying'>Удалить</v-btn> -->
            <v-btn dark flat @click.native="conduct" 
              v-if="item.status === LESSON_STATUS.PLANNED"
              :loading='conducting'>Провести</v-btn>
            <v-btn dark flat 
              v-if="item.status === LESSON_STATUS.CONDUCTED"
              @click.native="storeOrUpdate" 
              :loading='saving'>Сохранить</v-btn>
          </v-toolbar-items>
        </v-toolbar>
        <v-card-text>
          <Loader v-if='loading' class='loader-wrapper_fullscreen-dialog' />
          <v-container grid-list-xl class="pa-0 ma-0" fluid v-else>
            <!-- ЗАНЯТИЕ ПРОВЕДЕНО -->
            <v-layout wrap v-if="item.status === LESSON_STATUS.CONDUCTED">
              <v-flex md12>
                <v-data-table 
                  hide-actions
                  :headers="[
                    { text: '', sortable: false },
                    { text: 'Ученик', sortable: false },
                    { text: 'Отсутствовал', sortable: false }, 
                    { text: 'Опоздание', sortable: false },
                    { text: 'Цена', sortable: false },
                    { text: 'Комментарий', sortable: false },
                  ]"
                  :items='clientLessons'
                >
                  <template slot="items" slot-scope="{ item }">
                    <td width='30' class='px-0 text-md-center' @click='destroyClientLesson(item.id)'>
                      <v-icon class='pointer'>close</v-icon>
                    </td>
                    <td width='200'>
                      {{ item.client ? item.client.names.short : 'ученик не указан' }}
                    </td>
                    <td width='150'>
                      <v-switch color='red' v-model="item.is_absent" hide-details></v-switch>
                    </td>
                    <td width='150'>
                      <v-icon small v-if="!item.late" class='client-edit-icon'>edit</v-icon>
                      <v-edit-dialog
                        :return-value.sync="item.late"
                        lazy
                      > {{ item.late }}
                        <v-text-field
                          slot="input"
                          v-model="item.late"
                          label="Опоздание"
                          single-line
                          v-mask="'##'"
                        ></v-text-field>
                      </v-edit-dialog>
                    </td>
                    <td width='150'>
                      <v-icon small v-if="!item.price" class='client-edit-icon'>edit</v-icon>
                      <v-edit-dialog
                        :return-value.sync="item.price"
                        lazy
                      > {{ item.price }}
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
                      <v-icon small v-if="!item.comment" class='client-edit-icon'>edit</v-icon>
                      <v-edit-dialog
                        :return-value.sync="item.comment"
                        lazy
                      > {{ item.comment }}
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
              <v-flex md12>
                <AddBtn label='добавить ученика' @click.native='$refs.AddClientDialog.open()' />
              </v-flex>
            </v-layout>

            <!-- Планируемое занятие -->
            <v-layout wrap v-else>
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
                      <v-icon small v-if="!item.late" class='client-edit-icon'>edit</v-icon>
                      <v-edit-dialog
                        :return-value.sync="item.late"
                        lazy
                      > {{ item.late }}
                        <v-text-field
                          slot="input"
                          v-model="item.late"
                          label="Опоздание"
                          single-line
                          v-mask="'##'"
                        ></v-text-field>
                      </v-edit-dialog>
                    </td>
                    <td>
                      <v-icon small v-if="!item.comment" class='client-edit-icon'>edit</v-icon>
                      <v-edit-dialog
                        :return-value.sync="item.comment"
                        lazy
                      > {{ item.comment }}
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
            </v-layout>
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-layout>
</template>


<script>
import { DialogMixin } from '@/mixins'
import { LESSON_STATUS, MODEL_DEFAULTS, API_URL } from '@/components/Lesson'
import AddClientDialog from './AddClientDialog'

export default {
  mixins: [ DialogMixin ],

  components: {  },
  
  data() {
    return {
      API_URL,
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
      client.entry_id = this.item.entry_id
      this.item.clientLessons.push(client)
    },

    conduct() {
      this.conducting = true
      axios.post(apiUrl(API_URL, 'conduct', this.item.id), {clients: this.item.groupClients}).then(r => {
        this.conducting = false
        this.item = r.data
      })
    },
  },

  computed: {
    clientLessons() {
      this.recompute_client_lessons
      return this.item.clientLessons.filter(e => e.to_be_deleted !== true)
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
  & .client-edit-icon {
    position: absolute;
    top: 15px;
  }
}
</style>