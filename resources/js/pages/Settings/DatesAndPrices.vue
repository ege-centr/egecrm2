<template lang="html">
  <div>

    <Loader v-if='loading' />
    <div class='mb-3 flex-items'>
      <div>
        <v-chip v-for="year in $store.state.data.years" class='pointer ml-0 mr-3'
        :class="{'primary white--text': year.id == selected_year}"
        @click='selected_year = year.id'
        :key='year.id'>{{ year.title }}</v-chip>
      </div>
      <v-spacer></v-spacer>
      <!-- <div>
        <v-chip class='pointer ml-0 mr-3'
        :class="{'primary white--text': type === TYPE_EXAM}"
        @click='type = TYPE_EXAM'>
          экзамены
        </v-chip>
        <v-chip class='pointer ma-0'
        :class="{'primary white--text': type === TYPE_VACATION}"
        @click='type = TYPE_VACATION'>праздники</v-chip>
      </div> -->

    </div>
    <div v-if='items !== null && recommendedPrices !== null'>
      <!-- Праздники и экзамены -->
      <v-layout row justify-center>
        <v-dialog v-model="dialog" persistent max-width="330px" v-if="dialog_item !== null">
          <v-card>
            <v-card-text>
              <v-container class="pa-0 ma-0" fluid>
                <v-layout wrap>
                  <v-flex md12>
                    <div class='vertical-inputs'>
                      <div class='vertical-inputs__input'>
                        <v-select hide-details
                          v-model="dialog_item.type"
                          :items="types"
                          label="Тип"
                        ></v-select>
                      </div>
                      <div class='vertical-inputs__input'>
                        <DatePicker label="Дата" v-model="dialog_item.date" />
                      </div>
                      <div class='vertical-inputs__input' v-show='dialog_item.type === TYPE_EXAM'>
                        <DataSelect v-model='dialog_item.subject_id' type='subjects' />
                      </div>
                      <div class='vertical-inputs__input' v-show='dialog_item.type === TYPE_EXAM'>
                        <DataSelect v-model='dialog_item.grade_id' type='grades' />
                      </div>
                    </div>
                  </v-flex>
                </v-layout>
              </v-container>
            </v-card-text>
            <v-card-actions>
              <v-btn color="red darken-1" flat @click.native="destroy" v-show='dialog_item.id' :loading='destroying'>Удалить</v-btn>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" flat @click.native="dialog = false">Отмена</v-btn>
              <v-btn color="blue darken-1" flat @click.native='storeOrUpdate' :loading='saving'>{{ dialog_item.id ? 'Сохранить' : 'Добавить' }}</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-layout>

      <!-- Праздники и экзамены -->
      <v-layout row justify-center>
        <v-dialog v-model="recommendedPricesDialog" persistent max-width="330px" v-if="recommendedPriceDialogItem !== null">
          <v-card>
            <v-card-text>
              <v-container class="pa-0 ma-0" fluid>
                <v-layout wrap>
                  <v-flex md12>
                    <div class='vertical-inputs'>
                      <div class='vertical-inputs__input'>
                        <DataSelect v-model='recommendedPriceDialogItem.grade_id' type='grades' />
                      </div>
                    </div>
                    <div class='vertical-inputs'>
                      <div class='vertical-inputs__input'>
                        <v-text-field hide-details v-model='recommendedPriceDialogItem.price' label='Цена' v-mask="'####'" />
                      </div>
                    </div>
                  </v-flex>
                </v-layout>
              </v-container>
            </v-card-text>
            <v-card-actions>
              <v-btn color="red darken-1" flat @click.native="destroyRecommendedPrice" v-show='recommendedPriceDialogItem.id' :loading='destroying'>Удалить</v-btn>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" flat @click.native="recommendedPricesDialog = false">Отмена</v-btn>
              <v-btn color="blue darken-1" flat @click.native='storeOrUpdateRecommendedPrice' :loading='saving'>{{ recommendedPriceDialogItem.id ? 'Сохранить' : 'Добавить' }}</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-layout>


      <v-card>
        <v-card-text>
          <v-container grid-list-xl class="pa-0 ma-0" fluid>
            <v-layout>
              <v-flex md5>
                <div class='headline'>
                  Рекомендованные цены
                </div>
                
                <data-table no-elevation :items='currentYearRecommendedPrices'>
                  <tr slot-scope='{ item }'>
                    <td>
                      {{ getData('grades', item.grade_id).title }}
                    </td>
                    <td>
                      {{ item.price }} руб.
                    </td>
                    <td class='text-md-right'>
                      <v-btn flat icon color="black" class='ma-0' @click='editRecommendedPrice(item)'>
                        <v-icon>more_horiz</v-icon>
                      </v-btn>
                    </td>
                  </tr>
                </data-table>

                <AddBtn class='ma-0 mr-3 mt-3' @click.native='addRecommendedPrice' />
              </v-flex>
              <v-spacer></v-spacer>
              <v-flex md6>
                <div class='headline'>
                  Праздники и экзамены
                </div>
                <v-data-table hide-actions hide-headers :items='current_year_items' :paginate.sync="sortingOptions" class='mt-3'>
                  <template slot='items' slot-scope="{ item }">
                    <td>
                      {{ item.date | date }}
                    </td>
                    <td>
                      {{ types.find(e => e.value === item.type).text }}
                    </td>
                    <td>
                      <span v-if="item.type === TYPE_EXAM">{{ getData('subjects', item.subject_id).three_letters }}–{{ item.grade_id }}</span>
                    </td>
                    <td class='text-md-right'>
                      <v-btn flat icon color="black" class='ma-0' @click='edit(item)'>
                        <v-icon>more_horiz</v-icon>
                      </v-btn>
                    </td>
                  </template>
                  <template slot='no-data'>
                    <NoData />
                  </template>
                </v-data-table>
                <div class='mt-3'>
                  <AddBtn class='ma-0 mr-3' @click.native='add' />
                </div>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
      </v-card>
    </div>


    <!-- <div class='text-md-center mt-5'>
      <v-btn @click='save' :loading='saving'>сохранить</v-btn>
    </div> -->
  </div>
