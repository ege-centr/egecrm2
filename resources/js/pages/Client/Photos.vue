<template lang="html">
  <div>
    <DisplayData 
      :invisible-filters='{photos: 1}'
      :api-url='API_URL' :paginate='30'>
      <template slot='items' slot-scope="{ items }">
        <div v-for='item in items'>
          <div class='flex-items align-center mb-5'>
            <AvatarLoader 
              :entity-type='CLASS_NAME' 
              :item='item' 
              class='mr-3'
            />
            <router-link :to="{ name: 'ClientShow', params: { id: item.id }}">
              <PersonName :item='item' />
            </router-link>
          </div>
        </div>
      </template>
    </DisplayData>
  </div>
</template>

<script>

import { DisplayData } from '@/components/UI'
import { CLASS_NAME, API_URL } from '@/components/Client'
import AvatarLoader from '@/components/AvatarLoader'

export default {
  components: { DisplayData, AvatarLoader },

  data() {
    return {
      API_URL,
    }
  },
}
</script>