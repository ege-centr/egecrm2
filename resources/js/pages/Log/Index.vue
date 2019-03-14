<template>
  <div>
    <DisplayData :api-url='API_URL' :paginate='50' :filters='FILTERS'>
      <template slot='items' slot-scope='{ items }'>
        <LogList :items='items'/>
      </template>
    </DisplayData>
  </div>
</template>

<script>

import { DisplayData } from '@/components/UI'
import { API_URL, FILTERS } from '@/components/Log'
import LogList from '@/components/Log/List'

export default {
  components: { DisplayData, LogList },

  data() {
    return {
      API_URL,
      FILTERS,
    }
  },

  mounted() {
    if (this.FILTERS.findIndex(e => e.field === 'table') === -1) {
      axios.get(apiUrl('tables')).then(r => {
        this.FILTERS.push(
          {type: 'multiple', field: 'table', label: 'таблица', options: r.data.map(e => {return {id: e, title: e} })}
        )
      })
    }
  },
}
</script>
