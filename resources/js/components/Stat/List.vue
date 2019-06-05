<template>
  <v-data-table
    :items='items'
    :class='config.elevationClass'
    :headers="[
      {text: '', sortable: false},
      {text: 'заявок', sortable: false, width: 200},
      {text: 'новых договоров', sortable: false, width: 200},
      {text: 'новых услуг', sortable: false, width: 200},
      {text: 'сумма новых услуг', sortable: false, width: 200},
      {text: 'увеличение услуг', sortable: false, width: 200},
      {text: 'уменьшение услуг', sortable: false, width: 200},
      {text: 'изменение суммы услуг', sortable: false},
    ]"
    hide-actions
  >
    <template v-slot:items='{ item, index }'>
      <tr>
        <td style='white-space: nowrap'>
          {{ formatDate(item.date, index) }}
        </td>
        <td v-for="field in ['requests', 'contracts', 'subjects', 'contracts_sum', 'subjects_added', 'subjects_removed', 'contracts_sum_change']" :key='field'>
          {{ item[field] | hideZero }}
        </td>
      </tr>
    </template>
  </v-data-table>
</template>

<script>
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
    return {
      
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