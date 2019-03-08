<template>
  <div>
    <AdminDialog ref='AdminDialog' />
    <DisplayData :api-url='API_URL' :filters='FILTERS'>
      <template slot='buttons'>
        <AddBtn animated @click.native='$refs.AdminDialog.open(null)' label='добавить пользователя' />
      </template>
      <template slot='items' slot-scope='{ items }'>
        <AdminList :items='items' />
      </template>
    </DisplayData>
  </div>
</template>

<script>

import { DisplayData } from '@/components/UI'
import { API_URL, FILTERS, AdminList, AdminDialog } from '@/components/Admin'

export default {
  components: { DisplayData, AdminList, AdminDialog },

  data() {
    return {
      API_URL,
      FILTERS,
    }
  },
}
</script>
