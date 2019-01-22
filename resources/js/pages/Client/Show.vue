<template>
  <div>
    <div class='headline mb-4'>
      Клиент {{ clientId || $route.params.id }}
    </div>
    <v-card class='mb-4' :class='config.elevationClass'>
      <v-card-text class='relative card-with-loader'>
        <Loader v-if='loading'></Loader>
        <v-layout wrap v-if='client !== null'>
          <v-flex style='width: 400px'>
            <div class='flex-items'>
              <Avatar :size='100' class='mr-4' :photo='client.photo' />
              <div>
                <div class='grey--text text--darken-2 font-weight-medium caption'>Ученик</div>
                <div class='font-weight-bold'>
                  {{ client.last_name }}
                  {{ client.first_name }}
                  {{ client.middle_name }}
                  <span v-if='client.grade_id !== null'>({{ getData('grades', client.grade_id).title }})</span>
                </div>
                <div class='mt-4'>
                  <PhoneList :items='client.phones' :with-comments='true' />
                </div>
                <div v-if='client.email'>
                  <EmailShow :item='client.email' />
                </div>
                <div class='grey--text text--darken-2 font-weight-medium caption mt-4'>Представитель</div>
                <div class='font-weight-bold'>
                  {{ client.representative.last_name }}
                  {{ client.representative.first_name }}
                  {{ client.representative.middle_name }}
                </div>
                <div class='mt-4'>
                  <PhoneList :items='client.representative.phones' :with-comments='true' />
                </div>
                <div v-if='client.representative.email'>
                  <EmailShow :item='client.representative.email' />
                </div>
              </div>
            </div>
          </v-flex>
          <v-flex style='width: 400px'>
            <div>
              Место обучения в данный момент: {{ client.school || 'не указано' }}
            </div>
            <div>
              Классный руководитель: {{ client.headTeacher ? client.headTeacher.names.short : 'не назначен' }}
            </div>
            <div v-if='client.branches.length'>
              Удобные филиалы: <BranchList :items='client.branches' />
            </div>
            <div class='font-weight-bold my-4'>Паспортные данные представителя</div>
            <div>
              Паспорт: {{ client.representative.number }} {{ client.representative.series }}
            </div>
            <div>
              Паспорт выдан: {{ client.representative.issued_date | date }} {{ client.representative.issued_by }}
            </div>
            <div>
              Код подразделения: {{ client.representative.code || 'не указано' }}
            </div>
            <div>
              Дата рождения: {{ client.representative.birthday | date }}
            </div>
            <div>
              Место прописки: {{ client.representative.address || 'не указано' }}
            </div>
          </v-flex>
          <v-spacer></v-spacer>
          <v-flex class='text-md-right' align-end d-flex>
            <div>
              <v-btn @click='editClient(client.id)' flat icon color="black" class='ma-0'>
                <v-icon>more_horiz</v-icon>
              </v-btn>
            </div>
          </v-flex>
        </v-layout>
      </v-card-text>
    </v-card>

    <div v-if='client !== null'>
      <v-tabs fixed-tabs v-model='tabs' class='mb-4'>
        <v-tab>
          Заявки
        </v-tab>
        <v-tab>
          Договоры
        </v-tab>
        <v-tab>
          Группы
        </v-tab>
        <v-tab>
          Платежи
        </v-tab>
        <v-tab>
          Комментарии
        </v-tab>
        <v-tab>
          Тесты
        </v-tab>
      </v-tabs>

      <v-tabs-items v-model="tabs">
        <v-tab-item>
            <RequestList :items='client.requests' :phones='client.phones' @updated='loadData' />
        </v-tab-item>
        <v-tab-item>
          <IndexPage ref='ContractPage'
            :pagination='false' 
            :api-url='CONTRACT_API_URL' 
            :filters='contract_filters' 
            :invisible-filters="{client_id: client.id}"
            :pre-installed-filters='[{item: contract_filters[0], value: $store.state.data.academic_year}]'
          >
            <template slot='items' slot-scope='{ items }'>
              <ContractList :items='items' :client='client' @updated='$refs.ContractPage.loadData' />
            </template>
            <template slot='buttons-bottom'>
              <AddBtn @click.native='$refs.ContractDialog.open(null, {client_id: client.id})' />
            </template>
          </IndexPage>
          <!-- <ContractList
            :items='client.contracts'
            :client-id='client.id'
          /> -->
        </v-tab-item>
        <v-tab-item>
          <IndexPage ref='GroupPage'
            :pagination='false' 
            :api-url='GROUP_API_URL' 
            :filters='group_filters' 
            :invisible-filters="{client_id: $route.params.id}"
          >
            <template slot='items' slot-scope='{ items }'>
              <GroupList :items='items' />
            </template>
          </IndexPage>
          <!-- <GroupList :items='client.groups' /> -->
          <GroupNotAssignedList
            :client='client' 
            @moved='loadData' 
          />
        </v-tab-item>
        <v-tab-item>
          <IndexPage ref='PaymentPage'
            :pagination='false' 
            :sort='SORT'
            :api-url='PAYMENT_API_URL' 
            :filters='payment_filters' 
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
        <v-tab-item>
          <v-card :class='config.elevationClass'>
            <v-card-text>
              <Comments :class-name='CLASS_NAME' :entity-id='client.id' />
            </v-card-text>
          </v-card>
        </v-tab-item>
        <v-tab-item>
          <div class='headline'>
            <TestAdminClientList :client='client' />
          </div>
        </v-tab-item>
      </v-tabs-items>
    </div>
    <ClientDialog ref='ClientDialog' />
    <PaymentDialog ref='PaymentDialog' />
    <ContractDialog ref='ContractDialog' @updated.native='$refs.ContractPage.loadData' />
  </div>
