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
          <IndexPage ref='GroupPage'
            :pagination='false' 
            :api-url='GROUP_API_URL' 
            :filters='GROUP_FILTERS' 
            :invisible-filters="{teacher_id: $route.params.id}"
          >
            <template slot='items' slot-scope='{ items }'>
              <GroupList :items='items' />
            </template>
          </IndexPage>
        </v-tab-item>
        <v-tab-item>
          <IndexPage ref='PaymentPage'
            :pagination='false' 
            :api-url='PAYMENT_API_URL' 
            :filters='PAYMENT_FILTERS' 
            :sort='PAYMENT_SORT'
            :invisible-filters="{entity_id: $route.params.id, entity_type: CLASS_NAME}"
          >
            <template slot='items' slot-scope='{ items }'>
              <PaymentList :items='items' />
            </template>
            <template slot='buttons-bottom'>
              <AddBtn @click.native='$refs.PaymentDialog.open(null, {
                entity_id: $route.params.id,
                entity_type: CLASS_NAME,
              })' />
            </template>
          </IndexPage>
        </v-tab-item>
      </v-tabs-items>
    </div>
    <PaymentDialog ref='PaymentDialog' />
  </div>
</template>

<script>

import { API_URL, CLASS_NAME } from '@/components/Teacher'
import { 
  API_URL as GROUP_API_URL,
  FILTERS as GROUP_FILTERS,
  GroupList, 
} from '@/components/Group'
import { 
  API_URL as PAYMENT_API_URL, 
  FILTERS as PAYMENT_FILTERS,
  SORT as PAYMENT_SORT,
  PaymentDialog, 
  PaymentList, 
} from '@/components/Payment'
import { IndexPage } from '@/components/UI'

export default {
  components: { GroupList, PaymentList, IndexPage, PaymentDialog },

  data() {
    return {
      GROUP_API_URL,
      GROUP_FILTERS,
      PAYMENT_API_URL,
      CLASS_NAME,
      PAYMENT_SORT,
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
      axios.get(apiUrl(API_URL, this.$route.params.id)).then(r => {
        this.item = r.data
        this.loading = false
      })
    },
  }
}
</script>