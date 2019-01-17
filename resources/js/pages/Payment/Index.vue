<template lang="html">
  <div>
    <IndexPage :api-url='API_URL' :filters='FILTERS'>
      <template slot='items' slot-scope='{ items }'>
        <v-data-table
          :class='config.elevationClass'
          hide-actions
          hide-headers
          :items='items'
        >
          <template slot='items' slot-scope="{ item, index }">
            <td>
              {{ ENUMS.types.find(e => e.value == item.type).text }}
            </td>
            <td>
              {{ ENUMS.methods.find(e => e.value == item.method).text }}
            </td>
            <td>
              {{ ENUMS.categories.find(e => e.value == item.category).text }}
            </td>
            <td>
              {{ item.sum }} руб.
            </td>
            <td>
              {{ item.date | date }}
            </td>
            <td :class="{'text-md-right': !entityId}">
              <span v-if='item.id'>
                {{ getData('admins', item.created_admin_id).name }}
                {{ item.created_at | date-time }}
              </span>
            </td>
            <td class='text-md-right' v-if='entityId'>
              <v-menu left>
                <v-btn slot='activator' flat icon color="black" class='ma-0'>
                  <v-icon>more_horiz</v-icon>
                </v-btn>
                <v-list dense>
                  <v-list-tile @click='openDialog(item)'>
                      <v-list-tile-action>
                        <v-icon>edit</v-icon>
                      </v-list-tile-action>
                      <v-list-tile-content>
                        <v-list-tile-title>Редактировать</v-list-tile-title>
                      </v-list-tile-content>
                  </v-list-tile>
                  <v-list-tile @click='destroy(item)'>
                      <v-list-tile-action>
                        <v-icon>close</v-icon>
                      </v-list-tile-action>
                      <v-list-tile-content>
                        <v-list-tile-title>Удалить</v-list-tile-title>
                      </v-list-tile-content>
                  </v-list-tile>
                </v-list>
              </v-menu>
            </td>
          </template>
        </v-data-table>
      </template>
    </IndexPage>
    <!-- <v-container grid-list-md fluid class="px-0">
      <PaymentList />
    </v-container> -->
  </div>
</template>

<script>

import { IndexPage } from '@/components/UI'
import { API_URL, FILTERS, ENUMS } from '@/components/Payment'
import PaymentList from '@/components/Payment/List'

export default {
  components: { IndexPage, PaymentList },

  data() {
    return {
      API_URL,
      FILTERS,
      ENUMS,
    }
  },
  
}
</script>
