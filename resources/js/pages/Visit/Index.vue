<template>
  <div class='relative'>
   <div class='show-up-to grey--text darken-3 mb-1 text-md-right caption flex-items justify-end'>
      показать вперед до: 
      <v-menu class='mx-1'>
        <span slot='activator' class='sort-label'>{{ date | date }}</span>
        <v-date-picker no-title
          locale='ru'
          :min='min'
          v-model="date">
        </v-date-picker>
      </v-menu>
    </div>
    <DisplayData class='visits-page' :api-url='API_URL' :paginate='15' :invisibleFilters='{date: date}' ref='DisplayData'>
      <template slot='items' slot-scope='{ items }'>
        <VisitList :items='items' />
      </template>
    </DisplayData>
  </div>
</template>

<script>

import { DisplayData, DatePicker } from '@/components/UI'
import { API_URL, VisitList } from '@/components/Visit'

export default {
  components: { DisplayData, DatePicker, VisitList },

  data() {
    return {
      API_URL,
      min: moment().format('YYYY-MM-DD'),
      date: moment().format('YYYY-MM-DD'),
    }
  },

  watch: {
    date(date) {
      this.$refs.DisplayData.reloadData()
    }
  }
}
</script>

<style lang="scss">
  .show-up-to {
    position: absolute;
    right: 0;
    top: 7px;
    z-index: 1;
    white-space: nowrap;
    & .sort-label {
      border-bottom: 1px dotted grey;
      cursor: pointer;
    }
  }
  
  .visits-page {
    & .container {
      padding-top: 0;
    }
  }
</style>