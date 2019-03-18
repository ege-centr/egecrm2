<template>
  <div>
    <DisplayData :api-url='API_URL' :custom-tabs="{field: 'type', data: TYPE}">
      <template slot='items' slot-scope='{ items }'>
        <SmsTemplateList :items='items' />
      </template>
    </DisplayData>
  </div>
</template>

<script>

import { DisplayData } from '@/components/UI'
import { API_URL, TYPE } from '@/components/Sms/Template'
import SmsTemplateList from '@/components/Sms/Template/List'

export default {
  components: { DisplayData, SmsTemplateList },

  data() {
    return {
      API_URL,
      TYPE,
    }
  },
}
</script>
