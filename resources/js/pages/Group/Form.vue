<template>
  <div>
    <Loader v-if='loading' />
    <div v-if='!loading && item !== null'>
      <v-container grid-list-xl class="pa-0 ma-0" fluid>
        <v-layout wrap>
          <v-flex md12 class='headline'>
            {{ item.id ? `Редактирование группы №${item.id}` : 'Добавление группы' }}
          </v-flex>

          <v-flex md12>
            <div class='vertical-inputs'>
              <div class='vertical-inputs__input'>
                <v-select clearable hide-details
                  v-model="item.teacher_id"
                  :items="teachers"
                  label="Учитель"
                  item-value='id'
                  item-text='names.abbreviation'
                ></v-select>
              </div>
              <div class='vertical-inputs__input'>
                <v-select clearable hide-details
                  v-model="item.head_teacher_id"
                  :items="teachers"
                  label="Классный руководитель"
                  item-value='id'
                  item-text='names.abbreviation'
                ></v-select>
              </div>
              <div class='vertical-inputs__input'>
                <GradeAndYear :item='item' />
              </div>
              <div class='vertical-inputs__input'>
                <v-select clearable hide-details
                  v-model="item.subject_id"
                  :items="$store.state.data.subjects"
                  item-value='id'
                  item-text='name'
                  label="Предмет"
                ></v-select>
              </div>
              <div class='vertical-inputs__input'>
                <v-select clearable hide-details
                  v-model="item.cabinet_id"
                  :items="cabinets"
                  item-value='id'
                  label="Кабинет"
                ></v-select>
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

        <v-layout>
          <v-flex md12 justify-center class='text-md-center'>
            <v-btn @click='storeOrUpdate' :loading='saving'>
              {{ item.id ? 'сохранить' : 'добавить' }}
            </v-btn>
          </v-flex>
        </v-layout>
      </v-container>
    </div>
  </div>
</template>

<script>

import { model_defaults, API_URL } from '@/components/Group/data'
import GradeAndYear from '@/components/GradeAndYear'

export default {
  components: { GradeAndYear },

  data() {
    return {
      item: model_defaults,
      teachers: [],
      cabinets: [],
      loading: true,
      saving: false,
    }
  },
  async created() {
    this.loadData()
  },
  methods: {
    async loadData() {
      await axios.get(apiUrl('teachers')).then(r => {
        this.teachers = r.data
      })
      await axios.get(apiUrl('cabinets')).then(r => {
        this.cabinets = r.data
      })
      if (this.$route.params.id) {
        await axios.get(apiUrl(`${API_URL}/${this.$route.params.id}`)).then(r => {
          this.dates = r.data.lessons.map(e => e.date)
          Vue.nextTick(() => this.item = r.data)
        })
      }
      this.loading = false
    },

    async storeOrUpdate() {
      this.saving = true
      if (this.item.id) {
        await axios.put(apiUrl(`${API_URL}/${this.item.id}`), this.item).then(r => {
          this.item = r.data
        })
      } else {
        await axios.post(apiUrl(API_URL), this.item).then(r => {
          this.$router.push({ name: 'GroupEdit', params: { id: r.data }})
        })
      }
      this.saving = false
    },
  },
}
</script>
