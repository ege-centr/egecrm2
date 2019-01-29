<template>
  <div>
    <IndexPage :api-url='API_URL' :pagination='false'>
      <template slot='items' slot-scope='{ items }'>
        <MacroList :items='items' />
      </template>
    </IndexPage>
  </div>
</template>

<script>

import { IndexPage } from '@/components/UI'
import { API_URL, MacroList } from '@/components/Macro'

export default {
  components: { IndexPage, MacroList },

  data() {
    return {
      API_URL,
    }
  },
}
</script>
