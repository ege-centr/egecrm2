<template>
  <div>
    <DisplayData :api-url='API_URL' :filters='FILTERS' :paginate='30'>
      <template slot='items' slot-scope='{ items }'>
        <TeacherList :items='items' v-if='items.length > 0' />
        <NoData
          v-else
          box
        />
      </template>
    </DisplayData>
  </div>
</template>

<script>

import { DisplayData } from '@/components/UI'
import { API_URL, FILTERS, TeacherList } from '@/components/Teacher'

export default {
  components: { DisplayData, TeacherList },

  data() {
    return {
      API_URL,
      FILTERS,
    }
  },
}
</script>
