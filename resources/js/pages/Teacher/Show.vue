<template>
  <div>
    <div class='headline mb-4'>
      Преподаватель {{ $route.params.id }}
    </div>

    <v-card class='mb-4' :class='config.elevationClass'>
      <v-card-text class='relative card-with-loader'>
        <Loader v-if='loading'></Loader>
        <v-layout wrap v-if='item !== null'>
          <v-flex md12>
            <div class='flex-items'>
              <v-avatar v-if='item' :size='100' class='bg-avatar mr-4' :style="{backgroundImage: `url(${item.photo_url})`}"></v-avatar>
              <v-avatar v-else size='100' class='mr-4'>
                <img src="/img/no-profile-img.jpg">
              </v-avatar>
              <div class='mr-5 pr-5'>
                <div class='item-label'>Преподаватель</div>
                {{ item ? item.names.full : 'Не установлен' }}
              </div>
            </div>
          </v-flex>
        </v-layout>
      </v-card-text>
    </v-card>

    <div v-if='item !== null'>
      <v-tabs fixed-tabs v-model='tabs' class='mb-4'>
        <v-tab>
          Группы
        </v-tab>
        <v-tab>
          Платежи
        </v-tab>
      </v-tabs>
      <v-tabs-items v-model="tabs">
        <v-tab-item>
          <GroupList :items='item.groups' />
        </v-tab-item>
        <v-tab-item>
          <PaymentList
            :items='item.payments'
            :entity-id='item.id'
            :class-name='CLASS_NAME'
          />
        </v-tab-item>
      </v-tabs-items>
    </div>

  </div>
</template>

<script>

import { API_URL, CLASS_NAME } from '@/components/Teacher/data'
import GroupList from '@/components/Group/List'
import PaymentList from '@/components/Payment/List'

export default {
  components: { GroupList, PaymentList },

  data() {
    return {
      CLASS_NAME,
      loading: true,
      item: null,
      tabs: null,
    }
  },

  created() {
    this.loadData()
  },

  methods: {
    loadData() {
      axios.get(apiUrl(`${API_URL}/${this.$route.params.id}`)).then(r => {
        this.item = r.data
        this.loading = false
      })
    },
  }
}
</script>