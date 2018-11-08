<template>
  <div>
    <v-layout class='mb-4'>
      <v-flex xs12 class="text-xs-right">
        <router-link :to="{ name: 'GroupCreate' }" class='black-link'>
          <v-btn small fab color="primary">
            <v-icon dark>add</v-icon>
          </v-btn>
          добавить группу
        </router-link>
      </v-flex>
    </v-layout>
    <GroupList :editable='true' />
  </div>
</template>

<script>

import GroupList from '@/components/Group/List'

export default {
  components: { GroupList }
}
</script>
