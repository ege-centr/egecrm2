<template>
  <div>
    <Print ref='Print' :params="{type: 'contract', option: printOption}" />
    <display-list
      :dialog-component='Dialog'
      :items='items'
      :add-btn='show.addBtn'
      :model-defaults='modelDefaults'
      add-btn-label='добавить договор'
      ref='DisplayList'
      @updated="$emit('updated')"
    >
      <template slot='item' slot-scope='{ item }'>
        <tr>
          <td v-if='show.client'>
            <router-link :to="{name: 'ClientShow', params: {id: item.client.id}}">
              {{ item.client.default_name }}
            </router-link>
          </td>
          <td>
            <span v-if='item.id'>
              №{{ item.number }} версия {{ item.version }}
            </span>
          </td>
          <td>
            от {{ item.date | date }}
          </td>
          <td>
            {{ item.sum }} руб.
          </td>
          <td>
            <span v-if='item.payment_count > 0'>
              {{ item.payment_count }} платежей
            </span>
            <span class='grey--text' v-else>
              платежей нет
            </span>
          </td>
          <td>
            <span v-if='item.grade_id'>
              {{ getData('grades', item.grade_id).title }}
            </span>
          </td>
          <td v-if='show.year'>
            {{ getData('years', item.year).title }}
          </td>
          <td>
            <span v-if='item.discount > 0'>
              скидка {{ item.discount }}%
            </span>
            <span v-else class='grey--text'>
              скидок нет
            </span>
          </td>
          <td>
            <span v-for='(subject, index) in item.subjects' :class="{
              'error--text': subject.status == SUBJECT_STATUS.terminated,
              'orange--text': subject.status == SUBJECT_STATUS.to_be_terminated
            }" :key='subject.id'
              v-show='index < 4'
            >
              {{ getData('subjects', subject.subject_id).three_letters }}
              <span class='grey--text'>{{ subject.lessons }}</span>
            </span>
            <span class='pointer'
              v-if='item.subjects.length > 4' 
              small>...
            </span>
          </td>
          <td class='text-md-right'>
            <v-menu left>
              <v-btn slot='activator' flat icon color="black" class='ma-0'>
                <v-icon>more_horiz</v-icon>
              </v-btn>
              <v-list dense>
                <v-list-tile @click='$refs.DisplayList.edit(item.id)'>
                    <v-list-tile-action>
                      <v-icon>edit</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                      <v-list-tile-title>Редактировать</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile @click='$refs.DisplayList.Dialog.addVersion(item.id)'>
                    <v-list-tile-action>
                      <v-icon>file_copy</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                      <v-list-tile-title>Добавить версию</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile @click="print(item, 'contract')">
                    <v-list-tile-action>
                      <v-icon>print</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                      <v-list-tile-title>Договор</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile @click="print(item, 'additional-agreement')">
                    <v-list-tile-action>
                      <v-icon>print</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                      <v-list-tile-title>Допсоглашение</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile @click="print(item, 'act')">
                    <v-list-tile-action>
                      <v-icon>print</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                      <v-list-tile-title>Акт оказанных услуг</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile @click="print(item, 'termination-agreement')">
                    <v-list-tile-action>
                      <v-icon>print</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                      <v-list-tile-title>Соглашение о расторжении</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
              </v-list>
            </v-menu>
          </td>
        </tr>
      </template>
    </display-list>
  </div>
</template>

<script>

import Dialog from './Dialog'
import DisplayList from '@/components/UI/DisplayList'
import Print from '@/components/Print'
import DisplayOptions from '@/mixins/DisplayOptions'
import { SUBJECT_STATUS } from './'

export default {
  props: {
    items: {
      type: Array,
      required: true
    },

    clientId: {
      type: Number,
      required: false,
    }
  },

  components: { DisplayList, Print },

  mixins: [ DisplayOptions ],

  data() {
    return {
      printOption: 'contract',
      SUBJECT_STATUS,
      Dialog,
      defaultDisplayOptions: {
        client: false,
        year: false,
        addBtn: true,
        payments: true,
        year: false,
      },
    }
  },

  methods: {
    print(item, printOption) {
      this.printOption = printOption
      Vue.nextTick(() => this.$refs.Print.open({id: item.id}))
    }
  },

  computed: {
    modelDefaults() {
      if (this.clientId) {
        return {client_id: this.clientId}
      }
      return {}
    }
  }
}
</script>