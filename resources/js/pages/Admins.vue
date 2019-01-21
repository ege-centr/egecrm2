<template lang="html">
  <div>
    <v-layout>
      <v-flex xs12 class="text-xs-right">
        <a class='black-link' @click='add'>
          <v-btn small fab color="primary">
            <v-icon dark>add</v-icon>
          </v-btn>
          добавить пользователя
        </a>
      </v-flex>
    </v-layout>
    <AdminList ref='AdminList' /></AdminList>
  </div>
</template>

<script>

import AdminList from '@/components/Admin/List'

export default {
  components: { AdminList },

  methods: {
    add() {
      this.$refs.AdminList.$refs.AdminDialog.add()
    }
  }
}
</script>
