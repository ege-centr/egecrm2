<template>
  <div>
    <Print ref='Print' :params="{type: 'payment', method: selectedMethod}" />
    <display-list 
      :dialog-component='Dialog'
      :items='items'
      :model-defaults='modelDefaults'
      :add-btn='show.addBtn'
      add-btn-label='добавить платеж'
      ref='DisplayList'
      @updated="$emit('updated')"
    >
      <template slot='item' slot-scope="{ item }">
        <tr>
          <td v-if='show.entity'>
            <router-link v-if='item.entity' :to="{
              name: item.entity_type === userTypes.client ? 'ClientShow' : 'TeacherShow',  
              params: {id: item.entity.id}
            }">
              {{ item.entity.default_name }}
            </router-link>
          </td>
          <td>
            <span v-if='item.type'>
              {{ ENUMS.types.find(e => e.id == item.type).title }}
            </span>
          </td>
          <td>
            <span v-if='item.method'>
              {{ ENUMS.methods.find(e => e.id == item.method).title }}
            </span>
          </td>
          <td>
            <span v-if='item.category'>
              {{ ENUMS.categories.find(e => e.id == item.category).title }}
            </span>
          </td>
            <td v-if='show.entity !== false'>
              <span v-if='item.year'>
              {{ getData('years', item.year).title }}
              </span>
          </td>
          <td>
            {{ item.sum }} руб.
          </td>
          <td>
            {{ item.date | date }}
          </td>
          <td>
            <span v-if='item.is_confirmed' class='green--text'>подтвержден</span>
            <span v-else class='red--text'>не подтвержден</span>
          </td>
          <td class='text-md-right'>
            <v-menu left v-if="['bill', 'cash'].includes(item.method) && item.type === 'payment'">
              <v-btn slot='activator' flat icon color="black" class='ma-0'>
                <v-icon>more_horiz</v-icon>
              </v-btn>
              <v-list dense>
                <v-list-tile @click='$refs.DisplayList.edit(item.id)'>
                    <v-list-tile-action>
                      <v-icon>edit</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                      <v-list-tile-title>Редактировать</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile @click.native='print(item)'>
                    <v-list-tile-action>
                      <v-icon>print</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                      <v-list-tile-title>Печать</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
              </v-list>
            </v-menu>
            <v-btn v-else @click='$refs.DisplayList.edit(item.id)' slot='activator' flat icon color="black" class='ma-0'>
              <v-icon>more_horiz</v-icon>
            </v-btn>
          </td>
        </tr>
      </template>
    </display-list>
  </div>
</template>



<script>
import Dialog from './Dialog'
import DisplayList from '@/components/UI/DisplayList'
import DisplayOptions from '@/mixins/DisplayOptions'
import { ENUMS } from './'
import Print from '@/components/Print'

export default {
  props: {
    items: {
      type: Array,
      required: true
    },
    
    modelDefaults: {
      type: Object,
      required: false,
      default: () => {},
    },
  },

  mixins: [ DisplayOptions ],

  components: { DisplayList, Print },

  data() {
    return {
      ENUMS,
      Dialog,
      defaultDisplayOptions: {
        entity: false,
        addBtn: true,
      },
      selectedMethod: null,
    }
  },


  methods: {
    print(item) {
      this.selectedMethod = item.method
      this.$nextTick(() => this.$refs.Print.open({id: item.id}))
    }
  }
}
</script>
