<template>
  <div>
    <DisplayData :api-url='API_URL'>
      <template slot='items' slot-scope='{ items }'>
        <SmsMessageList :items='items' />
      </template>
    </DisplayData>
  </div>
</template>

<script>

import { DisplayData } from '@/components/UI'
import { API_URL } from '@/components/Sms/Message'
import SmsMessageList from '@/components/Sms/Message/List'

export default {
  components: { DisplayData, SmsMessageList },

  data() {
    return {
      API_URL,
    }
  },
}
</script>
