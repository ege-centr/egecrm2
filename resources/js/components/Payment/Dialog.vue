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
                  :items="ENUMS.methods"
                  label="Cпособ оплаты"
                ></v-select>
              </v-flex>
              <v-flex md3>
                <v-select clearable
                  v-model="item.type"
                  :items="ENUMS.types"
                  label="Тип"
                ></v-select>
              </v-flex>
              <v-flex md3>
                <v-select clearable
                  v-model="item.category"
                  :items="ENUMS.categories"
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
          <v-btn color="blue darken-1" flat @click.native="storeOrUpdate" :loading='saving'>{{ item.id ? 'Сохранить' : 'Добавить' }}</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-layout>
</template>

<script>

import { API_URL, ENUMS, MODEL_DEFAULTS } from './data'

export default {
  props: {
    className: {
      type: String
    },
    entityId: {
      type: Number
    }
  },

  data() {
    return {
      ENUMS,
      dialog: false,
      saving: false,
      item: null,
    }
  },

  methods: {
    open(item) {
      if (item === null) {
        this.item = {
          class: this.className,
          entity_id: this.entityId,
          ...MODEL_DEFAULTS
        }
      } else {
        this.item = clone(item)
      }
      this.dialog = true
    },
    async storeOrUpdate() {
      this.saving = true
      if (this.item.id) {
        await axios.put(apiUrl(`${API_URL}/${this.item.id}`), this.item).then(r => {
          this.item = r.data
          this.$emit('updated', this.item)
        })
      } else {
        await axios.post(apiUrl(API_URL), this.item).then(r => {
          this.item = r.data
          this.$emit('stored', this.item)
        })
      }
      this.dialog = false
      setTimeout(() => this.saving = false, 300)
    }
  }
}
</script>
