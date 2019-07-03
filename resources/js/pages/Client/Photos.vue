<template>
  <div>
    
    <div class='tmp-avatar-loader'>
      <AvatarLoader ref='AvatarLoader' :item='selectedItem' :entity-type='CLASS_NAME' />
    </div>
    <DisplayData 
      :api-url='API_URL' 
      :paginate='30'
      :filters='filters'
    >
      <template slot='items' slot-scope="{ items }">
        <v-data-table 
          :items='items' 
          hide-headers 
          hide-actions
          :class='config.elevationClass'
        >
          <template v-slot:items="{ item }">
            <tr>
              <td width='150'>
                <router-link :to="{ name: 'ClientShow', params: { id: item.id }}">
                  {{ item.default_name }}
                </router-link>
              </td>
              <td width='120'>
                {{ item.photo.filename_original }}
              </td>
              <td width='120'>
                {{ item.photo.original_size }}
              </td>
               <td width='120'>
                <span v-if='item.photo.has_cropped'>
                  {{ item.photo.filename_cropped }}
                </span>
              </td>
              <td width='120'>
                <span v-if='item.photo.has_cropped'>
                  {{ item.photo.cropped_size }}
                </span>
              </td>
              <td>
                <div class='flex-items'>
                  <div v-for='review in item.reviews' class='mr-1'>
                    <ScoreCircle 
                      :class="{
                        'grey': !review.is_published
                      }" 
                      :score='review.rating' 
                    />
                  </div>
                </div>
              </td>
              <td class='text-md-right'>
                <v-btn @click='crop(item)' slot='activator' flat icon color="black" class='ma-0'>
                  <v-icon>more_horiz</v-icon>
                </v-btn>
              </td>
            </tr>
          </template>
        </v-data-table>
      </template>
    </DisplayData>
  </div>
</template>

<script>

import { DisplayData } from '@/components/UI'
import AvatarLoader from '@/components/AvatarLoader'
import ScoreCircle from '@/components/UI/ScoreCircle'
import { CLASS_NAME } from '@/components/Client'

export default {
  components: { DisplayData, AvatarLoader, ScoreCircle },

  data() {
    return {
      API_URL: 'clients/photos',
      CLASS_NAME,
      selectedItem: {},
      filters: [
        {label: 'Отзывы', field:'reviews', type: 'select', options: [
          {id: 0, title: 'без отзывов'},
          {id: 1, title: 'с отзывами'},
        ]},
        {label: 'Обрезка', field:'has_cropped', type: 'select', options: [
          {id: 0, title: 'нет обрезанного'},
          {id: 1, title: 'есть обрезанное'},
        ]},
      ]
    }
  },

  methods: {
    crop(item) {
      this.selectedItem = item
      Vue.nextTick(() => this.$refs.AvatarLoader.dialog = true)
    }
  }
}
</script>

<style lang='scss'>
  .tmp-avatar-loader .v-avatar {
    display: none;
  }
</style>