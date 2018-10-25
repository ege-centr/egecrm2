<template>
  <v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="1200px">
      <v-card>
        <v-card-title>
          <span class="headline">{{ item.id ? 'Редактирование' : 'Добавление' }} договора</span>
        </v-card-title>
        <v-card-text>
          <v-container grid-list-xl class="pa-0 ma-0">
            <v-layout>
              <v-flex md7>
                <v-layout wrap align-center v-for='subject in $store.state.data.subjects' :key='subject.id'>
                  <v-flex md5 py-0 @click='toggleSubject(subject)'>
                    <v-switch
                      :label="subject.name"
                      :color="getSubjectColor(subject)"
                      :input-value='findSubject(subject)'
                      hide-details
                    ></v-switch>
                  </v-flex>
                  <v-flex md7>
                    <v-layout align-center v-if='findSubject(subject)'>
                      <v-flex class='py-0 f-1'>
                        <v-text-field class='pa-0 ma-0' v-model="findSubject(subject).lessons" placeholder="уроков" hide-details></v-text-field>
                      </v-flex>
                      <v-flex class='py-0 f-1'>
                        <v-text-field class='pa-0 ma-0' v-model="findSubject(subject).lessons_planned" placeholder="программа" hide-details></v-text-field>
                      </v-flex>
                      <v-flex class='py-0 f-1'>
                        <v-btn icon @click="toggleEnum(subject)" class='ma-0'>
                          <v-icon>cached</v-icon>
                        </v-btn>
                      </v-flex>
                    </v-layout>
                  </v-flex>
                </v-layout>
              </v-flex>
              <v-flex md6>
                <div>
                  <v-select clearable
                    v-model="item.grade"
                    :items="$store.state.data.grades"
                    item-value='id'
                    item-text='title'
                    label="Класс"
                  ></v-select>
                </div>
                <div>
                  <v-select clearable
                    v-model="item.year"
                    :items="$store.state.data.years"
                    label="Год"
                  ></v-select>
                </div>
                <div>
                  <v-text-field v-model="item.sum" label="Cумма"></v-text-field>
                </div>
                <div>
                  <v-select clearable
                    v-model="item.discount"
                    :items="discounts"
                    label="Скидка"
                  ></v-select>
                </div>
                <div>
                  <v-menu
                    ref="date"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    :return-value.sync="item.date"
                    lazy
                    transition="scale-transition"
                    offset-y
                    full-width
                    min-width="290px"
                  >
                    <v-text-field
                      slot="activator"
                      v-model="item.date"
                      label="Дата"
                      prepend-icon="event"
                      readonly
                    ></v-text-field>
                    <v-date-picker
                      v-model="item.date"
                      @input="$refs.date.save(item.date)">
                    </v-date-picker>
                  </v-menu>
                </div>
                <v-subheader class='pa-0'>Платежи</v-subheader>
                <div v-for='(payment, index) in item.payments'>
                  <v-layout>
                    <v-flex>
                      <v-text-field v-model="payment.sum" label="Cумма"></v-text-field>
                    </v-flex>
                    <v-flex>
                      <v-text-field v-model="payment.lessons" label="Предметов"></v-text-field>
                    </v-flex>
                    <v-flex>
                      <v-menu
                        ref="paymentDate"
                        :close-on-content-click="false"
                        :nudge-right="40"
                        :return-value.sync="payment.date"
                        lazy
                        transition="scale-transition"
                        offset-y
                        full-width
                        min-width="290px"
                      >
                        <v-text-field
                          slot="activator"
                          v-model="payment.date"
                          label="Дата"
                          prepend-icon="event"
                          readonly
                        ></v-text-field>
                        <v-date-picker
                          v-model="payment.date"
                          @input="$refs.paymentDate[index].save(payment.date)">
                        </v-date-picker>
                      </v-menu>
                    </v-flex>
                  </v-layout>
                </div>
                <div>
                  <v-btn color='blue white--text darken-1' small class='ma-0' @click='addPayment'>
                    <v-icon class="mr-1">add</v-icon>
                    добавить платеж
                  </v-btn>
                </div>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" flat @click.native="dialog = false">Отмена</v-btn>
          <v-btn color="blue darken-1" flat @click.native="save">{{ item.id ? 'Сохранить' : 'Добавить' }}</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-layout>
</template>

<script>

import { discounts, subject_defaults, subject_statuses } from './data'

export default {
  props: ['item'],
  data() {
    return {
      dialog: false,
      loading: false,
      discounts,
      subject_statuses
    }
  },
  methods: {
    addPayment() {
      this.item.payments.push({})
    },
    toggleSubject(subject) {
      const index = this.item.subjects.findIndex(e => e.subject_id == subject.id)
      if (index === -1) {
        this.item.subjects.push({
          subject_id: subject.id,
          ...subject_defaults
        })
      } else {
        this.item.subjects.splice(index, 1)
      }
    },
    findSubject(subject) {
      return this.item.subjects.find(e => e.subject_id == subject.id)
    },
    toggleEnum(subject) {
      const index = this.item.subjects.findIndex(e => e.subject_id == subject.id)
      status = subject_statuses.indexOf(this.item.subjects[index].status)
      status++
      if (status >= subject_statuses.length) {
        status = 0
      }
      Vue.set(this.item.subjects, index, {...this.item.subjects[index], status: subject_statuses[status]})
    },
    getSubjectColor(s) {
      const subject = this.findSubject(s)
      if (subject) {
        switch(subject.status) {
          case subject_statuses[2]: return 'error'
          case subject_statuses[1]: return 'orange'
        }
      }
      return 'success'
    },
    save() {
      this.$emit('saved', this.item)
      this.dialog = false
    }
  }
}
</script>
