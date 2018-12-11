<template>
  <div>
    <Loader v-if='loading' />
    <div v-if='!loading && item !== null'>
      <v-container grid-list-xl class="pa-0 ma-0" fluid>
        <v-layout wrap>
          <v-flex md12 class='headline'>
            {{ item.id ? `Редактирование группы №${item.id}` : 'Добавление группы' }}
          </v-flex>
          <v-flex md3>
            <v-select clearable
              v-model="item.teacher_id"
              :items="teachers"
              label="Учитель"
              item-value='id'
              item-text='names.abbreviation'
            ></v-select>
          </v-flex>
          <v-flex md3>
            <v-select clearable
              v-model="item.head_teacher_id"
              :items="teachers"
              label="Классный руководитель"
              item-value='id'
              item-text='names.abbreviation'
            ></v-select>
          </v-flex>
          <v-flex md3>
            <v-select clearable
              v-model="item.grade_id"
              :items="$store.state.data.grades"
              item-value='id'
              item-text='title'
              label="Класс"
            ></v-select>
          </v-flex>
          <v-flex md3>
            <v-select clearable
              v-model="item.year"
              :items="$store.state.data.years"
              label="Год"
            ></v-select>
          </v-flex>
          <v-flex md3>
            <v-select clearable
              v-model="item.subject_id"
              :items="$store.state.data.subjects"
              item-value='id'
              item-text='name'
              label="Предмет"
            ></v-select>
          </v-flex>
          <v-flex md3>
            <v-select clearable
              v-model="item.cabinet_id"
              :items="cabinets"
              item-value='id'
              label="Кабинет"
            ></v-select>
          </v-flex>
          <v-flex md3>
            <v-text-field v-model="item.teacher_price" label="Цена за занятие, руб."></v-text-field>
          </v-flex>
          <v-flex>
            <v-text-field v-model="item.duration" label="Длительность занятия, мин."></v-text-field>
          </v-flex>
        </v-layout>

        <v-layout>
          <v-switch class='ml-3 mt-0'
            label="Готова к запуску"
            color="success"
            hide-details
            v-model='item.is_ready_to_start'
          ></v-switch>
        </v-layout>

        <v-layout>
          <v-switch class='ml-3'
            label="Заархивирована"
            color="error"
            hide-details
            v-model='item.is_archived'
          ></v-switch>
        </v-layout>

        <v-layout wrap v-if='item.clients.length'>
          <v-flex md12 class='headline mt-3'>
            Состав группы:
          </v-flex>
          <v-flex md12>
            <div v-for="(client, index) in item.clients" :key="client.id" class='flex-items align-center'>
              <div>
                {{ client.names.short }}
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

export default {
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
          this.dates = r.data.lessons.map(e => e.lesson_date)
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
