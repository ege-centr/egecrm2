<template lang="html">
  <v-layout row justify-center>
    <v-dialog v-model="dialog" persistent max-width="800px">
      <v-card v-if='item !== null'>
        <v-card-title>
          <span class="headline">{{ item.id ? 'Редактирование' : 'Добавление' }} платежа</span>
        </v-card-title>
        <v-card-text>
          <v-container grid-list-xl class="pa-0 ma-0">
            <v-layout>
              <v-flex md12>
                <div class='vertical-inputs'>
                  <div class='vertical-inputs__input'>
                     <v-select hide-details
                        v-model="item.method"
                        :items="withNullOption(ENUMS.methods)"
                        label="Cпособ оплаты"
                      ></v-select>
                  </div>
                  <div class='vertical-inputs__input'>
                    <v-select hide-details
                      v-model="item.type"
                      :items="withNullOption(ENUMS.types)"
                      label="Тип"
                    ></v-select>
                  </div>
                  <div class='vertical-inputs__input'>
                    <v-select hide-details
                      v-model="item.category"
                      :items="withNullOption(ENUMS.categories)"
                      label="Категория"
                    ></v-select>
                  </div>
                  <div class='vertical-inputs__input'>
                    <v-select hide-details
                      v-model="item.year"
                      :items="withNullOption($store.state.data.years)"
                      label="Год"
                    ></v-select>
                  </div>
                  <div class='vertical-inputs__input'>
                    <v-text-field hide-details v-model='item.sum' label='Сумма'></v-text-field>
                  </div>
                  <div class='vertical-inputs__input'>
                    <DatePicker label="Дата" :date="item.date" />
                  </div>
                </div>
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
import { DatePicker } from '@/components/UI'

export default {
  props: {
    className: {
      type: String
    },
    entityId: {
      type: Number
    }
  },

  components: { DatePicker },

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
