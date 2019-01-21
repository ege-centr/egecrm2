<template lang="html">
  <div>
    <IndexPage :api-url='API_URL' :filters='filters' :sort='SORT'>
      <template slot='items' slot-scope='{ items }'>
        <PaymentList :items='items' />
      </template>
    </IndexPage>
    <!-- <v-container grid-list-md fluid class="px-0">
      <PaymentList />
    </v-container> -->
  </div>
</template>

<script>

import { IndexPage } from '@/components/UI'
import { API_URL, FILTERS, SORT, ENUMS } from '@/components/Payment'
import PaymentList from '@/components/Payment/ListNew'

export default {
  components: { IndexPage, PaymentList },

  data() {
    return {
      API_URL,
      ENUMS,
      SORT,
      filters: [
        {label: 'Тип', field: 'type', type: 'multiple', options: ENUMS.types},
        {label: 'Метод', field: 'methods', type: 'multiple', options: ENUMS.methods},
        {label: 'Год', field: 'year', type: 'multiple', options: this.$store.state.data.years, valueField: 'id', textField: 'text'},
        {label: 'Категория', field: 'category', type: 'multiple', options: ENUMS.categories},
        {label: 'Пользователь', field: 'created_admin_id', type: 'select', options: this.$store.state.data.admins, valueField: 'id', textField: 'name'},
        {label: 'Дата', field: 'date', type: 'date'},
      ],
    }
  },
  
}
</script>
