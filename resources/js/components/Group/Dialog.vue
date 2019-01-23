<template>
  <v-layout row justify-center>
    <!-- <v-dialog v-model="dialog" transition="dialog-bottom-transition" content-class='v-dialog--fullscreen halfscreen-dialog'> -->
    <v-dialog v-model="dialog" transition="dialog-bottom-transition" fullscreen hide-overlay>
      <v-card>
        <v-toolbar dark color="primary">
          <v-btn icon dark @click.native="dialog = false">
            <v-icon>close</v-icon>
          </v-btn>
          <v-toolbar-title>{{ edit_mode ? 'Редактирование' : 'Добавление' }} группы</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items>
            <v-btn dark flat @click.native="storeOrUpdate" :loading='saving'>{{ edit_mode ? 'Сохранить' : 'Добавить' }}</v-btn>
          </v-toolbar-items>
        </v-toolbar>
        <v-card-text class='relative'>
          <Loader v-if='loading' class='loader-wrapper_fullscreen-dialog' />
          <v-container grid-list-xl class="pa-0 ma-0" fluid v-else>
            <v-layout wrap>
              <v-flex md12>
                <div class='vertical-inputs'>
                  <div class='vertical-inputs__input'>
                    <ClearableSelect v-model="item.teacher_id"
                      label="Учитель"
                      item-text='names.abbreviation'
                      :items='$store.state.data.teachers' />
                  </div>
                  <div class='vertical-inputs__input'>
                     <ClearableSelect v-model="item.head_teacher_id"
                        label="Классный руководитель"
                        item-text='names.abbreviation'
                        :items='$store.state.data.teachers' />
                  </div>
                  <div class='vertical-inputs__input'>
                    <GradeAndYear ref='GradeAndYear' :item='item' />
                  </div>
                  <div class='vertical-inputs__input'>
                    <ClearableSelect v-model="item.head_teacher_id"
                        label="Классный руководитель"
                        item-text='names.abbreviation'
                        :items='$store.state.data.teachers' />
                  </div>
                  <div class='vertical-inputs__input'>
                    <ClearableSelect v-model="item.cabinet_id"
                        label='Кабинет'
                        item-text='text'
                        :items='cabinets' />
                  </div>
                  <div class='vertical-inputs__input'>
                    <v-text-field hide-details v-model="item.teacher_price" label="Цена за занятие, руб."></v-text-field>
                  </div>
                  <div class='vertical-inputs__input'>
                    <v-text-field hide-details v-model="item.duration" label="Длительность занятия, мин."></v-text-field>
                  </div>
                  <div class='vertical-inputs__input'>
                    <v-switch class='mt-0'
                      label="Готова к запуску"
                      color="success"
                      hide-details
                      v-model='item.is_ready_to_start'
                    ></v-switch>
                  </div>
                  <div class='vertical-inputs__input'>
                    <v-switch class='mt-0'
                      label="Заархивирована"
                      color="error"
                      hide-details
                      v-model='item.is_archived'
                    ></v-switch>
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
import { model_defaults, API_URL } from '@/components/Group'
import GradeAndYear from '@/components/GradeAndYear'

export default {
  components: { GradeAndYear },

  data() {
    return {
      loading: true,
      saving: false,
      item: null,
      dialog: false,
      edit_mode: true,

      item: model_defaults,
      teachers: [],
      cabinets: [],
      saving: false,
    }
  },
  
  methods: {
    async open(item_id = null) {
      this.dialog = true
      this.loading = true
      await axios.get(apiUrl('cabinets')).then(r => {
        this.cabinets = r.data
      })
      if (item_id !== null) {
        this.edit_mode = true
        this.loadData(item_id)
      } else {
        this.edit_mode = false
        this.item = model_defaults
        this.loading = false
      }
    },

    async loadData(item_id) {
      axios.get(apiUrl(API_URL, item_id)).then(r => {
        this.item = r.data
        this.loading = false
      })
    },

    async storeOrUpdate() {
      this.saving = true
      if (this.item.id) {
        await axios.put(apiUrl(API_URL, this.item.id), this.item).then(r => {
          this.item = r.data
        })
      } else {
        await axios.post(apiUrl(API_URL), this.item).then(r => {
          this.$router.push({ name: 'GroupEdit', params: { id: r.data }})
        })
      }
      this.dialog = false
      this.waitForDialogClose(() => this.saving = false)
    },
  },
}
</script>