<template>
  <div>
    <div class='headline mb-5'>DEV1</div>

        <DisplayData ref='ContractPage'
            :tabs='true'
            :api-url='CONTRACT_API_URL' 
            :invisible-filters="{client_id: clientId}"
          >
            <template slot='items' slot-scope='{ items }'>
              <ContractList :items='items' :client-id='clientId' @updated='() => $refs.ContractPage.loadData()' />
            </template>
          </DisplayData>
  </div>
</template>



<script>
// Contracts
import { API_URL as CONTRACT_API_URL } from '@/components/Contract'
import ContractList from '@/components/Contract/List'
import { DisplayData } from '@/components/UI'

export default {
  components: { DisplayData, ContractList },
  
  data() {
    return {
      CONTRACT_API_URL,
      clientId: 8717,
    }
  },

 
}
</script>


<style scoped lang='scss'>
  .outer {
    background: PaleVioletRed;
    width: 200px;
    height: 50px;
    position: relative;
    border-radius: 40px;
    overflow: hidden;
    & .inner {
      // background: Turquoise;
      background: white;
      position: absolute;
      left: 0;
      height: 100%;
      width: 100%;
    }
  }
</style>
