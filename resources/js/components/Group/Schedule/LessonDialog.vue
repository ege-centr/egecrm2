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
            <v-btn dark icon v-if='edit_mode' @click.native='showSchedule = !showSchedule'>
              <v-icon>view_week</v-icon>
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
                    <v-text-field 
                      hide-details 
                      v-model='item.time' 
                      label='Время занятия' 
                      v-mask="'##:##'"
                      @blur='reloadPreview'
                    ></v-text-field>
                  </div>
                  <div class='vertical-inputs__input' style='display: inline-block'>
                    <v-select hide-details ref='select'
                      :loading='cabinetsLoading'
                      v-model='item.cabinet_id'
                      :items="$store.state.data.cabinets"
                      label="Кабинет"
                      item-value='id'
                      @blur='reloadPreview'
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
                    <TeacherSelect v-model="item.teacher_id" only-active />
                  </div>
                  <div class='vertical-inputs__input'>
                    <v-text-field 
                      @blur='reloadPreview'
                      hide-details 
                      v-model="item.duration" 
                      label="Длительность занятия, мин."></v-text-field>
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
                  <!-- <div class="vertical-inputs__input">
                    <v-btn 
                      style='width: 200px'
                      color='primary' 
                      small 
                      class='ml-0' 
                      @click='showSchedule = !showSchedule'>
                      {{ showSchedule ? 'Скрыть' : 'Показать' }} расписание
                    </v-btn>
                  </div> -->
                </div>
                <Preview
                  v-if='showSchedule'
                  :item='item'
                  :group='$parent.group'
                  ref='Preview'
                />
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
import Preview from './Preview'

export default {
  mixins: [ DialogMixin ],

  components: { DatePicker, DataSelect, TeacherSelect, Preview },
  
  data() {
    return {
      API_URL,
      LESSON_STATUS,
      MODEL_DEFAULTS,
      showSchedule: false,
      occupiedCabinetIds: [],
      cabinetsLoading: false,
    }
  },

  watch: {
    'item.date': function () {
      this.getOccupiedCabinetIds()
      this.reloadPreview()
    },

    'item.time': function () {
      this.getOccupiedCabinetIds()
    },
  },
  
  methods: {
    beforeOpen() {
      this.showSchedule = false
    },

    toggleCancelled(isCancelled) {
      this.item.status = isCancelled ? LESSON_STATUS.CANCELLED : LESSON_STATUS.PLANNED
    },

    getOccupiedCabinetIds() {
      if (this.item && this.item.date && this.item.time && this.item.time.length === 5) {
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

    reloadPreview() {
      if (this.showSchedule) {
        this.$refs.Preview.loadData()
      }
    },

    clearCabinet() {
      this.item.cabinet_id = null
      this.$refs.select.isMenuActive = false
      this.$refs.select.blur()
    },
  },

  computed: {
    showScheduleEnabled() {
      return this.item.date && 
        this.item.time.length === 5 && 
        this.item.duration > 0 &&
        this.item.cabinet_id > 0 &&
        this.item.teacher_id > 0
    }
  }
}
</script>