</template>

<script>

import RequestList from '@/components/Request/List'
import Comments from '@/components/Comments'
import ContractList from '@/components/Contract/ListNew'
import GroupList from '@/components/Group/List'
import { API_URL as PAYMENT_API_URL, PaymentDialog, PaymentList, ENUMS, SORT } from '@/components/Payment'
import PhoneList from '@/components/Phone/List'
import EmailShow from '@/components/Email/Show'
import BranchList from '@/components/UI/BranchList'
import { TestAdminClientList } from '@/components/Test'
import { IndexPage } from '@/components/UI'
import { API_URL as GROUP_API_URL } from '@/components/Group'
import { API_URL as CONTRACT_API_URL, ContractDialog } from '@/components/Contract'

import { API_URL, CLASS_NAME, ClientMap, GroupNotAssignedList, ClientDialog } from '@/components/Client'

export default {
  props: ['clientId'],

  data() {
    return {
      CLASS_NAME,
      GROUP_API_URL,
      CONTRACT_API_URL,
      PAYMENT_API_URL,
      SORT,
      tabs: null,
      loading: true,
      client: null,
      map_dialog: false,
      
      // TODO: дубль из pages/Group/Index
      group_filters: [
        {label: 'Год', field: 'year', type: 'multiple', options: this.$store.state.data.years, valueField: 'id', textField: 'text'},
        {label: 'Преподаватель', field: 'teacher_id', type: 'multiple', options: this.$store.state.data.teachers, valueField: 'id', textField: 'names.abbreviation'},
        {label: 'Предмет', field: 'subject_id', type: 'multiple', options: this.$store.state.data.subjects, valueField: 'id', textField: 'name'},
        {label: 'Класс', field: 'grade_id', type: 'multiple', options: this.$store.state.data.grades, valueField: 'id', textField: 'title'},
        {label: 'Филиал', field: 'branch_id', type: 'multiple', options: this.$store.state.data.branches, valueField: 'id', textField: 'full'},
      ],

      contract_filters: [
        {label: 'Год', field: 'year', type: 'select', options: this.$store.state.data.years, valueField: 'id', textField: 'text'},
      ],

      payment_filters: [
        {label: 'Тип', field: 'type', type: 'multiple', options: ENUMS.types},
        {label: 'Метод', field: 'methods', type: 'multiple', options: ENUMS.methods},
        {label: 'Год', field: 'year', type: 'multiple', options: this.$store.state.data.years, valueField: 'id', textField: 'text'},
        {label: 'Категория', field: 'category', type: 'multiple', options: ENUMS.categories},
        {label: 'Пользователь', field: 'created_admin_id', type: 'select', options: this.$store.state.data.admins, valueField: 'id', textField: 'name'},
        {label: 'Дата', field: 'date', type: 'date'},
      ],
    }
  },

  components: { 
    RequestList, Comments, ContractList, ClientMap, GroupList, GroupNotAssignedList, 
    PaymentList, ClientDialog, PhoneList, BranchList, EmailShow, TestAdminClientList,
    IndexPage, ContractDialog, PaymentDialog,
  },

  created() {
    this.loadData()
  },

  watch: {
    tabs(newVal) {
      // tests tab
      if (newVal === 5) {
        // 123
        // 
      }
    },
  },

  methods: {
    loadData() {
      axios.get(apiUrl(`${API_URL}/${this.clientId || this.$route.params.id}`)).then(r => {
        this.client = r.data
        this.loading = false
      })
    },

    editClient(id) {
      this.$refs.ClientDialog.open(id)
    },
  }
}
</script>

<style scoped lang='scss'>
  // .v-tabs__items {
  //   width: calc(100% + 20px);
  //   padding: 10px;
  //   margin-left: -10px;
  // }
</style>
