<template>
  <v-layout row justify-center v-if='item !== null'>
    <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay>
      <v-card>
       <v-toolbar dark color="primary">
          <v-btn icon dark @click.native="dialog = false">
            <v-icon>close</v-icon>
          </v-btn>
          <v-toolbar-title>{{ edit_mode ? 'Редактирование' : 'Добавление' }} договора</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items>
            <v-btn dark flat v-if='edit_mode' @click.native="destroy" :loading='destroying'>Удалить</v-btn>
            <v-btn dark flat @click.native="storeOrUpdate" :loading='saving'>{{ edit_mode ? 'Сохранить' : 'Добавить' }}</v-btn>
          </v-toolbar-items>
        </v-toolbar>
        <v-card-text>
          <Loader v-if='loading' class='loader-wrapper_fullscreen-dialog' />
          <v-container v-else grid-list-xl class="pa-0 ma-0">
            <v-layout class='mb-3'>
              <v-flex md7>
                <v-layout wrap align-center v-for='subject in $store.state.data.subjects' :key='subject.id'>
                  <v-flex style='max-width: 150px'>
                    <v-slider min='0' max='3' @change='subjectStatusHandler(subject.id)' 
                      :color="getSubjectColor(subject)"
                      :track-color="getSubjectColor(subject)"
                      class='ma-0'
                      :label="getData('subjects', subject.id).three_letters"
                      v-model="slider[subject.id]"
                      hide-details
                    ></v-slider>
                  </v-flex>
                  <v-flex class='ml-4' style='max-width: 246px'>
                    <v-layout align-center v-if='findSubject(subject)'>
                      <v-flex class='py-0 f-1'>
                        <v-text-field class='pa-0 ma-0' v-model="findSubject(subject).lessons" label="уроков" hide-details></v-text-field>
                      </v-flex>
                      <v-flex class='py-0 f-1'>
                        <v-text-field class='pa-0 ma-0' v-model="findSubject(subject).lessons_planned" label="программа" hide-details></v-text-field>
                      </v-flex>
                    </v-layout>
                  </v-flex>
                </v-layout>
              </v-flex>
            </v-layout>
            <v-layout>
              <v-flex md12>
                <div class='vertical-inputs mb-5'>
                  <div class='vertical-inputs__input'>
                    <GradeAndYear :item='item' label-type='GRADE_AND_YEAR' />
                  </div>
                  <div class='vertical-inputs__input'>
                    <v-text-field v-model="item.sum" label="Cумма" hide-details></v-text-field>
                  </div>
                  <div class='vertical-inputs__input'>
                    <v-select
                      v-model="item.discount"
                      :items="withNullOption(DISCOUNTS)"
                      label="Скидка"
                    ></v-select>
                  </div>
                  <div class='vertical-inputs__input'>
                    <DatePicker label="Дата" :date='item.date' />
                  </div>
                </div>


                
                
                <!-- <v-subheader class='pa-0'>Платежи</v-subheader> -->
                <div class='contract-payment' v-for='(payment, index) in item.payments' :key='index'>
                  <v-layout class='align-center'>
                    <v-flex>
                      <v-text-field v-model="payment.sum" label="Cумма"></v-text-field>
                    </v-flex>
                    <v-flex>
                      <v-text-field v-model="payment.lessons" label="Предметов"></v-text-field>
                    </v-flex>
                    <v-flex>
                      <DatePicker label="Дата" :date='payment.date' />
                    </v-flex>
                    <v-btn flat icon color="red" class='ma-0 mr-3' @click='item.payments.splice(index, 1)'>
                      <v-icon>remove</v-icon>
                    </v-btn>
                  </v-layout>
                </div>
                <div>
                  <v-btn fab dark small color="red" @click='addPayment'>
                    <v-icon dark>add</v-icon>
                  </v-btn>
                </div>
              </v-flex>
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
  SUBJECT_DEFAULTS,
  SUBJECT_STATUS_TO_BE_TERMINATED,
  SUBJECT_STATUS_TERMINATED,
  SUBJECT_STATUS_ACTIVE,
} from './'

import GradeAndYear from '@/components/GradeAndYear'
import { DatePicker } from '@/components/UI'

export default {
  components: { GradeAndYear, DatePicker },

  data() {
    return {
      DISCOUNTS,
      SUBJECT_STATUSES,
      dialog: false,
      saving: false,
      item: null,
      slider: {},
      edit_mode: false,
      loading: true,
      edit_mode: true,
      destroying: false,
    }
  },

  methods: {
    subjectStatusHandler(subject_id) {
      const status = this.slider[subject_id]
      let index = this.item.subjects.findIndex(e => e.subject_id == subject_id)
      if (status > 0) {
        if (index === -1) {
          index = this.item.subjects.push({
            subject_id: subject_id,
            ...SUBJECT_DEFAULTS
          }) - 1
        }
        switch (status) {
          case 1: {
            this.item.subjects[index].status = SUBJECT_STATUS_TERMINATED
            break
          }
          case 2: {
            this.item.subjects[index].status = SUBJECT_STATUS_TO_BE_TERMINATED
            break
          }
          default: {
            this.item.subjects[index].status = SUBJECT_STATUS_ACTIVE
          }
        }
      } else {
        this.item.subjects.splice(index, 1)
      }
    },
    
    statusToNumber(subject) {
      switch (subject.status) {
        case SUBJECT_STATUS_TERMINATED: return 1
        case SUBJECT_STATUS_TO_BE_TERMINATED: return 3
        default: return 3
      }
    },

    open(item_id = null, defaults = {}) {
      this.slider = {}
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

    addPayment() {
      this.item.payments.push({})
    },

    findSubject(subject) {
      return this.item.subjects.find(e => e.subject_id == subject.id)
    },

    getSubjectColor(s) {
      const subject = this.findSubject(s)
      if (subject) {
        switch(subject.status) {
          case SUBJECT_STATUS_TERMINATED: return 'error'
          case SUBJECT_STATUS_TO_BE_TERMINATED: return 'orange'
          default: return 'success'
        }
      }
      return 'grey'
    },

    loadData(item_id) {
      this.loading = true
      axios.get(apiUrl(API_URL, item_id)).then(r => {
        this.item = r.data
        this.item.subjects.forEach(subject => {
          this.slider[subject.subject_id] = this.statusToNumber(subject)
        })
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
