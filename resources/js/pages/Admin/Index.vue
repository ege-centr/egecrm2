<template>
  <div>
    <AdminDialog ref='AdminDialog' />
    <IndexPage :api-url='API_URL'>
      <template slot='buttons'>
        <AddBtn @click.native='$refs.AdminDialog.open(null)' label='добавить пользователя' />
      </template>
      <template slot='items' slot-scope='{ items }'>
        <AdminList :items='items' />
      </template>
    </IndexPage>
  </div>
</template>

<script>

import { IndexPage } from '@/components/UI'
import { API_URL, FILTERS, AdminList, AdminDialog } from '@/components/Admin'

export default {
  components: { IndexPage, AdminList, AdminDialog },

  data() {
    return {
      API_URL,
    }
  },
}
</script>
