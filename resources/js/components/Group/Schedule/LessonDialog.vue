<template>
  <v-layout row justify-center>
    <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay>
      <v-card>
        <v-toolbar dark color="primary">
          <v-toolbar-title>{{ edit_mode ? 'Редактирование' : 'Добавление' }} занятия</v-toolbar-title>
          <v-spacer></v-spacer>
          <TitleCredentials v-if='item && item.status === LESSON_STATUS.CONDUCTED' :item='item' user-field='conductedUser' time-field='conducted_at' />
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
          <v-container grid-list-xl class="pa-0 ma-0" fluid v-else>
            <v-layout>
              <v-flex md12 class='flex-items'>
                <div class='vertical-inputs' style='width: 400px'>
                  <div class='vertical-inputs__input'>
                    <DatePicker v-if='dialog' label="Дата занятия" v-model='item.date' />
                  </div>
                  <div class='vertical-inputs__input'>
                    <v-text-field hide-details v-model='item.time'  label='Время занятия' v-mask="'##:##'"></v-text-field>
                  </div>
                  <div class='vertical-inputs__input'>
                    <v-select hide-details ref='select'
                      :loading='cabinetsLoading'
                      v-model='item.cabinet_id'
                      :items="$store.state.data.cabinets"
                      label="Кабинет"
                      item-value='id'
                    >
                      <template v-slot:item='props'>
                        <span :class="{
                          'grey--text': occupiedCabinetIds.includes(props.item.id)
                        }">
                          {{ props.item.title }}
                        </span>
                      </template>
                      <v-list-tile slot='prepend-item' @click='clearCabinet'>
                        <v-list-tile-title class='grey--text'>
                          не установлено
                        </v-list-tile-title>
                      </v-list-tile>
                    </v-select>
                  </div>
                  <div class='vertical-inputs__input'>
                    <TeacherSelect v-model="item.teacher_id" />
                  </div>
                  <div class='vertical-inputs__input'>
                    <v-text-field hide-details v-model="item.duration" label="Длительность занятия, мин."></v-text-field>
                  </div>
                  <div class='vertical-inputs__input relative' v-if='item.id'>
                    <!-- <DivBlocker v-if="item.status === LESSON_STATUS.PLANNED" /> -->
                    <v-text-field v-model='item.price' label='Цена' hide-details></v-text-field>
                  </div>
                  <div class='vertical-inputs__input relative' v-if='item.id'>
                    <!-- <DivBlocker v-if="item.status === LESSON_STATUS.PLANNED" /> -->
                    <v-text-field v-model='item.bonus' label='Бонус' hide-details></v-text-field>
                  </div>
                  <div class="vertical-inputs__input" v-if="item.status !== LESSON_STATUS.CONDUCTED">
                    <v-switch class='ma-0'
                      label="Отменено"
                      hide-details
                      :input-value="item.status === LESSON_STATUS.CANCELLED"
                      @change='toggleCancelled'
                    ></v-switch>
                  </div>
                  <div class="vertical-inputs__input">
                      <v-switch class='ma-0'
                        label="Незапланировано"
                        hide-details
                        v-model='item.is_unplanned'
                      ></v-switch>
                  </div>
                </div>
                <div v-if='selectedCabinetSchedule !== null && selectedCabinetSchedule.length > 0'>
                  <div class='headline mb-3'>Загрузка кабинета</div>
                  <div v-for='(schedule, weekNumber) in selectedCabinetSchedule' :key='weekNumber' class='mb-1'>
                    <Timeline :items='schedule' :show-dates='true' />
                  </div>
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
import { DialogMixin } from '@/mixins'
import { DatePicker, DataSelect, TeacherSelect } from '@/components/UI'
import { LESSON_STATUS, MODEL_DEFAULTS, API_URL } from '@/components/Lesson'
import Timeline from '@/components/UI/Timeline'

export default {
  mixins: [ DialogMixin ],

  components: { DatePicker, DataSelect, TeacherSelect, Timeline },
  
  data() {
    return {
      API_URL,
      LESSON_STATUS,
      MODEL_DEFAULTS,
      occupiedCabinetIds: [],
      cabinetsLoading: false,
      selectedCabinetSchedule: null,
    }
  },

  created() {

  },

  watch: {
    'item.date': function () {
      this.getOccupiedCabinetIds()
    },

    'item.time': function () {
      this.getOccupiedCabinetIds()
    },

    'item.cabinet_id': function(newVal, oldVal) {
      // console.log(oldVal, '=>', newVal)
      this.getSelectedCabinetSchedule()
    },
  },
  
  methods: {
    toggleCancelled(isCancelled) {
      this.item.status = isCancelled ? LESSON_STATUS.CANCELLED : LESSON_STATUS.PLANNED
    },

    getOccupiedCabinetIds() {
      if (this.item.date && this.item.time && this.item.time.length === 5) {
        this.cabinetsLoading = true

        let params = _.pick(this.item, ['date', 'time'])

        if (this.item.id) {
          params.id = this.item.id
        }

        axios.get(apiUrl('cabinets/occupied'), { params }).then((r) => {
          this.occupiedCabinetIds = r.data
          this.cabinetsLoading = false
        })
      }
      this.occupiedCabinetIds = []
    },

    getSelectedCabinetSchedule() {
      if (this.item.cabinet_id && this.$parent.group.year) {
        this.cabinetsLoading = true
        let params = {
          lesson_id: this.item.id,
          cabinet_id: this.item.cabinet_id,
          year: this.$parent.group.year,
        }
  
        axios.get(apiUrl('cabinets/schedule'), { params }).then(r => {
          this.selectedCabinetSchedule = r.data
          this.cabinetsLoading = false
        })
      }
      this.selectedCabinetSchedule = null
    },

    clearCabinet() {
      this.item.cabinet_id = null
      this.$refs.select.isMenuActive = false
      this.$refs.select.blur()
    },
  }
}
</script>
