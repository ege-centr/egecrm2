<template lang="html">
  <v-layout row justify-center>
    <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay>
      <v-card>
        <v-toolbar dark color="primary">
          <v-toolbar-title>Редактирование свободного времени преподавателя</v-toolbar-title>
          <v-spacer></v-spacer>
          <TitleCredentials :item='item'/>
          <v-toolbar-items>
            <v-btn dark icon v-if='edit_mode' @click.native="destroy" :loading='destroying' class='mr-5'>
              <v-icon>delete</v-icon>
            </v-btn>
            <v-btn dark icon @click.native="saveAll" :loading='saving'>
              <v-icon>save_alt</v-icon>
            </v-btn>
            <v-btn icon dark @click.native="dialog = false">
              <v-icon>close</v-icon>
            </v-btn>
          </v-toolbar-items>
        </v-toolbar>
        <v-card-text>
          <Loader v-if='itemsLoading' class='loader-wrapper_fullscreen-dialog' />
          <v-container grid-list-xl class="pa-0 ma-0" fluid v-else>
            <v-layout>
              <v-flex md4>
                <v-data-table
                  class='relative-table'
                  :headers="[
                    { text: 'День', sortable: false },
                    { text: 'Время от', sortable: false },
                    { text: 'Время до', sortable: false },
                    { text: '', sortable: false },
                  ]"
                  hide-actions
                  :items='items'
                >
                  <template v-slot:items="{ item, index }">
                    <tr>
                      <td>
                        <v-menu
                          min-width="200px"
                          lazy
                          transition="scale-transition"
                        >
                          <div slot='activator'>
                            {{ DAY_LABELS[item.weekday] }}
                          </div>
                          <v-list dense>
                            <v-list-tile v-for='day in days' :key='day.value' @click='item.weekday = day.value'>
                                <v-list-tile-content>
                                  <v-list-tile-title>{{ day.text }}</v-list-tile-title>
                                </v-list-tile-content>
                            </v-list-tile>
                          </v-list>
                        </v-menu>
                      </td>
                      <td>
                        <v-edit-dialog
                          :return-value.sync="item.from"
                          lazy
                        > 
                          <v-btn small flat v-if="!item.from" fab class='edit-inside-table'>
                            <v-icon>edit</v-icon>
                          </v-btn>
                          <span v-else>{{ item.from }}</span>
                          <v-text-field
                            slot="input"
                            v-model="item.from"
                            single-line
                            v-mask="'##:##'"
                          ></v-text-field>
                        </v-edit-dialog>
                      </td>
                      <td>
                        <v-edit-dialog
                          :return-value.sync="item.to"
                          lazy
                        > 
                          <v-btn small flat v-if="!item.to" fab class='edit-inside-table'>
                            <v-icon>edit</v-icon>
                          </v-btn>
                          <span v-else>{{ item.to }}</span>
                          <v-text-field
                            slot="input"
                            v-model="item.to"
                            single-line
                            v-mask="'##:##'"
                          ></v-text-field>
                        </v-edit-dialog>
                      </td>
                       <td class='text-sm-right'>
                        <v-btn flat icon color="red" class='ma-0' @click='removeFreetime(index)'>
                          <v-icon>remove</v-icon>
                        </v-btn>
                      </td>
                    </tr>
                  </template>
                  <template v-slot:footer>
                    <tr>
                      <td colspan='3'></td>
                      <td class='text-sm-right'>
                        <v-btn color='primary' flat icon class='ma-0' @click='addFreetime'>
                          <v-icon>add</v-icon>
                        </v-btn>
                      </td>
                    </tr>
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

import { API_URL, MODEL_DEFAULTS } from './'
import { DialogMixin } from '@/mixins'
import { DAY_LABELS } from '@/components/Timeline'

export default {
  props: {
    teacherId: {
      type: Number,
      requried: true,
    }
  },
  
  mixins: [ DialogMixin ],

  data() {
    return {
      API_URL,
      MODEL_DEFAULTS,
      DAY_LABELS,
      itemsLoading: true,
      items: null,
      days: [
        {value: 1, text: 'ПН'},
        {value: 2, text: 'ВТ'},
        {value: 3, text: 'СР'},
        {value: 4, text: 'ЧТ'},
        {value: 5, text: 'ПТ'},
        {value: 6, text: 'СБ'},
        {value: 0, text: 'ВС'},
      ]
    }
  },

  methods: {
    beforeOpen() {
      this.itemsLoading = true
      const params = {
        teacher_id: this.teacherId
      }
      axios.get(apiUrl(API_URL), { params }).then(r => {
        this.items = r.data.data
        if (this.items.length === 0) {
          this.addFreetime()
        }
        this.itemsLoading = false
      })
    },

    removeFreetime(index) {
      this.items.splice(index, 1)
    },

    addFreetime() {
      this.items.push(_.clone(MODEL_DEFAULTS))
    },

    saveAll() {
      this.saving = true
      axios.post(apiUrl(API_URL), {
        items: this.items,
        teacher_id: this.teacherId,
      }).then(r => {
        this.$emit('updated')
        this.saving = false
        this.dialog = false
      })
    }
  }
}
</script>