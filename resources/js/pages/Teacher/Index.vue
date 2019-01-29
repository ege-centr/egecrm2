<template>
  <div>
    <IndexPage :api-url='API_URL' :filters='FILTERS' :pagination='false'>
      <template slot='items' slot-scope='{ items }'>
        <TeacherList :items='items' />
      </template>
    </IndexPage>
  </div>
</template>

<script>

import { IndexPage } from '@/components/UI'
import { API_URL, FILTERS, TeacherList } from '@/components/Teacher'

export default {
  components: { IndexPage, TeacherList },

  data() {
    return {
      API_URL,
      FILTERS,
    }
  },
}
</script>
