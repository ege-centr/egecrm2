<template>
  <div>
    <IndexPage :api-url='API_URL' :pagination='false'>
      <template slot='items' slot-scope='{ items }'>
        <AbstractGroupList :items='items' />
      </template>
    </IndexPage>
  </div>
</template>

<script>

import { IndexPage } from '@/components/UI'
import { API_URL, AbstractGroupList } from '@/components/AbstractGroup'

export default {
  components: { IndexPage, AbstractGroupList },

  data() {
    return {
      API_URL
    }
  },
}
</script>
