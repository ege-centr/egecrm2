<template>
  <div>
    <div class='headline mb-4'>
      {{ getData('teachers', $route.params.id).default_name }}
    </div>

    <v-card class='mb-4' :class='config.elevationClass'>
      <v-card-text class='relative card-with-loader'>
        <Loader v-if='loading'></Loader>
        <v-layout wrap v-if='item !== null'>
          <v-flex style='width: 400px'>
            <div class='flex-items'>
              <BgAvatar :photo='item.photo' :size='100' class='mr-4' />
              <div class='mr-5 pr-5'>
                <div class='item-label'>Предметы</div>
                <span v-for='(subject_id, index) in item.subjects_ec' :key='subject_id'>{{ getData('subjects', subject_id).name }}{{ index === item.subjects_ec.length - 1 ? '' : '+' }}</span>
                
                <span v-if='item.email'>
                  <div class='item-label mt-3'>Email</div>
                  <EmailShow :item='item.email' />
                </span>
                
              </div>
            </div>
          </v-flex>
          <v-flex style='width: 400px'>
            <div>
              <div class='item-label'>Телефон</div>
              <PhoneList :items='item.phones' :block='true' />
            </div>
          </v-flex>
          <v-spacer></v-spacer>
           <v-flex class='text-md-right' align-end d-flex>
             <div>
              <v-btn slot='activator' flat icon color="black" class='ma-0' @click="loading = true; PreviewMode.login(item.id, CLASS_NAME)">
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
        <v-tab>
          Отчёты
        </v-tab>
      </v-tabs>
      <v-tabs-items v-model="tabs">
        <!-- ГРУППЫ -->
        <v-tab-item>
          <DisplayDataAlgolia ref='GroupPage'
            :api-url='GROUP_API_URL' 
            :tabs='true'
            :invisible-filters="{teacher_id: $route.params.id}"
          >
            <template slot='items' slot-scope='{ items }'>
              <GroupList :items='items' :display-options='{teacher: false}' />
            </template>
          </DisplayDataAlgolia>
        </v-tab-item>

        <!-- ПЛАТЕЖИ -->
        <v-tab-item>
          <DisplayData ref='PaymentPage'
            :tabs='true'
            :api-url='PAYMENT_API_URL' 
            :sort='PAYMENT_SORT'
            :invisible-filters="{entity_id: $route.params.id, entity_type: CLASS_NAME}"
          >
            <template slot='items' slot-scope='{ items }'>
              <PaymentList 
                :items='items' 
                :model-defaults='{ entity_id: $route.params.id, entity_type: CLASS_NAME }' />
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
            :tabs='true'
            :api-url='PAYMENT_ADDITIONAL_API_URL' 
            :invisible-filters="{entity_id: $route.params.id, entity_type: CLASS_NAME}"
          >
            <template slot='items' slot-scope='{ items }'>
              <PaymentAdditionalList 
                :items='items' 
                :model-defaults='{ entity_id: $route.params.id, entity_type: CLASS_NAME }' 
              />
            </template>
          </DisplayData>
        </v-tab-item>

        <!-- Отзывы -->
        <v-tab-item>
          <DisplayData 
            ref='ReviewPage'
            :api-url='REVIEW_API_URL' 
            :tabs='true'
            :invisible-filters="{teacher_id: [$route.params.id]}"
          >
            <template slot='items' slot-scope='{ items }'>
              <ReviewAdminList :items='items' @updated='() => $refs.ReviewPage.reloadData()' />
            </template>
          </DisplayData>
        </v-tab-item>

        <!-- Отчёты -->
        <v-tab-item>
          <DisplayData 
            ref='ReportPage'
            :api-url='REPORT_API_URL' 
            :tabs='true'
            :invisible-filters="{teacher_id: [$route.params.id]}"
          >
            <template slot='items' slot-scope='{ items }'>
              <ReportList :items='items' @updated='() => $refs.ReportPage.reloadData()' />
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
import DisplayDataAlgolia from '@/components/UI/DisplayDataAlgolia'
import PreviewMode from '@/other/PreviewMode'
import Balance from '@/components/Balance/Balance'
import EmailShow from '@/components/Email/Show'
import BgAvatar from '@/components/UI/BgAvatar'
import PhoneList from '@/components/Phone/List'


// Reviews
import ReviewAdminList from '@/components/Review/Admin/List'
import { API_URL as REVIEW_API_URL } from '@/components/Review'

// Reports
import ReportList from '@/components/Report/List'
import { API_URL as REPORT_API_URL } from '@/components/Report'

export default {
  components: { 
    GroupList, PaymentList, DisplayData, PaymentDialog, PaymentAdditionalList, PaymentAdditionalDialog, Balance,
    ReviewAdminList, ReportList, EmailShow, BgAvatar, PhoneList, DisplayDataAlgolia,
  },

  data() {
    return {
      GROUP_API_URL,
      PAYMENT_API_URL,
      PAYMENT_ADDITIONAL_API_URL,
      REPORT_API_URL,
      REVIEW_API_URL,
      CLASS_NAME,
      PAYMENT_SORT,
      PreviewMode,
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