<template>
  <v-layout row justify-center>
    <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay>
      <v-card>
       <v-toolbar dark color="primary">
          <v-toolbar-title>{{ edit_mode ? 'Редактирование' : 'Добавление' }} договора</v-toolbar-title>
          <v-spacer></v-spacer>
          <TitleCredentials :item='item'/>
          <v-toolbar-items>
            <v-btn dark icon v-if='edit_mode' @click.native="destroy" :loading='destroying' class='mr-5'>
              <v-icon>delete</v-icon>
            </v-btn>
            <v-btn dark icon @click.native="storeOrUpdate" :loading='saving'>
              <v-icon>save_alt</v-icon>
            </v-btn>
            <v-btn icon dark @click.native="dialog = false">
              <v-icon>close</v-icon>
            </v-btn>
          </v-toolbar-items>
        </v-toolbar>
        <v-card-text>
          <Loader v-if='loading' class='loader-wrapper_fullscreen-dialog' />
          <v-container v-else grid-list-xl class="pa-0 ma-0" style='max-width: 100%'>
            <v-layout class='justify-space-between'>
              <!-- ПРЕДМЕТЫ ДОГОВОРА -->
              <v-flex md3>
                <v-data-table 
                  class='relative-table'
                  :headers="[
                    {text: 'Предмет', sortable: false},
                    {text: 'Уроков', sortable: false},
                    {text: 'Уроков по программе', sortable: false},
                  ]"
                  :items='$store.state.data.subjects'
                  hide-actions
                >
                  <template v-slot:items='{ item }'>
                    <tr>
                      <td class='pl-0'>
                        <v-btn class='ma-0' flat small :class="getSubjectColor(item)" @click='toggleSubjectStatus(item.id)'>
                          {{ item.three_letters }}
                        </v-btn>
                      </td>
                      <td>
                        <div v-if='findSubject(item)'>
                          <v-edit-dialog
                            :return-value.sync="findSubject(item).lessons"
                            lazy
                          > 
                            <v-btn small flat v-if="!findSubject(item).lessons" fab class='edit-inside-table'>
                              <v-icon>edit</v-icon>
                            </v-btn>
                            <span v-else>{{ findSubject(item).lessons }}</span>
                            <v-text-field
                              slot="input"
                              v-model="findSubject(item).lessons"
                              single-line
                              v-mask="'##'"
                            ></v-text-field>
                          </v-edit-dialog>
                        </div>
                      </td>
                      <td>
                        <div v-if='findSubject(item)'>
                          <v-edit-dialog
                            :return-value.sync="findSubject(item).lessons_planned"
                            lazy
                          > 
                            <v-btn small flat v-if="!findSubject(item).lessons_planned" fab class='edit-inside-table'>
                              <v-icon>edit</v-icon>
                            </v-btn>
                            <span v-else>{{ findSubject(item).lessons_planned }}</span>
                            <v-text-field
                              slot="input"
                              v-model="findSubject(item).lessons_planned"
                              single-line
                              v-mask="'##'"
                            ></v-text-field>
                          </v-edit-dialog>
                        </div>
                      </td>
                    </tr>
                  </template>
                </v-data-table>
              </v-flex>
              <!-- /ПРЕДМЕТЫ ДОГОВОРА -->


              <!-- ПЛАТЕЖИ -->
              <v-flex md4>
                <v-data-table
                  class='relative-table'
                  :headers="[
                    {text: 'Платеж, руб.', sortable: false, width: 150},
                    {text: 'Уроков', sortable: false, width: 150},
                    {text: 'Дата', sortable: false, width: 250},
                    {text: '', sortable: false},
                  ]"
                  :items='item.payments'
                  hide-actions
                >
                  <template v-slot:items='{ item, index }'>
                    <tr>
                      <td>
                        <v-edit-dialog
                          :return-value.sync="item.sum"
                          lazy
                        > 
                          <v-btn small flat v-if="!item.sum" fab class='edit-inside-table'>
                            <v-icon>edit</v-icon>
                          </v-btn>
                          <span v-else>{{ item.sum }}</span>
                          <v-text-field
                            slot="input"
                            v-model="item.sum"
                            single-line
                            v-mask="'######'"
                          ></v-text-field>
                        </v-edit-dialog>
                      </td>
                      <td>
                        <v-edit-dialog
                          :return-value.sync="item.lessons"
                          lazy
                        > 
                          <v-btn small flat v-if="!item.lessons" fab class='edit-inside-table'>
                            <v-icon>edit</v-icon>
                          </v-btn>
                          <span v-else>{{ item.lessons }}</span>
                          <v-text-field
                            slot="input"
                            v-model="item.lessons"
                            single-line
                            v-mask="'###'"
                          ></v-text-field>
                        </v-edit-dialog>
                      </td>
                      <td>
                        <v-menu
                          :ref="`datepicker-${index}`"
                          :return-value.sync="item.date"
                          min-width="290px"
                          lazy
                          transition="scale-transition"
                        >
                          <div slot='activator'>
                            <v-btn v-if='!item.date' small flat fab class='edit-inside-table'>
                              <v-icon>edit</v-icon>
                            </v-btn>
                            <span v-else>
                              {{ item.date | date }}
                            </span>
                          </div>
                          <v-date-picker no-title
                            locale='ru'
                            v-model="item.date"
                            :first-day-of-week='1'
                            @input="$refs[`datepicker-${index}`].save(item.date)">
                          </v-date-picker>
                        </v-menu>
                      </td>
                      <td class='text-sm-right'>
                        <v-btn flat icon color="red" class='ma-0' @click='removePayment(index)'>
                          <v-icon>remove</v-icon>
                        </v-btn>
                      </td>
                    </tr>
                  </template>
                  <template v-slot:footer>
                    <tr>
                      <td colspan='3'></td>
                      <td class='text-sm-right'>
                        <v-btn color='primary' flat icon class='ma-0' @click='addPayment'>
                          <v-icon>add</v-icon>
                        </v-btn>
                      </td>
                    </tr>
                  </template>
                </v-data-table>
              </v-flex>
              <!-- /ПЛАТЕЖИ -->



              <!-- САМ ДОГОВОР -->
              <v-flex md3>
                <div class='vertical-inputs mb-5'>
                  <div class='vertical-inputs__input'>
                    <DataSelect type='years' v-model="item.year" />
                  </div>
                  <div class='vertical-inputs__input'>
                    <DataSelect type='grades' v-model="item.grade_id" />
                  </div>
                  <div class='vertical-inputs__input'>
                    <v-text-field v-model="item.sum" label="Cумма" 
                      :persistent-hint='true'
                      :hide-details="!recommendedPrice"
                      :hint="recommendedPrice ? `рекомендуемая сумма: ${recommendedPrice}` : '' "></v-text-field>
                  </div>
                  <div class='vertical-inputs__input'>
                      <ClearableSelect v-model='item.discount'
                        label="Скидка"
                        :items='DISCOUNTS' 
                        item-text='names.abbreviation'
                      />
                  </div>
                  <div class='vertical-inputs__input'>
                    <DatePicker label="Дата" v-model='item.date' />
                  </div>
                </div>
              </v-flex>
              <!-- /САМ ДОГОВОР -->

            </v-layout>
          </v-container>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-layout>