</template>

<script>

const API_URL = 'special-dates'
const TYPE_EXAM = 'exam'
const TYPE_VACATION = 'vacation'
const key = 'recommended-prices'

import Calendar from '@/components/Calendar/Calendar'
import { DatePicker, DataSelect } from '@/components/UI'
import Settings from '@/other/settings'

export default {
  components: { Calendar, DatePicker, DataSelect },

  data() {
    return {
      TYPE_EXAM,
      TYPE_VACATION,
      // TODO: this.$store.state.data.years.slice(-1).value
      selected_year: 2018,
      type: TYPE_EXAM,
      types: [
        { text: 'экзамен', value: TYPE_EXAM },
        { text: 'праздник', value: TYPE_VACATION },
      ],
      items: null,
      loading: true,
      saving: false,
      destroying: false,
      dialog: false,
      recommendedPricesDialog: false,
      editing_item_index: null,
      dialog_item: null,
      recommendedPriceDialogItem: null,
      recommendedPrices: null,
      sortingOptions: {
        rowsPerPage: -1,
        sortBy: 'date'
      }
    }
  },

  created() {
    this.loadData()
  },

  methods: {
    loadData() {
      axios.get(apiUrl(API_URL)).then(r => {
        this.items = r.data
        this.loading = false
      })
      Settings.get(key, true).then(r => {
        this.recommendedPrices = r.data || []
        this.loading = false
      })
    },

    add() {
      this.dialog_item = {
        type: TYPE_EXAM,
        year: this.selected_year,
      },
      this.dialog = true
    },

    addRecommendedPrice() {
      this.recommendedPriceDialogItem = {
        year: this.selected_year,
      }
      this.recommendedPricesDialog = true
    },

    edit(item) {
      this.dialog_item = clone(item)
      this.dialog = true
    },

    editRecommendedPrice(item) {
      this.recommendedPriceDialogItem = clone(item)
      this.recommendedPricesDialog = true
    },

    destroy() {
      this.destroying = true
      axios.delete(apiUrl(API_URL, this.dialog_item.id)).then(r => {
        this.loadData()
        this.dialog = false
        this.destroying = false
      })
    },

    storeOrUpdate() {
      this.saving = true
      if (this.dialog_item.hasOwnProperty('id')) {
        axios.put(apiUrl(API_URL, this.dialog_item.id), this.dialog_item).then(r => {
          this.loadData()
          this.dialog = false
          this.saving = false
        })
      } else {
        axios.post(apiUrl(API_URL), this.dialog_item).then(r => {
          this.items.push(r.data)
          this.dialog = false
          this.saving = false
        })
      }
    },

    async storeOrUpdateRecommendedPrice() {
      this.saving = true
      if (this.recommendedPriceDialogItem.hasOwnProperty('id')) {
        const index = this.recommendedPrices.findIndex(e => e.id === this.recommendedPriceDialogItem.id)
        this.recommendedPrices.splice(index, 1, this.recommendedPriceDialogItem)
      } else {
        this.recommendedPriceDialogItem.id = uniqid()
        this.recommendedPrices.push(this.recommendedPriceDialogItem)
      }
      await Settings.set(key, this.recommendedPrices, true)
      this.saving = false
      this.recommendedPricesDialog = false
    },

    async destroyRecommendedPrice() {
      this.destroying = true
      const index = this.recommendedPrices.findIndex(e => e.id === this.recommendedPriceDialogItem.id)
      this.recommendedPrices.splice(index, 1)
      await Settings.set(key, this.recommendedPrices, true)
      this.destroying = false
      this.recommendedPricesDialog = false
    }
  },

  computed: {
    current_year_items() {
      return this.items.filter(e => e.year == this.selected_year)
    },

    currentYearRecommendedPrices() {
      return this.recommendedPrices.filter(e => e.year == this.selected_year)
    }
  }
}
</script>
