<template>
  <v-layout row justify-center v-if='item !== null'>
    <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay content-class='overflow-hidden'>
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
          <v-container v-else grid-list-xl class="pa-0 ma-0" style='max-width: 100%'>
            <v-layout class='mb-3'>
              <v-flex md6>
                <v-layout wrap align-center v-for='(subject, index) in $store.state.data.subjects' :key='subject.id'>
                  <v-flex style='max-width: 50px'>
                    <span :class="getSubjectColor(subject)">{{ subject.three_letters }}</span>
                  </v-flex>
                  <v-flex class='ml-3' style='max-width: 190px'>
                    <v-select hide-details class='pa-0 ma-0' @change='(e) => changeSubject(e, subject.id)'
                      :items="SUBJECT_STATUS_LABELS"
                      :value="findSubject(subject) ? findSubject(subject).status : undefined"
                      item-text='title'
                      item-value='id'
                      placeholder="Статус"
                      ref='select'
                    >
                      <v-list-tile slot='prepend-item' @click='changeSubject(null, subject.id); $refs.select[index].isMenuActive = false'>
                        <v-list-tile-title class='grey--text'>
                          не установлено
                        </v-list-tile-title>
                      </v-list-tile>
                    </v-select>
                  </v-flex>
                  <v-flex class='ml-3' style='max-width: 120px' v-if='findSubject(subject)'>
                    <v-text-field class='pa-0 ma-0' v-model="findSubject(subject).lessons" label="уроков" hide-details></v-text-field>
                  </v-flex>
                  <v-flex class='ml-3' style='max-width: 120px' v-if='findSubject(subject)'>
                    <v-text-field class='pa-0 ma-0' v-model="findSubject(subject).lessons_planned" label="программа" hide-details></v-text-field>
                  </v-flex>
                </v-layout>
              </v-flex>


              <v-flex md6>
                <div class='vertical-inputs mb-5'>
                  <div class='vertical-inputs__input'>
                    <DataSelect type='years' v-model="item.year" />
                  </div>
                  <div class='vertical-inputs__input'>
                    <DataSelect type='grades' v-model="item.grade_id" />
                  </div>
                  <div class='vertical-inputs__input'>
                    <v-text-field v-model="item.sum" label="Cумма" :hint="'рекомендуемая сумма: ' + getRecommendedPrice()"></v-text-field>
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

                <!-- <v-subheader class='pa-0'>Платежи</v-subheader> -->
                <div class='contract-payment' v-for='(payment, index) in item.payments' :key='index'>
                  <v-layout class='align-center'>
                    <v-flex>
                      <v-text-field hide-details v-model="payment.sum" label="Cумма"></v-text-field>
                    </v-flex>
                    <v-flex>
                      <v-text-field hide-details v-model="payment.lessons" label="Занятий"></v-text-field>
                    </v-flex>
                    <v-flex>
                      <DatePicker label="Дата" v-model='payment.date' />
                    </v-flex>
                    <v-btn flat icon color="red" class='ma-0 mr-3' @click='item.payments.splice(index, 1)'>
                      <v-icon>remove</v-icon>
                    </v-btn>
                  </v-layout>
                </div>
                <div class='mt-3'>
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
      recommended_prices: null,
    }
  },

  created() {
    Settings.get('recommended-prices', true).then(r => this.recommended_prices = r.data)
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
          case SUBJECT_STATUS_TERMINATED: return 'error--text'
          case SUBJECT_STATUS_TO_BE_TERMINATED: return 'orange--text'
          default: return 'success--text'
        }
      }
      return 'grey--text'
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

    loadData(item_id) {
      this.loading = true
      axios.get(apiUrl(API_URL, item_id)).then(r => {
        this.item = r.data
        this.loading = false
      })
    },

    getRecommendedPrice() {
      if (this.recommended_prices !== null && this.item.year && this.item.grade_id) {
        let lesson_count = 0
        this.item.subjects.forEach(subject => {
          lesson_count += subject.lessons
        })
        return lesson_count * parseInt(this.recommended_prices[this.item.year][this.item.grade_id])
      }
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
