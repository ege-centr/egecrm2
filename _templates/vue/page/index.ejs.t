---
to: resources/js/pages/<%= Name %>/Index.vue
---
<template>
  <div>
    <IndexPage :api-url='API_URL' :filters='FILTERS'>
      <template slot='items' slot-scope='{ items }'>
        <<%= Name %>List :items='items' />
      </template>
    </IndexPage>
  </div>
</template>

<script>

import { IndexPage } from '@/components/UI'
import { API_URL, FILTERS, <%= Name %>List } from '@/components/<%= Name %>'

export default {
  components: { IndexPage, <%= Name %>List },

  data() {
    return {
      API_URL,
      FILTERS,
    }
  },
}
</script>
