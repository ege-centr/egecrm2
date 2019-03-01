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
                <div class='mt-3 item-label'>Предметы</div>
                <span v-for='(subject_id, index) in item.subjects_ec' :key='subject_id'>{{ getData('subjects', subject_id).name }}{{ index === item.subjects_ec.length - 1 ? '' : '+' }}</span>
              </div>
            </div>
          </v-flex>
          <v-spacer></v-spacer>
           <v-flex class='text-md-right' align-end d-flex>
             <div>
              <v-btn slot='activator' flat icon color="black" class='ma-0' @click="loading = true; Preview.login(item.id, CLASS_NAME)">
                <v-icon>visibility</v-icon>
              </v-btn>
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
        <v-tab>
          Баланс
        </v-tab>
        <v-tab>
          Допуслуги
        </v-tab>
        <v-tab>
          Отзывы
        </v-tab>
      </v-tabs>
      <v-tabs-items v-model="tabs">
        <!-- ГРУППЫ -->
        <v-tab-item>
          <DisplayData ref='GroupPage'
            :api-url='GROUP_API_URL' 
            :tabs="{data: 'years', field: 'year'}"
            :invisible-filters="{teacher_id: $route.params.id}"
          >
            <template slot='items' slot-scope='{ items }'>
              <GroupList :items='items' :display-options='{teacher: false}' />
            </template>
          </DisplayData>
        </v-tab-item>

        <!-- ПЛАТЕЖИ -->
        <v-tab-item>
          <DisplayData ref='PaymentPage'
            :tabs="{data: 'years', field: 'year'}"
            :api-url='PAYMENT_API_URL' 
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
          </DisplayData>
        </v-tab-item>

        <!-- БАЛАНС -->
        <v-tab-item>
          <Balance :entity-type='CLASS_NAME' :entity-id='$route.params.id' />
        </v-tab-item>

        <!-- ДОПУСЛУГИ -->
        <v-tab-item>
           <DisplayData ref='PaymentAdditionalPage'
            :tabs="{data: 'years', field: 'year'}"
            :api-url='PAYMENT_ADDITIONAL_API_URL' 
            :invisible-filters="{entity_id: $route.params.id, entity_type: CLASS_NAME}"
          >
            <template slot='items' slot-scope='{ items }'>
              <PaymentAdditionalList :items='items' />
            </template>
            <template slot='buttons-bottom'>
              <AddBtn @click.native='$refs.PaymentAdditionalDialog.open(null, {
                entity_id: $route.params.id,
                entity_type: CLASS_NAME,
              })' />
            </template>
          </DisplayData>
        </v-tab-item>

        <!-- Отзывы -->
        <v-tab-item>
          <DisplayData 
            ref='ReviewPage'
            :api-url='REVIEW_API_URL' 
            :tabs="{data: 'years', field: 'year'}"
            :invisible-filters="{teacher_id: $route.params.id}"
          >
            <template slot='items' slot-scope='{ items }'>
              <ReviewAdminList :items='items' @updated='() => $refs.ReviewPage.reloadData()' />
            </template>
          </DisplayData>
        </v-tab-item>
      </v-tabs-items>
    </div>
    <PaymentDialog ref='PaymentDialog' />
    <PaymentAdditionalDialog ref='PaymentAdditionalDialog' />
  </div>
</template>

<script>

import { API_URL, CLASS_NAME } from '@/components/Teacher'
import { API_URL as GROUP_API_URL } from '@/components/Group'
import GroupList from '@/components/Group/List'
import { 
  API_URL as PAYMENT_API_URL, 
  SORT as PAYMENT_SORT,
  PaymentDialog, 
  PaymentList, 
} from '@/components/Payment'
import {
  API_URL as PAYMENT_ADDITIONAL_API_URL,
  PaymentAdditionalList,
  PaymentAdditionalDialog,
} from '@/components/Payment/Additional'
import { DisplayData } from '@/components/UI'
import Preview from '@/other/Preview'
import Balance from '@/components/Balance/Balance'


// Reviews
import ReviewAdminList from '@/components/Review/Admin/List'
import { API_URL as REVIEW_API_URL } from '@/components/Review'

export default {
  components: { 
    GroupList, PaymentList, DisplayData, PaymentDialog, PaymentAdditionalList, PaymentAdditionalDialog, Balance,
    ReviewAdminList,
  },

  data() {
    return {
      GROUP_API_URL,
      PAYMENT_API_URL,
      PAYMENT_ADDITIONAL_API_URL,
      REVIEW_API_URL,
      CLASS_NAME,
      PAYMENT_SORT,
      Preview,
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