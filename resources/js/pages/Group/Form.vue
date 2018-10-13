<template>
  <div>
    <div v-if='item !== null'>
      <v-container grid-list-xl class="pa-0 ma-0" fluid>
        <v-layout wrap>
          <v-flex md12 class='headline'>
            {{ item.id ? `Редактирование группы №${item.id}` : 'Добавление группы' }}
          </v-flex>
          <v-flex md3>
            <v-text-field v-model="item.head_teacher_id" label="Имя"></v-text-field>
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

import { model_defaults } from '@/components/Group/data'

export default {
  data() {
    return {
      item: model_defaults
    }
  },
  created() {
    this.loadData()
  },
  methods: {
    loadData() {
      axios.get(apiUrl('teachers')).then(r => {
        console.log(r)
      })
    }
  }
}
</script>
