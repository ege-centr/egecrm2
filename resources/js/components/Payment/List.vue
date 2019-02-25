<template>
  <div>
    <PaymentDialog ref='PaymentDialog' />
    <v-data-table
      :class='config.elevationClass'
      hide-actions
      hide-headers
      :items='items'
    >
      <template slot='items' slot-scope="{ item }">
        <td v-if='show.entity'>
          <router-link v-if='item.entity' :to="{
            name: item.class_name === CLIENT_CLASS_NAME ? 'ClientShow' : 'TeacherShow',  
            params: {id: item.entity.id}
          }">
            <PersonName :item='item.entity' />
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
          <span v-else>не подтвержден</span>
        </td>
        <td>
          <span v-if='item.id'>
            {{ getData('admins', item.created_admin_id).name }}
            {{ item.created_at | date-time }}
          </span>
        </td>
        <td class='text-md-right'>
          <v-btn @click='$refs.PaymentDialog.open(item.id)' slot='activator' flat icon color="black" class='ma-0'>
            <v-icon>more_horiz</v-icon>
          </v-btn>
        </td>
      </template>
      <!-- <template slot='footer' v-if='show.addBtn'>
        <tr>
          <td class='text-md-center pa-0' colspan='10'>
            <v-btn small flat color='primary' class='btn-tr' @click='$refs.PaymentDialog.open(null, {
              entity_id: entityId,
              entity_type: entityType,
            })'>
              добавить
            </v-btn>
          </td>
        </tr>
      </template> -->
    </v-data-table>
    <center class='mt-5' v-if='items.length === 0'>
      <AddBtn @click.native='$refs.PaymentDialog.open(null, {
        entity_id: entityId,
        entity_type: entityType,
      })' />
    </center>
  </div>
</template>
<script>

import { ENUMS, PaymentDialog } from './'
import { CLASS_NAME as CLIENT_CLASS_NAME } from '@/components/Client'
import { CLASS_NAME as TEACHER_CLASS_NAME } from '@/components/Teacher'
import DisplayOptions from '@/mixins/DisplayOptions'

export default {
  props: {
    items: {
      type: Array,
      required: true
    },
    entityId: {
      required: false,
    },
    entityType: {
      type: String,
      required: false,
    }
  },

  mixins: [ DisplayOptions ],

  components: { PaymentDialog },

  data() {
    return {
      ENUMS,
      CLIENT_CLASS_NAME,
      TEACHER_CLASS_NAME,
      defaultDisplayOptions: {
        entity: false,
        addBtn: true,
      }
    }
  },
  
}
</script>
