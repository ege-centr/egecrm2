<template>
  <div>
    <div class='headline mb-4'>
      Преподаватель {{ $route.params.id }}
    </div>

    <v-card class='elevation-3 mb-4'>
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
  </div>
</template>

<script>

import { API_URL } from '@/components/Teacher/data'

export default {
  data() {
    return {
      loading: true,
      item: null,
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