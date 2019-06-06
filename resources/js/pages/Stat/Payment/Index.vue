<template>
  <div>
    <div class='flex-items align-center relative'>
      <DivBlocker v-if="_.get($refs, 'DisplayData.pageLoading')" />
      <v-chip v-for="(label, id) in entityModes" class='pointer ml-0 mr-3'
        :class="{'primary white--text': id === selectedEntityMode}"
        @click='selectedEntityMode = id'
        :key='id'
      >
        {{ label }}
      </v-chip>
      <v-spacer></v-spacer>
      <v-chip v-for="(label, id) in TIME_MODE" class='pointer ml-0 mr-3'
        :class="{'primary white--text': id === selectedMode}"
        @click='selectedMode = id'
        :key='id'
      >
        {{ label }}
      </v-chip>
    </div>
     <DisplayData 
        ref='DisplayData'
        :invisible-filters="{mode: selectedMode, entity_type: selectedEntityMode}"
        :api-url='API_URL' 
        :paginate='50'
    >
      <template slot='items' slot-scope="{ items }">
        <StatPaymentList :items='items' :selected-mode='selectedMode' />
      </template>
    </DisplayData>
  </div>
</template>

<script>

import DisplayData from '@/components/UI/DisplayData'
import { TIME_MODE } from '@/other'
import { API_URL } from '@/components/Stat'
import StatPaymentList from '@/components/Stat/PaymentList'

export default {
  components: { DisplayData, StatPaymentList },
  
  data() {
    return {
      TIME_MODE,
      API_URL,
      entityModes: {
        [this.userTypes.client]: 'по клиентам',
        [this.userTypes.teacher]: 'по преподавателям',
      },
      selectedEntityMode: this.userTypes.client,
      selectedMode: 'day',
      selectedFilters: {},
    }
  }, 

  watch: {
      selectedMode(newVal, oldVal) {
        this.$refs.DisplayData.reloadData()
      },
      selectedEntityMode(newVal, oldVal) {
        this.$refs.DisplayData.reloadData()
      },
  },
}
</script>
