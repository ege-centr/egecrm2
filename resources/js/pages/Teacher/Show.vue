<template>
  <div>
    <div class='headline mb-4 flex-items align-center'>
      {{ getData('teachers', $route.params.id).default_name }}

      <v-chip 
        readonly
        class='ml-3 no-pointer-events'
        small
        outline 
        :color="item.is_banned ? 'grey' : 'success'" 
        v-if='this.item !== null'>
          {{ STATUS[item.in_egecentr] }}
        </v-chip>
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
        <v-tab>
          Расписание
        </v-tab>
        <v-tab>
          Комментарии
        </v-tab>
        <v-tab>
          Авторизация
        </v-tab>
      </v-tabs>
      <v-tabs-items v-model="tabs">
        <!-- ГРУППЫ -->
        <v-tab-item>
          <DisplayData ref='GroupPage'
            :api-url='GROUP_API_URL' 
            :tabs='true'
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
            :tabs='true'
            :api-url='PAYMENT_API_URL' 
            :sort='PAYMENT_SORT'
            :invisible-filters="{entity_id: $route.params.id, entity_type: CLASS_NAME}"
          >
            <template slot='items' slot-scope='{ items }'>
              <PaymentList 
                :items='items' 
                :model-defaults='{ entity_id: $route.params.id, entity_type: userTypes.teacher }' 
                @updated='$refs.PaymentPage.loadData()'
              />
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
                :model-defaults='{ entity_id: $route.params.id, entity_type: userTypes.teacher }' 
                @updated='$refs.PaymentAdditionalPage.loadData()'
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
              <ReviewAdminList 
                :display-options="{teacher: false}"
                :items='items' 
                @updated='() => $refs.ReviewPage.reloadData()' />
            </template>
          </DisplayData>
        </v-tab-item>

        <!-- Отчёты -->
        <v-tab-item>
          <v-switch class='ma-0 justify-end'
            label="ограничение по начислению бонусов"
            color='red'
            hide-details
            v-model='item.disable_bonuses'
            @change='update'
          ></v-switch>
          <DisplayData 
            ref='ReportPage'
            :api-url='REPORT_API_URL' 
            :tabs='true'
            :invisible-filters="{teacher_id: [$route.params.id]}"
          >
            <template slot='items' slot-scope='{ items }'>
              <ReportList 
                :display-options="{teacher: false}"
                :items='items' 
                @updated='() => $refs.ReportPage.reloadData()' />
            </template>
          </DisplayData>
        </v-tab-item>
        

        <!-- Расписание -->
        <v-tab-item>
          <TeacherFreetimeList :teacher-id='Number($route.params.id)' :editable='true' />
        </v-tab-item>

        <!-- Комментарии -->
        <v-tab-item>
          <v-card :class='config.elevationClass'>
            <v-card-text>
              <Comments :class-name='CLASS_NAME' :entity-id='Number($route.params.id)' />
            </v-card-text>
          </v-card>
        </v-tab-item>

        <!-- Логи -->
        <v-tab-item>
          <DisplayData 
            :api-url='LOG_API_URL' 
            :invisible-filters="{teacher_id: $route.params.id}"
          >
            <template slot='items' slot-scope='{ items }'>
              <LogList :items='items' />
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
import { API_URL, CLASS_NAME, STATUS } from '@/components/Teacher'
import { API_URL as GROUP_API_URL } from '@/components/Group'
import GroupList from '@/components/Group/List'
import TeacherFreetimeList from '@/components/Teacher/Freetime/List'
import Comments from '@/components/Comments'
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
import DisplayData from '@/components/UI/DisplayData'
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

// Логи
import { API_URL as LOG_API_URL } from '@/components/Log'
import LogList from '@/components/Log/List'

export default {
  components: { 
    GroupList, PaymentList, PaymentDialog, PaymentAdditionalList, PaymentAdditionalDialog, Balance,
    ReviewAdminList, ReportList, EmailShow, BgAvatar, PhoneList, DisplayData, TeacherFreetimeList,
    Comments, LogList,
  },

  data() {
    return {
      STATUS,
      GROUP_API_URL,
      PAYMENT_API_URL,
      PAYMENT_ADDITIONAL_API_URL,
      REPORT_API_URL,
      REVIEW_API_URL,
      LOG_API_URL,
      CLASS_NAME,
      PAYMENT_SORT,
      PreviewMode,
      loading: true,
      item: null,
      tabs: null,
    }
  },

  watch: {
    '$route.params': {
        handler() {
          this.loadData()
        },
        immediate: true,
    }
  },

  methods: {
    loadData() {
      this.item = null
      this.loading = true
      Vue.nextTick(() => {
        axios.get(apiUrl(API_URL, this.$route.params.id)).then(r => {
          this.item = r.data
          this.loading = false
        })
      })
    },

    update(value) {
      axios.put(apiUrl(API_URL, this.$route.params.id), {disable_bonuses: value})
    },
  }
}
</script>