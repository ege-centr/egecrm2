<template lang="html">
  <div>
    <Loader v-if='loading' />
    <div class='mb-3 flex-items'>
      <YearTabs :selected-year.sync='selectedYear' />
      <v-spacer></v-spacer>
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
                        <v-text-field hide-details v-model='recommendedPriceDialogItem.price' label='Цена' v-mask="'####'" />
                      </div>
                    </div>
                  </v-flex>
                </v-layout>
              </v-container>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" flat @click.native="recommendedPricesDialog = false">Отмена</v-btn>
              <v-btn color="blue darken-1" flat @click.native='storeOrUpdateRecommendedPrice' :loading='saving'>Сохранить</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-layout>


      <div class='headline mb-4'>
        Рекомендованные цены
      </div>

      <v-card>
        <v-card-text class='pa-0'>
          <v-container grid-list-xl class="pa-0 ma-0" fluid>
            <v-layout>
              <v-flex md12 class='py-0'>
                <v-data-table hide-headers hide-actions :items='currentYearRecommendedPrices'>
                  <template slot='items' slot-scope="{ item }">
                    <tr>
                      <td width='300'>
                        {{ getData('grades', item.grade_id).title }}
                      </td>
                      <td width='300'>
                        {{ item.price }} руб.
                      </td>
                      <td class='text-md-right'>
                        <v-btn flat icon color="black" class='ma-0' @click='editRecommendedPrice(item)'>
                          <v-icon>more_horiz</v-icon>
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


      <div class='mb-4 mt-5 flex-items align-center' style='justify-content: space-between'>
        <div class='headline'>Праздники и экзамены</div>
        <div v-if='currentYearItems.length > 0'>
          <AddBtn animated @click.native='add' />
        </div>
      </div>
      
      <v-card>
        <v-card-text class='pa-0'>
          <v-container grid-list-xl class="pa-0 ma-0" fluid>
            <v-layout>
              <v-flex md12 class='py-0'>
                <v-data-table hide-actions hide-headers :items='currentYearItems' :paginate.sync="sortingOptions" v-if='currentYearItems.length > 0'>
                  <template slot='items' slot-scope="{ item }">
                    <tr>
                      <td width='300'>
                        {{ item.date | date }}
                      </td>
                      <td width='300'>
                        {{ types.find(e => e.value === item.type).text }}
                      </td>
                      <td width='300'>
                        <span v-if="item.type === TYPE_EXAM">
                          {{ getData('subjects', item.subject_id).three_letters }}–{{ getData('grades', item.grade_id).short }}
                        </span>
                      </td>
                      <td class='text-md-right'>
                        <v-btn flat icon color="black" class='ma-0' @click='edit(item)'>
                          <v-icon>more_horiz</v-icon>
                        </v-btn>
                      </td>
                    </tr>
                  </template>
                </v-data-table>
                <NoData
                  v-else
                  transparent
                  :height='300'
                  :add='add'
                />
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
import YearTabs from '@/components/UI/YearTabs'

export default {
  components: { Calendar, DatePicker, DataSelect, YearTabs },

  data() {
    return {
      TYPE_EXAM,
      TYPE_VACATION,
      type: TYPE_EXAM,
      types: [
        { text: 'экзамен', value: TYPE_EXAM },
        { text: 'праздник', value: TYPE_VACATION },
      ],
      selectedYear: null,
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
      },
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
        year: this.selectedYear,
      },
      this.dialog = true
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
      const index = this.recommendedPrices.findIndex(e => e.id === this.recommendedPriceDialogItem.id)
      this.recommendedPrices.splice(index, 1, this.recommendedPriceDialogItem)
      await Settings.set(key, this.recommendedPrices, true)
      this.saving = false
      this.recommendedPricesDialog = false
    },
  },

  computed: {
    currentYearItems() {
      return this.items.filter(e => e.year == this.selectedYear)
    },

    currentYearRecommendedPrices() {
      return this.recommendedPrices.filter(e => e.year == this.selectedYear)
    }
  }
}
</script>
