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
              item-text='name_short'
            ></v-select>
          </v-flex>
          <v-flex md3>
            <v-select clearable
              v-model="item.head_teacher_id"
              :items="teachers"
              label="Классный руководитель"
              item-value='id'
              item-text='name_short'
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

        <v-layout wrap>
          <v-flex md12 class='headline mt-3'>
            Состав группы:
          </v-flex>
          <v-flex md12>
            <v-slide-x-transition :group='true'>
              <div v-for="(client_id, index) in item.clients" :key="client_id" class='flex-items align-center'>
                <v-btn flat icon color="red" class='ma-0 mr-3' @click='item.clients.splice(index, 1)'>
                  <v-icon>remove</v-icon>
                </v-btn>
                <div>
                  {{ clients.find(e => e.id == client_id).name }}
                </div>
              </div>
            </v-slide-x-transition>
          </v-flex>
          <v-flex md3 class='py-0'>
            <v-select 
              :items='clients' 
              item-value='id' 
              item-text='name' 
              label='Добавить ученика' 
              v-model='selected_client'
              @change='selectClient'
            />
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

import { model_defaults, url } from '@/components/Group/data'

export default {
  data() {
    return {
      item: model_defaults,
      selected_client: null,
      teachers: [],
      clients: [],
      cabinets: [],
      loading: true,
      saving: false
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
      await axios.get(apiUrl('clients?get_all=1')).then(r => {
        this.clients = r.data
      })
      await axios.get(apiUrl('cabinets')).then(r => {
        this.cabinets = r.data
      })
      if (this.$route.params.id) {
        await axios.get(apiUrl(`${url}/${this.$route.params.id}`)).then(r => {
          this.item = r.data
        })
      }
      this.loading = false
    },
    selectClient(client_id) {
      if (client_id && this.item.clients.indexOf(client_id) === -1) {
        this.item.clients.push(client_id)
      }
      Vue.nextTick(() => {
        this.selected_client = null
      })
    },
    async storeOrUpdate() {
      this.saving = true
      if (this.item.id) {
        await axios.put(apiUrl(`${url}/${this.item.id}`), this.item).then(r => {
          this.item = r.data
        })
      } else {
        await axios.post(apiUrl(url), this.item).then(r => {
          this.$router.push({ name: 'GroupEdit', params: { id: r.data }})
        })
      }
      this.saving = false
    },
  }
}
</script>
