<template>
  <v-data-table
    :items='items'
    :class='config.elevationClass'
    :headers="headers"
    hide-actions
  >
    <template v-slot:items='{ item, index }'>
      <tr>
        <td>
          {{ formatDate(item.date, index) }}
        </td>
        <td v-for="field in fields" :key='field'>
          {{ item[field] | hideZero }}
        </td>
      </tr>
    </template>
  </v-data-table>
</template>

<script>

import { ENUMS } from '@/components/Payment'

export default {
  props: {
    items: {
      type: Array,
      default: null,
      required: false
    },

    selectedMode: {
      type: String,
    }
  },

  data() {
    const headers = [
      {text: '', sortable: false, width: 200},
    ]
    const fields = []
    
    ENUMS.methods.forEach(method => {
      headers.push({text: method.title, sortable: false, width: 200})
      fields.push(method.id)
    })
      
    fields.push('payment_sum')
    fields.push('return_sum')
    headers.push({text: 'итого платежи', sortable: false, width: 200})
    headers.push({text: 'итого возвраты', sortable: false})
    return {
      ENUMS,
      headers,
      fields,
    }
  },
  
  methods: {
    formatDate(date, index) {
      switch(this.selectedMode) {
        case 'year':
          return _.get(
            this.$store.state.data.years[this.$store.state.data.years.length - index - 1],
            'title'
          )
        case 'month':
          return moment(date).format('MMMM YYYY')
        default: 
          return moment(date).format('DD MMMM YYYY')
      }
    }
  },
}
</script>