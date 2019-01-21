<template lang="html">
  <div>
    <IndexPage :api-url='API_URL' :filters='filters'>
      <template slot='buttons'>
        <AddBtn @click.native='$refs.ClientDialog.open(null)' label='добавить клиента' />
      </template>
      <template slot='items' slot-scope="{ items }">
        <v-data-table :items='items' item-key='id' hide-headers hide-actions :class='config.elevationClass'>
          <template slot="items" slot-scope="{ item }">
            <td>
              <router-link :to="{ name: 'ClientShow', params: { id: item.id }}">
                {{ item.names.short }}
              </router-link>
            </td>
            <td class='text-md-right'>
              <v-btn flat icon color="black" class='ma-0' @click='$refs.ClientDialog.open(item.id)'>
                <v-icon>more_horiz</v-icon>
              </v-btn>
            </td>
          </template>
        </v-data-table>
      </template>
    </IndexPage>
    <ClientDialog ref='ClientDialog' />
  </div>
</template>

<script>

import { IndexPage } from '@/components/UI'
import { API_URL, FILTERS, ClientDialog } from '@/components/Client'

export default {
  components: { IndexPage, ClientDialog },

  data() {
    return {
      API_URL,
      filters: [
        {label: 'Класс', field: 'grade_id', type: 'multiple', options: this.$store.state.data.grades, valueField: 'id', textField: 'title'},
        {label: 'Текущий класс', field: 'current_grade_id', type: 'multiple', options: this.$store.state.data.grades, valueField: 'id', textField: 'title'},
      ],
    }
  },
  
}
</script>
