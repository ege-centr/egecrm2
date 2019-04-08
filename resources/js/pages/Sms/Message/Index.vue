<template>
  <div class='sms-messages-page'>
    <SmsMessageDialog ref='SmsMessageDialog' :custom-input='true' />
    <div class='mb-2 width-100 text-sm-right'>
      <AddBtn label='отправить смс' icon='send' @click.native="$refs.SmsMessageDialog.open('')" />
    </div>
    <DisplayData :api-url='API_URL' :paginate='30'>
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
import SmsMessageDialog from '@/components/Sms/Message/Dialog'

export default {
  components: { DisplayData, SmsMessageList, SmsMessageDialog },

  data() {
    return {
      API_URL,
    }
  },
}
</script>



<style lang='scss'>
  .add-btn__icon {
    & i {
      font-size: 18px;
    }
  }
</style>