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
    <UserList ref='UserList' /></UserList>
  </div>
</template>

<script>

import UserList from '@/components/User/UserList'

export default {
  components: { UserList },

  methods: {
    add() {
      this.$refs.UserList.$refs.UserDialog.add()
    }
  }
}
</script>