</template>

<script>

import {
  API_URL,
  MODEL_DEFAULTS,
  DISCOUNTS,
  SUBJECT_STATUSES,
  SUBJECT_STATUS_LABELS,
  SUBJECT_DEFAULTS,
  SUBJECT_STATUS_TO_BE_TERMINATED,
  SUBJECT_STATUS_TERMINATED,
  SUBJECT_STATUS_ACTIVE,
} from './'

import Settings from '@/other/settings'
import { DatePicker, DataSelect } from '@/components/UI'

export default {
  components: { DataSelect, DatePicker },

  data() {
    return {
      DISCOUNTS,
      SUBJECT_STATUSES,
      SUBJECT_STATUS_LABELS,
      dialog: false,
      saving: false,
      item: null,
      edit_mode: false,
      loading: true,
      edit_mode: true,
      destroying: false,
      recommendedPrices: null,
    }
  },

  created() {
    Settings.get('recommended-prices', true).then(r => this.recommendedPrices = r.data)
  },

  methods: {
    open(item_id = null, defaults = {}) {
      this.dialog = true
      if (item_id !== null) {
        this.edit_mode = true
        this.loadData(item_id)
      } else {
        this.edit_mode = false
        this.item = {...MODEL_DEFAULTS, ...defaults }
        this.loading = false
      }
    },

    async addVersion(item_id) {
      this.dialog = true
      this.edit_mode = false
      await this.loadData(item_id)
      delete this.item.id
    },

    addPayment() {
      this.item.payments.push({})
    },

    removePayment(index) {
      this.item.payments.splice(index, 1)
    },

    findSubject(subject) {
      return this.item.subjects.find(e => e.subject_id == subject.id)
    },

    getSubjectColor(s) {
      const subject = this.findSubject(s)
      if (subject) {
        switch(subject.status) {
          case SUBJECT_STATUS_TERMINATED: return 'error--text'
          case SUBJECT_STATUS_TO_BE_TERMINATED: return 'orange--text'
          default: return 'success--text'
        }
      }
      return 'grey--text'
    },

    toggleSubjectStatus(subject_id) {
      const index = this.item.subjects.findIndex(e => e.subject_id == subject_id)
      if (index === -1) {
        this.item.subjects.push({
          subject_id: subject_id,
          ...SUBJECT_DEFAULTS
        }) - 1
      } else {
        const subject = this.item.subjects[index]
        if (subject.status === SUBJECT_STATUS_ACTIVE) {
          subject.status = SUBJECT_STATUS_TO_BE_TERMINATED
        } else if (subject.status === SUBJECT_STATUS_TO_BE_TERMINATED) {
          subject.status = SUBJECT_STATUS_TERMINATED
        } else if (subject.status === SUBJECT_STATUS_TERMINATED) {
          this.item.subjects.splice(index, 1)
        }
      }
    },

    changeSubject(status, subject_id) {
      let index = this.item.subjects.findIndex(e => e.subject_id == subject_id)
      if (status) {
        if (index === -1) {
          index = this.item.subjects.push({
            subject_id: subject_id,
            ...SUBJECT_DEFAULTS
          }) - 1
        }
        this.item.subjects[index].status = status
      } else {
        if (index !== -1) {
          this.item.subjects.splice(index, 1)
        }
      }
    },

    async loadData(item_id) {
      this.loading = true
      axios.get(apiUrl(API_URL, item_id)).then(r => {
        this.item = r.data
        this.loading = false
      })
    },

    destroy() {
      this.destroying = true
      axios.delete(apiUrl(API_URL, this.item.id)).then(r => {
        this.$emit('updated')
        this.dialog = false
        this.waitForDialogClose(() => this.destroying = false)
      })
    },

    async storeOrUpdate() {
      this.saving = true
      if (this.item.id) {
        await axios.put(apiUrl(API_URL, this.item.id), this.item).then(r => {
          this.item = r.data
          this.$emit('updated', this.item)
        })
      } else {
        await axios.post(apiUrl(API_URL), this.item).then(r => {
          this.item = r.data
          this.$emit('updated', this.item)
        })
      }
      this.dialog = false
      this.waitForDialogClose(() => this.saving = false)
    }
  },

  computed: {
    recommendedPrice() {
      if (this.recommendedPrices !== null && this.item.year && this.item.grade_id) {
        let lesson_count = 0
        this.item.subjects.forEach(subject => {
          if (subject.lessons > 0) {
            lesson_count += Number(subject.lessons)
          }
        })
        const recommendedPrice = this.recommendedPrices.find(e => e.year === this.item.year && e.grade_id === this.item.grade_id)
        if (recommendedPrice !== null) {
          return lesson_count * parseInt(recommendedPrice.price)
        }
      }
      return null
    }
  }
}
</script>

<style lang="scss" scoped>
  $vertical-input-width: 500px;

  // .vertical-inputs__input {
  //   width: $vertical-input-width;
  //    & .v-input {
  //     width: $vertical-input-width;
  //    }
  // }

  .contract-payment {
    width: $vertical-input-width;
  }
</style>