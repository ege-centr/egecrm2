<template>
  <div>
    <GroupDialog ref='GroupDialog' />
    <v-layout class='mb-4'>
      <v-flex xs12 class="text-xs-right">
        <v-btn small fab color="primary" @click='$refs.GroupDialog.open(null)'>
          <v-icon dark>add</v-icon>
        </v-btn>
        добавить группу
      </v-flex>
    </v-layout>
    <GroupList :editable='true' />
  </div>
</template>

<script>

import GroupList from '@/components/Group/List'
import GroupDialog from '@/components/Group/Dialog'

export default {
  components: { GroupList, GroupDialog }
}
</script>
