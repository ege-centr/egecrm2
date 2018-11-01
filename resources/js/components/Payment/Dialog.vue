<template lang="html">
  <v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="1200px">
      <v-card v-if='item !== null'>
        <v-card-title>
          <span class="headline">{{ item.id ? 'Редактирование' : 'Добавление' }} платежа</span>
        </v-card-title>
        <v-card-text>
          <v-container grid-list-xl class="pa-0 ma-0">
            <v-layout>
              <v-flex md3>
                <v-select clearable
                  v-model="item.method"
                  :items="enums.methods"
                  label="Cпособ оплаты"
                ></v-select>
              </v-flex>
              <v-flex md3>
                <v-select clearable
                  v-model="item.type"
                  :items="enums.types"
                  label="Тип"
                ></v-select>
              </v-flex>
              <v-flex md3>
                <v-select clearable
                  v-model="item.category"
                  :items="enums.categories"
                  label="Категория"
                ></v-select>
              </v-flex>
              <v-flex md3>
                <v-select clearable
                  v-model="item.year"
                  :items="$store.state.data.years"
                  label="Год"
                ></v-select>
              </v-flex>
            </v-layout>
            <v-layout>
              <v-flex md3>
                <v-text-field hide-details v-model='item.sum' label='Сумма'></v-text-field>
              </v-flex>
              <v-flex md3>
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

import { url, enums, model_defaults } from './data'

export default {
  data() {
    return {
      dialog: false,
      saving: false,
      destroying: false,
      item: null,
      enums
    }
  },
  methods: {
    open(item) {
      if (item === undefined) {
        this.item = model_defaults
      } else {
        this.item = clone(item)
      }
      this.dialog = true
    },
    save() {
      this.$emit('saved', this.item)
      this.dialog = false
    }
  }
}
</script>
